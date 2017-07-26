<?php
include 'conexion.php';
//VC4G

echo "<br>";
        $url="http://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=GOOG&interval=1min&apikey=VC4G";
        $contenido = file_get_contents($url);
        $obj_json = json_decode($contenido, true);

    
 
   
        
        $first_value = key($obj_json['Time Series (1min)']);
        echo $first_value;
        

        $fecha_gmt=gmdate("Y-m-d H:i",strtotime("-4 hour")).":00";
        echo " precio: ".$obj_json['Time Series (1min)'][$first_value]['1. open']."<br>";
        echo "<br>";



?>


