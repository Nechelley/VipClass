<?php
	//Classe que representa a tabela Imagem
	class ImagemBean{
		private $id;
		private $nome;

		private $aulaId;

		public function __construct(){
			$this->id = null;
			$this->nome = null;
			$this->aulaId = null;
		}

		public function getId(){
			return $this->id;
		}

		public function setId($valor){
			$this->id = $valor;
		}

		public function getNome(){
			return $this->nome;
		}

		public function setNome($valor){
			$this->nome = $valor;
		}

		public function getAulaId(){
			return $this->aulaId;
		}

		public function setAulaId($valor){
			$this->aulaId = $valor;
		}
	}
?>
