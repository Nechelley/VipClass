<?php
	//Classe que define oque e retornado para o front
	class Retorno{
		public $status = false;
		public $valor;
		// public $mensagem;

		public function getStatus(){
			return $this->status;
		}

		public function setStatus($valor){
			$this->status = $valor;
		}

		public function getValor(){
			return $this->valor;
		}

		public function setValor($valor){
			$this->valor = $valor;
		}

		// public function getmMensagem(){
		// 	return $this->mensagem;
		// }
		//
		// public function setmMensagem($valor){
		// 	$this->mensagem = $valor;
		// }
	}
?>
