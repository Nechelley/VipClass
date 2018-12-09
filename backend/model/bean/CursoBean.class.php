<?php
	//Classe que representa a tabela Curso
	class CursoBean{
		private $id;
		private $nome;
		private $valor;
		private $descricao;
		private $foiAprovado;
		private $flAtivo;

		private $professorId;

		public function __construct(){
			$this->id = null;
			$this->nome = null;
			$this->valor = null;
			$this->descricao = null;
			$this->foiAprovado = null;
			$this->flAtivo = null;
			$this->professorId = null;
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

		public function getValor(){
			return $this->valor;
		}

		public function setValor($valor){
			$this->valor = $valor;
		}

		public function getDescricao(){
			return $this->descricao;
		}

		public function setDescricao($valor){
			$this->descricao = $valor;
		}

		public function getFoiAprovado(){
			return $this->foiAprovado;
		}

		public function setFoiAprovado($valor){
			$this->foiAprovado = $valor;
		}

		public function getFlAtivo(){
			return $this->flAtivo;
		}

		public function setFlAtivo($valor){
			$this->flAtivo = $valor;
		}

		public function getProfessorId(){
			return $this->professorId;
		}

		public function setProfessorId($valor){
			$this->professorId = $valor;
		}
	}
?>
