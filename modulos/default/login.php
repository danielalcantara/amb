<?php
ob_start();
session_start();

require_once '../../inc/conn.php';
require_once '../../inc/security.php';
require_once '../../inc/funcoes.php';

$login = null;
$msgFalha = null;

if (filterPost('login')) {
    $login = filterPost('login');
    $senha = filterPost('senha');

    if (validaUsuario($login, $senha)) {
        RedirecPagina('index.php');
    } else {
        $msgFalha = 'Falha ao efetuar login. Favor verificar usuário e senha.';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Página de Login do Sistema SDC</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="../../js/funcoes.js"></script>
        <link href="../../css/login.css" rel="stylesheet" type="text/css">
    </head>
    <body onload="document.formLogin.login.focus();">
        <div id="divLogin">
            <div id="divTitulo">
                <p>Seja bem vindo!</p>
                <p>Favor informe seu usuário e senha para acessar o sistema.</p>
                <?php echo $msgFalha ? '<p class="msgErro">' . $msgFalha . '</p>' : ''; ?>
            </div>
            <div id="divForm">
                <form name="formLogin" id="formLogin" method="post">
                    <div id="divLabels">
                        <p>
                            <label for="login">Login:</label>
                        </p>
                        <p>
                            <label for="senha">Senha:</label>
                        </p>
                    </div>
                    <div id="divInputs">
                        <p>
                            <input type="text" name="login" maxlength="20" id="login" value="<?php echo $login; ?>">
                        </p>
                        <p>
                            <input type="password" name="senha" maxlength="20" id="senha">
                        </p>
                    </div>
                    <div class="divClear"></div>
                    <div id="divBotoes">
                        <input type="submit" value="Entrar">
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
mysql_close($conn);
ob_end_flush();
