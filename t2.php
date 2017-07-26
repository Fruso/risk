<?php
error_reporting(E_ERROR | E_PARSE);
include 'plantilla.php';
include 'modelo/grafico.php';



    $plantilla = new Plantilla();
    $plantilla->Header();

    $grafico = new Grafico();

    $result=$grafico->obtener_datos(1,1,$con);
    $result1=$grafico->obtener_datos(1,1,$con);

    $tabla1='<table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Id</th>
                <th>Precio</th>
                <th>Fecha</th>
                <th>% Variación</th>
                <th>% Variación Promedio</th>
            </tr>
            </thead>
            <tbody>';
    
    $contador1=1;
    $valor_anterior=1;
    $suma_variacion=0;
    $variacion_maxima=0;


    while ($obj1=mysqli_fetch_object($result1)){

            if($contador1==1){
                $valor_anterior=$obj1->valor; 
            }else{

                $porcentaje_variacion=((floatval($obj1->valor)/floatval($valor_anterior))-1)*100;
                $variacion=abs(round($porcentaje_variacion,5));
                $suma_variacion+= $variacion;
                $valor_anterior=$obj1->valor; 
                if( $variacion > $variacion_maxima){
                    echo $variacion;
                    echo '<br>';
                    $variacion_maxima=$variacion;
                }

            }
            $contador1++;


    }
    echo $suma_variacion;
    $promedio_variaciones=round($suma_variacion/$result1->num_rows,5);
    echo '<br>';
    echo $variacion_maxima;
    echo '<br>';
    echo $promedio_variaciones;

    $contador1=1;
    $valor_anterior=1;

    while ($obj=mysqli_fetch_object($result)){

        
        if($contador1==1){
            $tabla1.='<tr>
                    <td>'.$contador1.'</td>
                    <td>'.$obj->id.'</td>
                    <td>'.$obj->valor.'</td>
                    <td>'.$obj->fecha.'</td>
                    <td>-</td>
                    <td>-</td>
                  </tr>';
           $valor_anterior=$obj->valor;  
           
        }else{

            $porcentaje_variacion=round(((floatval($obj->valor)/floatval($valor_anterior))-1)*100,5);

            $porcentaje_variacion_promedio=round(((abs($porcentaje_variacion)/($variacion_maxima)))*100,2);
       
            
            $tabla1.='<tr>
                    <td>'.$contador1.'</td>
                    <td>'.$obj->id.'</td>
                    <td>'.$obj->valor.'</td>
                    <td>'.$obj->fecha.'</td>
                    <td>'.$porcentaje_variacion.'</td>
                    <td>'.$porcentaje_variacion_promedio.'</td>
                  </tr>';
            $valor_anterior=$obj->valor;
                     
        }



        $contador1++;         
    }



    $tabla1.='</tbody>
              </table>';




?>


<body>

<div class="jumbotron text-center">
  <h2>Información General Divisas</h2>
</div>
  
<div class="container">
  <div class="row">
    <div class="col-sm-12">
      <h3>Data</h3>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>

        <?php 
            echo $tabla1;
        ?>


    </div>

  </div>
</div>

</body>




<?php 
    $plantilla->Footer(); 
?>