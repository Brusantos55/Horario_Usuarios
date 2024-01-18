<?php
include_once "clases/user.php";
include_once "clases/db.php";
 
$pdo=DB::connect();

$resultado = $pdo->query("SELECT * FROM alumnos");
$row = $resultado->fetch(); $i=0;
while($row!=null){
    $consulta[0][$i] = $row; $i++;
    $row = $resultado->fetch(); 
}

$resultado = $pdo->query("SELECT * FROM usuarios");
$row = $resultado->fetch(); $i=0;
while($row!=null){
    $consulta[1][$i] = $row; $i++;
    $row = $resultado->fetch(); 
}

$resultado = $pdo->query("SELECT * FROM clases");
$row = $resultado->fetch(); $i=0;
while($row!=null){
    if($row['id']==$i){
        $consulta[2][$i] = $row; $i++;
        $row = $resultado->fetch(); 
    } else{
        $i++;
    }
}
echo json_encode(mb_convert_encoding($consulta, "UTF-8", "UTF-8"));
?>