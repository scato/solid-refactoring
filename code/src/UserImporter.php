<?php

class UserImporter
{
    private $dataReader;
    private $gateway;

    public function __construct(DataReaderInterface $dataReader, GatewayInterface $gateway)
    {
        $this->dataReader = $dataReader;
        $this->gateway = $gateway;
    }

    public function import()
    {
        // import all the things!
        while ($data = $this->dataReader->readData()) {
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
        if ($this->dataReader instanceof CloseableInterface) {
            $this->dataReader->close();
        }

        if ($this->gateway instanceof CloseableInterface) {
            $this->gateway->close();
        }
    }
}
