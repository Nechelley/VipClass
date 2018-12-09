<?php
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../utils/validador/Validador.class.php");
	require_once("../model/bean/Imagembean.class.php");
	require_once("../model/dao/ImagemDao.class.php");

	$entrada = Util::pegaInformacaoDoFront();

	$retorno = new Retorno();

	$entrada->acao = Util::limpaString($entrada->acao);

	$validador = new validador();
	switch($entrada->acao){
		case "get":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->id)){//busca um
				//cria bean
				$bean = new ImagemBean();
				$bean->setId(Util::limpaString($entrada->id));

				//setando dados
				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = ImagemDao::get($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else if(isset($entrada->aulaId)){
				//cria bean
				$bean = new ImagemBean();
				$bean->setAulaId(Util::limpaString($entrada->aulaId));

				//setando dados
				$validador->setDado("aulaId", $bean->getAulaId());

				//validando
				$validador->getDado("aulaId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("aulaId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = ImagemDao::getTodosPorAula($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else//busca todos
				$retorno = ImagemDao::getTodos();

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "insert":
			//setando bean
			$bean = new ImagemBean();
			$bean->setId(isset($entrada->id) ? Util::limpaString($entrada->id) : null);
			$bean->setNome(isset($entrada->nome) ? Util::limpaString($entrada->nome) : null);
			$bean->setAulaId(isset($entrada->aulaId) ? Util::limpaString($entrada->aulaId) : null);

			//setando dados no validador
			$validador->setDado("nome", $bean->getNome());
			$validador->setDado("aulaId", $bean->getAulaId());

			$validador->getDado("nome")->ehVazio($GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMinimo(1, true, $GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMaximo(45, true, $GLOBALS["msgErroNomeInvalido"]);

			$validador->getDado("aulaId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
			$validador->getDado("aulaId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

			if($validador->getQntErros() == 0){//deu certo
				//verifica se insere ou atualiza
				if(isset($entrada->id)){//atualiza
					$validador->setDado("id", $bean->getId());

					//validando
					$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
					$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

					if($validador->getQntErros() == 0)//deu certo
						$retorno = ImagemDao::update($bean);
					else{
						$retorno->setStatus(false);
						$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
					}
				}
				else//insere novo
					$retorno = ImagemDao::insert($bean);
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
				$bean = new ImagemBean();
				$bean->setId(Util::limpaString($entrada->id));

				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = ImagemDao::delete($bean);
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
