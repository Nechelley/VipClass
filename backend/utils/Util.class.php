<?php
	//Contem funcoes uteis para o projeto
	class Util{
		//limpa a string passada para evitar ataques no banco
		public static function limpaString($string) {
			return $string;
		}
 
		//retorna oque veio como entrada na requisicao
		public static function pegaInformacaoDoFront(){
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Headers: Content-Type, Authorization");

			$json = file_get_contents("php://input");
			return json_decode($json);
		}

		//envia a resposta de volta para o front
		public static function EnviaInformacaoParaFront($retorno){
			header("Content-Type: application/json");
			echo json_encode($retorno);
		}

		public static function isDataValida($data)
		{
			if (!isset($data) || is_null($data)){
				return false;
			}

			//Coloca o servidor no horário de Brasília
			date_default_timezone_set("America/Sao_Paulo");

			$dataAtual = (new DateTime())->getTimestamp();
			$data = (new DateTime($data))->getTimestamp();

			if ($data > $dataAtual) {
				return false;
			}

			return true;
		}

	}
?>
