<?php
	//Classe que conecta com o banco
	class Conexao{
		private $server;
		private $user;
		private $password;
		private $bd;
		private $link;

		//Contrutor
		public function Conexao($server = "127.0.0.1", $user = "root", $password = "", $bd = "mydb"){
			$this->server = $server;
			$this->user = $user;
			$this->password = $password;
			$this->bd = $bd;
		}

		//Conecta com o banco, retorna false caso ocorra erro
		public function conectar(){
			$this->link = mysqli_connect($this->server, $this->user, $this->password, $this->bd);

			return $this->link;
		}

		//Fecha conexao com o banco
		public function fechar(){
			mysqli_close($this->link);
		}
	}
?>
