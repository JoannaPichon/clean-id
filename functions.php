<?php

function getBdd($server, $dbname, $identifiant, $pass) {
	try
	{
		$bdd = new PDO("mysql:host=$server;dbname=$dbname", $identifiant, $pass);
	}
	catch (Exception $e)
	{
		return false;
	}
	return $bdd;
}

function message($type, $message) {
	echo '<div class="alert alert-' . $type . '" role="alert">' . $message . '</div>';	
}
?>