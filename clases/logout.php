<?php

    session_start();//needed?
    session_unset();
    session_destroy();

    header("location: ../index.php");

?>