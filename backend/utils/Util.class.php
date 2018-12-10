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
	}
?>
