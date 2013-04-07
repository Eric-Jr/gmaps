<?php 

require 'config.php';
require 'functions.php';

$conn = connect($config);

if ($conn) {
	$getLatitude = 'SELECT * FROM location_data';
	$results = query($getLatitude, $conn);
}

else {
	die('Could not establish a connection to the database.');
}

require 'index.tmpl.php';