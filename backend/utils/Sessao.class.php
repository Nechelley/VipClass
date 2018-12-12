<?php

/**
 * Classe que manipula a sessão
 */
class Sessao
{
	public static function iniciar()
	{
		session_start();
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

	public static function retornarUsuario()
	{
		return ($_SESSION['usuario']) ? $_SESSION['usuario'] : "";
	}
}
