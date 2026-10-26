<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        $arrayPrecios= array();
        for($i=0; count($arrayPrecios)<= 15; $i++){
            $arrayPrecios[]=1.66*$i;
            
        }
        echo"<pre>";
        print_r($arrayPrecios);
        echo "</pre>";
        echo "<br>";
        echo"<pre>";
        print_r(array_count_values($arrayPrecios));
        echo "</pre>";


       echo implode(" ", $arrayPrecios);

echo "<br>";
       $dias = array("lunes","miércoles", "juevesss", "lunes", "domingo", "domingo", "martes", "juevesssss", "jueves", "sabado", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo");
       $arrayPosicionJueves= array_keys($dias,"jueves");
 echo"<pre>";
       print_r($dias);
       echo "</pre>";
       foreach($arrayPosicionJueves as $posicion){
        unset($dias[$posicion]);
       }
       echo"<pre>";
       print_r($dias);
       echo "</pre>";
       $coincidenciasJueves = preg_grep("/jueves{3,}/", $dias);
       echo"<pre>";
       print_r($coincidenciasJueves);
       echo "</pre>";

       $semana = array ("02A","A98","67P","96N","A98","67P","96N","02A","A98","67P","96N");
       $coincideniasSemana = preg_grep("^[0-9][2-6][ANR]/", $semana);
       echo"<pre>";
       print_r($coincideniasSemana);
       echo "</pre>";


       //comparación email validación
         $emails = array("aaaaa@.com", "invalid-email", "another@example.com");
        $reg = "#^(((([a-z\d][\.\-\+_]?)*)[a-z0-9])+)\@(((([a-z\d][\.\-_]?){0,62})[a-z\d])+)\.([a-z\d]{2,6})$#i"; 
         $validEmails = preg_grep($reg, $emails);
         echo"<pre>";
         print_r($validEmails);
         echo "</pre>";

         echo array_shift($dias);
         var_dump($dias);
         echo array_pop($dias);
         var_dump($dias);
         echo array_unshift($dias, "lunes");
         var_dump($dias);
         echo array_push($dias, "domingo");
         var_dump($dias);
        echo"<pre>";
         print_r($dias);
         echo "</pre>";


         $precio = array(10, 20, 30, 40, 50);
         function aDolares(&$precio, $clave){
            $precio = $precio * 1.05;
         }

         array_walk($precio, "aDolares");
         echo"<pre>";
         print_r($precio);
         echo "</pre>";

         $matriz_destino=array("altura"=>1.75, "peso"=>70, "edad"=>30);
            $matriz_origen=array("altura"=>1.80, "peso"=>80, "edad"=>25, "pelo"=>"rubio");
            $matriz_destino=array_replace($matriz_destino, $matriz_origen);
            echo"<pre>";
            print_r($matriz_destino);
            echo "</pre>";

        
        $pricios1 = array(10, 20, 30, 40, 50,200,"200");
        $pricios2 = array(60, 70, 80, 90, 100,200);
        $pricios3 = array(110, 120, 130, 140, 150,200);
        $pricios4 = array(160, 170, 180, 190, 200,200);
       
        echo"<pre>";
        print_r(array_merge($pricios1, $pricios2, $pricios3, $pricios4));
        echo "</pre>";  


        echo"<pre>";
        print_r(array_merge_recursive($pricios1, $pricios2, $pricios3, $pricios4));
        echo "</pre>";


        // aquí faltan funciones porque me fui, hay que mirarlo en el pdf


        echo"<pre>";
        print_r(array_intersect($pricios1, $pricios2, $pricios3, $pricios4));
        echo "</pre>";
        echo"<pre>";
        print_r(array_intersect_assoc($pricios1, $pricios2, $pricios3, $pricios4));
        echo "</pre>";
  
    ?>
</body>
</html>