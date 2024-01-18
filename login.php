<?php
include_once 'clases/user.php';

if(isset($_POST['username']) && isset($_POST['password'])){
    
    $userForm = $_POST['username'];
    $passForm = $_POST['password'];

    $user = new User();
    if($user->userExists($userForm, $passForm)){
        //echo "Existe el usuario";
        
        $user->setUser($userForm);
    
        header("location: index.php");
    } else{
        //echo "No existe el usuario";
        $errorLogin = "Correo y/o contraseña incorrecto";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sol42</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="form">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <?php
                if(isset($errorLogin)){
                    echo $errorLogin;
                }
            ?>
            <h2 style="text-align: center;">Iniciar sesión</h2>
            <div class="couple">
                <label for="username">Correo electrónico:</label>
                <input type="text" name="username">
            </div>
            <div class="couple">
                <label for="password">Contraseña:</label>
                <div><input type="password" name="password" id="password" style="width: -moz-available;">
                <i class="bi bi-eye-slash" id="togglePassword"></i></div>
            </div>
            <input type="submit" value="Iniciar Sesión">
            <div id="logNav"><a href="index.php">Volver</a><a href="registro.php">Registrarse</a></div>
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

    </script>
</body>
</html>