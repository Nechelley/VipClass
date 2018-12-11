<?php
	//Classe que representa a tabela Professor
	class ProfessorBean{
		private $id;
		private $cpf;
		private $nome;
		private $sexo;
		private $nivelAcesso;
		private $email;
		private $senha;
		private $qtdTentativaLogin;
		private $estaLogado;
		private $dataPermissaoLogin;
		private $flAtivo;

		private $administradorQueAprovou;
		private $dataQueAprovou;

		public function __construct(){
			$this->id = null;
			$this->cpf = null;
			$this->nome = null;
			$this->sexo = null;
			$this->nivelAcesso = null;
			$this->email = null;
			$this->senha = null;
			$this->qtdTentativaLogin = null;
			$this->estaLogado = null;
			$this->dataPermissaoLogin = null;
			$this->flAtivo = null;
			$this->administradorQueAprovou = null;
			$this->dataQueAprovou = null;
		}

		public function getId(){
			return $this->id;
		}

		public function setId($valor){
			$this->id = $valor;
		}

		public function getCpf(){
			return $this->cpf;
		}

		public function setCpf($valor){
			$this->cpf = $valor;
		}

		public function getNome(){
			return $this->nome;
		}

		public function setNome($valor){
			$this->nome = $valor;
		}

		public function getSexo(){
			return $this->sexo;
		}

		public function setSexo($valor){
			$this->sexo = $valor;
		}

		public function getNivelAcesso(){
			return $this->nivelAcesso;
		}

		public function setNivelAcesso($valor){
			$this->nivelAcesso = $valor;
		}

		public function getEmail(){
			return $this->email;
		}

		public function setEmail($valor){
			$this->email = $valor;
		}

		public function getSenha(){
			return $this->senha;
		}

		public function setSenha($valor){
			$this->senha = $valor;
		}

		public function getQtdTentativaLogin(){
			return $this->qtdTentativaLogin;
		}

		public function setQtdTentativaLogin($valor){
			$this->qtdTentativaLogin = $valor;
		}

		public function getEstaLogado(){
			return $this->estaLogado;
		}

		public function setEstaLogado($valor){
			$this->estaLogado = $valor;
		}

		public function getDataPermissaoLogin(){
			return $this->dataPermissaoLogin;
		}

		public function setDataPermissaoLogin($valor){
			$this->dataPermissaoLogin = $valor;
		}

		public function getFlAtivo(){
			return $this->flAtivo;
		}

		public function setFlAtivo($valor){
			$this->flAtivo = $valor;
		}

		public function getAdministradorQueAprovou(){
			return $this->administradorQueAprovou;
		}

		public function setAdministradorQueAprovou($valor){
			$this->administradorQueAprovou = $valor;
		}

		public function getDataQueAprovou(){
			return $this->dataQueAprovou;
		}

		public function setDataQueAprovou($valor){
			$this->dataQueAprovou = $valor;
		}
	}
?>
