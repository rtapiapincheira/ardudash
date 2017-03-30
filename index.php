<?php
require_once('library/config.php');
require_once('library/framework.php');
require_once('library/SimpleDB.php');
$sdb = new SimpleDB;
?>

<!DOCTYPE html>
<html>
<head>
    <?=make_link('jquery.jqplot.min.css')?>
    <?=make_link('page.css')?>

    <script type="application/javascript">
        map_lat_center = <?=$map_lat_center?>;
        map_lon_center = <?=$map_lon_center?>;
        map_zoom_level = <?=$map_zoom_level?>;

        data = {};

        <?php
        $lat = array();
        $lon = array();

        $hum = array();
        $lig = array();
        $tem = array();

        foreach ($sdb->readEntries() as $e) {
            $cdate = @$e[0];

            $lat[] = @$e[1];
            $lon[] = @$e[2];

            $hum[] = array($cdate, @$e[3]);
            $lig[] = array($cdate, @$e[4]);
            $tem[] = array($cdate, @$e[5]);
        }

        if (count($hum) == 0) {
            $hum = array(
                array('2017-03-20 00:00:00', '0')
            );
            $lig = array(
                array('2017-03-20 00:00:00', '0')
            );
            $tem = array(
                array('2017-03-20 00:00:00', '0')
            );
        }

        echo "\n";
        echo "data.hum = [\n";
        foreach($hum as $h) {
            $hd = $h[0];
            $hv = $h[1];
            echo "['$hd', $hv],\n";
        }
        echo "];\n\n";

        echo "data.lig = [\n";
        foreach($lig as $l) {
            $ld = $l[0];
            $lv = $l[1];
            echo "['$ld', $lv],\n";
        }
        echo "];\n\n";

        echo "data.tem = [\n";
        foreach($tem as $t) {
            $td = $t[0];
            $tv = $t[1];
            echo "['$td', $tv],\n";
        }
        echo "];\n\n";

        echo "data.lat = [\n";
        foreach ($lat as $l) {
            echo "$l,\n";
        }
        echo "];\n";

        echo "data.lon = [\n";
        foreach ($lon as $l) {
            echo "$l,\n";
        }
        echo "];\n";
        ?>
    </script>
</head>
<body>



<div class="boxes_container">

    <h1>Data Visualization Dashboard for Arduino</h1>

    <p class="notice">The code for this project can be found at <a href="https://github.com/rtapiapincheira/ardudash" target="_blank">https://github.com/rtapiapincheira/ardudash</a>.</p>

    <br/>

    <p class="notice">Ardudash is a tool for plotting and visualizing data retrieved from Arduino and similar systems.</p>

    <br/>

    <p class="notice">All data is gathered through an API and stored server side using plain files. Server requires PHP 5+ and write
        access to the same folder where the app is deployed.</p>

    <br/>

    <div class="block">
        <h3>Map</h3>
        <div id="map" class="box1 tall"></div>

    </div>
    <div class="block">
        <h3>Raw data</h3>
        <div class="box3 tall" style="overflow: scroll;">
            <pre style="font-size:8pt; font-family: Courier, Tahoma, Monospaced,serif;
            text-align: left;">yyyy-MM-dd hh:mm:ss,lat,lon,hum,light,temp
<?php
                $entries = $sdb->readEntries();
                foreach($entries as $e) {
                    echo implode(',', $e)."\n";
                }
