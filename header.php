<header>
    <nav>
        <a href="index.php"><div id="logo"></div></a>
        <div id="menu">
            <a href="index.php">Actividades</a>
            <?php
                $page = explode("/",$_SERVER['PHP_SELF']);
                $aPriv = '<a href="login.php">login</a>';
                if(isset($_SESSION["user"])){
                    $user = $_SESSION["user"];  
                    $nameC = explode(" ",$user->getNombre());
                    $name = $nameC[0];
                    $aPriv = '<a href="aPriv.php">'.$name.'</a>';
                }
                if($page[sizeof($page)-1]=="aPriv.php")
                $aPriv = '<a href="clases/logout.php">logout</a>';
                echo $aPriv;
            ?>  
        </div>
    </nav>
</header>