<?php
	require_once('../utils/Util.class.php');
	require_once('../conexao/BindParam.class.php');
	require_once('../conexao/ProcessaQuery.class.php');

	//Classe que executa as acoes no banco
	class EmailDao{
		//Retorna todos os email
		public static function getTodos(){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Email;
			";

			return ProcessaQuery::consultarQuery($query);
		}

		//Retorna o email pelo id
		public static function get($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Email
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//cadastra email
		public static function insert($bean){//<FAZER> verificar quais campos realmente precisam ser inseridos e quais sao padroes
			$query = "
				INSERT INTO Email(mensagem, data_envio, Curso_id, Professor_Usuario_id)
				VALUES(:mensagem, :dataEnvio, :cursoId, :professorId);
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":mensagem", $bean->getMensagem(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":dataEnvio", $bean->getDataEnvio(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":cursoId", $bean->getCursoId(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":professorId", $bean->getProfessorId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//atualiza email
		public static function update($bean){//<FAZER> verificar que campos realmente precisam ser atualizados
			$query = "
				UPDATE Email SET
					mensagem = :mensagem,
					data_envio = :dataEnvio
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":mensagem", $bean->getMensagem(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":dataEnvio", $bean->getDataEnvio(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//deleta email
		public static function delete($bean){
			$query = "
				DELETE FROM Email
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//Retorna todas as imagens pelo id do curso
		public static function getTodosPorCurso($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Email
				WHERE Curso_id = :cursoId;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":cursoId", $bean->getCursoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//Retorna todas as imagens pelo id do professor
		public static function getTodosPorProfessor($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Email
				WHERE Professor_Usuario_id = :professorId;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":professorId", $bean->getProfessorId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}
	}
?>