?></pre>
        </div>
    </div>
    <div class="block">
        <h3>Plot</h3>
        <div id="chart" class="box2 tall"></div>
    </div>

    <br clear="both"/>

    <br/>
    <br/>

    <div>
        <?php
        $period = (int)SimpleDB::readValue('data/period.txt');
        function may_select($value, $req) {
            if ($value == $req) {
                return 'selected="selected"';
            }
            return '';
        }
        ?>
        <form method="post" action="setconfig.php">
            <select name="update_period" class="separator_control">
                <option value="15" <?=may_select(15, $period)?>>15 seconds</option>
                <option value="30" <?=may_select(30, $period)?>>30 seconds</option>
                <option value="60" <?=may_select(60, $period)?>>60 seconds</option>
                <option value="300" <?=may_select(300, $period)?>>5 minutes</option>
                <option value="900" <?=may_select(900, $period)?>>15 minutes</option>
            </select>

            <button class="control_button styled_control styled_button ok" type="submit">Set update period</button>
        </form>

        <div class="separator_control">
            Current: <?=$period?> seconds
        </div>
        <div class="separator_control">
            Actuator:
            <?php if (SimpleDB::readValue('data/actuator.txt') == '1'): ?>
                <span class="status_danger">Emergency!</span>
            <?php else: ?>
                <span class="status_normal">Normal</span>
            <?php endif; ?>
        </div>
        <div class="separator_control"></div>

        <button class="control_button styled_control styled_button warn" type="button" onclick="return cleanData();">Clean data</button>
    </div>

    <br clear="both"/>

    <br/>
    <br/>

    <h2>Instructions</h2>

    <?php
    $http_host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $current_link = "http://{$http_host}{$request_uri}";
    if (!endsWith($current_link, 'index.php')) {
        if (endsWith($current_link, '/')) {
            $current_link .= 'index.php';
        } else {
            $current_link .= '/index.php';
        }
    }
    $current_folder = substr($current_link, 0, strlen($current_link)-10);
    ?>


    <p class="notice">
        The entry point for the period is in the link <a href="<?=$current_folder?>/api.php" target="_blank"><?=$current_folder?>/api.php</a>.
        The period configured through the "Set update period" button above can be retrieved by visiting the link. Arduino and similar systems
        should make a GET call to this url and parse the 8-digit string returned and make use of it as the configured
        period between updates.
    </p>

    <br/>

    <p class="notice">
        Method: <b>GET</b>, Url: <b><?=$current_folder?>/api.php</b>
    </p>

    <hr/>

    <p class="notice">
        To send sensor data, Arduino and similar systems should make a GET call, this time specifying the parameters in the URL.
        For example, to send some real data, click the link and then reload this page to see it
        <?php $url = "$current_folder/api.php?lat=-33.438722&lon=-70.653411&hum=65&lig=35&tem=43.5"; ?>
        <a href="<?=$url?>" target="_blank"><?=$url?></a>
    </p>

    <br/>

    <p class="notice">
        Method: <b>GET</b>, Url: <b><?=$current_folder?>/api.php?lat=LATITUDE&lon=LONGITUDE&hum=HUMIDTY&lig=LIGHT&tem=TEMPERATURE</b>
    </p>

    <hr/>

    <p class="notice">
        Optionally, an additional parameter may be sent "act" to indicate an actuator has changed its state (value 0 or 1).
        For example, <?php $url = "$current_folder/api.php?lat=-33.438722&lon=-70.653411&hum=65&lig=35&tem=43.5&act=1"; ?>
        <a href="<?=$url?>" target="_blank"><?=$url?></a> (press the link, and reload this page; alternate between 0 and 1 to see the change)
    </p>

    <br/>

    <p class="notice">
        Method: <b>GET</b>, Url: <b><?=$current_folder?>/api.php?lat=LATITUDE&lon=LONGITUDE&hum=HUMIDTY&lig=LIGHT&tem=TEMPERATURE&act=ACTUATOR_0_OR_1</b>
    </p>

    <br/>
    <br/>

</div>

<script>

</script>

<?=make_script('jquery.min.js')?>
<?=make_script('jquery.jqplot.min.js')?>
<?=make_script('jqplot.categoryAxisRenderer.js', 'assets/plugins')?>
<?=make_script('jqplot.canvasTextRenderer.js', 'assets/plugins')?>
<?=make_script('jqplot.cursor.js', 'assets/plugins')?>
<?=make_script('jqplot.highlighter.js', 'assets/plugins')?>
<?=make_script('jqplot.trendline.js', 'assets/plugins')?>
<?=make_script('jqplot.dateAxisRenderer.js', 'assets/plugins')?>
<?=make_script('jqplot.canvasAxisTickRenderer.js', 'assets/plugins')?>
<?=make_script('page.js')?>
<?=make_script('js?key='.$google_maps_api_key.'&callback=myMap', 'https://maps.googleapis.com/maps/api')?>

</body>
</html>
