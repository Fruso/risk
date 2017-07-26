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
      
        $url="http://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=".$obj->Symbol."&interval=1min&apikey=VC4G";
        $contenido = file_get_contents($url);
        $obj_json = json_decode($contenido, true);
        
        //$fecha_gmt=gmdate("Y-m-d H:i",strtotime("-4 hour")).":00";
        $fecha_gmt=gmdate("Y-m-d H:i:s");

        $first_value = key($obj_json['Time Series (1min)']);
       

           
                echo " simbolo: ".$obj->Symbol."<br>";
                echo " open: ".$obj_json['Time Series (1min)'][''.$first_value]['1. open']."<br>";
                echo " hight: ".$obj_json['Time Series (1min)'][''.$first_value]['2. high']."<br>";
                echo " low: ".$obj_json['Time Series (1min)'][''.$first_value]['3. low']."<br>";
                echo " close: ".$obj_json['Time Series (1min)'][''.$first_value]['4. close']."<br>";
                echo " volume: ".$obj_json['Time Series (1min)'][''.$first_value]['5. volume']."<br>";
            
                echo "<br>";

                $id_nyse=$obj->id;
                $gmt_fecha=$fecha_gmt;
                $open=$obj_json['Time Series (1min)'][''.$first_value]['1. open'];
                $hight=$obj_json['Time Series (1min)'][''.$first_value]['2. high'];
                $low=$obj_json['Time Series (1min)'][''.$first_value]['3. low'];
                $close=$obj_json['Time Series (1min)'][''.$first_value]['4. close'];
                $volumen=$obj_json['Time Series (1min)'][''.$first_value]['5. volume'];
                $gmt_fecha_api=$first_value;

            //INSERTAR DATOS
            mysqli_query($con,"INSERT INTO nyse_data ( id_nyse, gmt_fecha, open, hight, low, close,volume,gmt_fecha_api) VALUES (".$id_nyse.", '".$fecha_gmt."', '".$open."', '".$hight."', '".$low."', '".$close."', '".$volumen."', '".$gmt_fecha_api."')");


        
      
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


