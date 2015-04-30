<?php

interface GatewayInterface
{
    public function findGroupByName($groupName);
    public function createGroup($groupName);

    public function findUserByUsername($username);
    public function createUser($username, $password, $groupId);
    public function updateUser($password, $groupId, $userId);

    public function close();
}
