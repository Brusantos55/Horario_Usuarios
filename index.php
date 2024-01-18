<?php
include_once "clases/user.php";
include_once "clases/db.php";

if(isset($_SESSION["user"])){
    $user=$_SESSION["user"];  
    $consulta[0]=$user->getId();
}
 
 $pdo=DB::connect();
 $resultado = $pdo->query("SELECT * FROM clases");
 $row = $resultado->fetch(); $i=0;
 while($row!=null){
      $consulta[1][$i] = $row; $i++;
      $row = $resultado->fetch(); 
 }
//  var_dump($consulta[0]);
 $data=json_encode($consulta);
 
 if ($_POST){
    $dwes = new PDO("mysql:host=localhost;dbname=pruebas", "root", "");
    if(isset($_REQUEST['apuntarse']))
    $sql = "INSERT INTO alumnos VALUES (DEFAULT, '{$_POST['clase']}', '{$_POST['alumno']}')";
    if(isset($_REQUEST['eliminar']))
    $sql = "DELETE FROM alumnos WHERE id_usuario = '{$_POST['alumno']}' AND id_clase = '{$_POST['clase']}'";
    if(isset($sql))
    $dwes->exec($sql);
 } 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios Copia</title>
    <link rel="stylesheet" type="text/css" href="horario.css" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script type="text/javascript" src="inscripciones.js"></script> 
    <title>Usuarios y Horario</title>
