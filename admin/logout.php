<?php

    session_start();
    
    header("location:../login.php?msg= Logout Successfully!...");
    
    session_destroy();
    



?>