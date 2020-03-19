<?php

namespace App\Controllers;

//os recursos do miniframework

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		$this->render('index');
	}

	public function inscreverse() {
		$this->view->erroCadastro = false;

		$this->view->usuario = [
			'nome' => '',
			'email' => '',
			'senha' => ''
		];

		$this->render('inscreverse');
	}

	public function registrar() {

		$usuario = Container::getModel('Usuario');
		$usuario->nome = $_POST['nome'];
		$usuario->email = $_POST['email'];
		$usuario->senha = $_POST['senha'];

		
		if($usuario->validarCadastro() && $usuario->getUsuarioPorEmail()){
			$usuario->salvar();
			$this->render('cadastro');
		}
		else{

			$this->view->usuario = [
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha']
			];

			$this->view->erroCadastro = true;
			$this->render('inscreverse');
		}

	}

}


?>