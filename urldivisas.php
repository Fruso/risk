<?php
include 'conexion.php';



if ($result=mysqli_query($con,"SELECT * FROM `divisas`"))
  {
  while ($obj=mysqli_fetch_object($result))
    {
      
        $url="https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20csv%20where%20url%3D%22http%3A%2F%2Ffinance.yahoo.com%2Fd%2Fquotes.csv%3Fe%3D.csv%26f%3Dc4l1%26s%3D".$obj->simbolo."%3DX%22%3B&format=json&diagnostics=true&callback=";
        $contenido = file_get_contents($url);
        $obj_json = json_decode($contenido, true);


        echo " Divisa: ".$obj->simbolo." precio: ".$obj_json['query']['results']['row']['col1']." fecha: ".gmdate("Y-m-d H:i:s");
        
        echo "<br>";
        
        //INSERTAR DATOS
        mysqli_query($con,"INSERT INTO divisas_datos (id_divisa, valor, fecha) VALUES (".$obj->id.", '".$obj_json['query']['results']['row']['col1']."', '".gmdate("Y-m-d H:i:s")."')");


    }

  mysqli_free_result($result);
}


/*
$url="https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20csv%20where%20url%3D%22http%3A%2F%2Ffinance.yahoo.com%2Fd%2Fquotes.csv%3Fe%3D.csv%26f%3Dc4l1%26s%3DEURUSD%3DX%22%3B&format=json&diagnostics=true&callback=";
$contenido = file_get_contents($url);
$obj_json = json_decode($contenido, true);


print_r($obj_json);

echo "<br>";


print_r($obj_json['query']['results']['row']['col1']);
*/
?>


