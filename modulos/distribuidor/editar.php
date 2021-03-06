<?php
include_once '../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $pagina = isset($_POST['pagina']) ? anti_injection($_POST['pagina']) : '';
    $filtro = isset($_POST['filtro']) ? anti_injection($_POST['filtro']) : '';
} else {
    RedirecPagina('index.php');
}

$nome_distribuidor = null;
$logradouro = null;
$comp_logradouro = null;
$bairro = null;
$cidade = null;
$estado = null;
$cep = null;
$telefone_1 = null;
$telefone_2 = null;
$cgc = null;
$inscricao_estadual = null;

if (isset($_POST['acao']) and is_numeric($id)) {
    if ($_POST['acao'] == 'editar') {
        $nome_distribuidor = anti_injection($_POST['nome_distribuidor']);
        $logradouro = anti_injection($_POST['logradouro']);
        $comp_logradouro = anti_injection($_POST['comp_logradouro']);
        $bairro = anti_injection($_POST['bairro']);
        $cidade = anti_injection($_POST['cidade']);
        $estado = anti_injection($_POST['estado']);
        $cep = anti_injection($_POST['cep']);
        $telefone_1 = anti_injection($_POST['telefone_1']);
        $telefone_2 = anti_injection($_POST['telefone_2']);
        $cgc = anti_injection($_POST['cgc']);
        $inscricao_estadual = anti_injection($_POST['inscricao_estadual']);

        if ($nome_distribuidor) {

            $nome_distribuidor = ConvertMaiusculo($nome_distribuidor);
            $logradouro = ConvertMaiusculo($logradouro);
            $bairro = ConvertMaiusculo($bairro);
            $cidade = ConvertMaiusculo($cidade);
            $estado = ConvertMaiusculo($estado);
            $comp_logradouro = ConvertMaiusculo($comp_logradouro);
            $cgc = ConvertMaiusculo($cgc);
            $inscricao_estadual = ConvertMaiusculo($inscricao_estadual);

            $sql = "UPDATE distribuidor SET nome_distribuidor = '" . $nome_distribuidor . "', logradouro = '" . $logradouro . "',";
            $sql .= "comp_logradouro = '" . $comp_logradouro . "', bairro = '" . $bairro . "', ";
            $sql .= "cep = '" . $cep . "', cidade = '" . $cidade . "', estado = '" . $estado . "', telefone_1 = '" . $telefone_1;
            $sql .= "', telefone_2 = '" . $telefone_2 . "', cgc = '" . $cgc . "', inscricao_estadual = '" . $inscricao_estadual . "' ";
            $sql .= "WHERE cod_distribuidor = " . $id;

            mysql_query($sql);

            if (mysql_error()) {
                $msg = "Falha ao atualizar o distribuidor. SQL erro: " . mysql_error();
                $util->setMsgErro($msg);
            } else {
                $msg = "Distribuidor atualizado com sucesso!";
                $util->setMsgSucesso($msg);
            }
        } else {
            $msg = "Faltando dados ou dados inválidos";
            $util->setMsgErro($msg);
        }
    }
}

