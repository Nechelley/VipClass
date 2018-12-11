<?php
	require_once('../utils/Util.class.php');
	require_once('../conexao/BindParam.class.php');
	require_once('../conexao/ProcessaQuery.class.php');


	//Classe que executa as acoes no banco
	class AdministradorDao{
		//Retorna todos os administrador
		public static function getTodos(){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Usuario
				INNER JOIN Administrador ON Administrador.Usuario_id = Usuario.id
				WHERE Usuario.fl_ativo = 1;
			";

			return ProcessaQuery::consultarQuery($query);
		}

		//Retorna o administrador pelo id
		public static function get($bean){
			$query = "
				SELECT
					id,
					cpf,
					email,
					nome,
					sexo
				FROM Usuario
				INNER JOIN Administrador ON Administrador.Usuario_id = Usuario.id
				WHERE Usuario.id = :id
				AND Usuario.fl_ativo = 1;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//cadastra administrador
		public static function insert($bean){//<FAZER> verificar quais campos realmente precisam ser inseridos e quais sao padroes
			$query = "
				INSERT INTO Usuario(cpf, nome, sexo, nivel_acesso, email, senha, qtd_tentativa_login, esta_logado, data_permissao_login, fl_ativo)
				VALUES(:cpf, :nome, :sexo, 0, :email, :senha, 0, FALSE, NOW(), 1);

				INSERT INTO Administrador(Usuario_id)
				VALUES(LAST_INSERT_ID());
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

		//atualiza administrador
		public static function update($bean){
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

		//deleta administrador
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

		//retorna administradores com o cpf informado
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

		//retorna administradores com o email informado
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

		//aprova um professor
		public static function aprovarProfessor($beanAdministrador, $beanProfessor){
			$query = "
				UPDATE Professor SET
					Administrador_Usuario_id = :idAdministrador,
					data_aprovacao_administrador = NOW()
				WHERE Usuario_id = :idProfessor;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":idAdministrador", $beanAdministrador->getId(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":idProfessor", $beanProfessor->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//aprova um curso
		public static function aprovarCurso($beanCurso){
			$query = "
				UPDATE Curso SET
					aprovado_pelo_administrador = 1
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $beanCurso->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//Retorna o administrador pelo id
		public static function getSenha($bean){
			$query = "
				SELECT
					senha
				FROM Usuario
				INNER JOIN Administrador ON Administrador.Usuario_id = Usuario.id
				WHERE Usuario.id = :id
				AND Usuario.fl_ativo = 1;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//aprova um professor
		public static function desaprovarProfessor($beanAdministrador, $beanProfessor){
			$query = "
				UPDATE Professor SET
					Administrador_Usuario_id = :idAdministrador,
					fl_ativo = 0
				WHERE Usuario_id = :idProfessor;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":idAdministrador", $beanAdministrador->getId(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":idProfessor", $beanProfessor->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//aprova um curso
		public static function desaprovarCurso($beanCurso){
			$query = "
				UPDATE Curso SET
					aprovado_pelo_administrador = 0
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $beanCurso->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}
	}
?>
