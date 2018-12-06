<?php
	//<FAZER> verificacao se email e cpf ja estao cadastrados
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../model/bean/Alunobean.class.php");
	require_once("../model/dao/AlunoDao.class.php");

	$entrada = Util::pegaInformacaoDoFront();

	$retorno = new Retorno();

	$entrada->acao = Util::limpaString($entrada->acao);
	switch($entrada->acao){
		case "get":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->id)){
				$entrada->id = Util::limpaString($entrada->id);
				if($entrada->id != "" && $entrada->id >= 0){
					//cria bean
					$bean = new AlunoBean();
					$bean->setId($entrada->id);

					//busca
					$retorno = AlunoDao::get($bean);
				}
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroAlunoIdInvalido"]);
				}
			}
			else{
				//busca
				$retorno = AlunoDao::getTodos();
			}

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "insert":
			$bean = new AlunoBean();

			//validando campos
			$erros = array();

			if(isset($entrada->cpf)){
				$entrada->cpf = Util::limpaString($entrada->cpf);
				if(true) //<FAZER> fazer funcao que valida cpf
					$bean->setCpf($entrada->cpf);
				else
					array_push($erros,$GLOBALS["msgErroAlunoCpfInvalido"]);
			}
			else
				array_push($erros,$GLOBALS["msgErroAlunoCpfInvalido"]);
			if(isset($entrada->nome)){
				$entrada->nome = Util::limpaString($entrada->nome);
				if(strlen($entrada->nome) > 0  && strlen($entrada->nome) <= 45)
					$bean->setNome($entrada->nome);
				else
					array_push($erros,$GLOBALS["msgErroAlunoNomeInvalido"]);
			}
			else
				array_push($erros,$GLOBALS["msgErroAlunoNomeInvalido"]);
			if(isset($entrada->sexo)){
				$entrada->sexo = Util::limpaString($entrada->sexo);
				if($entrada->sexo == "M" || $entrada->sexo == "F")
					$bean->setSexo($entrada->sexo);
				else
					array_push($erros,$GLOBALS["msgErroAlunoSexoInvalido"]);
			}
			else
				array_push($erros,$GLOBALS["msgErroAlunoSexoInvalido"]);
			if(isset($entrada->email)){
				$entrada->email = Util::limpaString($entrada->email);
				if(strlen($entrada->email) > 0  && strlen($entrada->email) <= 45 && true) //<FAZER> fazer funcao que valida email
					$bean->setEmail($entrada->email);
				else
					array_push($erros,$GLOBALS["msgErroAlunoEmailInvalido"]);
			}
			else
				array_push($erros,$GLOBALS["msgErroAlunoEmailInvalido"]);
			if(isset($entrada->senha)){
				$entrada->senha = Util::limpaString($entrada->senha);
				if(strlen($entrada->senha) > 0  && strlen($entrada->senha) <= 45)
					$bean->setSenha(password_hash($entrada->senha, PASSWORD_DEFAULT ));//ja salva o hash
				else
					array_push($erros,$GLOBALS["msgErroAlunoSenhaInvalido"]);
			}
			else
				array_push($erros,$GLOBALS["msgErroAlunoSenhaInvalido"]);

			if(empty($erros)){
				//verifica se insere ou atualiza
				if(isset($entrada->id)){
					$entrada->id = Util::limpaString($entrada->id);
					if($entrada->id != "" && $entrada->id >= 0){
						$bean->setId($entrada->id);

						//<FAZER>

						$retorno = AlunoDao::update($bean);
					}
					else{
						$retorno->setStatus(false);
						$retorno->setValor($GLOBALS["msgErroIdAlunoInvalido"]);
					}
				}
				else
					$retorno = AlunoDao::insert($bean);
			}
			else{
				$retorno->setStatus(false);
				$errosMsg = "";
				foreach ($erros as $e) {
					$errosMsg .= $e;
				}
				$retorno->setValor($errosMsg);
			}

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "delete":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->id)){
				$entrada->id = Util::limpaString($entrada->id);
				if($entrada->id != "" && $entrada->id >= 0){
					//cria bean
					$bean = new AlunoBean();
					$bean->setId($entrada->id);

					//busca
					$retorno = AlunoDao::delete($bean);
				}
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroAlunoIdInvalido"]);
				}
			}
			else{
				$retorno->setStatus(false);
				$retorno->setValor($GLOBALS["msgErroAlunoIdInvalido"]);
			}

			Util::EnviaInformacaoParaFront($retorno);

			break;
		default:
			$retorno->setStatus(false);
			$retorno->setValor($GLOBALS["msgSemAcao"]);

			Util::EnviaInformacaoParaFront($retorno);

			break;
	}

?>