</head>
<body>
<?php require 'header.php';?><!-- header -->
    <div id="tabla">
        <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Lunes</th>
                    <th>Martes</th>
                    <th>Miercoles</th>
                    <th>Jueves</th>
                    <th>Viernes</th>
                    <!-- <th>Sabado</th> -->
                    <!-- th>Domingo</th -->
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div id="PopUpFondo">
    </div>
    <script>
        var phpData = <?php print_r($data); ?>;
        horario(phpData);
        function horario(phpData) {
            let sesion=phpData[0];
            console.log(phpData[1])
            let ejSQL=ordenaConsulta(phpData[1]);
            let arrHoras = horasTotales(ejSQL);
            let arrSaltarceldas=[];
            let indiceSalto=0;
            let ultimosalto=[[],[]];
            let strHTMLgenerado = "";
            let bolclase = false;
            let indiceClase=0;

            //for con el numero de horas necesarias 
            for (let i=0; i<arrHoras.length; i++) {
                strHTMLgenerado += "<tr><td>" + arrHoras[i] + "</td>";
                //for con el numero de dias 
                for (let j = 0; j < 5; j++) {
                    bolclase=false;
                    //si hay una orden de salto y coincide con los indices actuales
                    if( arrSaltarceldas[indiceSalto]!=undefined &&
                        i==arrSaltarceldas[indiceSalto][0] &&
                        j==arrSaltarceldas[indiceSalto][1]){
                        //y si el ultimo salto no es el ultimo que hay pa saltar
                        if(ultimosalto!=arrSaltarceldas[arrSaltarceldas.length-1]){
                            indiceSalto++
                        }
                        //añado los indices de la ultima celda saltada                                                     
                        ultimosalto=[[i],[j]];
                    } else {
                        //for con el numero de clases 
                        for(let k=0;k<ejSQL.length;k++){
                            //cuando el dia y la HORA de la clase coincide con los indices...
                            if(ejSQL[k]['dia']==j+1 && ejSQL[k]['hora_ini'].substring(0,2)==arrHoras[i]){
                                //ordeno escribir la clase al terminar este bucle
                                indiceClase=k;
                                bolclase=true;
                            }
                        }

                        if(bolclase){
                            //añade nfilas a la info de la clase 
                            nfilas_hora(ejSQL,indiceClase);
                            let onclick
                            if(sesion==null){
                                onclick="";
                            } else {
                                onclick="onclick=\"listaAlumnos("+ejSQL[indiceClase]['id']+","+sesion+")\"";
                            }
                            //genera una celda con la info de la clase
                            strHTMLgenerado +=  "<td rowspan=\""+ejSQL[indiceClase]['nfilas']+
                            "\" style=\"background-color:"+setColor(ejSQL[indiceClase])+"\""+onclick+">"
                            +ejSQL[indiceClase]['hora_ini'].substring(0,5)+"-"+ejSQL[indiceClase]['hora_fin'].substring(0,5)+"<br>"+
                            ejSQL[indiceClase]['descripcion']+"<br>"+ejSQL[indiceClase]['nombre_profe']+"</td>";
                            
                            //si nfilas mayor que uno
                            if(ejSQL[indiceClase]['nfilas']>1){
                                //si añado pero ya salte la ultima
                                if(ultimosalto==arrSaltarceldas[arrSaltarceldas.length-1]){
                                    indiceSalto++
                                }
                                //añado las filas a saltar
                                for(let k=1;k<ejSQL[indiceClase]['nfilas'];k++){
                                    arrSaltarceldas.push([[i+k],[j]]);
                                    arrSaltarceldas=arrSaltarceldas;
                                }
                            }
                            
                        } else {
                            //genera celda vacia
                            strHTMLgenerado += "<td></td>";
                        }
                    }
                }
            }
            //añade el codigo html generado al cuerpo de la tabla
            document.querySelector("tbody").innerHTML = strHTMLgenerado;
        }

        // function horasNecesarias(consulta) {
        //     //no agrega horas intermedias 
        //     let arrHoras = [];
        //     let indiceHIni = 2;
        //     let indiceHFin = 3;
        //     let unico = true;
        //     //para todas las clases
        //     for (let i = 0; i < consulta.length; i++) {
        //         //por cada una de las horas ya guardadas
        //         for (let j = 0; j < arrHoras.length; j++) {
        //             if (j == 0) { unico = true }
        //             //comparo las horas guardadas con la clase que toca
        //             if (consulta[i][indiceHIni].substring(0, 2) == arrHoras[j]) {
        //                 unico = false;
        //             }
        //         }
        //         //si no hay coincidencia agrego la hora a la lista
        //         if (unico){ 
        //             arrHoras.push(consulta[i][indiceHIni].substring(0, 2)) }
        //         //con las horas de inicio ^ y con las de final v
        //         for (let j = 0; j < arrHoras.length; j++) {
        //             if (j == 0) { unico = true }
        //             if (consulta[i][indiceHFin].substring(0, 2) == arrHoras[j]) {
        //                 unico = false;
        //             }
        //         }
        //         //si unico y hora final mayor que enpunto
        //         if (unico && consulta[i][indiceHFin].substring(3, 5)>"00") { 
        //             arrHoras.push(consulta[i][indiceHFin].substring(0, 2)) }
        //     }
        //     return arrHoras;
        // }

        function horasTotales(consulta) {
            //no agrega horas intermedias 
            let arrHoras = [];
            // let indiceHIni = 2;
            // let indiceHFin = 3;
            let horaIni = 23;
            let horaFin = 0;
            //para todas las clases
            for (let i=0; i<consulta.length; i++) {
                if(consulta[i]['hora_ini'].substring(0, 2)<horaIni)horaIni=parseInt(consulta[i]['hora_ini'].substring(0, 2));
                if(consulta[i]['hora_fin'].substring(0, 2)>horaFin)horaFin=parseInt(consulta[i]['hora_fin'].substring(0, 2));
            }
            console.log('horaini = '+horaIni+'\nhoraFin = '+horaFin);
            for(let i=horaIni; i<=horaFin; i++){
                i<10?arrHoras.push("0"+i):arrHoras.push(i.toString())
            }
            console.log(arrHoras)
            return arrHoras;
        }

        function nfilas_hora(clases,indiceClase) {

            let strHoraIniH = clases[indiceClase]['hora_ini'].substring(0, 2);
            let strHoraFinH = clases[indiceClase]['hora_fin'].substring(0, 2);
            let strHoraFinMin = clases[indiceClase]['hora_fin'].substring(3, 5);
            
            let nFilas = parseInt(strHoraFinH) - parseInt(strHoraIniH);
            //si termina despues de enpunto    
            if (parseInt(strHoraFinMin) > 0) { 
                nFilas += 1
                // y si la siguiente clase empieza a la misma hora que termina
                if((indiceClase+1)<clases.length && 
                    clases[indiceClase]['hora_fin'].substring(0,2)==
                    clases[(indiceClase+1)]['hora_ini'].substring(0,2)){ 
                        nFilas -= 1;}
            }
            //agrego otra caracteristica al array
            clases[indiceClase]['nfilas']=nFilas;
            return nFilas;
        }

        function ordenaArray(arr){
            for(var i = 0; i < arr.length; i++){
            
                // Last i elements are already in place 
                for(var j = 0; j < ( arr.length - i -1 ); j++){
                
                    // Checking if the item at present iteration
                    // is greater than the next iteration
                    if(arr[j] > arr[j+1]){
                        
                        // If the condition is true then swap them
                        var temp = arr[j]
                        arr[j] = arr[j + 1]
                        arr[j+1] = temp
                    }
                }
            }
            return arr;
        }

        function ordenaConsulta(consulta){
            for (let i = 0; i < consulta.length; i++) {
                for (let j = 0; j < (consulta.length - i - 1); j++) {
                    if (consulta[j]['dia']+consulta[j]['hora_ini'].substring(0,2) > consulta[j+1]['dia']+consulta[j+1]['hora_ini'].substring(0,2)) {
                        let temp = consulta[j]
                        consulta[j] = consulta[j + 1]
                        consulta[j + 1] = temp
                    }
                }
            }
            return consulta;
        }

        function setColor(clase){
            let color="pink";

            if(clase['descripcion']=="Zazen"){color="goldenrod"}
            if(clase['descripcion']=="Clase individual"){color="lightblue"}
            if(clase['nombre_profe']=="Cristina"){color="lightgreen"}
            
            return color;
        }
    </script>
<?php if(isset($user) && $user->getProfe()) echo"<p style='text-align:rigth;'><a href='clase.php'>Añadir actividad</a></p>"; ?>
</body>
</html>