<?php

require_once 'funcoes.php';

//  Configurações de segurança
// ==============================
$_SG['conectaServidor'] = false;    // Abre uma conexão com o servidor MySQL?
$_SG['abreSessao'] = false;         // Inicia a sessão com um session_start()?

$_SG['caseSensitive'] = true;     // Usar case-sensitive? Onde 'thiago' é diferente de 'THIAGO'

$_SG['validaSessao'] = true;       // Deseja validar o usuário e a senha a cada carregamento de página?
// Evita que, ao mudar os dados do usuário no banco de dado o mesmo contiue logado.

$_SG['servidor'] = 'localhost';    // Servidor MySQL
$_SG['usuario'] = 'root';          // Usuário MySQL
$_SG['senha'] = '';                // Senha MySQL
$_SG['banco'] = 'test';            // Banco de dados MySQL

$_SG['paginaLogin'] = 'modulos/default/login.php'; // Página de login

$_SG['tabela'] = 'TbAdmUsuario';       // Nome da tabela onde os usuários são salvos
$_SG['tabelaSessao'] = 'TbAdmSessao';       // Nome da tabela onde as sessões dos usuários são salvas. Evita que um usuário faça login em mais de uma máquina
// ==============================
// Verifica se precisa fazer a conexão com o MySQL
if ($_SG['conectaServidor'] == true) {
    $_SG['link'] = mysql_connect($_SG['servidor'], $_SG['usuario'], $_SG['senha']) or die("MySQL: Não foi possível conectar-se ao servidor [" . $_SG['servidor'] . "].");
    mysql_select_db($_SG['banco'], $_SG['link']) or die("MySQL: Não foi possível conectar-se ao banco de dados [" . $_SG['banco'] . "].");
}

// Verifica se precisa iniciar a sessão
if ($_SG['abreSessao'] == true) {
    session_start();
}

/**
 * Função que valida um usuário e senha
 *
 * @param string $usuario - O usuário a ser validado
 * @param string $senha - A senha a ser validada
 *
 * @return bool - Se o usuário foi validado ou não (true/false)
 */
function validaUsuario($usuario, $senha) {
    global $_SG;
    $retorno = true;

    $cS = ($_SG['caseSensitive']) ? 'BINARY' : '';

    // Usa a função addslashes para escapar as aspas
    $nusuario = addslashes($usuario);
    $nsenha = addslashes($senha);

    // Monta uma consulta SQL (query) para procurar um usuário
    $sql = "SELECT `Id`, `Login`, `Nome`, `Senha` FROM `" . $_SG['tabela'] . "` WHERE " .
            $cS . " `Login` = '" . $nusuario . "' LIMIT 1";
    $query = mysql_query($sql);
    $resultado = mysql_fetch_assoc($query);

    // Verifica se encontrou algum registro
    if (empty($resultado)) {
        // Nenhum registro foi encontrado => o usuário é inválido
        $retorno = false;
    } else {
        if (crypt($nsenha, $resultado['Senha'])) {
            // O registro foi encontrado => o usuário é valido
            // Definimos os valores na sessão com os dados do usuário
            $retorno = logarUsuario($resultado);
        } else {
            $retorno = false;
        }
    }

    return $retorno;
}

function logarUsuario($usuario) {

    global $_SG;
    $_SESSION['usuarioID'] = $usuario['Id']; // Pega o valor da coluna 'id do registro encontrado no MySQL
    $_SESSION['usuarioNome'] = $usuario['Nome']; // Pega o valor da coluna 'nome' do registro encontrado no MySQL
    // Definimos dois valores na sessão com os dados do login
    $_SESSION['usuarioLogin'] = $usuario['Login'];
    //$_SESSION['usuarioSenha'] = $senha;s

    if ($_SG['validaSessao']) {
        $sql = "SELECT COUNT(*) FROM TbAdmSessao WHERE IdUsuario = " . $usuario['Id'];
        $result = mysql_query($sql);

        if (mysql_result($result, 0) > 0) {
            $sql = "UPDATE " . $_SG['tabelaSessao'] . " SET Id = '" . session_id() . "', Ip = '" .
                    filterServer('REMOTE_ADDR') . "', IdUsuario = " . $usuario['Id'] . ", ";
            $sql .= "DataUltimoAcesso = NOW(), Logado = 1 WHERE IdUsuario = " . $usuario['Id'];
        } else {
            $sql = "INSERT INTO " . $_SG['tabelaSessao'] . " (Id, Ip, IdUsuario, DataUltimoAcesso, Logado) ";
            $sql .= "VALUE ('" . session_id() . "', '" . filterServer('REMOTE_ADDR') . "', " . $usuario['Id'] . ", NOW()";
            $sql .= ", 1)";
        }

        return mysql_query($sql);
    }
}

/**
 * Função que protege uma página
 */
function protegePagina() {
    global $_SG;

    if ($_SESSION['usuarioID'] === false OR $_SESSION['usuarioNome'] === false) {
        // Não há usuário logado, manda pra página de login
        expulsaVisitante();
    } else {
        // Há usuário logado, verifica se precisa validar o login novamente
        if ($_SG['validaSessao'] == true) {
            // Verifica se o usuário está logado em mais de uma máquina
            validaSessao();
        }
    }
}

/**
 * Função para expulsar um visitante
 */
function expulsaVisitante() {
    global $_SG;

    // Remove as variáveis da sessão (caso elas existam)
    session_destroy();

    // Manda pra tela de login
    header("Location: " . pegarRaizSite() . $_SG['paginaLogin']);
}

function validaSessao() {
    $sql = "SELECT COUNT(*) FROM tbAdmSessao WHERE Id='" . session_id() . "' AND IdUsuario = " . $_SESSION['usuarioID'] . " AND Logado = 1";
    $result = mysql_query($sql);
    if (mysql_result($result, 0) == 0) {
        expulsaVisitante();
    }
}
