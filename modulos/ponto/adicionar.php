<?php
include_once '../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

$campos = null;

if (isset($_POST['acao']) == 'adicionar') {
    $campos["nome"] = anti_injection($_POST['nome']);
    $campos["endereco"] = anti_injection($_POST['endereco']);
    $campos["complemento"] = anti_injection($_POST['complemento']);
    $campos["bairro"] = anti_injection($_POST['bairro']);
    $campos["cidade"] = anti_injection($_POST['cidade']);
    $campos["estado"] = anti_injection($_POST['estado']);
    $campos["cep"] = anti_injection($_POST['cep']);
    $campos["telefone"] = anti_injection($_POST['telefone']);
    $campos["cod_grupo"] = anti_injection($_POST['cod_grupo']);

    if ($campos["nome"] and $campos["endereco"] and $campos["bairro"] and $campos["cidade"] and $campos["estado"] and is_numeric($campos["cod_grupo"])) {

        $nome = ConvertMaiusculo($campos["nome"]);
        $endereco = ConvertMaiusculo($campos["endereco"]);
        $bairro = ConvertMaiusculo($campos["bairro"]);
        $cidade = ConvertMaiusculo($campos["cidade"]);
        $estado = ConvertMaiusculo($campos["estado"]);
        $complemento = ConvertMaiusculo($campos["complemento"]);

        $sql = "INSERT INTO pontos (cod_grupo, nome_ponto, endereco, comp_endereco,";
        $sql .= "bairro_ponto, cep, cidade_ponto, estado, telefone) ";
        $sql .= "VALUES (" . $campos["cod_grupo"] . ", '" . $nome . "', '" . $endereco . "', '";
        $sql .= $complemento . "', '" . $bairro . "', '" . $campos["cep"] . "', '" . $cidade . "', '" . $estado . "', '" . $campos["telefone"] . "')";
        mysql_query($sql);

        if (mysql_error()) {
            $msg = "Falha ao cadastrar o ponto. SQL erro: " . mysql_error();
            $util->setMsgErro($msg);
        } else {
            $msg = "Ponto cadastrado com sucesso!";
            $util->setMsgSucesso($msg);
        }
    } else {
        $msg = "Faltando dados ou dados inválidos";
        $util->setMsgErro($msg);
    }
}

if ($util->getMsgSucesso()) {
    $campos = null;
}
echo $util->checkMensagem();
?>
<p class="tituloPagina">Cadastro de ponto</p>
<div>
    <form name="formAddPonto" id="formAddPonto" class="formTableless" action="" method="post">
        <input type="hidden" name="acao" value="adicionar">
        <label for="nome">Nome: </label>
        <input type="text" class="obr inputTextoLongo" name="nome" id="nome" value="<?php echo $campos["nome"]; ?>" size="40">
        <br>
        <label for="endereco">Endereço: </label>
        <input type="text" class="obr inputTextoLongo" name="endereco" id="endereco" value="<?php echo $campos["endereco"]; ?>" size="60">
        <br>
        <label for="complemento">Complemento: </label>
        <input type="text" name="complemento" id="complemento" value="<?php echo $campos["complemento"]; ?>" size="60">
        <br>
        <label for="bairro">Bairro: </label>
        <input type="text" class="obr" name="bairro" id="bairro" value="<?php echo $campos["bairro"]; ?>" size="30">
        <br>
        <label for="cidade">Cidade: </label>
        <input type="text" class="obr" name="cidade" id="cidade" value="<?php echo $campos["cidade"]; ?>" size="30">
        <br>
        <label for="estado">Estado: </label>
        <?php
        echo $util->montarComboBoxEstado($campos["estado"]);
        ?>
        <br>
        <label for="cep">CEP: </label>
        <input type="text" name="cep" id="cep" class="cep" maxlength="10" onKeyPress="MascaraCep(this);" value="<?php echo $campos["cep"]; ?>" 
               onblur="ValidaCep(this);" size="6">
        <br>
        <label for="telefone">Telefone: </label>
        <input type="text" name="telefone" id="telefone" class="telefone" onkeypress="MascaraTelefone(this);" value="<?php echo $campos["telefone"]; ?>"
               onblur="ValidaTelefone(this);" maxlength="14" size="10">
        <br>
        <label for="cod_grupo">Grupo: </label>
        <?php
        echo $util->montarComboBox('cod_grupo', 'nome_grupo', 'grupos_pontos', $campos["cod_grupo"]);
        ?>
        <div id="botoesAcao" class="botoesAcao">
            <img class="botao" alt="Voltar" title="Voltar" src="<?php echo $util->getRaizSite(); ?>img/icones/voltar.png"
                 onclick="RedirecPagina('index.php');">
            <img class="botao" alt="Limpar" title="Limpar" src="<?php echo $util->getRaizSite(); ?>img/icones/limpar.png"
                 onclick="LiparForm('formAddPonto');">
            <img class="botao" alt="Salvar" title="Salvar" src="<?php echo $util->getRaizSite(); ?>img/icones/salvar.png" onclick="SubmitValidaForm('formAddPonto');">
        </div>
    </form>
</div>
<?php
include_once '../../inc/rodape.php';
?>