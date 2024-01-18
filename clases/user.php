<?php

session_start();

include 'db.php';

class User {
    private $id;
    private $nombre;
    private $email;
    private $profe;


    public function userExists($userMail, $pass){
        $shapass = sha1($pass);
        $query = DB::connect()->prepare('SELECT * FROM usuarios WHERE email = :user AND password = :pass');
        $query->execute(['user' => $userMail, 'pass' => $shapass]);

        if($query->rowCount()){
            return true;
        }else{
            return false;
        }
    }

    public function setUser($user){
        $query = DB::connect()->prepare('SELECT * FROM usuarios WHERE email = :user');
        $query->execute(['user' => $user]);
        
        foreach ($query as $currentUser) {
            $this->id = $currentUser['id'];
            $this->nombre = $currentUser['nombre'];
            $this->email = $currentUser['email'];
            $this->profe = $currentUser['profe'];
        }
        $_SESSION["user"]=$this;
    }

    public function getNombre(){
        return $this->nombre;
    }
    public function getId(){
        return $this->id;
    }
    public function getMail(){
        return $this->email;
    }
    public function getProfe(){
        $salida=false;
        if($this->profe*1==1)
        $salida=true;
        return $salida;
    }
}

?>