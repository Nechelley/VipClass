<?php
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../utils/validador/Validador.class.php");
	require_once("../model/bean/CompraBean.class.php");
	require_once("../model/dao/CompraDao.class.php");

	$entrada = Util::pegaInformacaoDoFront();

	$retorno = new Retorno();

	$entrada->acao = Util::limpaString($entrada->acao);

	$validador = new validador();
	switch($entrada->acao){
		case "get":
			//verifica se e para buscar todos ou so um
			if(isset($entrada->alunoId) && isset($entrada->cursoId)){//busca um
				//cria bean
				$bean = new CompraBean();
				$bean->setAlunoId(Util::limpaString($entrada->alunoId));
				$bean->setCursoId(Util::limpaString($entrada->cursoId));

				//setando dados
				$validador->setDado("alunoId", $bean->getAlunoId());
				$validador->setDado("cursoId", $bean->getCursoId());

				//validando
				$validador->getDado("alunoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("alunoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = CompraDao::get($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else if(isset($entrada->alunoId)){
				//cria bean
				$bean = new CompraBean();
				$bean->setAlunoId(Util::limpaString($entrada->alunoId));

				//setando dados
				$validador->setDado("alunoId", $bean->getAlunoId());

				//validando
				$validador->getDado("alunoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("alunoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = CompraDao::getTodosPorAluno($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else if(isset($entrada->cursoId)){
				//cria bean
				$bean = new CompraBean();
				$bean->setCursoId(Util::limpaString($entrada->cursoId));

				//setando dados
				$validador->setDado("cursoId", $bean->getCursoId());

				//validando
				$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = CompraDao::getTodosPorCurso($bean);
				else{
					$retorno->setStatus(false);
					$retorno->setValor($GLOBALS["msgErroIdInvalido"]);
				}
			}
			else//busca todos
				$retorno = CompraDao::getTodos();

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "insert":
			//setando bean
			$bean = new CompraBean();
			$bean->setDataCompra(isset($entrada->dataCompra) ? Util::limpaString($entrada->dataCompra) : null);
			$bean->setValorCompra(isset($entrada->valorCompra) ? Util::limpaString($entrada->valorCompra) : null);
			$bean->setAlunoId(isset($entrada->alunoId) ? Util::limpaString($entrada->alunoId) : null);
			$bean->setCursoId(isset($entrada->cursoId) ? Util::limpaString($entrada->cursoId) : null);

			//setando dados no validador
			$validador->setDado("dataCompra", $bean->getDataCompra());
			$validador->setDado("valorCompra", $bean->getValorCompra());
			$validador->setDado("alunoId", $bean->getAlunoId());
			$validador->setDado("cursoId", $bean->getCursoId());

			$validador->getDado("dataCompra")->ehVazio($GLOBALS["msgErroDataCompraInvalido"]);
			$validador->getDado("dataCompra")->ehData($GLOBALS["msgErroDataCompraInvalido"]);

			$validador->getDado("valorCompra")->ehVazio($GLOBALS["msgErroValorCompraInvalido"]);
			$validador->getDado("valorCompra")->temMinimo(1, true, $GLOBALS["msgErroValorCompraInvalido"]);

			$validador->getDado("alunoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
			$validador->getDado("alunoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

			$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
			$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

			if($validador->getQntErros() == 0)//deu certo
				$retorno = CompraDao::insert($bean);
			else{
				$retorno->setStatus(false);
				$retorno->setValor($validador->getTodosErrosMensagens());
			}

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "update":
			//setando bean
			$bean = new CompraBean();
			$bean->setDataCompra(isset($entrada->dataCompra) ? Util::limpaString($entrada->dataCompra) : null);
			$bean->setValorCompra(isset($entrada->valorCompra) ? Util::limpaString($entrada->valorCompra) : null);
			$bean->setAlunoId(isset($entrada->alunoId) ? Util::limpaString($entrada->alunoId) : null);
			$bean->setCursoId(isset($entrada->cursoId) ? Util::limpaString($entrada->cursoId) : null);

			//setando dados no validador
			$validador->setDado("dataCompra", $bean->getDataCompra());
			$validador->setDado("valorCompra", $bean->getValorCompra());
			$validador->setDado("alunoId", $bean->getAlunoId());
			$validador->setDado("cursoId", $bean->getCursoId());

			$validador->getDado("dataCompra")->ehVazio($GLOBALS["msgErroDataCompraInvalido"]);
			$validador->getDado("dataCompra")->ehData($GLOBALS["msgErroDataCompraInvalido"]);

			$validador->getDado("valorCompra")->ehVazio($GLOBALS["msgErroValorCompraInvalido"]);
			$validador->getDado("valorCompra")->temMinimo(1, true, $GLOBALS["msgErroValorCompraInvalido"]);

			$validador->getDado("alunoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
			$validador->getDado("alunoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

			$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
			$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

			if($validador->getQntErros() == 0)//deu certo
				$retorno = CompraDao::update($bean);

			else{
				$retorno->setStatus(false);
				$retorno->setValor($validador->getTodosErrosMensagens());
			}

			Util::EnviaInformacaoParaFront($retorno);

			break;
		case "delete":
			if(isset($entrada->alunoId) && isset($entrada->cursoId)){
				//cria bean
				$bean = new CompraBean();
				$bean->setAlunoId(Util::limpaString($entrada->alunoId));
				$bean->setCursoId(Util::limpaString($entrada->cursoId));

				//setando dados
				$validador->setDado("alunoId", $bean->getAlunoId());
				$validador->setDado("cursoId", $bean->getCursoId());

				//validando
				$validador->getDado("alunoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("alunoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				$validador->getDado("cursoId")->ehVazio($GLOBALS["msgErroIdInvalido"]);
				$validador->getDado("cursoId")->temMinimo(1, $GLOBALS["msgErroIdInvalido"]);

				if($validador->getQntErros() == 0)//deu certo
					$retorno = CompraDao::delete($bean);
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
