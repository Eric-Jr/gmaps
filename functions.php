<?php

function connect($config) {
	
	try {
		$conn = new PDO("mysql:host=localhost;dbname=" . $config['DB_NAME'],
						$config['DB_USERNAME'],
						$config['DB_PASSWORD']);

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		return $conn;

	} catch (Exception $e) {
		return false;
	}
}

function query($query, $conn) {
	$stmt = $conn->prepare($query);
	$stmt->setFetchMode(PDO::FETCH_OBJ);
	$stmt->execute();
	$results = $stmt->fetchAll();	
	return $results ? $results : 'Now rows returned.';
}

function get($tableName, $conn) {
	try {
		$results = $conn->query("SELECT * FROM $tableName");
		return ( $results->rowCount() > 0) ? $results : false;
	} catch (Exception $e) {
		echo "Not rows returned."; die();
	}
}