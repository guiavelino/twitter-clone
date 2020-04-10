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

    public function getAll() {
		$query = "
			select 
				u.id, 
				u.nome, 
				u.email,
				(
					select
						count(*)
					from
						usuarios_seguidores as us 
					where
						us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
				) as seguindo_sn
			from  
				usuarios as u
			where 
				u.nome like :nome and u.id != :id_usuario
			";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":nome", "%".$this->nome."%");
        $stmt->bindValue(":id_usuario", $this->id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

    public function seguirUsuario($id_usuario_seguindo){
        $query = "insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values(:id_usuario, :id_usuario_seguindo)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->id);
        $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo);
        $stmt->execute();

        return true;
    }

    public function deixarSeguirUsuario($id_usuario_seguindo){
        $query = "delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->id);
        $stmt->bindValue(":id_usuario_seguindo", $id_usuario_seguindo);
        $stmt->execute();

        return true;
    }

    public function getTotalTweets(){
        $query = "SELECT count(*) as total_tweets from tweets where id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalSeguindo(){
        $query = "SELECT count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getTotalSeguidores(){
        $query = "SELECT count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(":id_usuario", $this->id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
   


}


?>