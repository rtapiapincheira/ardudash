<?php

require_once('library/SimpleDB.php');

$sdb = new SimpleDB;

if (
    isset($_GET['lat']) &&
    isset($_GET['lon']) &&
    isset($_GET['hum']) &&
    isset($_GET['lig']) &&
    isset($_GET['tem'])
) {
    $data = array(
        date("Y-m-d H:i:s"),
        $_GET['lat'],
        $_GET['lon'],
        $_GET['hum'],
        $_GET['lig'],
        $_GET['tem']
    );

    $sdb->addEntry($data);
}

$period = (int)(SimpleDB::readValue('period.txt'));
echo sprintf("%08d", $period);