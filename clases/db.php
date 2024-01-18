<?php

class DB{
    
    public static function connect(){
    
        $host     = 'localhost';
        $db       = 'pruebas';
        $user     = 'root';
        $password = "";
        $charset  = 'utf8mb4';

        try{
            
            $connection = "mysql:host=" . $host . ";dbname=" . $db . ";charset=" . $charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($connection, $user, $password, $options);
    
            return $pdo;

        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }   
    }
}

?>