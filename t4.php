<?php
error_reporting(E_ERROR | E_PARSE);
include 'plantilla.php';
include 'modelo/grafico.php';
include 'modelo/dato.php';
include 'clases/general.php';

    $plantilla = new Plantilla();
    $plantilla->Header();

    $dato = new Dato();

    //TRANSFORMAR QUERY A ARRAY
    $array_datos = array();
    $result=$dato->get_nyse(1,$con);
    while ($obj=mysqli_fetch_object($result)){
        //echo $obj->Open;
        $fila = array("open"=>$obj->Open, "hight"=>$obj->High, "low"=>$obj->Low, "close"=>$obj->Close, "volume"=>$obj->Volume, "gmt_fecha"=>$obj->gmt_fecha);
        array_push($array_datos,$fila);
        
    }
    
    $INTERVALO=120;
    $NUMERO_PIPS_A_GANAR=0.2;
    
    //TRASNFORMAR ARRAY A ARRAY POR INTERVALOS DE TIEMPO
    $fecha_primero=new DateTime($array_datos[0]['gmt_fecha']);
    $fecha_hasta= $fecha_primero->modify('+'.$INTERVALO.' minutes');
    $open= $array_datos[0]['open'];
    $close= null;

    $array_datos_en_intervalos = array();

    for($i=1;$i<count($array_datos);$i++){
        

        $dateTime = new DateTime($array_datos[$i]['gmt_fecha']);
        if($dateTime<$fecha_primero){
            /*
            echo $array_datos[$i]['open']."<br>";
            echo $array_datos[$i]['gmt_fecha']."<br>";
            //echo $dateTime->format('Y-m-d H:i:s')."<br>";
            echo "<br>";
            echo "<br>";
            */
        }else{
            $close= $array_datos[$i-1]['close'];
            $fila = array("open"=>$open, "close"=>$close, "gmt_fecha"=>$fecha_primero);
            array_push($array_datos_en_intervalos,$fila);

            $open=$array_datos[$i]['open'];
            $fecha_primero=new DateTime($array_datos[$i]['gmt_fecha']);
            $fecha_hasta= $fecha_primero->modify('+'.$INTERVALO.' minutes');            

        }


    }


    //COMIENZA EL SCRIPT DEL REPORTE



    $general = new General();

    $toque_por_arriba=0;
    $toco_arriba=false;

    $toco_arriba_aux=false;
    $toco_arriba_cantar_veces_mayor_pips=0;

    for($i=0;$i<count($array_datos_en_intervalos);$i++){

        /*
            echo $array_datos_en_intervalos[$i]['open']."<br>";
            echo $array_datos_en_intervalos[$i]['close']."<br>";
            echo $array_datos_en_intervalos[$i]['gmt_fecha']->format('Y-m-d H:i:s')."<br>";
            //echo $dateTime->format('Y-m-d H:i:s')."<br>";
            echo "<br>";
            echo "<br>";
        */ 
            $des=$general->bandas_bollinger($i,$array_datos_en_intervalos, 10);
           
          
            $dato_grafico.="[".$i.",  ".$array_datos_en_intervalos[$i]['open'].", ".$des['des1'].", ".$des['des2']."],";
            //$des2=$array_datos_en_intervalos[$i]['open']-$des;

            if($array_datos_en_intervalos[$i]['open']>$des['des1'] && !$toco_arriba){
                $toque_por_arriba++;
                $toco_arriba=true;
                $marcar_punto_toque_arriba=$array_datos_en_intervalos[$i]['open'];

                
            }else if($array_datos_en_intervalos[$i]['open']<=$des['des1']){
                $toco_arriba=false;
            }
           
           if($toco_arriba){
                if(abs($marcar_punto_toque_arriba-$array_datos_en_intervalos[$i]['open'])>$NUMERO_PIPS_A_GANAR && !$toco_arriba_aux){
                    echo "marca: ".$marcar_punto_toque_arriba." i: ".$i." precio: ".$array_datos_en_intervalos[$i]['open']."  des1: ".$des['des1']."<br>";
                    $toco_arriba_aux=true;
                    $toco_arriba_cantar_veces_mayor_pips++;
                }
           }else{
               $toco_arriba_aux=false;
           }
    }
    
     echo "Toque_x_arriba: ".$toque_por_arriba."<br>";
     echo "Toque_x_arriba_mayor_20pips: ".$toco_arriba_cantar_veces_mayor_pips."<br>";
     $efectividad_des1=($toco_arriba_cantar_veces_mayor_pips/$toque_por_arriba)*100;
     echo "efectividad: ".($efectividad_des1)."<br>";

    $tabla1='<table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Id</th>
                <th>Precio</th>
                <th>Fecha</th>
                <th>% Variación</th>
                <th>% Variación vs Promedio</th>
            </tr>
            </thead>
            <tbody>';
    


    $tabla1.='</tbody>
              </table>';


// curveType: 'function',
    $grafico_js="<script type='text/javascript'>
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                        ['Fecha', 'Precio', 'des1', 'des2'],
                        ".$dato_grafico."
                
                        ]);

                        var options = {
                       
                        legend: { position: 'bottom' }
                        ,
                        explorer: {
                            maxZoomOut:2,
                            keepInBounds: true
                        }, 
                        series: {
                            0: { color: '#0000FF' },
                            1: { color: '#FF0000' },
                            2: { color: '#FF0000' }
                            },
                        };

                        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                        chart.draw(data, options);
                    }
                </script>";

?>


<body>

<div class="jumbotron text-center">
  <h2>Testing teoria H1</h2>
</div>
  
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <h3>Reporte</h3>
      <p>promedio, desviacion estantdar, entradas, salidas</p>
        
        <div id="curve_chart" style="width: 900px; height: 500px"></div>
        
        <?php 
            echo $tabla1;
        ?>


    </div>

  </div>
</div>

        <?php 
            echo $grafico_js;
        ?>


</body>




<?php 
    $plantilla->Footer(); 
?>