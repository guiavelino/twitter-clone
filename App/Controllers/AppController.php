<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

    public function validaAutenticacao(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == null){
            header('Location: /?login=erro');
        }
    }

	public function timeline(){
        $this->validaAutenticacao();
        $tweet = Container::getModel('Tweet');
        $tweet->id_usuario = $_SESSION['id'];
        $this->view->tweets = $tweet->getAll();

        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];
        $this->view->totaltweets = $usuario->getTotalTweets();
        $this->view->totalSeguindo = $usuario->getTotalSeguindo();
        $this->view->totalSeguidores = $usuario->getTotalSeguidores();

        $this->render('timeline');
    }

    public function tweet(){
        $this->validaAutenticacao();
        $tweet = Container::getModel('Tweet');
        $tweet->tweet = $_POST['tweet'];
        $tweet->id_usuario = $_SESSION['id'];
        $tweet->salvar();
        
        header('Location: /timeline');
    }

    public function quemSeguir(){
        $this->validaAutenticacao();
        
        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];
        $this->view->totaltweets = $usuario->getTotalTweets();
        $this->view->totalSeguindo = $usuario->getTotalSeguindo();
        $this->view->totalSeguidores = $usuario->getTotalSeguidores();

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';        
        $usuarios = array();

        if($pesquisarPor != ''){
            $usuario = Container::getModel('Usuario');
            $usuario->nome = $pesquisarPor;
            $usuario->id = $_SESSION['id'];
            $usuarios = $usuario->getAll();
        }

        $this->view->usuarios = $usuarios;

        $this->render('quemSeguir');
    }   

    public function acao(){
        $this->validaAutenticacao();
        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->id = $_SESSION['id'];

        if($acao == 'seguir'){
            $usuario->seguirUsuario($id_usuario_seguindo);
        }
        else if($acao == 'deixar_de_seguir'){
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header("Location: /quem_seguir");
    }


}


?>