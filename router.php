<?php
//Aplicable solo para obtener informacion en formato json
include 'modelo/grafico.php';

$op = 0;
$op = $_REQUEST['op'] ?? '0';

if($op==1){
    $grafico = new Grafico();
    $grafico->obtener_datos_json(1,1,$con);
}
if($op==2){
    
}
if($op==3){
    
}
if($op==4){
    
}


?>