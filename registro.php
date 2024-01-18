<?php
include_once 'clases/user.php';

if($_POST){
    if( $_POST['password']===$_POST['passwordos']){
        $email = $_POST['email'];
        $password = sha1($_POST['password']);
        $nombre = $_POST['nombre'];
        $telefono = $_POST['telefono'];

        $query = DB::connect()->prepare("INSERT INTO usuarios (email,password,nombre,telefono)
          VALUES('$email','$password','$nombre','$telefono');");

        $resultado = $query->execute(); 
        
        if($resultado==1){
          $user = new User();
          $user->setUser($email);
          header("location: index.php");
        } 
    } else {
        $pdError="<span style='color:red;'>Las contraseñas no coinciden</span>";
    }
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
<style>
.couple:nth-child(-n +2){
margin: 0 0 10px;}
</style>
<body>
<div id="form">
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" id="fRegistro">
    <h2 style="text-align: center;">Registrarse</h2>
      <div class="couple">
        <label for="name">Nombre completo</label>
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
            <div class="label">
                <label for="email">Correo electrónico
                <span title="campo obligatorio">*</span></label>
            </div>
            <input
            type="email"
            name="email"
            id="email"
            required="required"
            placeholder="ejemplo@mail.net"
            />
        </div>
        <div class="couple">
            <label for="password">Contraseña:<span title="campo obligatorio">*</span></label>
            <div><input type="password" name="password" id="password" style="width: -moz-available;">
            <i class="bi bi-eye-slash " id="togglePassword"></i></div>
        </div>
        <div class="couple">
            <label for="passwordos">Repite la contraseña:<span title="campo obligatorio">*</span></label>
            <div><input type="password" name="passwordos" id="passwordos" style="width: -moz-available;">
            <i class="bi bi-eye-slash " id="togglePasswordos"></i></div>
            <?php if(isset($pdError))echo $pdError; ?>
        </div>
        <div class="couple">
            <label for="name">Telefono</label>
            <input type="number" id="telf" name="telefono" />
        </div>
        <input type="submit" value="enviar"/>
        <p><a href="index.php">Volver</a></p>
    </form>
</div>

<script>

    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
        // toggle the type attribute
        const type = password.getAttribute("type") === "password" ? "text" : "password"; //que bonito
        password.setAttribute("type", type);
        
        // toggle the icon
        this.classList.toggle("bi-eye");
    });

    const togglePasswordos = document.querySelector("#togglePasswordos");
    const passwordos = document.querySelector("#passwordos");

    togglePasswordos.addEventListener("click", function () {
        // toggle the type attribute
        const type = passwordos.getAttribute("type") === "password" ? "text" : "password"; //que bonito
        passwordos.setAttribute("type", type);
        
        // toggle the icon
        this.classList.toggle("bi-eye");
    });

</script>
</body>
</html>