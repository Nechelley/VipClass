<?php
	require_once("Conexao.class.php");
	require_once("BindParam.class.php");
	require_once("../utils/Retorno.class.php");

	//Classe que processa as querys
	class ProcessaQuery{//<FAZER> implementar parte de transacao
		//Conecta com o banco e executa a query passada, serve para inserts, updates e deletes
		public static function executarQuery($query, $bindParams = array()){
			try {
				$retorno = new Retorno();

				$conexao = Conexao::getConexao()->prepare($query);

				foreach ($bindParams as $bindParam) {
					$conexao->bindValue($bindParam->getNome(), $bindParam->getValor(), $bindParam->getTipo());
				}

				if($conexao->execute())
					$retorno->setStatus(true);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["$msgErroExecQuery"]);
				}
			} catch (Exception $e) {
				$retorno->setStatus(false);
				$retorno->setValor($GLOBALS["$msgErroExecQuery"]);
				$retorno->setValor("Mensagem: ".$e->getMessage());// PARA DEBUG APENAS
				// GeraLog::getInstance()->inserirLog("Erro: Código: " . $e->getCode() . " Mensagem: " . $e->getMessage());//<FAZER> esquema de log semelhante, parece uma boa ideia
			} finally {
				Conexao::fechaConexao();
				return $retorno;
			}
		}

		//Conecta com o banco e consulta a query passada, serve apenas para consultas
		public static function consultarQuery($query, $bindParams = array()){
			try {
				$retorno = new Retorno();

				$conexao = Conexao::getConexao()->prepare($query);

				foreach ($bindParams as $bindParam) {
					$conexao->bindValue($bindParam->getNome(), $bindParam->getValor(), $bindParam->getTipo());
				}

				$conexao->execute();

				$objs = $conexao->fetchAll(PDO::FETCH_OBJ);

				if($objs !== false){
					$retorno->setStatus(true);
					$retorno->setValor($objs);
				} else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["$msgErroExecQuery"]);
				}
			} catch (Exception $e) {
				$retorno->setStatus(false);
				$retorno->setValor($GLOBALS["$msgErroExecQuery"]);
				$retorno->setValor("Mensagem: ".$e->getMessage());// PARA DEBUG APENAS
				// GeraLog::getInstance()->inserirLog("Erro: Código: " . $e->getCode() . " Mensagem: " . $e->getMessage());//<FAZER> esquema de log semelhante, parece uma boa ideia
			} finally {
				Conexao::fechaConexao();
				return $retorno;
			}
		}
	}
?>
