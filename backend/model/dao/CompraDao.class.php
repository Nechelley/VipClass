<?php
	require_once('../utils/Util.class.php');
	require_once('../conexao/BindParam.class.php');
	require_once('../conexao/ProcessaQuery.class.php');

	//Classe que executa as acoes no banco
	class CompraDao{
		//Retorna todas compras
		public static function getTodos(){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Aluno_Compra_Curso;
			";

			return ProcessaQuery::consultarQuery($query);
		}

		//Retorna a compra pelo id
		public static function get($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Aluno_Compra_Curso
				WHERE alunoId = :alunoId
				AND cursoId = :cursoId;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":alunoId", $bean->getAlunoId(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":cursoId", $bean->getAlunoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//cadastra imagem
		public static function insert($bean){//<FAZER> verificar quais campos realmente precisam ser inseridos e quais sao padroes
			$query = "
				INSERT INTO Aluno_Compra_Curso(data_compra, valor_compra, Aluno_Usuario_id, Curso_id)
				VALUES(:dataCompra, :valorCompra, :alunoId, :cursoId);
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":dataCompra", $bean->getDataCompra(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":valorCompra", $bean->getValorCompra(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":alunoId", $bean->getAlunoId(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":cursoId", $bean->getCursoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//atualiza compra
		public static function update($bean){//<FAZER> verificar que campos realmente precisam ser atualizados
			$query = "
				UPDATE Aluno_Compra_Curso SET
					data_compra = :dataCompra,
					valor_compra = :valorCompra
				WHERE Aluno_Usuario_id = :alunoId
				AND Curso_id = :cursoId;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":dataCompra", $bean->getDataCompra(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":valorCompra", $bean->getValorCompra(), PDO::PARAM_STR));
			array_push($bindParams, new BindParam(":alunoId", $bean->getAlunoId(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":cursoId", $bean->getAlunoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//deleta imagem
		public static function delete($bean){
			$query = "
				DELETE FROM Aluno_Compra_Curso
				WHERE Aluno_Usuario_id = :alunoId
				AND Curso_id = :cursoId;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":alunoId", $bean->getAlunoId(), PDO::PARAM_INT));
			array_push($bindParams, new BindParam(":cursoId", $bean->getAlunoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::executarQuery($query, $bindParams);
		}

		//Retorna todas as imagens pelo id do aluno
		public static function getTodosPorAluno($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Aluno_Compra_Curso
				WHERE Aluno_Usuario_id = :alunoId;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":alunoId", $bean->getAlunoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}

		//Retorna todas as imagens pelo id do curso
		public static function getTodosPorCurso($bean){//<FAZER> verificar quais campos realmente precisam ser buscados
			$query = "
				SELECT
					*
				FROM Aluno_Compra_Curso
				WHERE Curso_id = :cursoId;
			";

			//parametros de bind
			$bindParams = array();
			array_push($bindParams, new BindParam(":cursoId", $bean->getAlunoId(), PDO::PARAM_INT));

			//executa
			return ProcessaQuery::consultarQuery($query, $bindParams);
		}
	}
?>
