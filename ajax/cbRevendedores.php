<?php

require_once '../inc/funcoes.php';
require_once '../class/Conexao/Conexao.php';
require_once '../class/DAO/RevendedorDao.php';

$idPonto = anti_injection($_POST['idPonto']);

$revendedorDao = new RevendedorDao();
$dadosComboRev = $revendedorDao->listarCombo($idPonto);
montaComboNovo($dadosComboRev, Array('id' => 'revendedor', 'obrigatorio' => true, 'name' => 'revendedor'));
