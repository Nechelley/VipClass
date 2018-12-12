<?php
	//Classe que conecta com o banco, usando PDO e padrao singleton
	class Conexao{
		private static $server;
		private static $bd;
		private static $user;
		private static $password;

		private static $instance;

		private function __construct() { }

		public static function getConexao($server = "127.0.0.1", $bd = "mydb", $user = "root", $password = "toyotaeenaruguto") {
			if (!isset(self::$instance)) {
				self::$server = $server;
				self::$bd = $bd;
				self::$user = $user;
				self::$password = $password;


				self::$instance = new PDO("mysql:host=".self::$server.";dbname=".self::$bd, self::$user, self::$password);

				self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// para debug
				// self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);// para quando jogar no servidor
				self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);// faz com que strings vazias se tornem NULL no banco
			}

			return self::$instance;
		}

		//Fecha conexao com o banco
		public static function fechaConexao() {
			self::$instance = null;
		}
	}
?>
