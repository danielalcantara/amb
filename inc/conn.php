<?php

$db = array();

$db['server'] = 'localhost';
$db['user'] = 'cont9rg2_sdcbd';
$db['password'] = 'workambbd2013';
$db['name'] = 'cont9rg2_siscatalogo';

// Estabelecendo conexão com o banco de dados ('servidor', 'usuário', 'senha')
$conn = mysql_connect($db['server'], $db['user'], $db['password']);

// Selecionando o banco
mysql_select_db($db['name'], $conn);

ini_set('default_charset', 'UTF-8'); // Para o charset das páginas e

mysql_set_charset('utf8'); // para a conexão com o MySQL