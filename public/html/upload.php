<?php
    require('../../php/PointsGPX.php');

    session_start();
    ob_start();

    echo "distance : ".$_SESSION['distance']." <br> elevation : ".$_SESSION['elevation']."<br> denivele :". $_SESSION['denivele']." <br>";
?>