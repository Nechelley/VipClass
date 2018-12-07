<?php
	//Classe que auxilia a passagem de parametros para q query
	class BindParam{
		private $nome = null;
		private $valor = null;
		private $tipo = null;

		public function __construct($nome, $valor, $tipo){
			$this->nome = $nome;
			$this->valor = $valor;
			$this->tipo = $tipo;
		}

		public function getNome(){
			return $this->nome;
		}

		public function setNome($valor){
			$this->nome = $valor;
		}

		public function getValor(){
			return $this->valor;
		}

		public function setValor($valor){
			$this->valor = $valor;
		}

		public function getTipo(){
			return $this->tipo;
		}

		public function setTipo($valor){
			$this->tipo = $valor;
		}
	}
?>
