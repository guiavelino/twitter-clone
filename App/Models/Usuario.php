<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Usuario extends Model{

    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function salvar(){
        $query = "INSERT INTO usuarios(nome, email, senha) values(:n, :e, :s)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":n", $this->nome);
        $stmt->bindValue(":e", $this->email);
        $stmt->bindValue(":s", $this->senha);
        $stmt->execute();

        return $this;
    }

    public function validarCadastro(){
        $valido = true;

        if(strlen($this->nome) < 3 || strlen($this->email) < 3 || strlen($this->senha) < 3){
            $valido = false;
        }

        return $valido;
    }

    public function getUsuarioPorEmail(){
        $query = "SELECT * FROM usuarios where email = :e";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":e", $this->email);
        $stmt->execute();

        //O usuário ja foi cadastrado
        if($stmt->rowCount() > 0){
            return false;
        }
        return true;
    }

    public function autenticar(){
        $query = "SELECT * FROM usuarios where email = :e AND senha = :s";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":e", $this->email);
        $stmt->bindValue(":s", $this->senha);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!empty($usuario['id'])){
            $this->id = $usuario['id'];
            $this->nome = $usuario['nome'];
            $this->email = $usuario['email'];
            $this->senha = $usuario['senha'];
        }
    }

}


?>