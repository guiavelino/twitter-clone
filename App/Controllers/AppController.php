<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function timeline(){
        session_start();
        if(isset($_SESSION['id'])){
            $tweet = Container::getModel('Tweet');
            $tweet->id_usuario = $_SESSION['id'];
            $this->view->tweets = $tweet->getAll();
            $this->render('timeline');
        }
        else{
            header('Location: /?login=erro');
        }
    }


    public function tweet(){
        session_start();
        if(isset($_SESSION['id'])){
            $tweet = Container::getModel('Tweet');
            $tweet->tweet = $_POST['tweet'];
            $tweet->id_usuario = $_SESSION['id'];
            $tweet->salvar();
            header('Location: /timeline');
        }
        else{
            header('Location: /?login=erro');
        }
    }
}


?>