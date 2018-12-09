<?php
	require_once('../utils/Util.class.php');
	require_once('../conexao/BindParam.class.php');
	require_once('../conexao/ProcessaQuery.class.php');

	//Classe que executa as acoes no banco
	class AulaDao{
		//Retorna todos as aulas
		public static function getTodos(){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Aula;
			";

			return ProcessaQuery::consultarQuery($query);
		}

		//Retorna a aula pelo id da aula
		public static function get($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Aula
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//cadastra aula
		public static function insert($bean){//<FAZER> verificar quais campos realmente precisam ser inseridos e quais sao padroes
			$query = "
				INSERT INTO Aula(nome, texto, link_video_aula, Curso_id)
				VALUES(:nome, :texto, :linkVideoAula, :cursoId);
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":nome", $bean->getNome(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":texto", $bean->getTexto(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":linkVideoAula", $bean->getLinkVideoAula(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":cursoId", $bean->getCursoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//atualiza aula
		public static function update($bean){//<FAZER> verificar que campos realmente precisam ser atualizados
			$query = "
				UPDATE Aula SET
					nome = :nome,
					texto = :texto,
					link_video_aula = :linkVideoAula
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":nome", $bean->getNome(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":texto", $bean->getTexto(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":linkVideoAula", $bean->getLinkVideoAula(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_STR));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//deleta aula
		public static function delete($bean){
			$query = "
				DELETE FROM Aula
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//Retorna todas as aulas pelo id do curso
		public static function getTodosPorCurso($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Aula
				WHERE Aula.Curso_id = :cursoId;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":cursoId", $bean->getCursoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}
	}
?>
