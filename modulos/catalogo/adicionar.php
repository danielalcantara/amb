<?php
include_once '../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

$numCatalogo = null;
$identificacao = null;
$descricao = null;
$fabrica = null;

if (filterPost('acao') == 'adicionar') {
    $numCatalogo = anti_injection($_POST['numCatalogo']);
    $identificacao = anti_injection($_POST['identificacao']);
    $descricao = anti_injection($_POST['descricao']);
    $fabrica = anti_injection($_POST['cod_fabrica']);

    if (is_numeric($numCatalogo) and $identificacao and is_numeric($fabrica)) {
        $identificacao = ConvertMaiusculo($identificacao);
        $descricao = ConvertMaiusculo($descricao);

        $sql = "INSERT INTO catalogos (cod_fabrica,numero_catalogo,identificacao,descricao)";
        $sql .= "VALUES (" . $fabrica . ", " . $numCatalogo . ", '" . $identificacao . "', '" . $descricao . "')";
        mysql_query($sql);
        if (mysql_error()) {
            $msg = "Falha ao cadastrar o catálogo. SQL erro: " . mysql_error();
            $util->setMsgErro($msg);
        } else {
            $msg = "Catálogo cadastrado com sucesso!";
            $util->setMsgSucesso($msg);
        }
    } else {
        $msg = "Faltando dados ou dados inválidos";
        $util->setMsgErro($msg);
    }
}

if ($util->getMsgSucesso()) {
    $numCatalogo = '';
    $identificacao = '';
    $descricao = '';
    $fabrica = '';
}
echo $util->checkMensagem();
?>
<div>
    <form name="formEditCatalogo" id="formEditCatalogo" class="formTableless" action="" method="post">
        <input type="hidden" name="acao" value="adicionar">
        <p class="tituloPagina">Editar catálogo</p>
        <label for="numCatalogo">Nº Catálogo: </label>
        <input type="text" class="obr" name="numCatalogo" id="numCatalogo" value="<?php echo $numCatalogo; ?>" size="10" onkeypress="return SomenteNumero(event);">
        <br>
        <label for="identCatalogo">Identificação: </label>
        <input type="text" class="obr" name="identificacao" id="identificacao" value="<?php echo $identificacao; ?>" size="20">
        <br>
        <label for="descricao">Descrição: </label>
        <input type="text" name="descricao" id="descricao" value="<?php echo $descricao; ?>" size="40">
        <br>
        <label for="fabrica">Fábrica: </label>
        <?php
        echo $util->montarComboBox('cod_fabrica', 'nome_fabrica', 'fabricas', $fabrica);
        ?>
        <div id="botoesAcao" class="botoesAcao">
            <img class="botao" alt="Voltar" title="Voltar" src="<?php echo $util->getRaizSite(); ?>img/icones/voltar.png" 
                 onclick="RedirecPagina('index.php');">
            <img class="botao" alt="Limpar" title="Limpar" src="<?php echo $util->getRaizSite(); ?>img/icones/limpar.png"
                 onclick="LiparForm('formAddPedido');">
            <img class="botao" alt="Salvar" title="Salvar" src="<?php echo $util->getRaizSite(); ?>img/icones/salvar.png"
                 onclick="SubmitValidaForm('formEditCatalogo');">
        </div>
    </form>
</div>
<?php
include_once '../../inc/rodape.php';
?>