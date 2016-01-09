<?php
date_default_timezone_set('Europe/Lisbon');

require_once '../class/database.class.php';

if (isset($_COOKIE["cookie_cn"]) == "conectado") {
    $database = new Database();

    if (isset($_GET['cam']) && (is_numeric($_GET["cam"])) && isset($_GET['temp']) && (is_numeric($_GET['temp']))) {
        $date = date('Y-m-d h:m:s');
        $cam  = $_GET['cam'];
        $temp = $_GET['temp'];
        
        $database->query("INSERT INTO data(datetime, temp, truck) VALUES(CURRENT_TIMESTAMP, :temp, :truck)");
        $database->bind(':temp', $temp);
        $database->bind(':truck', $cam);
        $database->execute();
        $database->query("INSERT INTO ultimastemperaturas (udatetime, utemp, utruck, normalizada) VALUES(CURRENT_TIMESTAMP, :temp, :truck, 0) ON DUPLICATE KEY UPDATE udatetime=CURRENT_TIMESTAMP, utemp=:temp, normalizada=0");
        $database->bind(':temp', $temp);
        $database->bind(':truck', $cam);
        $database->execute();
    }
} else {
    header("Location: ../index.php");
}
?>