<?php

class CsvFileReader
{
    private $fh;

    public function __construct()
    {
        $this->fh = fopen('users.csv', 'r');
        fgetcsv($this->fh, 1000, ';');
    }

    public function readData()
    {
        return fgetcsv($this->fh, 1000, ';');
    }

    public function close()
    {
        fclose($this->fh);
    }
}
