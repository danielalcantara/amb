<?php
include_once '../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

$cod_ponto = null;
$data_cadastro = null;
$nome = null;
$logradouro = null;
$numero = null;
$comp_logradouro = null;
$bairro = null;
$ponto_referencia = null;
$cep = null;
$cidade = null;
$estado = null;
$telefone = null;
$data_nascimento = null;
$estado_civil = null;
$cod_profissao = null;
$sexo = null;
$cpf = null;
$rg = null;
$org_exp = null;

if (isset($_POST['acao'])) {
    $cod_ponto = anti_injection($_POST['cod_ponto']);
    $nome = anti_injection($_POST['nome']);
    $logradouro = anti_injection($_POST['logradouro']);
    $numero = $_POST['numero'] ? anti_injection($_POST['numero']) : 'NULL';
    $comp_logradouro = anti_injection($_POST['comp_logradouro']);
    $bairro = anti_injection($_POST['bairro']);
    $ponto_referencia = anti_injection($_POST['ponto_referencia']);
    $cep = anti_injection($_POST['cep']);
    $cidade = anti_injection($_POST['cidade']);
    $estado = anti_injection($_POST['estado']);
    $telefone = anti_injection($_POST['telefone']);
    $data_nascimento = anti_injection($_POST['data_nascimento']);
    $estado_civil = $_POST['cod_estadocivil'] ? anti_injection($_POST['cod_estadocivil']) : 2;
    $cod_profissao = $_POST['cod_profissao'] ? anti_injection($_POST['cod_profissao']) : 0;
    $sexo = anti_injection($_POST['sexo']);
    $cpf = anti_injection($_POST['cpf']);
    $rg = anti_injection($_POST['rg']);
    $org_exp = anti_injection($_POST['org_exp']);

    if ($data_nascimento and validaDataView($data_nascimento)) {
        $data_nascimento = FormatDataViewBD($data_nascimento);
    } else {
        $data_nascimento = '00-00-0000';
    }

    if ($nome and is_numeric($cod_ponto) and is_numeric($cod_profissao) and is_numeric($estado_civil)) {

        $nome = ConvertMaiusculo($nome);
        $logradouro = ConvertMaiusculo($logradouro);
        $bairro = ConvertMaiusculo($bairro);
        $cidade = ConvertMaiusculo($cidade);
        $estado = ConvertMaiusculo($estado);
        $comp_logradouro = ConvertMaiusculo($comp_logradouro);
        $ponto_referencia = ConvertMaiusculo($ponto_referencia);
        $org_exp = ConvertMaiusculo($org_exp);
        $data_cadastro = date('Y-m-d');

        $sql = "INSERT INTO revendedor (cod_ponto, data_cadastro, nome, logradouro, numero, comp_logradouro, sexo, ";
        $sql .= "bairro, ponto_referencia, cep, cidade, estado, telefone, data_nascimento, estado_civil, cod_profissao, cpf, rg, org_exp) ";
        $sql .= "VALUES (" . $cod_ponto . ", '" . $data_cadastro . "', '" . $nome . "', '" . $logradouro . "', " . $numero . ", '";
        $sql .= $comp_logradouro . "', '" . $sexo . "', '" . $bairro . "', '" . $ponto_referencia . "', '" . $cep . "', '" . $cidade . "', '";
        $sql .= $estado . "', '" . $telefone . "', '" . $data_nascimento . "', " . $estado_civil . ", ";
        $sql .= $cod_profissao . ", '" . $cpf . "', '" . $rg . "', '" . $org_exp . "')";

        mysql_query($sql);

        if (mysql_error()) {
            $msg = "Falha ao cadastrar o revendedor. SQL erro: " . mysql_error();
            $util->setMsgErro($msg);
        } else {
            $msg = "Revendedor cadastrado com sucesso!";
            $util->setMsgSucesso($msg);
        }
    } else {
        $msg = "Faltando dados ou dados inválidos!";
        $util->setMsgErro($msg);
    }
}

