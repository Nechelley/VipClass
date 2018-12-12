<?php
include_once("../utils/Msgs.php");
include_once("../utils/Retorno.class.php");
include_once("../utils/Util.class.php");
include_once("../utils/NivelAcesso.class.php");
include_once("../utils/Sessao.class.php");
include_once("../utils/validador/Validador.class.php");
include_once("../model/bean/LoginBean.class.php");
include_once("../model/dao/AutenticacaoDao.class.php");

$entradas = Util::pegaInformacaoDoFront();
$retorno = new Retorno();

//Se a ação não for informada, envia uma mensagem de erro ao cliente
if (!isset($entradas->acao)) {
	$retorno->setValor($GLOBALS["msgSemAcao"]);
	Util::EnviaInformacaoParaFront($retorno);
	die();
}

try {
	switch ($entradas->acao) {
		case 'login':
			$email = isset($entrada->email) ? Util::limpaString($entrada->email) : null;
			$senha = isset($entrada->senha) ? Util::limpaString($entrada->senha) : null;

			//Valida os dados da requisição
			$validador = new Validador();
			$validador->setDado("email", $email);
			$validador->setDado("senha", $senha);

			$validador->getDado("email")
				->ehVazio($GLOBALS["msgErroEmailInvalido"])
				->temMinimo(1, true, $GLOBALS["msgErroEmailInvalido"])
				->temMaximo(45, true, $GLOBALS["msgErroEmailInvalido"])
				->ehEmail($GLOBALS["msgErroEmailInvalido"]);

			$validador->getDado("senha")
				->ehVazio($GLOBALS['msgErroSenhaInvalido'])
				->temMinimo(1, true, $GLOBALS["msgErroSenhaInvalido"])
				->temMaximo(45, true, $GLOBALS["msgErroSenhaInvalido"]);

			//Se houve algum erro na validação, é lançado uma exceção
			if ($validador->getQntErros() !== 0) {
				throw new RuntimeException($validador->getTodosErrosMensagens());
			}

			//Cria o hash da senha
			$senha = sha1($senha);

			$loginBean = new LoginBean($email, $senha);

			$retorno = AutenticacaoDao::verificarLogin($loginBean);

			break;
		case 'logout':
			Sessao::iniciar();
			$usuario = Sessao::obterUsuario();
			if ($usuario) {
				AutenticacaoDao::deslogarUsuario($usuario['id']);
			}
			Sessao::destruir();

			$retorno->setStatus(true);
			break;
		case 'usuarioLogado':
			$idUsuario = isset($entrada->idUsuario) ? Util::limpaString($entrada->idUsuario) : null;

			$validador = new Validador();
			$validador->setDado("idUsuario", $idUsuario);

			$validador->getDado("idUsuario")->ehVazio($GLOBALS['msgErroIdInvalido']);

			//Se houver erros na validação, é enviado uma mensagem de erro
			if ($validador->getQntErros() !== 0) {
				throw new RuntimeException($validador->getTodosErrosMensagens());
			}

			$retorno = AutenticacaoDao::verificarUsuarioLogado($idUsuario);
			break;
		case 'usuarioPodeLogar':
			$idUsuario = isset($entrada->idUsuario) ? Util::limpaString($entrada->idUsuario) : null;

			$validador = new Validador();
			$validador->setDado("idUsuario", $idUsuario);

			$validador->getDado("idUsuario")->ehVazio($GLOBALS['msgErroIdInvalido']);

			//Se houver erros na validação, é enviado uma mensagem de erro
			if ($validador->getQntErros() !== 0) {
				throw new RuntimeException($validador->getTodosErrosMensagens());
			}

			$retorno = AutenticacaoDao::verificarLoginDisponivel($idUsuario);
			break;
		case 'loginAdm':
			$email = isset($entrada->email) ? Util::limpaString($entrada->email) : null;
			$senha = isset($entrada->senha) ? Util::limpaString($entrada->senha) : null;

			//Valida os dados da requisição
			$validador = new Validador();
			$validador->setDado("email", $email);
			$validador->setDado("senha", $senha);

			$validador->getDado("email")
				->ehVazio($GLOBALS["msgErroEmailInvalido"])
				->temMinimo(1, true, $GLOBALS["msgErroEmailInvalido"])
				->temMaximo(45, true, $GLOBALS["msgErroEmailInvalido"])
				->ehEmail($GLOBALS["msgErroEmailInvalido"]);

			$validador->getDado("senha")
				->ehVazio($GLOBALS['msgErroSenhaInvalido'])
				->temMinimo(1, true, $GLOBALS["msgErroSenhaInvalido"])
				->temMaximo(45, true, $GLOBALS["msgErroSenhaInvalido"]);

			//Se houve algum erro na validação, é lançado uma exceção
			if ($validador->getQntErros() !== 0) {
				throw new RuntimeException($validador->getTodosErrosMensagens());
			}

			//Cria o hash da senha
			$senha = sha1($senha);

			$loginBean = new LoginBean($email, $senha);

			$retorno = AutenticacaoDao::verificarLoginAdministrador($loginBean);

			break;
		default :
			$retorno->setValor($GLOBALS['msgSemAcao']);
	}
} catch (Exception $e) {
	$retorno->setValor($e->getMessage());
}

Util::EnviaInformacaoParaFront($retorno);
