<?php

namespace App\Controllers;

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		$this->render('index');
	}

	public function inscreverse() {
		$this->view->erroCadastro = false;
		$this->view->usuarioExistente = false;
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
		$usuario->senha = md5($_POST['senha']);

		
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

			if(!$usuario->validarCadastro()){
				$this->view->erroCadastroDados = 1;
			}
			else{
				$this->view->erroCadastroDados = 2;
			}

			$this->render('inscreverse');
		}

	}

}


?>