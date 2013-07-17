<?php

namespace ZfcUserMetadata;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use ZfcUser\Entity\UserInterface;
use ZfcUser\Extension\AbstractExtension;

class DoctrineExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'metadata_doctrine';
    }

    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        // disable extension if doctrine extension is not present
        if (!$this->getManager()->has('doctrine')) {
            return;
        }

        $this->listeners[] = $events->attach(Extension::EVENT_GET_METADATA, array($this, 'onGetMetadata'));
    }

    /**
     * @param EventInterface $e
     * @return array
     */
    public function onGetMetadata(EventInterface $e)
    {
        $user = $e->getTarget();
        if (!$user instanceof UserInterface) {
            return null;
        }

        $key = $e->getParam('key');
        if (!is_string($key)) {
            return $this->getObjectRepository()->findBy(array('user' => $user));
        }

        return $this->getObjectRepository()->findBy(array('user' => $user, 'key' => $key));
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getObjectRepository()
    {
        $metadata = $this->getManager()->get('metadata');
        return $this->getObjectManager()->getRepository($metadata->getOption('entity_class'));
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getObjectManager()
    {
        /** @var \ZfcUserDoctrine\Extension $doctrine */
        $doctrine = $this->getManager()->get('doctrine');
        return $doctrine->getObjectManager();
    }
}