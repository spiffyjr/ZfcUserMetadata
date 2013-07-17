<?php

namespace ZfcUserMetadata;

use ZfcUser\Entity\UserInterface;
use ZfcUser\Extension\AbstractExtension;
use ZfcUserMetadata\Entity\UserMetadataInterface;

class Extension extends AbstractExtension
{
    const EVENT_GET_METADATA = 'metadata.getMetadata';

    /**
     * @var array
     */
    protected $options = array(
        'entity_class' => 'ZfcUserMetadata\Entity\UserMetadata'
    );

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'metadata';
    }

    /**
     * @param UserInterface $user
     * @param null|string $key
     * @return array
     */
    public function getMetadata(UserInterface $user, $key = null)
    {
        $manager = $this->getManager();
        $event   = $manager->getEvent();
        $event->setTarget($user);
        $event->setParam('key', $key);

        $metadata = $manager->getEventManager()->trigger(static::EVENT_GET_METADATA, $event);
        $metadata = $metadata->last();

        if (null === $metadata) {
            return array();
        }

        if (null !== $key) {
            return empty($metadata) ? null : $metadata[0]->getValue();
        }

        $result = array();
        foreach ($metadata as $entity) {
            if (!$entity instanceof UserMetadataInterface) {
                // todo: throw exception
                echo 'each element of metadata should be an instance of UserMetadataInterface';
                exit;
            }

            $result[$entity->getKey()] = $entity->getValue();
        }

        return $result;
    }
}