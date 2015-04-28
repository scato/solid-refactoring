<?php

error_reporting(E_ALL & ~E_DEPRECATED);

require_once 'vendor/autoload.php';

$importer = new UserImporter();
$importer->import();
