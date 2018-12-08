<?php
	//<FAZER> verificacao se email e cpf ja estao cadastrados
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../utils/validador/Validador.class.php");
	require_once("../model/bean/Professorbean.class.php");
	require_once("../model/dao/ProfessorDao.class.php");

	$entrada = Util::pegaInformacaoDoFront();

	$retorno = new Retorno();

	$entrada->acao = Util::limpaString($entrada->acao);

	$validador = new validador();
	switch($entrada->acao){
		case "get":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->id)){
				$entrada->id = Util::limpaString($entrada->id);

				//setando dados
				$validador->setDado("id", intval($entrada->id));

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0){//deu certo
					//cria bean
					$bean = new ProfessorBean();
					$bean->setId($entrada->id);

					//busca
					$retorno = ProfessorDao::get($bean);
				}
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else{
				//busca
				$retorno = ProfessorDao::getTodos();
			}

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "insert":
			$bean = new ProfessorBean();

			$validador->setDado("cpf", (isset($entrada->cpf) ? $entrada->cpf : null));
			$validador->setDado("nome", (isset($entrada->nome) ? $entrada->nome : null));
			$validador->setDado("sexo", (isset($entrada->sexo) ? $entrada->sexo : null));
			$validador->setDado("email", (isset($entrada->email) ? $entrada->email : null));
			$validador->setDado("senha", (isset($entrada->senha) ? $entrada->senha : null));

			$validador->getDado("cpf")->ehVazio($GLOBALS["msgErroCpfInvalido"]);
			$validador->getDado("cpf")->ehCPF($GLOBALS["msgErroCpfInvalido"]);

			$validador->getDado("nome")->ehVazio($GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMinimo(1, true, $GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMaximo(45, true, $GLOBALS["msgErroNomeInvalido"]);

			$validador->getDado("sexo")->ehVazio($GLOBALS["msgErroSexoInvalido"]);
			$validador->getDado("sexo")->ehStringPermitida(["M","F"], $GLOBALS["msgErroSexoInvalido"]);

			$validador->getDado("email")->ehVazio($GLOBALS["msgErroEmailInvalido"]);
			$validador->getDado("email")->temMinimo(1, true, $GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("email")->temMaximo(45, true, $GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("email")->ehEmail($GLOBALS["msgErroEmailInvalido"]);
			//<FAZER> acrescentar verificacao se email ja esta cadastrado

			$validador->getDado("senha")->ehVazio($GLOBALS["msgErroSenhaInvalido"]);
			$validador->getDado("senha")->temMinimo(1, true, $GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("senha")->temMaximo(45, true, $GLOBALS["msgErroNomeInvalido"]);

			if($validador->getQntErros() == 0){//deu certo
				$bean->setCpf($entrada->cpf);
				$bean->setNome($entrada->nome);
				$bean->setSexo($entrada->sexo);
				$bean->setEmail($entrada->email);
				$bean->setSenha(password_hash($entrada->senha, PASSWORD_DEFAULT));//ja salva o hash

				//verifica se insere ou atualiza
				if(isset($entrada->id)){
					$entrada->id = Util::limpaString($entrada->id);

					//setando dados
					$validador->setDado("id", $entrada->id);

					//validando
					$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
					$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

					if($validador->getQntErros() == 0){//deu certo
						$bean->setId($entrada->id);

						$retorno = ProfessorDao::update($bean);
					}
					else{
						$retorno->setStatus(false);
						$retorno->setValor($GLOBALS["msgErroIdProfessorInvalido"]);
					}
				}
				else
					$retorno = ProfessorDao::insert($bean);
			}
			else{
				$retorno->setStatus(false);
				$retorno->setValor($validador->getTodosErrosMensagens());
			}

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "delete":
			if(isset($entrada->id)){
				$entrada->id = Util::limpaString($entrada->id);

				//setando dados
				$validador->setDado("id", $entrada->id);

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0){//deu certo
					//cria bean
					$bean = new ProfessorBean();
					$bean->setId($entrada->id);

					//deleta
					$retorno = ProfessorDao::delete($bean);
				}
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else{
				$retorno->setStatus(false);
				$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
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
