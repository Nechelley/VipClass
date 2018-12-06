<?php
	//Classe que representa a tabela Aluno
	class AlunoBean{
		private $id = null;
		private $cpf = null;
		private $nome = null;
		private $sexo = null;
		private $nivelAcesso = null;
		private $email = null;
		private $senha = null;
		private $qtdTentativaLogin = null;
		private $estaLogado = null;
		private $dataPermissaoLogin = null;
		private $flAtivo = null;
		private $credito = null;

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

		public function getCredito(){
			return $this->credito;
		}

		public function setCredito($valor){
			$this->credito = $valor;
		}
	}
?>
