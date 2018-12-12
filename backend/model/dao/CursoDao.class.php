<?php
	require_once('../utils/Util.class.php');
	require_once('../conexao/BindParam.class.php');
	require_once('../conexao/ProcessaQuery.class.php');

	//Classe que executa as acoes no banco
	class CursoDao{
		//Retorna todos os cursos
		public static function getTodos(){//<FAZER> verificar quais campos realmente precisam ser buscados e verificar se precisa retornar as infs do professor dono do curso
			$query = "
				SELECT
					*
				FROM Curso;
			";

			return ProcessaQuery::consultarQuery($query);
		}

		//Retorna o curso pelo id
		public static function get($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Curso
				WHERE Curso.id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//cadastra curso
		public static function insert($bean){//<FAZER> verificar quais campos realmente precisam ser inseridos e quais sao padroes
			$query = "
				INSERT INTO Curso(nome, valor, descricao, aprovado_pelo_administrador, fl_ativo, Professor_Usuario_id)
				VALUES(:nome, :valor, :descricao, NULL, TRUE, :professorId);
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":nome", $bean->getNome(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":valor", $bean->getValor(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":descricao", $bean->getDescricao(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":professorId", $bean->getProfessorId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//atualiza curso
		public static function update($bean){//<FAZER> verificar que campos realmente precisam ser atualizados
			$query = "
				UPDATE Curso SET
					nome = :nome,
					valor = :valor,
					descricao = :descricao
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":nome", $bean->getNome(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":valor", $bean->getValor(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":descricao", $bean->getDescricao(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_STR));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//deleta curso
		public static function delete($bean){
			$query = "
				UPDATE Curso SET
					fl_ativo = 0
				WHERE id = :id;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":id", $bean->getId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//Retorna todos os cursos
		public static function getTodosNaoAprovados(){//<FAZER> verificar quais campos realmente precisam ser buscados e verificar se precisa retornar as infs do professor dono do curso
			$query = "
				SELECT
					*
				FROM Curso
				WHERE aprovado_pelo_administrador IS NULL;
			";

			return ProcessaQuery::consultarQuery($query);
		}
	}
?>
