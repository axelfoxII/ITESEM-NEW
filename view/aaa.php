<?php
    echo date('Y-m-d H:i:s')."<br>";

    $zonahoraria = date_default_timezone_get();
    echo 'Zona horaria predeterminada: ' . $zonahoraria;

    echo "<br><br>";

    $date = new DateTime(date('Y-m-d H:i:s'), new DateTimeZone('UTC'));
    echo $date->format('Y-m-d H:i') . "<br>";

    $date->setTimezone(new DateTimeZone('America/La_Paz')); 
    echo $date->format('Y-m-d H:i') . "<br>";

?>