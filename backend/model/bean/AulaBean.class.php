<?php
	//Classe que representa a tabela Aula
	class AulaBean{
		private $id;
		private $nome;
		private $texto;
		private $linkVideoAula;

		private $cursoId;

		public function __construct(){
			$this->id = null;
			$this->nome = null;
			$this->texto = null;
			$this->linkVideoAula = null;
			$this->cursoId = null;
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

		public function getTexto(){
			return $this->texto;
		}

		public function setTexto($valor){
			$this->texto = $valor;
		}

		public function getLinkVideoAula(){
			return $this->linkVideoAula;
		}

		public function setLinkVideoAula($valor){
			$this->linkVideoAula = $valor;
		}

		public function getCursoId(){
			return $this->cursoId;
		}

		public function setCursoId($valor){
			$this->cursoId = $valor;
		}
	}
?>
