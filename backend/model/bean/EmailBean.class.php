<?php
	//Classe que representa a tabela Email
	class EmailBean{
		private $id;
		private $mensagem;
		private $dataEnvio;

		private $cursoId;
		private $professorId;

		public function __construct(){
			$this->id = null;
			$this->mensagem = null;
			$this->aulaId = null;
		}

		public function getId(){
			return $this->id;
		}

		public function setId($valor){
			$this->id = $valor;
		}

		public function getMensagem(){
			return $this->mensagem;
		}

		public function setMensagem($valor){
			$this->mensagem = $valor;
		}

		public function getDataEnvio(){
			return $this->dataEnvio;
		}

		public function setDataEnvio($valor){
			$this->dataEnvio = $valor;
		}

		public function getCursoId(){
			return $this->cursoId;
		}

		public function setCursoId($valor){
			$this->cursoId = $valor;
		}

		public function getProfessorId(){
			return $this->professorId;
		}

		public function setProfessorId($valor){
			$this->professorId = $valor;
		}
	}
?>
