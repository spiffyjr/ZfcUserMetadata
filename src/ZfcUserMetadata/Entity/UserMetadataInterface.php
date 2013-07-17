<?php

namespace ZfcUserMetadata\Entity;

use ZfcUser\Entity\UserInterface;

interface UserMetadataInterface
{
    /**
     * @return UserInterface
     */
    public function getUser();

    /**
     * @param UserInterface $user
     * @return UserMetadataInterface
     */
    public function setUser(UserInterface $user);

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $key
     * @return UserMetadataInterface
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return UserMetadataInterface
     */
    public function setValue($value);
}