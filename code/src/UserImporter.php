<?php

class UserImporter
{
    public function import()
    {
        // open file, skip header
        $fileReader = new CsvFileReader();

        // open database connection
        $gateway = new MysqlGateway();

        // import all the things!
        while ($data = $fileReader->readData()) {
            $username = $data[0];
            $password = $data[1];
            $groupName = $data[2];

            $group = $gateway->findGroupByName($groupName);

            if (!$group) {
                $group = $gateway->createGroup($groupName);
            }

            $user = $gateway->findUserByUsername($username);

            if (!$user) {
                $gateway->createUser($username, $password, $group['id']);
            } else {
                $gateway->updateUser($password, $group['id'], $user['id']);
            }
        }

        // clean up
        $fileReader->close();
        $gateway->close();
    }
}
