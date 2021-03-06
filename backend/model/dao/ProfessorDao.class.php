<?php
	require_once('../utils/Util.class.php');
	require_once('../conexao/BindParam.class.php');
	require_once('../conexao/ProcessaQuery.class.php');


	//Classe que executa as acoes no banco
	class ProfessorDao{
		//Retorna todos os professores
		public static function getTodos(){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Usuario
				INNER JOIN Professor ON Professor.Usuario_id = Usuario.id
				WHERE Usuario.fl_ativo = 1;
			";

			return ProcessaQuery::consultarQuery($query);
		}

		//Retorna o professor pelo id
		public static function get($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Usuario
				INNER JOIN Professor ON Professor.Usuario_id = Usuario.id
				WHERE Usuario.id = :id
				AND Usuario.fl_ativo = 1;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//cadastra professor
		public static function insert($bean){//<FAZER> verificar quais campos realmente precisam ser inseridos e quais sao padroes
			$query = "
				INSERT INTO Usuario(cpf, nome, sexo, nivel_acesso, email, senha, qtd_tentativa_login, esta_logado, data_permissao_login, fl_ativo)
				VALUES(:cpf, :nome, :sexo, 1, :email, :senha, 1, FALSE, NOW(), 1);

				INSERT INTO Professor(Usuario_id, Administrador_Usuario_id, data_aprovacao_administrador)
				VALUES(LAST_INSERT_ID(), NULL, NULL);
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

		//atualiza professor
		public static function update($bean){//<FAZER> verificar que campos realmente precisam ser atualizados
			$query = "
				UPDATE Usuario SET
					cpf = :cpf,
					nome = :nome,
					sexo = :sexo,
					email = :email,
					senha = :senha
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":cpf", $bean->getCpf(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":nome", $bean->getNome(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":sexo", $bean->getSexo(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":email", $bean->getEmail(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":senha", $bean->getSenha(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//deleta professor
		public static function delete($bean){
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

		//retorna professores com o cpf informado
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

		//retorna professores com o email informado
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

		//Retorna todos os professores
		public static function getTodosNaoAprovados(){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Usuario
				INNER JOIN Professor ON Professor.Usuario_id = Usuario.id
				WHERE Usuario.fl_ativo = 1
				AND Administrador_Usuario_id IS NULL;
			";

			return ProcessaQuery::consultarQuery($query);
		}
	}
?>
