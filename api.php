<?php

require_once('library/SimpleDB.php');

setlocale(LC_ALL, 'us_US');

$sdb = new SimpleDB;

$act = isset($_GET['act']) ? $_GET['act'] : '0';

$fix = isset($_GET['fix']) ? $_GET['fix'] : '2'; # By default if nothing set explicitly, assume it comes from a 2D fix
$valid_fix = ($fix == '2' || $fix == '3');

if (
    isset($_GET['lat']) &&
    isset($_GET['lon']) &&
    isset($_GET['hum']) &&
    isset($_GET['lig']) &&
    isset($_GET['tem'])
) {
    $lat = $_GET['lat'];
    $lon = $_GET['lon'];
    $hum = $_GET['hum'];
    $lig = $_GET['lig'];
    $tem = $_GET['tem'];

    $data = array(
        date("Y-m-d H:i:s", strtotime("-6 hours")),
        $lat,
        $lon,
        $hum,
        $lig,
        $tem,
        $fix
    );

    # Data seems to be generated by a fix, so insert as is
    if ($valid_fix) {
        $sdb->addEntry($data);
    }
    # Need to recover last valid gps data, so as to repeat it
    else {
        $entries = $sdb->readEntries();

        # If n == 0, it means there is no valid data yet. Then, discard the current.
        $n = count($entries);
        if ($n > 0) {

            # Search backwards and get the one that:
            # 1) Has explicitly set as fixed (that its, the 7th value is 2 or 3)
            # 2) Or has 6 values only (that is assumed to be a 2d fix.
            $found_row = NULL;
            for ($i = $n-1; $i >= 0; $i--) {
                $row = $entries[$i];

                # Found latest implicitly fixed gps data
                if (count($row) == 6) {
                    $row[] = '2';
                    $found_row = $row;
                    break;
                }
                # Or found latest explicitly fixed gps data
                else if (count($row) >= 7 && ($row[6] === '2' || $row[6] === '3')) {
                    $found_row = $row;
                    break;
                }
            }

            # Replace current data with the latest fixed data
            if ($found_row) {
                $data[1] = $found_row[1];
                $data[2] = $found_row[2];
                $data[6] = $found_row[6];

                # Write actual data
                $sdb->addEntry($data);
            }
        }
    }
    
}

SimpleDB::writeValue('data/actuator.txt', ($act == '1' ? '1' : '0'));

$period = (int)(SimpleDB::readValue('data/period.txt'));
echo sprintf("%08d", $period);