if ($util->getMsgSucesso()) {
    $cod_ponto = null;
    $data_cadastro = null;
    $nome = null;
    $logradouro = null;
    $numero = null;
    $comp_logradouro = null;
    $bairro = null;
    $ponto_referencia = null;
    $cep = null;
    $cidade = null;
    $estado = null;
    $telefone = null;
    $data_nascimento = null;
    $estado_civil = null;
    $cod_profissao = null;
    $sexo = null;
    $cpf = null;
    $rg = null;
    $org_exp = null;
}
echo $util->checkMensagem();
?>
<p class="tituloPagina">Cadastro de revendedor</p>
<form name="formAddRevendedor" id="formAddRevendedor" class="formTableless" action="" method="post">
    <input type="hidden" name="acao" value="adicionar">
    <label for="nome">Nome: </label>
    <input type="text" class="obr inputTextoLongo" name="nome" id="nome" value="<?php echo $nome; ?>" maxlength="50">
    <br>
    <label for="logradouro">Endereço: </label>
    <input type="text" name="logradouro" id="logradouro" class="inputTextoLongo" value="<?php echo $logradouro; ?>" maxlength="100">
    <br>
    <label for="numero">Nº.: </label>
    <input type="text" name="numero" id="numero" class="numEndereco" value="<?php echo $numero; ?>" onkeypress="return SomenteNumero(event);">
    <br>
    <label for="comp_logradouro">Complemento: </label>
    <input type="text" name="comp_logradouro" id="comp_logradouro" maxlength="50" class="inputTextoLongo" value="<?php echo $comp_logradouro; ?>">
    <br>
    <label for="ponto_referencia">Ponto Referência: </label>
    <input type="text" name="ponto_referencia" id="ponto_referencia" class="inputTextoLongo" value="<?php echo $ponto_referencia; ?>">
    <br>
    <label for="bairro">Bairro: </label>
    <input type="text" name="bairro" id="bairro" maxlength="50" value="<?php echo $bairro; ?>">
    <br>
    <label for="cidade">Cidade: </label>
    <input type="text" name="cidade" id="cidade" value="<?php echo $cidade; ?>" size="30">
    <br>
    <label for="estado">Estado: </label>
    <?php
    echo $util->montarComboBoxEstado($estado);
    ?>
    <br>
    <label for="cep">CEP: </label>
    <input type="text" name="cep" id="cep" class="cep" maxlength="10" onKeyPress="MascaraCep(this);" value="<?php echo $cep; ?>" 
           onblur="ValidaCep(this);" size="6">
    <br>
    <label for="telefone">Telefone: </label>
    <input type="text" name="telefone" id="telefone" class="telefone" onkeypress="MascaraTelefone(this);" value="<?php echo $telefone; ?>"
           onblur="ValidaTelefone(this);" maxlength="14">
    <br>
    <label for="data_nascimento">Data Nascimento: </label>
    <input type="text" name="data_nascimento" id="data_nascimento" class="data datepicker" onKeyPress="MascaraData(this);" 
           value="<?php echo $data_nascimento; ?>" onBlur="ValidaData(this);" maxlength="10">
    <br>
    <label for="cod_estadocivil">Estado Civil: </label>
    <?php
    $cfgCombo = Array('id' => $estado_civil, 'class' => 'estadoCivil');
    montaCombo('estado_civil', 'cod_estadocivil', 'estado_civil', $cfgCombo);
    ?>
    <br>
    <label for="cod_profissao">Profissão: </label>
    <?php
    $cfgCombo = Array('id' => $cod_profissao, 'class' => 'selectTextoLongo');
    montaCombo('profissao', 'cod_profissao', 'descricao', $cfgCombo);
    ?>
    <br>
    <label for="sexo">Sexo: </label>
    <?php
    echo $util->montarComboBoxSexo($sexo, true);
    ?>
    <br>
    <label for="cpf">CPF: </label>
    <input type="text" name="cpf" id="cpf" class="cpf" onkeypress="MascaraCPF(this);" value="<?php echo $cpf; ?>"
           onblur="ValidarCPF(this);" maxlength="14">
    <br>
    <label for="rg">RG: </label>
    <input type="text" name="rg" id="rg" class="rg" onkeypress="MascaraRG(this);" value="<?php echo $rg; ?>"
           onblur="ValidaRG(this);" maxlength="12">
    <br>
    <label for="org_exp">Orgão Exp.: </label>
    <input type="text" name="org_exp" id="org_exp" value="<?php echo $org_exp; ?>" size="20">
    <br>
    <label for="cod_ponto">Ponto de Venda: </label>
    <?php
    $cfgCombo = Array('id' => $cod_ponto, 'class' => 'obr selectTextoLongo');
    montaCombo('pontos', 'cod_ponto', 'nome_ponto', $cfgCombo);
    ?>
    <div id="botoesAcao" class="botoesAcao">
        <img class="botao" alt="Voltar" title="Voltar" src="<?php echo $util->getRaizSite(); ?>img/icones/voltar.png" onclick="RedirecPagina('index.php');">
        <img class="botao" alt="Limpar" title="Limpar" src="<?php echo $util->getRaizSite(); ?>img/icones/limpar.png" onclick="LiparForm('formAddRevendedor');">
        <img class="botao" alt="Salvar" title="Salvar" src="<?php echo $util->getRaizSite(); ?>img/icones/salvar.png" onclick="SubmitValidaForm('formAddRevendedor');">
    </div>
</form>
<?php
include_once '../../inc/rodape.php';
?>