<?php
include_once "clases/bd.php";
include_once "clases/user.php";

$pdo=DB::connect(); 

if($_REQUEST['aniadir']){

    $dia         = $_POST['dia'];
    $profe       = $_POST['nameP'];
    $hora_ini    = $_POST['hora_ini'];
    $hora_fin    = $_POST['hora_fin'];
    $descripcion = $_POST['nombre'];
    
    $pdo->query("INSERT INTO clases (nombre_profe,hora_ini,hora_fin,descripcion, dia)
        VALUES('$profe','$hora_ini','$hora_fin','$descripcion', '$dia');");
    
}

if($_REQUEST['eliminar']){

    $clase=$_POST['clase'];
    $pdo->query("DELETE FROM `clases` WHERE `clases`.`id` = $clase");

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <title>Document</title>
</head>
<body>
<div id="form">
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="fRegistro">
    <h2 style="text-align: center;">Nueva actividad</h2>
    <div class="couple" style="flex-direction:row;">
        <div class="couple">
            <label for="name">Nombre de la actividad</label>
            <input
            type="text"
            id="name"
            name="nombre"
            autofocus="autofocus"
            required="required"
            maxlength="50"
            />
        </div>
        <div class="couple">
            <label for="nameP">Nombre del profesor</label>
            <select name="nameP" style="height: 42px;">
                <?php
                $resultado = $pdo->query("SELECT nombre FROM usuarios WHERE `profe`=1");

                    if($resultado) {
                        
                        $row = $resultado->fetch();
                        while($row!=null){
                            $namePC = explode(" ", $row['nombre']);
                            $nombre=$namePC[0];
                            echo "<option value='$nombre'>$nombre</option>";
                            $row = $resultado->fetch();
                        }
                    }
                ?>
            </select>
        </div>
    </div>
    <div class="couple" style="flex-direction:row;"> 
        <div class="couple">
            <label for="dia">dia:</label>
            <select id="dia" name="dia">
                <option value="1">Lunes</option>
                <option value="2">Martes</option>
                <option value="3">Miercoles</option>
                <option value="4">Jueves</option>
                <option value="5">Viernes</option>
            </select>
        </div>
        <div class="couple">
            <label for="hora_ini">Hora de inicio</label>
            <input
            type="time"
            name="hora_ini"
            id="hora_ini"
            required="required"
            />
        </div>
        <div class="couple">
            <label for="hora_fin">Hora de final</label>
            <input
            type="time"
            name="hora_fin"
            id="hora_fin"
            required="required"
            />
        </div>
    </div>
    <input type="submit" name="aniadir" value="añadir"/>
    <p><a href="horario.php">Volver</a></p>
</form>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="fRegistro">
    <div class="couple">
    <h2 style="text-align: center;">Eliminar actividad</h2>
        <label for="clase">clase:</label>
        <select id="clase" name="clase">
        <?php
        $resultado = $pdo->query("SELECT * FROM clases");
        $row = $resultado->fetch();
            while($row!=null){
                switch ($row['dia']) {
                    case 0:
                        $dia="Lunes";
                        break;
                    case 1:
                        $dia="Martes";
                        break;
                    case 2:
                        $dia="Miercoles";
                        break;
                    case 3:
                        $dia="Jueves";
                        break;
                    case 4:
                        $dia="Viernes";
                        break;
                    case 5:
                        $dia="Sabado";
                        break;
                    }
                echo "<option value=".$row['id'].">".$dia.' '.substr($row['hora_ini'],0,5).' - '.$row['descripcion']."</option>";     
                $row = $resultado->fetch(); 
            }
        ?>
        </select>
    </div>
    <input type="submit" name="eliminar" value="eliminar"/>
</form>
</div>
</body>
</html>