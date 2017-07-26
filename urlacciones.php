<?php
include 'conexion.php';
//VC4G
$piso=$_GET['piso'];
$alcance=$_GET['alcance'];

echo $piso;
echo "<br>";
echo $alcance;
echo "<br>";
echo "<br>";
echo "<br>";

$time_start = microtime(true); 

$total=0;
if ($result=mysqli_query($con,"SELECT * FROM `nyse`ORDER BY Symbol ASC limit ".$piso.",".$alcance))
  {
  while ($obj=mysqli_fetch_object($result))
    {
      
        $url="http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.quotes%20where%20symbol%20in%20(%22".$obj->Symbol."%22)&env=store://datatables.org/alltableswithkeys&format=json";
        $contenido = file_get_contents($url);
        $obj_json = json_decode($contenido, true);

        if($obj_json['query']['results']['quote']['LastTradePriceOnly']!=""){
            echo " Accion: ".$obj->Symbol."<br>";
            echo " precio: ".$obj_json['query']['results']['quote']['LastTradePriceOnly']."<br>";
            echo " fecha gmt: ".gmdate("Y-m-d H:i:s")."<br>";
            echo " Volume: ".$obj_json['query']['results']['quote']['Volume']."<br>";
            echo " AverageDailyVolume: ".$obj_json['query']['results']['quote']['AverageDailyVolume']."<br>";
            echo " created: ".$obj_json['query']['created']."<br>";
            echo "<br>";

            $id_nyse=$obj->id;
            $precio=$obj_json['query']['results']['quote']['LastTradePriceOnly'];
            $fecha_gmt=gmdate("Y-m-d H:i:s");
            $volumen=$obj_json['query']['results']['quote']['Volume'];
            $AverageDailyVolume=$obj_json['query']['results']['quote']['AverageDailyVolume'];
            $created=$obj_json['query']['created'];

            //INSERTAR DATOS
            mysqli_query($con,"INSERT INTO nyse_data ( id_nyse, gmt_fecha, precio, Volume, AverageDailyVolume, created) VALUES (".$id_nyse.", '".$fecha_gmt."', '".$precio."', '".$volumen."', '".$AverageDailyVolume."', '".$created."')");

        }
        
      
        $total++;
    }
    echo "<br>";
    echo $total;

  mysqli_free_result($result);
}




$time_end = microtime(true);

//dividing with 60 will give the execution time in minutes other wise seconds
$execution_time = ($time_end - $time_start)/60;

//execution time of the script
echo "<br>";echo "<br>";echo "<br>";
echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';

?>


