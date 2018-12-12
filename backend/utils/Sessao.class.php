<?php

/**
 * Classe que manipula a sessão
 */
class Sessao
{
	public static function iniciar()
	{
		if (session_status() !== PHP_SESSION_ACTIVE) {
			session_start();
		}
	}

	public static function gerarNovoId()
	{
		session_regenerate_id(true);
	}

	public static function destruir()
	{
		//Limpa os dados da sessão
		$_SESSION = array();
		//Remove os dados armazenados no servidor
		session_destroy();
		//Deleta o cookie da sessão no navegador
		setcookie(session_name(), '', time()-300);
	}

	public static function gravarUsuario($usuario)
	{
		$_SESSION['usuario'] = $usuario;
	}

	public static function obterUsuario()
	{
		return ($_SESSION['usuario']) ? $_SESSION['usuario'] : false;
	}

	public static function isAtiva()
	{
		if (
			session_status() === PHP_SESSION_ACTIVE &&
			self::obterUsuario()
		) {
			return true;
		}

		return false;
	}
}
