<?php
include './conexion.php';

class General{


    function bandas_bollinger($index,$array_datos, $periodo){
       
        if($index-$periodo<0){
            
            $fila = array("des1"=>$array_datos[$index]['open'], "promedio"=>$array_datos[$index]['open'], "des2"=>$array_datos[$index]['open']);
            return $fila;
        }else{

            $suma=0;
            $aux=0;
            for($i=($index-$periodo)+1;$i<=$index;$i++){
                    $suma+=floatval($array_datos[$i]['open']);
                    $aux++;
            }
            $promedio=($suma/$periodo);
            //echo $aux." fumn<br>";

            $suma_diferencias=0;
            for($i=($index-$periodo)+1;$i<=$index;$i++){
                $dif=abs($promedio-$array_datos[$i]['open']);
                $suma_diferencias+=($dif*$dif);
            }
            
            $promedio_diferencias=$suma_diferencias/$periodo;
            $des =  sqrt ( $promedio_diferencias );
            $fila = array("des1"=>$promedio+$des, "promedio"=>$promedio, "des2"=>$promedio-$des);
            return $fila ;

        }
    }




    function datos_a_intervalos($array_datos, $intervalo_minutos){

        $fecha_primero=new DateTime($array_datos[0]['gmt_fecha']);
        $fecha_hasta= $fecha_primero->modify('+'.$intervalo_minutos.' minutes');
        $open= $array_datos[0]['open'];
        $id_open= $array_datos[0]['id'];
         
        $close= null;
        $id_close= null;

        $hight=-100000;
        $low=100000;

        $array_datos_en_intervalos = array();

        for($i=1;$i<count($array_datos);$i++){
            

            $dateTime = new DateTime($array_datos[$i]['gmt_fecha']);
            if($dateTime<$fecha_primero){

                if($array_datos[$i]['open']>$hight){
                    $hight=$array_datos[$i]['open'];
                }

                if($array_datos[$i]['open']<$low){
                    $low=$array_datos[$i]['open'];
                }                
                /*
                echo $array_datos[$i]['open']."<br>";
                echo $array_datos[$i]['gmt_fecha']."<br>";
                //echo $dateTime->format('Y-m-d H:i:s')."<br>";
                echo "<br>";
                echo "<br>";
                */
            }else{
                $close= $array_datos[$i-1]['close'];
                $id_close= $array_datos[$i-1]['id'];
                $fila = array("id_open"=>$id_open,"id_close"=>$id_close,"open"=>$open,"open"=>$open, "close"=>$close, "hight"=>$hight, "low"=>$low, "gmt_fecha"=>$fecha_primero);
                array_push($array_datos_en_intervalos,$fila);

                $open=$array_datos[$i]['open'];
                $id_open=$array_datos[$i]['id'];


                $fecha_primero=new DateTime($array_datos[$i]['gmt_fecha']);
                $fecha_hasta= $fecha_primero->modify('+'.$intervalo_minutos.' minutes');     
                $hight=-100000;
                $low=100000;                       

            }


        }       
        return $array_datos_en_intervalos;

    }

    function recorrer_micro_registros_intervalos($array_datos, $id_open, $id_close){

    }



} 



?>