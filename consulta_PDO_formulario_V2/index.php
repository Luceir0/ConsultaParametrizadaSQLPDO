<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Consulta parametrizada SQL PDO</title>
    </head>
    <body>
        <?php
        require_once 'conexiones.inc.php';
        
        echo '<form action="'.$_SERVER["PHP_SELF"].'" method="post">';
        echo 'Nombre <input type="text" name="nombre"> </br> </br>';
        echo 'Nif <input type="text" name="nif"> </br> </br>';
        echo 'Apellido 1 <input type="text" name="ap1"> </br> </br>';
        echo 'Apellido 2 <input type="text" name="ap2"> </br> </br>';
        echo '<input value="Consultar" type="submit" name="aceptar"> </br> </br>';
        echo '</form>';
        
        //Valores iniciales de strings de consulta:
        
        $initialQuery = 'SELECT * FROM persona';
        $restrictions = '';
        
        //Posibles parámetros que se pueden tener en cuenta en la consulta:
        
        $possibleParams = ['nif', 'nombre', 'ap1', 'ap2'];
        
        if (count ($_POST) > 1){            
            try {
                //Recorrido de los posibles parámetros teniendo en cuenta los valores de $_POST, incluyendo las restricciones a string $restrictions:
                    foreach ($possibleParams as $oneParam) {
                        if(!empty ($_POST[$oneParam])) {
                            if (empty($restrictions)){
                                $restrictions = $restrictions . 'WHERE ' . $oneParam . '=:' . $oneParam;
                            } else {
                                $restrictions = $restrictions . ' AND ' . $oneParam . '=:' . $oneParam;
                            }
                        }
                    }
                    
                    //Conexión:
                    
                   $con = conecta();
                    
                   //Formateo final de la query:
                   
                   $stringQuery = $initialQuery . ' ' . $restrictions;
                   
                   //Preparación de la consulta:
                   
                    $consulta = $con -> prepare($stringQuery);
                   
                    //Bindeo de parámetros, y asociación de valores:
                    
                    if(!empty ($_POST["nif"])) {
                      $consulta -> bindParam(':nif', $parametro_nif, PDO::PARAM_STR);
                      $parametro_nif = $_POST["nif"];
                    } 
                    if (!empty ($_POST["nombre"])){
                      $consulta -> bindParam(':nombre', $parametro_nombre, PDO::PARAM_STR);
                      $parametro_nombre = $_POST["nombre"];
                    } 
                    if (!empty ($_POST["ap1"])){
                      $consulta -> bindParam(':ap1', $parametro_ap1, PDO::PARAM_STR);
                      $parametro_ap1 = $_POST["ap1"];
                    } 
                    if (!empty ($_POST["ap2"])){
                      $consulta -> bindParam(':ap2', $parametro_ap2, PDO::PARAM_STR);
                      $parametro_ap2 = $_POST["ap2"];
                    }
                    
                    //Ejecución de la consulta:
                    
                    $consulta -> execute();
                    
                    //Muestra de datos en pantalla:
                    
                    while ($registro = $consulta -> fetch()) {
                        echo $registro["nif"] . '/' . $registro["nombre"] . '/' . $registro["ap1"] . '/' . $registro["ap2"] . '<br/>';  
                }
                
                //Catch de errores:
                
            } catch (PDOException $e) {
                echo $e -> getMessage();
            }finally {
                
                //Cierre de consulta y conexión:
                
                $consulta = null;
                $con = null;
            }
        }

        ?>
    </body>
</html>
