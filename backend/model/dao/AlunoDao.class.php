<?php
	require_once('../utils/Util.class.php');
	require_once('../conexao/BindParam.class.php');
	require_once('../conexao/ProcessaQuery.class.php');


	//Classe que executa as acoes no banco
	class AlunoDao{
		//Retorna todos os alunos
		public static function getTodos(){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Usuario
				INNER JOIN Aluno ON Aluno.Usuario_id = Usuario.id;
				WHERE Usuario.fl_ativo = 1;
			";

			return ProcessaQuery::consultarQuery($query);
		}

		//Retorna o aluno pelo id
		public static function get($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Usuario
				INNER JOIN Aluno ON Aluno.Usuario_id = Usuario.id
				WHERE Usuario.id = :id
				AND Usuario.fl_ativo = 1;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//cadastra aluno
		public static function insert($bean){//<FAZER> verificar quais campos realmente precisam ser inseridos e quais sao padroes
			$query = "
				INSERT INTO Usuario(cpf, nome, sexo, nivel_acesso, email, senha, qtd_tentativa_login, esta_logado, data_permissao_login, fl_ativo)
				VALUES(:cpf, :nome, :sexo, 0, :email, :senha, 0, TRUE, NOW(), 1);

				INSERT INTO Aluno(Usuario_id, credito)
				VALUES(LAST_INSERT_ID(), 0);
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":cpf", $bean->getCpf(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":nome", $bean->getNome(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":sexo", $bean->getSexo(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":email", $bean->getEmail(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":senha", $bean->getSenha(), PDO::PARAM_STR));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//atualiza aluno
		public static function update($bean){//<FAZER> verificar que campos realmente precisam ser atualizados
			$query = "
				UPDATE Usuario SET
					cpf = :cpf,
					nome = :nome,
					sexo = :sexo,
					email = :email,
					senha = :senha,
					qtd_tentativa_login = :qtd_tentativa_login,
					esta_logado = :esta_logado,
					data_permissao_login = :data_permissao_login
				WHERE id = :id;

				UPDATE Aluno SET
					credito = :credito
				WHERE Usuario_id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":cpf", $bean->getCpf(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":nome", $bean->getNome(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":sexo", $bean->getSexo(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":email", $bean->getEmail(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":senha", $bean->getSenha(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":qtd_tentativa_login", $bean->getQtdTentativaLogin(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":esta_logado", $bean->getEstaLogado(), PDO::PARAM_BOOL));
			array_push($bindParams, new BindParam(":data_permissao_login", $bean->getDataPermissaoLogin(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":credito", $bean->getCredito(), PDO::PARAM_STR));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//deleta aluno
		public static function delete($bean){//<FAZER> verificar se realmente remove o aluno um se so seta fl_ativo como false
			$query = "
				UPDATE Usuario SET
					fl_ativo = 0
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//retorna alunos com o cpf informado
		public static function getPorCpf($bean){
			$query = "
				SELECT
					cpf
				FROM Usuario
				WHERE cpf = :cpf
				AND fl_ativo = 1;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":cpf", $bean->getCpf(), PDO::PARAM_STR));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//retorna alunos com o email informado
		public static function getPorEmail($bean){
			$query = "
				SELECT
					email
				FROM Usuario
				WHERE email LIKE :email
				AND fl_ativo = 1;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":email", $bean->getEmail(), PDO::PARAM_STR));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}
	}
?>
