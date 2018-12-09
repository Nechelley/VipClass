<?php
	require_once("ValidadorException.class.php");
	//item da valdiacao
	class ValidadorItem{
		private $itemValor;
		private $listaComMsgsErro;//$listaComMsgsErro[codigo do erro] = msg do erro
		private $listaComBoolDeErro;//$listaComBoolDeErro[codigo do erro] = true / false se tem ou nao o erro

		//O valor do dado pode ser definidos ou não
		public function __construct($itemValor = null){
			if($itemValor != null)
				$this->set($itemValor);
		}

		//Recebe o dado à ser validado
		public function set($itemValor){
			$this->itemValor = $itemValor;
		}

		//Retorna o dado validado
		public function get(){
			return $this->itemValor;
		}

		//Retorna, se houver, o erro referente ao codigo de erro
		public function getErrorMsg($codigoDeErro){
			if(isset($this->listaComMsgsErro[$codigoDeErro]))
				return $this->listaComMsgsErro[$codigoDeErro];
			else
				return new ValidadorItem();
			return $this->listaComMsgsErro[$codigoDeErro];
		}

		//Retorna o último erro ocorrido
		public function getLastErrorMsg(){
			$mensagem = end($this->listaComMsgsErro);
			if(isset($mensagem))
				return $mensagem;
		}

		//Retorna o primeiro erro ocorrido
		public function getFirstErrorMsg(){
			$mensagem = reset($this->listaComMsgsErro);
			if(isset($mensagem))
				return $mensagem;
		}

		//Retorna positivo se houver erro
		public function getError($codigoDeErro){
			if(isset($this->listaComBoolDeErro[$codigoDeErro]))
				return $this->listaComBoolDeErro[$codigoDeErro];
		}

		//Retorna todos os erros, separados apenas pelo delimitador
		public function getTodosErrosMensagens($separador = " - "){//<FAZER> melhorar retorno dessa mensagem e verificar padrao com o front
			$msgTodosErros = "";
			if($this->listaComMsgsErro == null)
				return $msgTodosErros;
			foreach($this->listaComMsgsErro as $msgErro)
				print_r($msgErro);
				if(isset($msgTodosErros)){
					$msgTodosErros .= $separador.$msgErro;}
				else
					$msgTodosErros .= $msgErro;
			return $msgTodosErros;
		}

		//Retorna a quantidade de erros gerados para aquele campo
		public function getQntErros(){
			if(empty($this->listaComMsgsErro))
				return 0;
			return count($this->listaComMsgsErro);
		}

		/**************************/
		/* Metodos de Verificacao */
		/**************************/

		//Verifica se o dado passado está vazio
		public function ehVazio($mensagem = null, $codigoDeErro = Validador::ErroVazio){
			$itemValor = $this->trim()->get();
			if(!(strlen($itemValor) > 0)){
				if($mensagem != null)
					$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
				$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
			}
			return $this;
		}

		//Verifica se o dado passado e um numero
		public function ehNumerico($mensagem = null, $codigoDeErro = Validador::ErroNumerico){
			$itemValor = $this->trim()->get();
			$itemValor = str_replace(",", ".", $itemValor);
			if(!(is_numeric($itemValor))){
				if($mensagem != null)
					$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
				$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
			}
			return $this;
		}

		//Verifica se o dado passado tem quantidade de caracteres acima do limite passado como parametro ou se o numero ultrapassa o valor
		public function temMaximo($limiteMaximo , $ehString = false, $mensagem = null , $codigoDeErro = Validador::ErroMaximo){
			if($this->get() == null)
				return $this;
			if(is_int($limiteMaximo)){
				if($ehString){//string
					if(strlen($this->itemValor) >= $limiteMaximo){
						if($mensagem != null)
							$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
						$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
					}
				}
				else{//numero
					if($this->itemValor >= $limiteMaximo){
						if($mensagem != null)
							$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
						$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
					}
				}
			}
			else
				throw new ValidadorException("A variável limiteMaximo não é numérica");
			return $this;
		}

		//Verifica se o dado passado tem quantidade de caracteres abaixo do limite passado como parametro ou se o numero esta abaixo do limite
		public function temMinimo($limiteMinimo , $ehString = false, $mensagem = null , $codigoDeErro = Validador::ErroMinimo){
			if($this->get() == null)
				return $this;
			if(is_numeric($limiteMinimo)){
				if($ehString){//string
					if(strlen($this->itemValor) <= $limiteMinimo){
						if($mensagem != null)
							$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
						$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
					}
				}
				else{//numero
					if($this->itemValor <= $limiteMinimo){
						if($mensagem != null)
							$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
						$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
					}
				}
			}
			else
				throw new ValidadorException("A variável limiteMinimo não é numérica");
			return $this;
		}

		//Verifica se o dado e um CPF válido
		public function ehCPF($mensagem , $codigoDeErro = Validador::ErroCPF){
			$cpf = $this->itemValor;
			$cpf = str_pad(preg_replace("[^0-9]", "", $cpf), 11, "0", STR_PAD_LEFT);
			if
			(
				strlen($cpf) != 11 ||
				$cpf == "00000000000" ||
				$cpf == "11111111111" ||
				$cpf == "22222222222" ||
				$cpf == "33333333333" ||
				$cpf == "44444444444" ||
				$cpf == "55555555555" ||
				$cpf == "66666666666" ||
				$cpf == "77777777777" ||
				$cpf == "88888888888" ||
				$cpf == "99999999999"
			)
				$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
			else
				for ($t = 9; $t < 11; $t++){
					for ($d = 0, $c = 0; $c < $t; $c++)
						$d += $cpf{$c} * (($t + 1) - $c);

					$d = ((10 * $d) % 11) % 10;
					if ($cpf{$c} != $d){
						if($mensagem != null)
							$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
						$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
					}
				}
			return $this;
		}

		//Verifica se o dado e um e-mail válido
		public function ehEmail($mensagem , $codigoDeErro = Validador::ErroEmail){
			$email = $this->itemValor;
			$ehValido = filter_var($email, FILTER_VALIDATE_EMAIL);

			if(!$ehValido){
				if($mensagem != null)
					$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
				$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
			}
			return $this;
		}

		//Verifica se o dado e uma data válida no formato(aaaa-mm-dia hh:mm:ss)
		public function ehData($mensagem = null , $codigoDeErro = Validador::ErroData){
			$data = $this->itemValor;
			$dataHoraSplit = explode(" ",$data);
			$dataSplit = explode("-",$dataHoraSplit[0]);
			$horaSplit = explode(":",$dataHoraSplit[1]);

			if(!is_numeric($dataSplit[0]) || !is_numeric($dataSplit[1]) || !is_numeric($dataSplit[2]) || !is_numeric($horaSplit[0])  || !is_numeric($horaSplit[1]) || !is_numeric($horaSplit[2]) || !checkdate($dataSplit[1], $dataSplit[2], $dataSplit[0]) || ($horaSplit[0] < 0 || $horaSplit[0] > 23) || ($horaSplit[1] < 0 || $horaSplit[1] > 59) || ($horaSplit[2] < 0 || $horaSplit[2] > 59)){
				if($mensagem != null)
					$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
				$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
				return $this;
			}
			return $this;
		}

		//Verifica se a string possui um dos valores passados como parametro
		public function ehStringPermitida($stringPermitidas , $mensagem = null , $codigoDeErro = Validador::ErroStringPermitida){
			if(!is_string($this->itemValor))
				throw new ValidadorException("A variável não é uma string");

			$stringEhPermitida = false;

			foreach ($stringPermitidas as $string) {
				if($string == $this->itemValor){
					$stringEhPermitida = true;
					break;
				}
			}

			if(!$stringEhPermitida){
				if($mensagem != null)
					$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
				$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
			}
			return $this;
		}

		//Verifica se dado
		public function jaCadastrado($retornoComItens, $atributo, $valorAntigo, $mensagem = null , $codigoDeErro = Validador::ErroStringPermitida){
			if($valorAntigo == null)
				return $this;

			foreach ($retornoComItens->getValor() as $obj) {
				if($atributo == "cpf"){
					if($obj->cpf == $this->itemValor && (($valorAntigo->getValor())[0])->cpf != $this->itemValor){
						if($mensagem != null)
							$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
						$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
						break;
					}
				}
				else if($atributo == "email"){
					if($obj->email == $this->itemValor && (($valorAntigo->getValor())[0])->email != $this->itemValor){
						if($mensagem != null)
							$this->listaComMsgsErro[$codigoDeErro] = $mensagem;
						$this->listaComBoolDeErro[$codigoDeErro] = TRUE;
						break;
					}
				}
			}

			return $this;
		}

		/*********************/
		/* Metodos de filtro */
		/*********************/

		//Retira, por padrão, caracteres de espaço ( ), tab (\t), retorno de carro (\r) e quebra de linhas (\n) no inicio e no fim do dado. Pode ser personalizada usando a variável $caracteresParaRemover
		public function trim($caracteresParaRemover = " \t\n\r"){
			$this->itemValor = trim($this->itemValor, $caracteresParaRemover);
			return $this;
		}
	}
?>
