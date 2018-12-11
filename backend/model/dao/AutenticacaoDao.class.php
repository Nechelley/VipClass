<?php
require_once('../conexao/BindParam.class.php');
require_once('../conexao/ProcessaQuery.class.php');

class AutenticacaoDao
{
	private static $limiteTentativasLogin = 3;

	public static function verificarLogin(LoginBean $loginBean)
	{
		//Busca algum usuário cadastrado com o email que está tentando logar
		$retornoBusca = self::getUsuarioPorEmail($loginBean);

		if (!$retornoBusca->getStatus()){
			throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
		}

		$resultadoConsulta = $retornoBusca->getValor();
		//Objeto que será enviado como resposta para o cliente
		$retornoResposta = new Retorno(); 

		//Verifica se há um usuário cadastrado
		if(
			isset($resultadoConsulta) && 
			is_array($resultadoConsulta) && 
			!empty($resultadoConsulta)
		) {
			$usuario = reset($resultadoConsulta); //Pega o primeiro usuário
			$senha = $loginBean->getSenha();

			//Verifica se o administrador aprovou o cadastro do professor
			if(
				$usuario->nivel_acesso === NivelAcesso::PROFESSOR && 
				!Util::isDataValida($usuario->data_aprovacao_administrador)
			){
				throw new Exception($GLOBALS['msgErroLogin']);
			}

			//Se o usuário já está logado, o login é interrompido
			if ($usuario->esta_logado === 1){
				throw new RuntimeException($GLOBALS['msgErroUsuarioLogado']);
			}

			//Se as tentativas de login consecutiva atingiu o limite e ainda não 
			// passou o tempo mínimo de espera, é enviado uma mensagem de erro ao cliente
			if (
				$usuario->qtd_tentativa_login % self::$limiteTentativasLogin == 0 &&
				!Util::isDataValida($usuario->data_permissao_login)
			) {
				throw new RuntimeException($GLOBALS['msgErroTentativasLogin']);
			}

			//Verifica se a senha está correta
			if (password_verify($senha, $usuario->senha)) {
				//Dados que serão enviados para o cliente
				$dadosUsuario = [
					"id" => $usuario->id,
					"nome" => $usuario->nome,
					"nivelAcesso" => $usuario->nivel_acesso
				];
							
				//Atualiza o "status logado" para logado
				if (!self::atualizarStatusLogado($usuario->id, true)->getStatus()){
					throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
				}

				//Reinicia o contador de tentantivas de login
				if (!self::resetarTentativaLogin($usuario->id)->getStatus()) {
					throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
				}

				//Login realizado com sucesso, responde com os dados do usuário
				$retornoResposta->setStatus(true);
				$retornoResposta->setValor($dadosUsuario);

				return $retornoResposta;
			} 

			//Senha inválida, incrementar tentantiva no banco
			$retorno = self::incrementarTentativaLogin($usuario->id, $usuario->qtd_tentativa_login);
			if (!$retorno->getStatus()) {
				throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
			}
		}

		//Email e/ou senha inválida, enviar mensagem coerente com o erro.
		throw new Exception($GLOBALS['msgErroLogin']);
	}

	/**
	 * Busca por um usuário cadastrado que tenha o email que está tentando logar
	 */
	public static function getUsuarioPorEmail(LoginBean $loginBean)
	{
		$query = "
			SELECT
				U.id,
				U.nome,
				U.nivel_acesso,
				U.email,
				U.senha,
				U.qtd_tentativa_login,
				U.esta_logado,
				U.data_permissao_login,
				P.data_aprovacao_administrador
			FROM Usuario AS U
				LEFT JOIN Professor AS P 
					ON P.Usuario_id = U.id
				LEFT JOIN Aluno AS A
					ON A.Usuario_id = U.id
			WHERE U.email = :email
				AND U.fl_ativo = 1
		";
		
		return ProcessaQuery::consultarQuery($query, [
			new BindParam(":email", $loginBean->getEmail(), PDO::PARAM_STR)
		]);
	}

	public static function incrementarTentativaLogin($idUsuario, $contadorAtual)
	{
		$query = "
			UPDATE Usuario SET
				qtd_tentativa_login = :quantidade,
				data_permissao_login = (NOW() + INTERVAL 5 MINUTE)
			WHERE id = :idUsuario
		";

		return ProcessaQuery::executarQuery($query, [
			new BindParam(":quantidade", ++$contadorAtual, PDO::PARAM_INT),
			new BindParam(":idUsuario", $idUsuario, PDO::PARAM_INT)
		]);
	}

	public static function resetarTentativaLogin($idUsuario)
	{
		$query = "
			UPDATE Usuario SET
				qtd_tentativa_login = 0
			WHERE id = :idUsuario
		";

		return ProcessaQuery::executarQuery($query, [
			new BindParam(":idUsuario", $idUsuario, PDO::PARAM_INT)
		]);
	}

	public static function atualizarStatusLogado($idUsuario, $status)
	{
		$query = "
			UPDATE Usuario SET
				esta_logado = :logado
			WHERE id = :idUsuario
		";

		$isLogado = $status ? 1 : 0;

		return ProcessaQuery::executarQuery($query, [
			new BindParam(":logado", $isLogado, PDO::PARAM_INT),
			new BindParam(":idUsuario", $idUsuario, PDO::PARAM_INT)
		]);
	}
}
