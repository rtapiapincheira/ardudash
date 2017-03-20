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
                    echo implode(',', $e);
                }
?></pre>
        </div>
    </div>
    <div class="block">
        <h3>Plot</h3>
        <div class="box2 tall">

        </div>
    </div>

    <br clear="both"/>

    <br/>
    <br/>

    <div>
        <form method="post" action="setconfig.php">
            <select name="update_period" class="separator_control">
                <option value="15">15 seconds</option>
                <option value="30">30 seconds</option>
                <option value="60">60 seconds</option>
                <option value="300">5 minutes</option>
                <option value="900">15 minutes</option>
            </select>

            <button class="control_button styled_control styled_button ok" type="submit">Set update period</button>
        </form>

        <div class="separator_control">
            Current: <?=SimpleDB::readValue('period.txt')?> seconds
        </div>
        <div class="separator_control"></div>
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

    <br/>
    <br/>

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

</div>

<script>

</script>

<?=make_script('jquery.min.js')?>
<?=make_script('jquery.jqplot.min.js')?>
<?=make_script('jqplot.dateAxisRenderer.js', 'assets/plugins')?>
<?=make_script('page.js')?>
<?=make_script('js?key='.$google_maps_api_key.'&callback=myMap', 'https://maps.googleapis.com/maps/api')?>

</body>
</html>
