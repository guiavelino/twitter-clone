<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {

	public function timeline(){
        session_start();
        if(isset($_SESSION['id'])){
           $this->render('timeline');
        }
        else{
            header('Location: /?login=erro');
        }
    }

}


?>