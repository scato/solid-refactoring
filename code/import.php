<?php

error_reporting(E_ALL & ~E_DEPRECATED);

require_once 'vendor/autoload.php';

// open database connection
mysql_connect('localhost', 'solid', 'R3f@ct0r!ng');
mysql_select_db('solid_refactoring');

// open file
$fh = fopen('users.csv', 'r');
$data = fgetcsv($fh, 1000, ';');

// check header
$header = array('username', 'password', 'group');
if ($data != $header) {
    die("File does not contain the right headers\n");
}

// import all the things!
while ($data = fgetcsv($fh, 1000, ';')) {
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

mysql_close();

