<?php
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../utils/validador/Validador.class.php");
	require_once("../model/bean/Aulabean.class.php");
	require_once("../model/dao/AulaDao.class.php");

	$entrada = Util::pegaInformacaoDoFront();

	$retorno = new Retorno();

	$entrada->acao = Util::limpaString($entrada->acao);

	$validador = new validador();
	switch($entrada->acao){
		case "get":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->id)){//busca um
				//cria bean
				$bean = new AulaBean();
				$bean->setId(Util::limpaString($entrada->id));

				//setando dados
				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = AulaDao::get($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else if(isset($entrada->cursoId)){
				//cria bean
				$bean = new AulaBean();
				$bean->setCursoId(Util::limpaString($entrada->cursoId));

				//setando dados
				$validador->setDado("cursoId", $bean->getCursoId());

				//validando
				$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = AulaDao::getTodosPorCurso($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else//busca todos
				$retorno = AulaDao::getTodos();

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "insert":
			//setando bean
			$bean = new AulaBean();
			$bean->setId(isset($entrada->id) ? Util::limpaString($entrada->id) : null);
			$bean->setNome(isset($entrada->nome) ? Util::limpaString($entrada->nome) : null);
			$bean->setTexto(isset($entrada->texto) ? Util::limpaString($entrada->texto) : null);
			$bean->setLinkVideoAula(isset($entrada->linkVideoAula) ? Util::limpaString($entrada->linkVideoAula) : null);
			$bean->setCursoId(isset($entrada->cursoId) ? Util::limpaString($entrada->cursoId) : null);

			//setando dados no validador
			$validador->setDado("nome", $bean->getNome());
			$validador->setDado("texto", $bean->getTexto());
			$validador->setDado("linkVideoAula", $bean->getLinkVideoAula());
			$validador->setDado("cursoId", $bean->getCursoId());

			$validador->getDado("nome")->ehVazio($GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMinimo(1, true, $GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMaximo(45, true, $GLOBALS["msgErroNomeInvalido"]);

			$validador->getDado("texto")->ehVazio($GLOBALS["msgErroTextoInvalido"]);

			$validador->getDado("linkVideoAula")->temMinimo(1, true, $GLOBALS["msgErroLinkVideoAulaInvalido"]);
			$validador->getDado("linkVideoAula")->temMaximo(200, true, $GLOBALS["msgErroLinkVideoAulaInvalido"]);

			$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
			$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

			if($validador->getQntErros() == 0){//deu certo
				//verifica se insere ou atualiza
				if(isset($entrada->id)){//atualiza
					$validador->setDado("id", $bean->getId());

					//validando
					$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
					$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

					if($validador->getQntErros() == 0)//deu certo
						$retorno = AulaDao::update($bean);
					else{
						$retorno->setStatus(false);
						$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
					}
				}
				else//insere novo
					$retorno = AulaDao::insert($bean);
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
				$bean = new AulaBean();
				$bean->setId(Util::limpaString($entrada->id));

				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = AulaDao::delete($bean);
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
