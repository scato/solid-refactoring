<?php

class MysqlGateway implements GatewayInterface
{
    public function __construct()
    {
        mysql_connect('localhost', 'solid', 'R3f@ct0r!ng');
        mysql_select_db('solid_refactoring');
    }

    public function findGroupByName($groupName)
    {
        $result = mysql_query("SELECT * FROM groups WHERE name = '{$groupName}'");

        return mysql_fetch_assoc($result);
    }

    public function createGroup($groupName)
    {
        mysql_query("INSERT INTO groups (name) VALUES ('{$groupName}')");

        return array('id' => mysql_insert_id(), 'name' => $groupName);
    }

    public function findUserByUsername($username)
    {
        $result = mysql_query("SELECT * FROM users WHERE username = '{$username}'");

        return mysql_fetch_assoc($result);
    }

    public function createUser($username, $password, $groupId)
    {
        mysql_query("INSERT INTO users (username, password, group_id) VALUES ('{$username}', MD5('{$password}'), {$groupId})");
    }

    public function updateUser($password, $groupId, $userId)
    {
        mysql_query("UPDATE users SET password = MD5('{$password}'), group_id = {$groupId} WHERE id = '{$userId}'");
    }

    public function close()
    {
        mysql_close();
    }
}
