<?php
header('Content-Type: text/plain');
session_start();

if (!isset($_SESSION['user'])) {
	header("Location: ../index.php");
}

if (isset($_POST['get_temp'])) {
	require_once '../class/database.class.php';
	$database = new Database();

	$database->query("SELECT * FROM ultimastemperaturas,maxtemp WHERE utemp>=mtemp AND normalizada=0");

	$result = $database->resultset();
	$count  = $database->rowCount();
	for ($i = 0; $i < $count; $i++) {
		echo "[NOTIFICAÇÃO] A temperatura máxima de " . $result[$i]['mtemp'] . "ºC foi ultrapassada no camião nº " . $result[$i]['utruck'] . " às " . $result[$i]['udatetime'] . ": " . $result[$i]["utemp"] . "ºC" . "\n";
	}
}

?>