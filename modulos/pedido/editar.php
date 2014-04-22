<?php
include_once '../../inc/cabecalho.php';
require_once '../../class/Model/Pedido.php';
require_once '../../class/Facade/PedidoFacade.php';
require_once '../../class/Model/PedidoParcela.php';

// Adicionando css e javascript específicos da página
$header->addStyle(pegarRaizSite() . 'css/janelaModal.css', false);
$header->addStyle('css/styles.css', false);
$header->addScript(pegarRaizSite() . 'js/janelaModal.js', true);
$header->addScript('js/funcoes.js', true);

$header->write();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

$idPedido = filterPost('id');

$facade = new PedidoFacade();

if (!$idPedido) {
    RedirecPagina('index.php');
}

$CodPedido = filterPost('codPedido');
$IdCatalogo = filterPost('cod_catalogo');
$IdRevendedor = filterPost('revendedor');
$idPonto = filterPost('ponto');
$DataPedido = filterPost('dataPedido');
$DataEntrega = filterPost('dataEntrega');
$TotalVendas = filterPost('totalVendas');
$Desconto = filterPost('desconto');
$Situacao = filterPost('situacao');
$DataSituacao = filterPost('dataSituacao');
$parcelasValor = filterPost('valorParcela', true);
$parcelasData = filterPost('dataParcela', true);

if (filterPost('acao') == 'editar') {
    $TotalVendasBd = FormataMoedaParaReal(filterPost('totalVendas'));
    $DescontoBd = FormataMoedaParaReal(filterPost('desconto'));

    if (is_numeric($IdCatalogo) and is_numeric($IdRevendedor) and is_double($TotalVendasBd) and
            $Situacao and validaDataView($DataPedido) and validaDataView($DataEntrega) and
            (!$Desconto or is_double($DescontoBd)) and (!$DataSituacao or validaDataView($DataSituacao))) {

        $CodPedido = is_numeric($CodPedido) ? $CodPedido : mt_rand(5, 9999) . date('dmYHis');
        $DataPedidoBd = FormatDataViewBD($DataPedido);
        $DataEntregaBd = FormatDataViewBD($DataEntrega);
        $DataSituacaoBd = FormatDataViewBD($DataSituacao) ? FormatDataViewBD($DataSituacao) : null;

        $pedido = new Pedido();

        $pedido->setId($idPedido);
        $pedido->setCodPedido($CodPedido);
        $pedido->setIdCatalogo($IdCatalogo);
        $pedido->setIdRevendedor($IdRevendedor);
        $pedido->setTotalVendas($TotalVendasBd);
        $pedido->setDataPedido($DataPedidoBd);
        $pedido->setDataEntrega($DataEntregaBd);
        $pedido->setDesconto($DescontoBd);
        $pedido->setSituacao($Situacao);
        $pedido->setDataSituacao($DataSituacaoBd);

        if (is_array($parcelasValor)) {
            $numParcelas = count($parcelasValor);
            for ($cont = 0; $cont < $numParcelas; $cont++) {
                $valorParcela = FormataMoedaParaReal($parcelasValor[$cont]);
                $dataParcela = FormatDataViewBD($parcelasData[$cont]);
                if (is_double($valorParcela) and validarDataBD($dataParcela)) {
                    $parcela = new PedidoParcela();
                    $parcela->setValor($valorParcela);
                    $parcela->setData($dataParcela);
                    $pedido->setParcelas($parcela);
                }
            }
        }

        $returnCadastro = $facade->CadastrarPedido($pedido);

        if (!$returnCadastro) {
            $msg = "Falha ao atualizar o pedido. Favor verificar campos do formulário.";
            $util->setMsgErro($msg);
        } else {
            $msg = "Pedido atualizado com sucesso!";
            $util->setMsgSucesso($msg);
        }
    } else {
        $msg = "Faltando dados ou dados inválidos";
        $util->setMsgErro($msg);
    }
}

