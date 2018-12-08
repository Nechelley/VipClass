<?php
	require_once("ValidadorException.class.php");
	require_once("ValidadorItem.class.php");
	//classe que cuida da valdacao das entradas
	class Validador{
		private $listaValoresCampo;
		private $listaValoresCampoValidadorItem;

		//constantes de charset
		const UTF_8 = 1;
		const ISO_8859_1 = 2;

		//constantes de erro
		const ErroVazio = 1;
		const ErroNumerico = 2;
		const ErroMaximo = 3;
		const ErroMinimo = 4;
		const ErroCPF = 5;
		const ErroEmail = 6;
		const ErroData = 7;
		const ErroStringPermitida = 8;
		const ErroDadoJaCadastrado = 9;

		public function __construct(){
			$this->listaValoresCampo = array();
			$this->listaValoresCampoValidadorItem = array();
		}

		//Adiciona dados para ser validados
		public function setDado($apelido,$valor){
			$this->listaValoresCampo[$apelido] = $valor;
			$this->listaValoresCampoValidadorItem[$apelido] = new ValidadorItem(addslashes($valor));
		}

		//Retorna um objeto contendo dados a serem validados
		public function getDado($apelido){
			if(isset($this->listaValoresCampoValidadorItem[$apelido]))
				return $this->listaValoresCampoValidadorItem[$apelido];
			else
				throw new ValidadorException("Apelido ".$apelido." não encontrado");
		}

		//Retorna os dados sem nenhuma modificacao
		public function getOriginal($apelido)
		{
			if(isset($v_campos[$apelido]))
				return $listaValoresCampo[$apelido];
			else
				throw new ValidadorException("Apelido ".$apelido." não encontrado");
		}

		//Retorna a quantidade de erros, independente do apelido referente ao campo
		public function getQntErros(){
			$qntErros = 0;
			foreach($this->listaValoresCampoValidadorItem as $objs)
				$qntErros += $objs->getQntErros();
			return $qntErros;
		}

		//Retorna todos os erros, separados apenas pelo delimitador
		public function getTodosErrosMensagens($separador = " - "){
			$msgTodosErros = "";
			foreach($this->listaValoresCampoValidadorItem as $obj)
				if(isset($msgTodosErros))
					$msgTodosErros .= $separador.$obj->getTodosErrosMensagens($separador);
				else
					$msgTodosErros .= $obj->getTodosErrosMensagens($separador);
			return $msgTodosErros;
		}
	}
?>
