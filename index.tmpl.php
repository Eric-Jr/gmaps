<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tauri|Roboto+Condensed">
	<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDV5Q6TqfJk4XlwNyMTKu6VcuqiTO9Y_yc&sensor=false">
    </script>
    <script type="text/javascript" src="js/infobox.js"></script>
	<style type="text/css">
		html { height: 100%; }
		body { height: 100%; margin: 0; padding: 0; }
		#map-canvas { height: 100%; }
		.labels { 
			border-radius: 7px;
			padding: 10px;
			background: #fff;
			color: #d80000;
			width: 230px;
			font-family: 'Roboto Condensed', Tauri, sans-serif;
		 }

		 .name {
		 	color: #626262;
		 	font-size: 1.5em;
		 	border-bottom: 3px solid;
		 	background: url('../img/ico01.png');
		 }
	</style>
	<script type="text/javascript">
		function initialize() {
			//Set map styles
			var styles = [
  				{
    				stylers: [
      				{ hue: "#d80000" },
      				{ saturation: -20 }
    				]
  				},{
    				featureType: "road",
    				elementType: "geometry",
    				stylers: [
      					{ lightness: 100 },
      					{ visibility: "simplified" }
    				]
  				},{
    				featureType: "road",
    				elementType: "labels",
    				stylers: [
      					{ visibility: "off" }
    				]
  				}
			];

			//Set map options
			var mapOptions = {
				zoom: 15,
				center: new google.maps.LatLng(38.857722, -77.050338),
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				styles: styles
			}

			//Create map instance
			var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

			//Get data from the database
			var dbDump = <?php echo json_encode($results) ?>;

			//Construct Latitude variables
			var lat1 = parseFloat(dbDump[0].latitude);
			var lat2 = parseFloat(dbDump[1].latitude);
			var lat3 = parseFloat(dbDump[2].latitude);

			//Construct Longitude variables
			var lng1 = parseFloat(dbDump[0].longitude);
			var lng2 = parseFloat(dbDump[1].longitude);
			var lng3 = parseFloat(dbDump[2].longitude);

			//Set Locations for map markers
			var locations = [ new google.maps.LatLng(lat1, lng1), //Enterprise
							  new google.maps.LatLng(lat2, lng2),  //Crystal City Metro
							  new google.maps.LatLng(lat3, lng3)   //Seven Eleven
							];

			//Set Options for map markers
			var mark1Opts = mrkOpts(dbDump[0].name, map, locations[0]);
			var mark2Opts = mrkOpts(dbDump[1].name, map, locations[1]);
			var mark3Opts = mrkOpts(dbDump[2].name, map, locations[2]);

			//Helper function for setting marker options
			function mrkOpts(name, mapName, location) {
				var name = {
					position: location,
					map: mapName,
					boxClass: "labels",
            		zIndex: null,
            		disableAutoPan: true,
            		isHidden: false,
            		pane: "floatPane",
            		enableEventPropagation: true
				}	

				return name;
			}
			
			//Create Markers
			var mrkList = [ new google.maps.Marker(mark1Opts),
							new google.maps.Marker(mark2Opts),
							new google.maps.Marker(mark3Opts), 
						  ];	

			//Construct geographical information
			var address1 = dbDump[0].address;
			var address2 = dbDump[1].address;
			var address3 = dbDump[2].address;
			var cityStateZip1 = dbDump[0].city_state_zip;
			var cityStateZip2 = dbDump[1].city_state_zip;
			var cityStateZip3 = dbDump[2].city_state_zip;
			
			//Construct HTML content
			var htmlList = [ '<p class="name">' + dbDump[0].name + "</p>" + "<p>" + address1 + "</p>" + "<p>" + cityStateZip1 + "</p>",
							 '<p class="name">' + dbDump[1].name + "</p>" + "<p>" + address2 + "</p>" + "<p>" + cityStateZip2 + "</p>",
							 '<p class="name">' + dbDump[2].name + "</p>" + "<p>" + address3 + "</p>" + "<p>" + cityStateZip3 + "</p>"
						   ];

			//Set Options for infoBoxes
			var optsList = [ ibOpts('mark1Opts', htmlList[0]), 
							 ibOpts('mark2Opts', htmlList[1]), 
							 ibOpts('mark3Opts', htmlList[2]) 
						   ];

			//Helper function for setting info box options
			function ibOpts(name, html) {
				var name = {
					content: html,
					boxClass: "labels",
            		zIndex: null,
            		disableAutoPan: true,
            		isHidden: false,
            		pane: "floatPane",
            		enableEventPropagation: true
				}	

				return name;
			}

			//Create info boxes
			var ibList = [ new InfoBox(optsList[0]),
						   new InfoBox(optsList[1]),
						   new InfoBox(optsList[2]) 
						 ];

			//Create event listeners and handlers
			for (i=0; i < mrkList.length; i++) {
				google.maps.event.addListener(mrkList[i], 'click', function() {
        			var a = mrkList.indexOf(this);
        			var curIb = ibList[a];
        			for (i=0; i < ibList.length; i++) {
        				var ibs = ibList[i];
        				if (ibs != curIb) {
        					ibs.close();
        				}
        			}
        			map.setZoom(17);
        			map.setCenter(locations[a]);
					ibList[a].setPosition(locations[a]);
        			ibList[a].open(map, this);
				});
			}
		}
	</script>
</head>
<body onload="initialize()">
	<div id="map-canvas"></div>
</body>
</html>