<?php
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../utils/validador/Validador.class.php");
	require_once("../model/bean/ProfessorBean.class.php");
	require_once("../model/dao/ProfessorDao.class.php");

	$entrada = Util::pegaInformacaoDoFront();

	$retorno = new Retorno();

	$entrada->acao = Util::limpaString($entrada->acao);

	$validador = new validador();
	switch($entrada->acao){
		case "get":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->id)){//busca um
				//cria bean
				$bean = new ProfessorBean();
				$bean->setId(Util::limpaString($entrada->id));

				//setando dados
				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = ProfessorDao::get($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else if(isset($entrada->naoAprovados))
				$retorno = ProfessorDao::getTodosNaoAprovados();
			else//busca todos
				$retorno = ProfessorDao::getTodos();

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "insert":
			//setando bean
			$bean = new ProfessorBean();
			$bean->setId(isset($entrada->id) ? Util::limpaString($entrada->id) : null);
			$bean->setCpf(isset($entrada->cpf) ? Util::limpaString($entrada->cpf) : null);
			$bean->setNome(isset($entrada->nome) ? Util::limpaString($entrada->nome) : null);
			$bean->setSexo(isset($entrada->sexo) ? Util::limpaString($entrada->sexo) : null);
			$bean->setEmail(isset($entrada->email) ? Util::limpaString($entrada->email) : null);
			$bean->setSenha(isset($entrada->senha) ? Util::limpaString($entrada->senha) : null);

			//setando dados no validador
			$validador->setDado("cpf", $bean->getCpf());
			$validador->setDado("nome", $bean->getNome());
			$validador->setDado("sexo", $bean->getSexo());
			$validador->setDado("email", $bean->getEmail());
			$validador->setDado("senha", $bean->getSenha());

			$validador->getDado("cpf")->ehVazio($GLOBALS["msgErroCpfInvalido"]);
			$validador->getDado("cpf")->ehCPF($GLOBALS["msgErroCpfInvalido"]);
			$validador->getDado("cpf")->jaCadastrado(ProfessorDao::getPorCpf($bean), "cpf", (isset($entrada->id) ? ProfessorDao::get($bean) : null), $GLOBALS["msgErroCpfInvalido"]);

			$validador->getDado("nome")->ehVazio($GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMinimo(1, true, $GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMaximo(45, true, $GLOBALS["msgErroNomeInvalido"]);

			$validador->getDado("sexo")->ehVazio($GLOBALS["msgErroSexoInvalido"]);
			$validador->getDado("sexo")->ehStringPermitida(["M","F"], $GLOBALS["msgErroSexoInvalido"]);

			$validador->getDado("email")->ehVazio($GLOBALS["msgErroEmailInvalido"]);
			$validador->getDado("email")->temMinimo(1, true, $GLOBALS["msgErroEmailInvalido"]);
			$validador->getDado("email")->temMaximo(45, true, $GLOBALS["msgErroEmailInvalido"]);
			$validador->getDado("email")->ehEmail($GLOBALS["msgErroEmailInvalido"]);
			$validador->getDado("email")->jaCadastrado(ProfessorDao::getPorEmail($bean), "email", (isset($entrada->id) ? ProfessorDao::get($bean) : null), $GLOBALS["msgErroEmailInvalido"]);

			$validador->getDado("senha")->ehVazio($GLOBALS["msgErroSenhaInvalido"]);
			$validador->getDado("senha")->temMinimo(1, true, $GLOBALS["msgErroSenhaInvalido"]);
			$validador->getDado("senha")->temMaximo(45, true, $GLOBALS["msgErroSenhaInvalido"]);

			if($validador->getQntErros() == 0){//deu certo
				$bean->setSenha(password_hash($bean->getSenha(), PASSWORD_DEFAULT));//ja salva o hash

				//verifica se insere ou atualiza
				if(isset($entrada->id)){//atualiza
					if(sha1(Util::limpaString($entrada->senhaAntiga)) == (ProfessorDao::getSenha($bean)->valor[0])->senha){
						$validador->setDado("id", $bean->getId());

						//validando
						$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
						$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

						if($validador->getQntErros() == 0)//deu certo
							$retorno = ProfessorDao::update($bean);
						else{
							$retorno->setStatus(false);
							$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
						}
					}
					else{
						$retorno->setStatus(false);
						$retorno->setValor($GLOBALS["msgErroSenhaAntigaInvalido"]);
					}
				}
				else//insere novo
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
				//cria bean
				$bean = new ProfessorBean();
				$bean->setId(Util::limpaString($entrada->id));

				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = ProfessorDao::delete($bean);
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
