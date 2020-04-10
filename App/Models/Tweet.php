<?php

namespace App\Models;
use MF\Model\Model;

class Tweet extends Model{

    private $id;
    private $id_usuario;
    private $tweet;
    private $data;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function salvar(){
        $query = "INSERT INTO tweets(id_usuario, tweet) values(:i, :t)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':i', $this->id_usuario);
        $stmt->bindValue(':t', $this->tweet);
        $stmt->execute();

        return $this;
    }

    public function getAll(){
        $query = "
        SELECT
            t.id,
            t.id_usuario,
            u.nome, t.tweet,
            DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
        from 
            tweets as t 
            left join usuarios as u on(t.id_usuario = u.id)
        where 
            t.id_usuario = :i
            or t.id_usuario in(select id_usuario_seguindo from usuarios_seguidores where id_usuario = :i)
        order by
            t.data desc
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':i', $this->id_usuario);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    
}


?>