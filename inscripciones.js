var alumnos=undefined
var usuarios=undefined
var clases=undefined

window.onload = consulta();

function consulta(){
    fetch("consulta.php")
    .then(function (response){
        return response.json();
    })
    .then(function (data) {
        principal(data);
    }) 
    console.log('inscripcciones')
}

function principal(data){
    console.log(data)
    alumnos=data[0];
    usuarios=data[1];
    clases=data[2]
}


function listaAlumnos(idClase,idUser){
    document.getElementById('PopUpFondo').style.display="flex";
    document.getElementById('PopUpFondo').innerHTML='<div id="PopUpCuerpo"><div id="PopUpHeader"><div></div><div class="boton" onclick="cerrarP()">x</div></div><div id="PopUpMain"><h3></h3><div id="textoprueba"></div><div id=botonP></div</div></div>'

    let al=[];
    let apuntado=false;
    let formtext="<form action='index.php' method='post'><input name='clase' type='hidden' value='"
    +clases[idClase][0]+"'/><input name='alumno' type='hidden' value='"+idUser+"'/>";
    
    console.log(alumnos)
    for(let i = 0; i<alumnos.length; i++){
        if(alumnos[i][1]==clases[idClase]['id']){
            al.push(alumnos[i][2])
        }
        if(alumnos[i]['id_clase']==idClase && alumnos[i]['id_usuario']==idUser){
            apuntado=true;
        } 
    }
    apuntado ? formtext+="<input name='eliminar' type='submit' value='desapuntarse'/>" : formtext+="<input name='apuntarse' type='submit' value='Apuntarse'/>";
    
    let texto="hay "+al.length+" alumnos apuntados</p>";
    document.querySelector("#PopUpMain > h3").innerHTML=clases[idClase]['descripcion']+" | "+clases[idClase]['hora_ini'].substring(0,5)+" - "+clases[idClase]['hora_fin'].substring(0,5);
    if(al.length<16){
        document.getElementById('botonP').innerHTML=formtext+"</form>";
    } else {
        texto+=' <br><b>La clase esta completa</b>'
    }    
    if(apuntado)texto+=' Ya estas apuntado';
    document.getElementById('textoprueba').innerHTML = texto;
}
function cerrarP(){
    document.getElementById('PopUpFondo').style.display="none";
}