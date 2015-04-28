<?php

class UserImporter
{
    public function import()
    {
        // open file, skip header
        $fileReader = new CsvFileReader();

        // open database connection
        mysql_connect('localhost', 'solid', 'R3f@ct0r!ng');
        mysql_select_db('solid_refactoring');

        // import all the things!
        while ($data = $fileReader->readData()) {
            $result = mysql_query("SELECT * FROM groups WHERE name = '{$data[2]}'");
            $group = mysql_fetch_assoc($result);

            if (!$group) {
                mysql_query("INSERT INTO groups (name) VALUES ('{$data[2]}')");
                $group = array('id' => mysql_insert_id(), 'name' => $data[2]);
            }

            $result = mysql_query("SELECT * FROM users WHERE username = '{$data[0]}'");
            $user = mysql_fetch_assoc($result);

            if (!$user) {
                mysql_query("INSERT INTO users (username, password, group_id) VALUES ('{$data[0]}', MD5('{$data[1]}'), {$group['id']})");
            } else {
                mysql_query("UPDATE users SET password = MD5('{$data[1]}'), group_id = {$group['id']} WHERE id = '{$user['id']}'");
            }
        }

        // clean up
        $fileReader->close();
        mysql_close();
    }
}
