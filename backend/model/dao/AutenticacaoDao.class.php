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

			//Verifica se o administrador aprovou o cadastro do professor
			if(
				$usuario->nivel_acesso == NivelAcesso::PROFESSOR && 
				!Util::isDataValida($usuario->data_aprovacao_administrador)
			){
				//Se não aprovou, envia mensagem de erro
				throw new Exception($GLOBALS['msgErroLogin']);
			}

			$isSessaoAtiva = !Util::isDataValida($usuario->data_validade_sessao);

			//Se o usuário já está logado, o login está indisponível
			if ($usuario->esta_logado == 1 && $isSessaoAtiva){
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
			if ($loginBean->getSenha() == $usuario->senha) {
				//Dados que serão enviados para o cliente
				$dadosUsuario = [
					"id" => $usuario->id,
					"nome" => $usuario->nome,
					"nivel_acesso" => $usuario->nivel_acesso
				];
							
				//Atualiza o "status logado" para logado
				if (!self::atualizarStatusLogado($usuario->id, true)->getStatus()){
					throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
				}

				//Reinicia o contador de tentantivas de login
				if (!self::resetarTentativaLogin($usuario->id)->getStatus()) {
					throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
				}

				//Armazena o usuário na sessão
				Sessao::iniciar();
				Sessao::gerarNovoId();
				Sessao::gravarUsuario([
					"id" => $usuario->id,
					"nome" => $usuario->nome,
					"cpf" => $usuario->cpf,
					"sexo" => $usuario->sexo,
					"nivelAcesso" => $usuario->nivel_acesso,
					"email" => $usuario->email
				]);

				//Atualiza a validade da sessão
				if (!self::atualizarValidadeSessao($usuario->id)->getStatus()) {
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

	public static function verificarLoginAdministrador(LoginBean $loginBean)
	{
		//Busca algum usuário cadastrado com o email que está tentando logar
		$retornoBusca = self::getAdministradorPorEmail($loginBean);

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

			$isSessaoAtiva = !Util::isDataValida($usuario->data_validade_sessao);

			//Se o usuário já está logado, o login está indisponível
			if ($usuario->esta_logado == 1 && $isSessaoAtiva){
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
			if ($loginBean->getSenha() == $usuario->senha) {
				//Dados que serão enviados para o cliente
				$dadosUsuario = [
					"id" => $usuario->id,
					"nome" => $usuario->nome,
					"nivel_acesso" => $usuario->nivel_acesso
				];
							
				//Atualiza o "status logado" para logado
				if (!self::atualizarStatusLogado($usuario->id, true)->getStatus()){
					throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
				}

				//Reinicia o contador de tentantivas de login
				if (!self::resetarTentativaLogin($usuario->id)->getStatus()) {
					throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
				}

				//Armazena o usuário na sessão
				Sessao::iniciar();
				Sessao::gerarNovoId();
				Sessao::gravarUsuario([
					"id" => $usuario->id,
					"nome" => $usuario->nome,
					"cpf" => $usuario->cpf,
					"sexo" => $usuario->sexo,
					"nivelAcesso" => $usuario->nivel_acesso,
					"email" => $usuario->email
				]);

				//Atualiza a validade da sessão
				if (!self::atualizarValidadeSessao($usuario->id)->getStatus()) {
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


	public static function verificarUsuarioLogado($idUsuario)
	{
		$retornoBusca = self::getUsuarioPorId($idUsuario);

		if (!$retornoBusca->getStatus()) {
			throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
		}

		$resultadoBusca = $retornoBusca->getValor();

		$usuario = isset($resultadoBusca[0]) ? $resultadoBusca[0] : null;
		//Verifica se o usuário existe
		if (is_null($usuario)) {
			throw new Exception($GLOBALS['msgErroUsuarioInexistente']);
		}

		$retorno = new Retorno();
		$retorno->setStatus(true);

		$isSessaoAtiva = !Util::isDataValida($usuario->data_validade_sessao);

		//Verifica se está logado e a sessão não expirou
		if ($usuario->esta_logado == 1 && $isSessaoAtiva){
			$retorno->setValor(["esta_logado" => true]);
			return $retorno;
		}

		$retorno->setValor(["esta_logado" => false]);
		return $retorno;
	}

	public static function verificarLoginDisponivel($idUsuario)
	{
		$retornoBusca = self::getUsuarioPorId($idUsuario);
		if (!$retornoBusca->getStatus()) {
			throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
		}

		$resultadoBusca = $retornoBusca->getValor();

		$usuario = isset($resultadoBusca[0]) ? $resultadoBusca[0] : null;
		//Verifica se o usuário existe
		if (is_null($usuario)) {
			throw new Exception($GLOBALS['msgErroUsuarioInexistente']);
		}

		$retorno = new Retorno();
		$retorno->setStatus(true);
		$retorno->setValor(["pode_logar" => false]);

		//Verifica se o administrador aprovou o cadastro do professor
		if(
			$usuario->nivel_acesso == NivelAcesso::PROFESSOR && 
			!Util::isDataValida($usuario->data_aprovacao_administrador)
		){
			//Se não aprovou, envia mensagem de login indisponivel
			return $retorno;
		}

		$isSessaoAtiva = !Util::isDataValida($usuario->data_validade_sessao);

		//Se o usuário já está logado, o login está indisponível
		if ($usuario->esta_logado == 1 && $isSessaoAtiva){
			return $retorno;
		}

		//Se as tentativas de login consecutiva atingiu o limite e ainda não 
		// passou o tempo mínimo de espera, o login está indisponível
		if (
			$usuario->qtd_tentativa_login % self::$limiteTentativasLogin == 0 &&
			!Util::isDataValida($usuario->data_permissao_login)
		) {
			return $retorno;
		}

		$retorno->setValor(['pode_logar' => true]);
		return $retorno;
	}

	public static function deslogarUsuario($idUsuario)
	{
		//Altera a data de validade da sessão para uma data passada
		if (!self::invalidarSessao($idUsuario)->getStatus()){
			throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
		}

		//Altera o "status logado" para "deslogado"
		if (!self::atualizarStatusLogado($idUsuario, false)->getStatus()) {
			throw new RuntimeException($GLOBALS['msgErroInternoServidor']);
		}
	}

	/**
	 * Busca por um usuário cadastrado que tenha o email que está tentando logar
	 */
	public static function getUsuarioPorEmail(LoginBean $loginBean)
	{
		$query = "
			SELECT
				U.id,
				U.cpf,
				U.nome,
				U.sexo,
				U.nivel_acesso,
				U.email,
				U.senha,
				U.qtd_tentativa_login,
				U.esta_logado,
				U.data_permissao_login,
				U.data_validade_sessao,
				P.data_aprovacao_administrador
			FROM 
				Usuario AS U
				LEFT JOIN Professor AS P 
					ON P.Usuario_id = U.id
			WHERE 
				U.email = :email AND
				U.fl_ativo = 1 AND
				(
					U.nivel_acesso = :professor OR
					U.nivel_acesso = :aluno
				)
		";

		return ProcessaQuery::consultarQuery($query, [
			new BindParam(":email", $loginBean->getEmail(), PDO::PARAM_STR),
			new BindParam(":professor", 1, PDO::PARAM_INT),
			new BindParam(":aluno", 2, PDO::PARAM_INT)
		]);
	}

	public static function getAdministradorPorEmail(LoginBean $loginBean)
	{
		$query = "
			SELECT
				U.id,
				U.cpf,
				U.nome,
				U.sexo,
				U.nivel_acesso,
				U.email,
				U.senha,
				U.qtd_tentativa_login,
				U.esta_logado,
				U.data_permissao_login,
				U.data_validade_sessao
			FROM 
				Usuario AS U
			WHERE 
				U.email = :email AND
				U.fl_ativo = 1 AND
				U.nivel_acesso = :administrador 
		";

		return ProcessaQuery::consultarQuery($query, [
			new BindParam(":email", $loginBean->getEmail(), PDO::PARAM_STR),
			new BindParam(":administrador", 0, PDO::PARAM_INT)
		]);
	}

	public static function getUsuarioPorId($idUsuario)
	{
		$query = "
			SELECT
				U.id,
				U.cpf,
				U.nome,
				U.sexo,
				U.nivel_acesso,
				U.email,
				U.qtd_tentativa_login,
				U.esta_logado,
				U.data_permissao_login,
				U.data_validade_sessao,
				P.data_aprovacao_administrador
			FROM 
				Usuario AS U
				LEFT JOIN Professor AS P
					ON P.Usuario_id = U.id
			WHERE
				fl_ativo = 1
				AND id = :idUsuario
		";

		return ProcessaQuery::consultarQuery($query, [
			new BindParam(":idUsuario", $idUsuario, PDO::PARAM_INT)
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

	public static function atualizarValidadeSessao($idUsuario)
	{
		$query = "
			UPDATE Usuario SET
				data_validade_sessao = (NOW() + INTERVAL 30 MINUTE)
			WHERE
				id = :idUsuario
		";

		return ProcessaQuery::executarQuery($query, [
			new BindParam(":idUsuario", $idUsuario, PDO::PARAM_INT)
		]);
	}

	public static function invalidarSessao($idUsuario)
	{
		$query = "
			UPDATE Usuario SET
				data_validade_sessao = (NOW() - INTERVAL 5 MINUTE)
			WHERE
				id = :idUsuario
		";

		return ProcessaQuery::executarQuery($query, [
			new BindParam(":idUsuario", $idUsuario, PDO::PARAM_INT)
		]);
	}
}
