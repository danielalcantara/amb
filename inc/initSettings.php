<?php

ob_start();
error_reporting(E_ALL);
header('Content-type: text/html; charset=utf-8');
session_cache_expire(60);
session_start();

// Funções do sistema
require_once 'funcoes.php';

// Fazendo conexão com o banco de dados
require_once 'conn.php';
// Importando classe de conexão que implementa o PDO
require_once pegarRaizSite() . 'class/Conexao/Conexao.php';

// Classe que gera o cabeçalho HTML da página
require_once pegarRaizSite() . 'class/Util/pageheader.class.php';

// Classe de utilidades do sistema
require_once(pegarRaizSite() . 'class/Util/Util.class.php');

$util = Util::getInstance();

$util->setRaizSite(pegarRaizSite());

$header = new PageHeader();

$header->addDefaults();