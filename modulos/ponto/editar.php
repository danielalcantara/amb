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

$nome = null;
$endereco = null;
$complemento = null;
$bairro = null;
$cidade = null;
$estado = null;
$cep = null;
$telefone = null;
$cod_grupo = null;

if (isset($_POST['acao']) and is_numeric($id)) {
    if ($_POST['acao'] == 'editar') {
        $nome = anti_injection($_POST['nome']);
        $endereco = anti_injection($_POST['endereco']);
        $complemento = anti_injection($_POST['complemento']);
        $bairro = anti_injection($_POST['bairro']);
        $cidade = anti_injection($_POST['cidade']);
        $estado = anti_injection($_POST['estado']);
        $cep = anti_injection($_POST['cep']);
        $telefone = anti_injection($_POST['telefone']);
        $cod_grupo = anti_injection($_POST['cod_grupo']);

        if ($nome and $endereco and $bairro and $cidade and $estado and is_numeric($cod_grupo)) {

            $nome = ConvertMaiusculo($nome);
            $endereco = ConvertMaiusculo($endereco);
            $bairro = ConvertMaiusculo($bairro);
            $cidade = ConvertMaiusculo($cidade);
            $estado = ConvertMaiusculo($estado);
            $complemento = ConvertMaiusculo($complemento);

            $sql = "UPDATE pontos SET cod_grupo = " . $cod_grupo . ", ";
            $sql .= "nome_ponto = '" . $nome . "', endereco = '" . $endereco;
            $sql .= "', comp_endereco = '" . $complemento . "', bairro_ponto = '" . $bairro . "', cep = '" . $cep . "'";
            $sql .= ", cidade_ponto = '" . $cidade . "', estado = '" . $estado . "', telefone = '" . $telefone;
            $sql .= "' WHERE cod_ponto = " . $id;

            mysql_query($sql);

            if (mysql_error()) {
                $msg = "Falha ao atualizar o ponto. SQL erro: " . mysql_error();
                $util->setMsgErro($msg);
            } else {
                $msg = "Ponto atualizado com sucesso!";
                $util->setMsgSucesso($msg);
            }
        } else {
            $msg = "Faltando dados ou dados inválidos";
            $util->setMsgErro($msg);
        }
    }
}

if (!$util->getMsgErro()) {
    $sql = "SELECT cod_grupo, nome_ponto, endereco, comp_endereco, bairro_ponto ";
    $sql .= ", cep, cidade_ponto, estado, telefone FROM pontos ";
    $sql .= "WHERE cod_ponto = " . $id;
    $rs = mysql_query($sql);
    $linha = mysql_fetch_array($rs);
    list($cod_grupo, $nome, $endereco, $complemento, $bairro, $cep, $cidade, $estado, $telefone) = $linha;
}
echo $util->checkMensagem();
?>
<p class="tituloPagina">Cadastro de ponto</p>
<div>
    <form name="formEditPonto" id="formEditPonto" class="formTableless" action="" method="post">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="pagina" value="<?php echo $pagina; ?>">
        <input type="hidden" name="filtro" value="<?php echo $filtro; ?>">
        <label for="nome">Nome: </label>
        <input type="text" class="obr inputTextoLongo" name="nome" id="nome" value="<?php echo $nome; ?>" size="40">
        <br>
        <label for="endereco">Endereço: </label>
        <input type="text" class="obr inputTextoLongo" name="endereco" id="endereco" value="<?php echo $endereco; ?>" size="60">
        <br>
        <label for="complemento">Complemento: </label>
        <input type="text" name="complemento" id="complemento" value="<?php echo $complemento; ?>" size="60">
        <br>
        <label for="bairro">Bairro: </label>
        <input type="text" class="obr" name="bairro" id="bairro" value="<?php echo $bairro; ?>" size="30">
        <br>
        <label for="cidade">Cidade: </label>
        <input type="text" class="obr" name="cidade" id="cidade" value="<?php echo $cidade; ?>" size="30">
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
               onblur="ValidaTelefone(this);" maxlength="14" size="10">
        <br>
        <label for="cod_grupo">Grupo: </label>
        <?php
        echo $util->montarComboBox('cod_grupo', 'nome_grupo', 'grupos_pontos', $cod_grupo);
        ?>
        <div id="botoesAcao" class="botoesAcao">
            <img class="botao" src="<?php echo $util->getRaizSite(); ?>img/icones/voltar.png" onclick="voltarParaIndex('formEditPonto');">
            <img class="botao" src="<?php echo $util->getRaizSite(); ?>img/icones/limpar.png" onclick="LiparForm('formEditPonto');">
            <img class="botao" src="<?php echo $util->getRaizSite(); ?>img/icones/salvar.png" onclick="SubmitValidaForm('formEditPonto');">
        </div>
    </form>
</div>
<?php
include_once '../../inc/rodape.php';
?>