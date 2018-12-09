<?php
	//Classe que representa a tabela Aluno_Compra_Curso
	class CompraBean{
		private $dataCompra;
		private $valorCompra;

		private $alunoId;
		private $cursoId;

		public function __construct(){
			$this->dataCompra = null;
			$this->valorCompra = null;
			$this->alunoId = null;
			$this->cursoId = null;
		}

		public function getDataCompra(){
			return $this->dataCompra;
		}

		public function setDataCompra($valor){
			$this->dataCompra = $valor;
		}

		public function getValorCompra(){
			return $this->valorCompra;
		}

		public function setValorCompra($valor){
			$this->valorCompra = $valor;
		}

		public function getAlunoId(){
			return $this->alunoId;
		}

		public function setAlunoId($valor){
			$this->alunoId = $valor;
		}

		public function getCursoId(){
			return $this->cursoId;
		}

		public function setCursoId($valor){
			$this->cursoId = $valor;
		}
	}
?>
