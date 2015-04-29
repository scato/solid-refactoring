<?php

class UserImporter
{
    private $fileReader;
    private $gateway;

    public function __construct(CsvFileReader $fileReader, MysqlGateway $gateway)
    {
        $this->fileReader = $fileReader;
        $this->gateway = $gateway;
    }

    public function import()
    {
        // import all the things!
        while ($data = $this->fileReader->readData()) {
            $username = $data[0];
            $password = $data[1];
            $groupName = $data[2];

            $group = $this->gateway->findGroupByName($groupName);

            if (!$group) {
                $group = $this->gateway->createGroup($groupName);
            }

            $user = $this->gateway->findUserByUsername($username);

            if (!$user) {
                $this->gateway->createUser($username, $password, $group['id']);
            } else {
                $this->gateway->updateUser($password, $group['id'], $user['id']);
            }
        }

        // clean up
        $this->fileReader->close();
        $this->gateway->close();
    }
}
