<?php
error_reporting(E_ERROR | E_PARSE);
include 'plantilla.php';
include 'modelo/grafico.php';
include 'modelo/dato.php';
include 'clases/general.php';


$dato = new Dato();

$fila = 1;
if (($gestor = fopen("archivos/test2.csv", "r")) !== FALSE) {
    while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {
        
        /*
        $numero = count($datos);
        echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
        $fila++;
        for ($c=0; $c < $numero; $c++) {
            echo $datos[$c] . "<br />\n";

        }
        */

             $afila = array("Gmt_time"=>$datos[0], "Open"=>$datos[1], "Hight"=>$datos[2], "Low"=>$datos[3], "Close"=>$datos[4], "Volume"=>$datos[5]);
             $dato->cargar_csv($afila,$con);
    }
    fclose($gestor);
}
?>