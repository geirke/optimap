<html>
<head>
<?php
$api_www_gebweb = "51c2f2096bbdcd6422b5d172c555cacf";
$api_gebweb = "c0bcc979825cde992937b2d732343b9c";
$api_www_optimap = "39029a66ad579141aa9de7a43b3558eb";
$api_optimap = "5e9f01f368a532d706d5418ab7f1a069";
$server = $_SERVER["SERVER_NAME"];
$api_domain = "optimap.net";
$api_key = $api_optimap;
$pos = strpos($server, "gebweb.net");
if ($pos !== false) {
  $api_domain = "gebweb.net";
  $api_key = $api_gebweb;
}
$pos = strpos($server, "www.gebweb.net");
if ($pos !== false) {
  $api_domain = "www.gebweb.net";
  $api_key = $api_www_gebweb;
}
$pos = strpos($server, "www.optimap.net");
if ($pos !== false) {
  $api_domain = "www.optimap.net";
  $api_key = $api_www_optimap;
}
?>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<title>Garmin GPX export</title>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="http://developer.garmin.com/web/communicator-api/prototype/prototype.js"></script>
<script type="text/javascript" src="http://developer.garmin.com/web/communicator-api/garmin/device/GarminDeviceDisplay.js"></script>
<script type="text/javascript">
jQuery.noConflict();

var apiDomain = <?php echo "'" . $api_domain . "'"; ?>;
var apiKey = <?php echo "'" . $api_key . "'"; ?>;

function garminExport() {
  var display = new Garmin.DeviceDisplay("garminDisplay", { 
    pathKeyPairsArray: ["http://" + apiDomain,
			apiKey],
	autoFindDevices: true, //start searching for devices
	hideIfBrowserNotSupported: true,
	showStatusElement: true,  //provide minimal feedback
	findDevicesButtonText: "Upload route to Garmin GPS unit",  //allows you to customize the action text
	showCancelFindDevicesButton: false,  //no need to cancel small data transfers
	showDeviceSelectOnLoad: false,               //
	showDeviceSelectNoDevice: false,             //autoReadData: false,
                                                  //don't automatically read the tracks/etc
	autoWriteData: true,  //automatically write the data once devices found
	showReadDataElement: false,
 
	getWriteData: function() {
	  return <?php echo json_encode($_POST["gpx"]); ?>;
        },
 
     afterFinishWriteToDevice: function() {
          alert("Route saved successfully");
     }	
  });
}

function garminExportWaypoints() {
  var display = new Garmin.DeviceDisplay("garminDisplay", { 
    pathKeyPairsArray: ["http://" + apiDomain,
			apiKey],
	autoFindDevices: true, //start searching for devices
	hideIfBrowserNotSupported: true,
	showStatusElement: true,  //provide minimal feedback
	findDevicesButtonText: "Upload waypoints to Garmin GPS unit",  //allows you to customize the action text
	showCancelFindDevicesButton: false,  //no need to cancel small data transfers
	showDeviceSelectOnLoad: false,               //
	showDeviceSelectNoDevice: false,             //autoReadData: false,
                                                  //don't automatically read the tracks/etc
	autoWriteData: true,  //automatically write the data once devices found
	showReadDataElement: false,
 
	getWriteData: function() {
	  return <?php echo json_encode($_POST["gpxWp"]); ?>;
        },
 
     afterFinishWriteToDevice: function() {
          alert("Route saved successfully");
     }	
  });
}

jQuery(function() {
  jQuery("input:button").button();
});

</script>
</head>
<body>
<div id="garminDisplay"></div>
<input id="garminRoute" type="button" value="Export Route" onClick="garminExport()"/>
<input id="garminWaypoints" type="button" value="Export Waypoints" onClick="garminExportWaypoints()"/>

<p>Not all Garmin devices support routes (<a href="http://developer.garmin.com/web-device/garmin-communicator-plugin/device-support-matrix/" target="_new">see device support matrix</a>). If your device supports routes, choose 'Export Route', if not, select 'Export Waypoints'.</p>

<p>The 'Export Route' option stores the route on your device, but you may still have to import it under 'Tools' - 'My Data' - 'Import Route from File'</p>

<p>The 'Export Waypoints' option creates waypoints named 'OptiMap 001' etc. in the favourites on your device. Be sure to delete all previous OptiMap waypoints before using this option, since you won't know which waypoints are from a previous trip...</p>

<p>You can also download the .GPX files directly and put them in the GPX folder on your device manually:<br>
<?php
$sub_dir = "";
$rnd_token = mt_rand(100000000, 999999999);
$date_token = date("Ymd");
if (isset($_POST["gpx"])) {
  if (strlen($_POST["gpx"]) < 100000) {
    $gpx = $_POST["gpx"];
    $fname = "tomtom/" .  $date_token . $rnd_token . ".gpx";
    file_put_contents($fname, $gpx);

    echo "<a href='http://www.gebweb.net/optimap/" . $sub_dir . $fname
        . "'>Download GPX route file</a><br>\n";
  } else {
    echo "GPX is too large!\n";
  }
}
if (isset($_POST["gpxWp"])) {
  if (strlen($_POST["gpxWp"]) < 100000) {
    $gpx = $_POST["gpxWp"];
    $fname = "tomtom/" .  $date_token . $rnd_token . ".wp.gpx";
    file_put_contents($fname, $gpx);

    echo "<a href='http://www.gebweb.net/optimap/" . $sub_dir . $fname
        . "'>Download GPX waypoints file</a>\n";
  } else {
    echo "GPX is too large!\n";
  }
}
?>
</p>

<p>The Garmin support is experimental. Please let me know if your device works or does not work by posting a comment <a href="http://gebweb.net/blogpost/2012/01/25/optimap-version-4-is-here/" target="_new">here</a>.</p>

</body>
</html>
