<?php
require_once '../../inc/funcoes.php';
require_once '../../class/Conexao/Conexao.php';
require_once '../../class/DAO/PedidoDao.php';

set_time_limit(1000);

$idRevendedorOrigem = filterPost('idRevendedorOrigem');
$idRevendedorDestino = filterPost('revendedor');

if(is_numeric($idRevendedorOrigem) and is_numeric($idRevendedorDestino)) {
    $pedidoDao = new pedidoDao();
    if($pedidoDao->migrarPorRevendedor($idRevendedorOrigem, $idRevendedorDestino)) {
        RedirecPagina('migracao.php?idRevendedorOrigem=' . $idRevendedorOrigem . '&msgSucesso=Migração realizada com sucesso!');
    } else {
        RedirecPagina('migracao.php?idRevendedorOrigem=' . $idRevendedorOrigem . '&msgErro=Falha na migração dos pedidos, '
                . 'favor contactar o administrador do sistema.');
    }
} else {
    RedirecPagina('index.php');
}

