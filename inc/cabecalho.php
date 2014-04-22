<?php

require_once 'security.php';
require_once 'initSettings.php';

// Verifica se o usuário esta logado no sistema
protegePagina();

$raiz = pegarRaizSite();

$header->setTitle('AMB Web - Sistema de Distribuição de Catálogos');
$header->setContentType('text/html');
$header->setCharset('utf-8');
$header->addStyle($raiz . 'css/styles.css', false);
$header->addStyle($raiz . 'css/menu.css', false);
$header->addStyle($raiz . 'css/uiThemes/smoothness/jquery-ui-1.10.3.custom.min.css', false);
$header->addScript($raiz . 'js/jquery-1.9.1.js', true);
$header->addScript($raiz . 'js/jquery-ui-1.10.3.custom.min.js', true);
$header->addScript($raiz . 'js/funcoes.js', true);