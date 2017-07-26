<?php
include './conexion.php';

class Grafico{


    function obtener_datos($id_divisa, $periodo_minutos,$con){
        
        $result=mysqli_query($con,"SELECT * FROM `divisas_datos` where id_divisa=1");

        /*
        $result=mysqli_query($con,"select t.*,
                            (select avg(t2.valor)
                                from divisas_datos t2
                                where t2.fecha <= t.fecha and t2.fecha >= date_sub(t.fecha, interval 5 minute)  and t2.id_divisa=t.id_divisa
                            ) as avgprice
                            from divisas_datos t where t.id_divisa=1
                            GROUP BY UNIX_TIMESTAMP(t.fecha) DIV 300");
        */
          
        return $result;

    }
   
    function obtener_datos_nyse_fb($id_divisa, $periodo_minutos,$con){
        
        $result=mysqli_query($con,"SELECT * FROM `nyse_amazon` WHERE TIME(gmt_fecha)>='15:00:00' AND TIME(gmt_fecha)<'21:00:00' AND DAYOFWEEK(gmt_fecha) != 1 AND DAYOFWEEK(gmt_fecha) != 7 ORDER BY gmt_fecha ASC");


        return $result;
    }
   

   function obtener_datos_json($id_divisa, $periodo_minutos,$con){
        $result=mysqli_query($con,"SELECT * FROM `divisas_datos` where id_divisa=1");
        $myArray = array();

        while ($obj=mysqli_fetch_object($result)){
            array_push($myArray, $obj);
 
        }        
        $obj_json = json_encode($myArray);

        echo $obj_json;

   }



   function test(){
    echo 'is work';
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