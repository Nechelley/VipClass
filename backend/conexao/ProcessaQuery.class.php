<?php
	require_once("Conexao.class.php");
	require_once("../utils/Retorno.class.php");

	//Classe que processa as querys
	class ProcessaQuery{//<FAZER> implementar parte de transacao
		//Conecta com o banco e executa a query passada, serve para inserts, updates e deletes
		public static function executarQuery($query){
			$retorno = new Retorno();

			//conecta no banco
			$conexao = new Conexao();
			$link = $conexao->conectar();
			if(!$link){//erro de conexao
				$retorno->setStatus(false);
				$retorno->setValor($GLOBALS["msgErroConectarBanco"]);
				//die(mysqli_error($link));//exibe erro sql
				$conexao->fechar();

				return $retorno;
			}

			//executa a query
			if(!mysqli_multi_query($link, $query)){//erro na query
				$retorno->setStatus(false);
				$retorno->setValor($GLOBALS["msgErroExecQuery"]);
				//die(mysqli_error($link));//exibe erro sql
				$conexao->fechar();

				return $retorno;
			}

			$retorno->setStatus(true);
			$retorno->setValor(true);
			$conexao->fechar();

			return $retorno;
		}

		//Conecta com o banco e consulta a query passada, serve apenas para consultas
		public static function consultarQuery($query){
			$retorno = new Retorno();

			//conecta no banco
			$conexao = new Conexao();
			$link = $conexao->conectar();
			if(!$link){//erro de conexao
				$retorno->setStatus(false);
				$retorno->setValor($GLOBALS["msgErroConectarBanco"]);
				//die(mysqli_error($link));//exibe erro sql
				$conexao->fechar();

				return $retorno;
			}

			//consulta a query
			$objs = mysqli_fetch_object(mysqli_query($link, $query));
			if(!($retorno->setValor($objs))){//erro na query
				$retorno->setStatus(false);
				$retorno->setValor($GLOBALS["msgErroExecQuery"]);
				//die(mysqli_error($link));//exibe erro sql
				$conexao->fechar();

				return $retorno;
			}

			$retorno->setStatus(true);
			$conexao->fechar();

			return $retorno;
		}
	}
?>
