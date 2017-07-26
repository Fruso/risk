<?php
include './conexion.php';

class Dato{


    function get_nyse($id_nyse, $con){
        $result=mysqli_query($con,"SELECT * FROM `nyse_apple` WHERE TIME(gmt_fecha)>='15:00:00' AND TIME(gmt_fecha)<'21:00:00' AND DAYOFWEEK(gmt_fecha) != 1 AND DAYOFWEEK(gmt_fecha) != 7 ORDER BY gmt_fecha ASC");
        return $result;
    }

    function cargar_csv($array_dato, $con){
        $result=mysqli_query($con,"INSERT INTO forex_eurousd (Gmt_time, Open, High, Low, Close, Volume) VALUES ('".$array_dato['Gmt_time']."', '".$array_dato['Open']."', '".$array_dato['Hight']."', '".$array_dato['Low']."', '".$array_dato['Close']."', '".$array_dato['Volume']."');");
        return $result;
    }
    function get_eurousd($id_nyse, $con){
        $result=mysqli_query($con,"SELECT * FROM `forex_eurousd` WHERE DAYOFWEEK(gmt_fecha) != 1 AND DAYOFWEEK(gmt_fecha) != 7 AND YEAR(gmt_fecha)>=2005 ORDER BY gmt_fecha ASC");
        return $result;
    }

} 


    /*
    $result=mysqli_query($con,"SELECT * FROM `divisas_datos` where id_divisa=1");
        while ($obj=mysqli_fetch_object($result)){
          
            echo $obj->valor;
            echo "<br>";
        }
     mysqli_free_result($result);   
    */


?>