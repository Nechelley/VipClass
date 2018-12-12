<?php
require_once ("../utils/Sessao.class.php");
require_once ("../model/dao/AutenticacaoDao.class.php");

if (!Sessao::isAtiva()) {
	//Para o script e retornar resposta para o cliente?
	die();
}

$usuarioSessao = Sessao::obterUsuario();

//Atualiza a data de validade da sessão no banco (acrescenta 30 minutos na data)
if (!AutenticacaoDao::atualizarValidadeSessao($usuarioSessao['id'])->getStatus()) {
	// Para o script?
}