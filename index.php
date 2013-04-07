<?php 

require 'config.php';
require 'functions.php';

$conn = connect($config);

if ($conn) {
	$dbDump = 'SELECT * FROM location_data';
	$results = query($dbDump, $conn);
}

else {
	die('Could not establish a connection to the database.');
}

require 'index.tmpl.php';