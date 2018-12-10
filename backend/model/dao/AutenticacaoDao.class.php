<?php


class AutenticacaoDao
{
	public static function verificarLogin(LoginBean $loginBean)
	{
		$query = "
			SELECT
				id,
				nome,
				nivel_acesso,
				email,
				senha,
				!ISNULL(P.Usuario_id) AS is_professor,
				!ISNULL(A.Usuario_id) AS is_aluno
			FROM Usuario AS U
				LEFT JOIN Professor AS P 
					ON P.Usuario_id = U.id
				LEFT JOIN Aluno AS A
					ON A.Usuario_id = U.id
			WHERE U.email = :email
				AND U.fl_ativo = 1
				AND U.esta_logado = 0
		";

		$retorno = ProcessaQuery::consultarQuery($query, [
			new BindParam(":email", $loginBean->getEmail(), PDO::PARAM_STR)
		]);

		$resultadoConsulta = $retorno->getValor();
		$retornoResposta = new Retorno();

		//Verifica se há resultado para a consulta
		if(
			isset($resultadoConsulta) && 
			is_array($resultadoConsulta) && 
			!empty($resultadoConsulta)
		) {
			$usuario = reset($resultadoConsulta);
			$senha = $loginBean->getSenha();

			//Verifica se a senha está correta
			if (password_verify($senha, $usuario->senha)) {
				$retornoResposta->setStatus(true);
				$retornoResposta->setValor([
					"id" => $usuario->id,
					"nome" => $usuario->nome,
					"nivelAcesso" => $usuario->nivel_acesso,
					"email" => $usuario->email
				]);

				return $retornoResposta;
			} 
		}

		$retornoResposta->setValor($GLOBALS['msgErroLogin']);

		return $retornoResposta;
	}
}
