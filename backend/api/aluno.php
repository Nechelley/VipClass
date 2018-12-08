<?php
	//<FAZER> verificacao se email e cpf ja estao cadastrados
	require_once("../utils/Msgs.php");
	require_once("../utils/Retorno.class.php");
	require_once("../utils/Util.class.php");
	require_once("../utils/validador/Validador.class.php");
	require_once("../model/bean/Alunobean.class.php");
	require_once("../model/dao/AlunoDao.class.php");

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
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroAlunoIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroAlunoIdInvalido"]);

				if($validador->getQntErros() == 0){//deu certo
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

			$validador->setDado("cpf", (isset($entrada->cpf) ? $entrada->cpf : null));
			$validador->setDado("nome", (isset($entrada->nome) ? $entrada->nome : null));
			$validador->setDado("sexo", (isset($entrada->sexo) ? $entrada->sexo : null));
			$validador->setDado("email", (isset($entrada->email) ? $entrada->email : null));
			$validador->setDado("senha", (isset($entrada->senha) ? $entrada->senha : null));

			$validador->getDado("cpf")->ehVazio($GLOBALS["msgErroAlunoCpfInvalido"]);
			$validador->getDado("cpf")->ehCPF($GLOBALS["msgErroAlunoCpfInvalido"]);

			$validador->getDado("nome")->ehVazio($GLOBALS["msgErroAlunoNomeInvalido"]);
			$validador->getDado("nome")->temMinimo(1, true, $GLOBALS["msgErroAlunoNomeInvalido"]);
			$validador->getDado("nome")->temMaximo(45, true, $GLOBALS["msgErroAlunoNomeInvalido"]);

			$validador->getDado("sexo")->ehVazio($GLOBALS["msgErroAlunoSexoInvalido"]);
			$validador->getDado("sexo")->ehStringPermitida(["M","F"], $GLOBALS["msgErroAlunoSexoInvalido"]);

			$validador->getDado("email")->ehVazio($GLOBALS["msgErroAlunoEmailInvalido"]);
			$validador->getDado("email")->temMinimo(1, true, $GLOBALS["msgErroAlunoNomeInvalido"]);
			$validador->getDado("email")->temMaximo(45, true, $GLOBALS["msgErroAlunoNomeInvalido"]);
			$validador->getDado("email")->ehEmail($GLOBALS["msgErroAlunoEmailInvalido"]);
			//<FAZER> acrescentar verificacao se email ja esta cadastrado

			$validador->getDado("senha")->ehVazio($GLOBALS["msgErroAlunoSenhaInvalido"]);
			$validador->getDado("senha")->temMinimo(1, true, $GLOBALS["msgErroAlunoNomeInvalido"]);
			$validador->getDado("senha")->temMaximo(45, true, $GLOBALS["msgErroAlunoNomeInvalido"]);

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
					$validador->getDado("id")->ehVazio($GLOBALS["msgErroAlunoIdInvalido"]);
					$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroAlunoIdInvalido"]);

					if($validador->getQntErros() == 0){//deu certo
						$bean->setId($entrada->id);

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
				$validador->getDado("id")->ehVazio($GLOBALS["msgErroAlunoIdInvalido"]);
				$validador->getDado("id")->temMinimo(1, $GLOBALS["msgErroAlunoIdInvalido"]);

				if($validador->getQntErros() == 0){//deu certo
					//cria bean
					$bean = new AlunoBean();
					$bean->setId($entrada->id);

					//deleta
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