if (!$util->getMsgErro()) {
    $pedido = $facade->buscarPedido($idPedido);
    list($idPedido, $CodPedido, $IdCatalogo, $IdRevendedor, $TotalVendas, $Desconto, , $DataPedido, $DataEntrega, 
            ,$Situacao, $DataSituacao, $cod_ponto) = $pedido;
}
echo $util->checkMensagem();
?>
<div>
    <p class="tituloPagina">Cadastro de pedido</p>
    <form name="formEditPedido" id="formEditPedido" class="formTableless" onsubmit="return validaForm();" action="" method="post">
        <div id="divEsquerda">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="id" value="<?php echo $idPedido; ?>">
            <label for="codPedido">Cod. Pedido Fab.:</label>
            <input type="text" name="codPedido" id="numero" value="<?php echo $CodPedido; ?>" size="15" 
                   onkeypress="return SomenteNumero(event);">
            <br>
            <label for="idCatalogo">Catálogo:</label>
            <?php
            montaCombo('catalogos', 'cod_catalogo', 'numero_catalogo,descricao', Array('class' => 'obr', 'id' => $IdCatalogo,
                'order' => 'DESC', 'optionComplemento' => 'Nº.:'));
            ?>
            <br>
            <label for="idCatalogo">Ponto:</label>
            <?php
            $dadosComboPonto = $facade->listarComboPonto();
            montaComboNovo($dadosComboPonto, Array('idOption' => $cod_ponto, 'id' => 'ponto',
                'obrigatorio' => true, 'name' => 'ponto'));
            ?>
            <br>
            <label for="idRevendedor">Revendedor:</label>
            <div id="comboRevendedor">
                <?php
                $dadosComboRev = $facade->listarComboRevendedor($cod_ponto);
                montaComboNovo($dadosComboRev, Array('idOption' => $IdRevendedor, 'id' => 'revendedor',
                    'obrigatorio' => true, 'name' => 'revendedor'));
                ?>
            </div>
            <br>
            <label for="totalVendas">Data Pedido:</label>
            <input type="text" name="dataPedido" id="dataPedido" class="data obr datepicker" onKeyPress="MascaraData(this);" 
                   onBlur="ValidaData(this);" maxlength="10" value="<?php echo FormatDataBDView($DataPedido); ?>">
            <br>
            <label for="totalVendas">Data Entrega:</label>
            <input type="text" name="dataEntrega" id="dataEntrega" class="data obr datepicker" onKeyPress="MascaraData(this);" 
                   onBlur="ValidaData(this);" maxlength="10" value="<?php echo FormatDataBDView($DataEntrega); ?>">
            <br>
            <label for="totalVendas">Total Venda:</label>
            <input type="text" name="totalVendas" id="totalVendas"  value="<?php echo FormataRealParaMoeda($TotalVendas); ?>" 
                   onKeyPress="return MascaraMoeda(this, '.', ',', event);" class="campoMoeda obr">
            <a href="#divAdParcelas" rel="modal"><input type="button" onclick="return false;" value="Adicionar parcelas"></a>
            <br>
            <label for="desconto">desconto:</label>
            <input type="text" name="desconto" id="desconto" value="<?php echo FormataRealParaMoeda($Desconto); ?>" 
                   onKeyPress="return MascaraMoeda(this, '.', ',', event);" class="campoMoeda obr">
            <br>
            <label for="situacao">Situação:</label>
            <select name="situacao" id="situacao" class="comboSituacao">
                <option value="aberto" <?php echo $Situacao == 'aberto' ? 'selected' : ''; ?>>Aberto</option>
                <option value="devolvido" <?php echo $Situacao == 'devolvido' ? 'selected' : ''; ?>>Devolvido</option>
                <option value="extraviado" <?php echo $Situacao == 'extraviado' ? 'selected' : ''; ?>>Extraviado</option>
                <option value="finalizado" <?php echo $Situacao == 'finalizado' ? 'selected' : ''; ?>>Finalizado</option>
            </select>
            &nbsp;
            <span id="campoDataSituacao" <?php echo $Situacao == "aberto" ? 'class="campoHidden"' : ''; ?>>
                <span class="tituloCampo">Data:</span>
                <input type="text" name="dataSituacao" id="dataSituacao" value="<?php echo FormatDataBDView($DataSituacao); ?>"
                       class="data <?php echo $Situacao != "aberto" ? 'obr"' : ''; ?> 
                       datepicker" onKeyPress="MascaraData(this);" onBlur="ValidaData(this);" maxlength="10">
            </span>
        </div>
        <div id="divDireita">
            <div id="divInfRevendedor">
                <p class="tituloInfRevendedor">
                    Informações revendedor:
                </p>
                <div id="infRevendedor">
                    <?php
                    $dadosRevendedor = $facade->buscarInfoRevendedor($IdRevendedor);
                    imprimeDadosRevendedor($dadosRevendedor);
                    ?>
                </div>
            </div>
        </div>
        <div id="botoesAcao" class="botoesAcao">
            <img class="botao" alt="Voltar" title="Voltar" src="<?php echo $util->getRaizSite(); ?>img/icones/voltar.png"
                 onclick="RedirecPagina('index.php');">
            <img class="botao" alt="Limpar" title="Limpar" src="<?php echo $util->getRaizSite(); ?>img/icones/limpar.png"
                 onclick="LiparForm('formEditPedido');">
            <input type="image" class="botao" alt="Salvar" title="Salvar" src="<?php echo $util->getRaizSite(); ?>img/icones/salvar.png">
        </div>
        <?php
        include_once 'parcelas.php';
        ?>
    </form>
</div>
<?php
include_once '../../inc/rodape.php';
