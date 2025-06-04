<?php
    require_once("settings.php");
    
    $driver = new mysqli_driver();
    $driver->report_mode = MYSQLI_REPORT_OFF;

    $connection = mysqli_connect($hostname,$username,$password,$database);

    if(mysqli_connect_errno()){
        echo "<p style='color: red;'>Database connection problem!!!</p>";
        echo "<p style='color: red;'>Error code: ".mysqli_connect_errno()."</p>";
        echo "<p style='color: red;'>Error error message: ".mysqli_connect_error()."</p>";
    }


?>