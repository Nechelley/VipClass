<?php
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../utils/validador/Validador.class.php");
	require_once("../model/bean/Cursobean.class.php");
	require_once("../model/dao/CursoDao.class.php");

	$entrada = Util::pegaInformacaoDoFront();

	$retorno = new Retorno();

	$entrada->acao = Util::limpaString($entrada->acao);

	$validador = new validador();
	switch($entrada->acao){
		case "get":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->id)){//busca um
				//cria bean
				$bean = new CursoBean();
				$bean->setId(Util::limpaString($entrada->id));

				//setando dados
				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = CursoDao::get($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else if(isset($entrada->naoAprovados))
				$retorno = CursoDao::getTodosNaoAprovados();
			else//busca todos
				$retorno = CursoDao::getTodos();

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "insert":
			//setando bean
			$bean = new CursoBean();
			$bean->setId(isset($entrada->id) ? Util::limpaString($entrada->id) : null);
			$bean->setNome(isset($entrada->nome) ? Util::limpaString($entrada->nome) : null);
			$bean->setValor(isset($entrada->valor) ? Util::limpaString($entrada->valor) : null);
			$bean->setDescricao(isset($entrada->descricao) ? Util::limpaString($entrada->descricao) : null);
			$bean->setProfessorId(isset($entrada->professorId) ? Util::limpaString($entrada->professorId) : null);

			//setando dados no validador
			$validador->setDado("nome", $bean->getNome());
			$validador->setDado("valor", $bean->getValor());
			$validador->setDado("descricao", $bean->getDescricao());
			$validador->setDado("professorId", $bean->getProfessorId());

			$validador->getDado("nome")->ehVazio($GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMinimo(1, true, $GLOBALS["msgErroNomeInvalido"]);
			$validador->getDado("nome")->temMaximo(45, true, $GLOBALS["msgErroNomeInvalido"]);

			$validador->getDado("valor")->ehVazio($GLOBALS["msgErroValorInvalido"]);

			$validador->getDado("descricao")->ehVazio($GLOBALS["msgErroDescricaoInvalido"]);
			$validador->getDado("descricao")->temMinimo(1, true, $GLOBALS["msgErroDescricaoInvalido"]);
			$validador->getDado("descricao")->temMaximo(200, true, $GLOBALS["msgErroDescricaoInvalido"]);

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
						$retorno = CursoDao::update($bean);
					else{
						$retorno->setStatus(false);
						$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
					}
				}
				else//insere novo
					$retorno = CursoDao::insert($bean);
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
				$bean = new CursoBean();
				$bean->setId(Util::limpaString($entrada->id));

				$validador->setDado("id", $bean->getId());

				//validando
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = CursoDao::delete($bean);
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
