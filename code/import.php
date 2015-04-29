<?php

error_reporting(E_ALL & ~E_DEPRECATED);

require_once 'vendor/autoload.php';

// open file, skip header
$fileReader = new CsvFileReader();

// open database connection
$gateway = new MysqlGateway();

$importer = new UserImporter($fileReader, $gateway);
$importer->import();
