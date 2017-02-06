<?php
	$host = "localhost";
	$user  = "root";
	$password =  "";
	 
	try {
		$account = new PDO("mysql:host=$host;dbname=account", $user, $password);
	} catch(PDOException $e) {
		die('Conexiunea la baza de date este imposibila.');
	}
	 
	try {
		$player = new PDO("mysql:host=$host;dbname=player", $user, $password);
	} catch(PDOException $e) {
		die('Conexiunea la baza de date este imposibila.');
	}
