<?php

require_once('library/SimpleDB.php');
require_once('library/sms.php');

$post = $_POST;
if (isset($_POST['update_period'])) {
    $update_period = $_POST['update_period'];
    SimpleDB::writeValue('data/period.txt', $update_period);

    $ss = new SmsSender;
    $ss->sendMessage("ardudash config has changed! period = $update_period seconds.");
}
header('Location: index.php');