if (!$util->getMsgErro()) {
    $sql = "SELECT nome_distribuidor, logradouro, comp_logradouro, bairro, telefone_1, telefone_2, ";
    $sql .= "cep, cidade, estado, cgc, inscricao_estadual FROM distribuidor ";
    $sql .= "WHERE cod_distribuidor = " . $id;
    $rs = mysql_query($sql);
    $linha = mysql_fetch_array($rs);
    list($nome_distribuidor, $logradouro, $comp_logradouro, $bairro, $telefone_1, $telefone_2, $cep, $cidade, $estado,
            $cgc, $inscricao_estadual) = $linha;
}
?>
<div id="distribuidorEditar" class="formCadastro">
    <form name="formEditDistribuidor" id="formEditDistribuidor" action="" method="post">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="pagina" value="<?php echo $pagina; ?>">
        <input type="hidden" name="filtro" value="<?php echo $filtro; ?>">
        <?php
        echo $util->checkMensagem();
        ?>
        <p class="tituloPagina">Cadastro de Distribuidor</p>
        <div style="float: left;">
            <p class="label">
                <label for="nome_distribuidor">Nome: </label>
            </p>
            <p class="label">
                <label for="logradouro">Endereço: </label>
            </p>
            <p class="label">
                <label for="comp_logradouro">Complemento: </label>
            </p>
            <p class="label">
                <label for="bairro">Bairro: </label>
            </p>
            <p class="label">
                <label for="cidade">Cidade: </label>
            </p>
            <p class="label">
                <label for="estado">Estado: </label>
            </p>
            <p class="label">
                <label for="cep">CEP: </label>
            </p>
            <p class="label">
                <label for="telefone_1">Telefone 1: </label>
            </p>
            <p class="label">
                <label for="telefone_2">Telefone 2: </label>
            </p>
            <p class="label">
                <label for="cgc">CGC: </label>
            </p>
            <p class="label">
                <label for="inscricao_estadual">Inscrição Estadual: </label>
            </p>
        </div>
        <div style="float: left;">
            <p class="inputs">
                <input type="text" class="obr" name="nome_distribuidor" id="nome_distribuidor" value="<?php echo $nome_distribuidor; ?>" maxlength="60" size="60">
            </p>
            <p class="inputs">
                <input type="text" name="logradouro" id="logradouro" value="<?php echo $logradouro; ?>" maxlength="100" size="60">
            </p>
            <p class="inputs">
                <input type="text" name="comp_logradouro" id="comp_logradouro" value="<?php echo $comp_logradouro; ?>" maxlength="100" size="60">
            </p>
            <p class="inputs">
                <input type="text" name="bairro" id="bairro" maxlength="50" value="<?php echo $bairro; ?>" size="30">
            </p>
            <p class="inputs">
                <input type="text" name="cidade" id="cidade" value="<?php echo $cidade; ?>" size="30" maxlength="50">
            </p>
            <p class="inputs">
                <?php
                echo $util->montarComboBoxEstado($estado);
                ?>
            </p>
            <p class="inputs">
                <input type="text" name="cep" id="cep" maxlength="10" onKeyPress="MascaraCep(this);" value="<?php echo $cep; ?>" 
                       onblur="ValidaCep(this);" size="6">
            </p>
            <p class="inputs">
                <input type="text" name="telefone_1" id="telefone_1" onkeypress="MascaraTelefone(this);" value="<?php echo $telefone_1; ?>"
                       onblur="ValidaTelefone(this);" maxlength="14" size="10">
            </p>
            <p class="inputs">
                <input type="text" name="telefone_2" id="telefone_2" onkeypress="MascaraTelefone(this);" value="<?php echo $telefone_2; ?>"
                       onblur="ValidaTelefone(this);" maxlength="14" size="10">
            </p>
            <p class="inputs">
                <input type="text" name="cgc" id="cgc" value="<?php echo $cgc; ?>" size="20" maxlength="16" onkeypress="return SomenteNumero(event);">
            </p>
            <p class="inputs">
                <input type="text" name="inscricao_estadual" id="inscricao_estadual" value="<?php echo $inscricao_estadual; ?>" size="30" maxlength="20">
            </p>
        </div>
        <div id="botoesAcao" class="botoesAcao">
            <img class="botao" src="<?php echo $util->getRaizSite(); ?>img/icones/voltar.png" onclick="voltarParaIndex('formEditDistribuidor');">
            <img class="botao" src="<?php echo $util->getRaizSite(); ?>img/icones/limpar.png" onclick="LiparForm('formEditDistribuidor');">
            <img class="botao" src="<?php echo $util->getRaizSite(); ?>img/icones/salvar.png" onclick="SubmitValidaForm('formEditDistribuidor');">
        </div>
    </form>
</div>
<?php
include_once '../../inc/rodape.php';
?>