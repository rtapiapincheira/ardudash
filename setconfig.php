<?php

require_once('library/SimpleDB.php');

$post = $_POST;
if (isset($_POST['update_period'])) {
    SimpleDB::writeValue('data/period.txt', $_POST['update_period']);
}
header('Location: index.php');
