<?php
require_once('library/SimpleDB.php');

$sdb = new SimpleDB;
$sdb->clean();

header('Location: index.php');
