<?php 
    include_once "clases/user.php"; 
    include_once "clases/db.php";
    
    $pdo=DB::connect();

    $profeBool = (bool)$_SESSION["user"]->getProfe();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Rural YogaTierra</title>
</head>
<body>
    <?php require 'header.php';?>
    <div id="titulos">
        <h1>
            <?php echo $name; ?>
        </h1>
        <h2>Tus Clases</h2>
        <?php

        function getDay($day){
            $days = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes");
            return $days[$day-1] ?? 'Fin de Semana';
        }

        if($profeBool){

            $resultado = $pdo->query("SELECT * FROM clases where nombre_profe = '{$name}'");
            $cantidad = $resultado->rowCount();
            $row = $resultado->fetch();
            if($cantidad>0){ 
                while($row!=null){
                    echo '<form action="aPriv.php" method="post">'.
                    "<input name='clase' type='hidden' value='".$row['id']."'/>".
                    getDay($row['dia']).' '.substr($row['hora_ini'],0,5).' - '.$row['descripcion'].
                    "<input name='eliminar' type='submit' value='Eliminar Clase'/></form>";
                    $row = $resultado->fetch(); 
                }
            } else {
                echo 'No tienes ningun alumno';
            }

        } else {
            
            $resultadoClases = $pdo->query("SELECT * FROM alumnos where id_usuario = '{$_SESSION["user"]->getId()}'");
            $cantidad = $resultadoClases->rowCount();
            if($cantidad>0){ 
                $clases = $resultadoClases->fetch();
                while($clases!=null){
                        $resultado = $pdo->query("SELECT * FROM clases where id = '{$clases['id_clase']}'");
                        $row = $resultado->fetch();
                        
                        echo '<form action="aPriv.php" method="post">'.
                        "<input name='clase' type='hidden' value='".$clases['id']."'/>".
                        getDay($row['dia']).' '.substr($row['hora_ini'],0,5).' - '.$row['descripcion'].
                        "<input name='desapuntarse' type='submit' value='desapuntarse'/></form>";

                        $row = $resultado->fetch(); 
                        $clases = $resultadoClases->fetch(); 
                    }
            } else {
                echo 'No estas apuntado a ninguna clase';
            }
        }

        if(isset($_REQUEST['desapuntarse'])){
            $pdo->query("DELETE FROM alumnos WHERE id = '{$_POST['clase']}'");
            header("Refresh:0.1;");
        }

        if(isset($_REQUEST['eliminar'])){
            $pdo->query("DELETE FROM clases WHERE id = '{$_POST['clase']}'");
            header("Refresh:0.1;");
        }

        ?>
    </div>
</body>
</html>
