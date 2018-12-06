<?php
	require_once('../conexao/ProcessaQuery.class.php');
	require_once('../utils/Util.class.php');

	//Classe que executa as acoes no banco
	class AlunoDao{
		//Retorna todos os alunos
		public static function getTodos(){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Usuario
				INNER JOIN Aluno ON Aluno.Usuario_id = Usuario.id;
			";

			//executa
			return ProcessaQuery::consultarQuery($query);
		}

		//Retorna o aluno pelo id
		public static function get($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Usuario
				INNER JOIN Aluno ON Aluno.Usuario_id = Usuario.id
				WHERE Usuario.id = {$bean->getId()};
			";

			//executa
			return ProcessaQuery::consultarQuery($query);
		}

		//cadastra aluno
		public static function insert($bean){//<FAZER> verificar quais campos realmente precisam ser inseridos e quais sao padroes
			//ajusta campos
			$bean->setCpf(Util::ajustaCampoParaBD($bean->getCpf()));
			$bean->setNome(Util::ajustaCampoParaBD($bean->getNome()));
			$bean->setSexo(Util::ajustaCampoParaBD($bean->getSexo()));
			$bean->setEmail(Util::ajustaCampoParaBD($bean->getEmail()));
			$bean->setSenha(Util::ajustaCampoParaBD($bean->getSenha()));

			$query = "
				INSERT INTO Usuario(cpf, nome, sexo, nivel_acesso, email, senha, qtd_tentativa_login, esta_logado, data_permissao_login, fl_ativo)
				VALUES({$bean->getCpf()}, {$bean->getNome()}, {$bean->getSexo()}, 0, {$bean->getEmail()}, {$bean->getSenha()}, 0, TRUE, NOW(), 1);

				INSERT INTO Aluno(Usuario_id, credito)
				VALUES(LAST_INSERT_ID(), 0);
			";

			//executa
			return ProcessaQuery::executarQuery($query);
		}

		//atualiza aluno
		public static function update($bean){//<FAZER> verificar que campos realmente precisam ser atualizados
			//ajusta campos
			$bean->setCpf(Util::ajustaCampoParaBD($bean->getCpf()));
			$bean->setNome(Util::ajustaCampoParaBD($bean->getNome()));
			$bean->setSexo(Util::ajustaCampoParaBD($bean->getSexo()));
			$bean->setEmail(Util::ajustaCampoParaBD($bean->getEmail()));
			$bean->setSenha(Util::ajustaCampoParaBD($bean->getSenha()));
			$bean->setQtdTentativaLogin(Util::ajustaCampoParaBD($bean->getQtdTentativaLogin()));
			$bean->setEstaLogado(Util::ajustaCampoParaBD($bean->getEstaLogado()));
			$bean->setDataPermissaoLogin(Util::ajustaCampoParaBD($bean->getDataPermissaoLogin()));
			$bean->setFlAtivo(Util::ajustaCampoParaBD($bean->getFlAtivo()));

			$query = "
				UPDATE Usuario SET
					cpf = {$bean->getCpf()},
					nome = {$bean->getNome()},
					sexo = {$bean->getSexo()},
					email = {$bean->getEmail()},
					senha = {$bean->getSenha()},
					qtd_tentativa_login = {$bean->getQtdTentativaLogin()},
					esta_logado = {$bean->getEstaLogado()},
					data_permissao_login = {$bean->getDataPermissaoLogin()},
					fl_ativo = {$bean->getFlAtivo()}
				WHERE id = {$bean->getId()};

				UPDATE Usuario SET
					credito = {$bean->getCredito()}
				WHERE Usuario_id = {$bean->getId()};
			";

			//executa
			return ProcessaQuery::executarQuery($query);
		}

		//deleta aluno
		public static function delete($bean){//<FAZER> verificar se realmente remove o aluno um se so seta fl_ativo como false
			$query = "
				DELETE FROM Aluno
				WHERE Usuario_id = {$bean->getId()};

				DELETE FROM Usuario
				WHERE id = {$bean->getId()};
			";

			//executa
			return ProcessaQuery::executarQuery($query);
		}
	}
?>
