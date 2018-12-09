<?php
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../utils/validador/Validador.class.php");
	require_once("../model/bean/Emailbean.class.php");
	require_once("../model/dao/EmailDao.class.php");

	$entrada = Util::pegaInformacaoDoFront();

	$retorno = new Retorno();

	$entrada->acao = Util::limpaString($entrada->acao);

	$validador = new validador();
	switch($entrada->acao){
		case "get":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->id)){//busca um
				//cria bean
				$bean = new EmailBean();
				$bean->setId(Util::limpaString($entrada->id));

				//setando dados
				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = EmailDao::get($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else if(isset($entrada->cursoId)){
				//cria bean
				$bean = new EmailBean();
				$bean->setCursoId(Util::limpaString($entrada->cursoId));

				//setando dados
				$validador->setDado("cursoId", $bean->getCursoId());

				//validando
				$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = EmailDao::getTodosPorCurso($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else if(isset($entrada->professorId)){
				//cria bean
				$bean = new EmailBean();
				$bean->setProfessorId(Util::limpaString($entrada->professorId));

				//setando dados
				$validador->setDado("professorId", $bean->getProfessorId());

				//validando
				$validador->getDado("professorId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("professorId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = EmailDao::getTodosPorProfessor($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else//busca todos
				$retorno = EmailDao::getTodos();

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "insert":
			//setando bean
			$bean = new EmailBean();
			$bean->setId(isset($entrada->id) ? Util::limpaString($entrada->id) : null);
			$bean->setMensagem(isset($entrada->mensagem) ? Util::limpaString($entrada->mensagem) : null);
			$bean->setDataEnvio(isset($entrada->dataEnvio) ? Util::limpaString($entrada->dataEnvio) : null);
			$bean->setCursoId(isset($entrada->cursoId) ? Util::limpaString($entrada->cursoId) : null);
			$bean->setProfessorId(isset($entrada->professorId) ? Util::limpaString($entrada->professorId) : null);

			//setando dados no validador
			$validador->setDado("mensagem", $bean->getMensagem());
			$validador->setDado("dataEnvio", $bean->getDataENvio());
			$validador->setDado("cursoId", $bean->getCursoId());
			$validador->setDado("professorId", $bean->getProfessorId());

			$validador->getDado("mensagem")->ehVazio($GLOBALS["msgErroMensagemInvalido"]);
			$validador->getDado("mensagem")->temMinimo(1, true, $GLOBALS["msgErroMensagemInvalido"]);

			$validador->getDado("dataEnvio")->ehVazio($GLOBALS["msgErroDataEnvioInvalido"]);
			$validador->getDado("dataEnvio")->ehData($GLOBALS["msgErroDataEnvioInvalido"]);

			$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
			$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

			$validador->getDado("professorId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
			$validador->getDado("professorId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

			if($validador->getQntErros() == 0){//deu certo
				//verifica se insere ou atualiza
				if(isset($entrada->id)){//atualiza
					$validador->setDado("id", $bean->getId());

					//validando
					$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
					$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

					if($validador->getQntErros() == 0)//deu certo
						$retorno = EmailDao::update($bean);
					else{
						$retorno->setStatus(false);
						$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
					}
				}
				else//insere novo
					$retorno = EmailDao::insert($bean);
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
				$bean = new EmailBean();
				$bean->setId(Util::limpaString($entrada->id));

				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = EmailDao::delete($bean);
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
