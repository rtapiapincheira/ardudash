<?php

class SimpleDB {

    private $filename = 'data/data.txt';

    public function __construct($filename = NULL) {
        if ($filename) {
            $this->filename = $filename;
        }
    }

    public function readEntries() {
        $fid = fopen($this->filename, 'r');
        $result = array();
        while(($line = fgets($fid)) !== FALSE) {
            $result[] = explode(',', trim($line));
        }
        fclose($fid);
        return $result;
    }

    public function addEntry($entry) {
        $fid = fopen($this->filename, 'a');
        fwrite($fid, implode(',', $entry));
        fwrite($fid, "\n");
        fclose($fid);
    }

    public function writeEntries($entries) {
        $fid = fopen($this->filename, 'a');
        foreach ($entries as $e) {
            fwrite($fid, implode(',', $e));
            fwrite($fid, "\n");
        }
        fclose($fid);
    }

    public function clean() {
        $fid = fopen($this->filename, 'w');
        fclose($fid);
    }

    public static function writeValue($filename, $value) {
        $fid = fopen($filename, 'w');
        fwrite($fid, $value);
        fclose($fid);
    }

    public static function readValue($filename) {
        return trim(file_get_contents($filename));
    }
}