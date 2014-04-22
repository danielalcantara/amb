<?php

require_once '../inc/funcoes.php';
require_once '../class/Conexao/Conexao.php';
require_once '../class/DAO/RevendedorDao.php';

$id = filterPost('idRevendedor');
$revendedorDao = new RevendedorDao();
$dadosRevendedor = $revendedorDao->buscar($id);

imprimeDadosRevendedor($dadosRevendedor);
