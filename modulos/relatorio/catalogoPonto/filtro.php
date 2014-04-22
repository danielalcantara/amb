<?php
include_once '../../../inc/cabecalho.php';
require_once pegarRaizSite() . 'class/Facade/RelatorioCatalogoFacade.php';

$facade = new RelatorioCatalogoFacade();

$header->addScript('js/funcoes.js', true);

$header->write();

$cfg['menuCorrente'] = 3;

$util->getTopoPagina($cfg);

$idCatalogo = $_GET['idCatalogo'];

if(!is_numeric($idCatalogo)) {
    RedirecPagina('index.php');
}

$sql = "SELECT identificacao, descricao FROM catalogos WHERE cod_catalogo = " . $idCatalogo;

$result = mysql_query($sql);
$catalogo = mysql_fetch_array($result);
?>
<p class="tituloPagina">Relatório catálogo por ponto</p>
<div>
    <form name="filtroRelatorio" id="filtroRelatorio" class="formTableless" method="post" action="" onsubmit="gerarRelatorio();">
        <input type="hidden" name="idCatalogo" id="idCatalogo" value="<?php echo $idCatalogo; ?>">
        <p class="label">Dados para gerar relatório:</p>
        <div style="float: left;">
            <label>Catálogo:</label>
            <input type="text" readonly="readonly" value="<?php echo $catalogo['identificacao']; ?>">
            <br>
            <label>Descrição:</label>
            <input type="text" readonly="readonly" value="<?php echo $catalogo['descricao']; ?>">
            <br>
            <label>Ponto de Venda:</label>
            <?php
            $dadosComboPonto = $facade->listarComboPontoPorCatalogo($idCatalogo);
            montaComboNovo($dadosComboPonto, Array('id' => 'ponto', 'name' => 'ponto', 'comboFiltro' => true));
            ?>
            <br>
            <label for="dataPedidos">Data Pedidos:</label>
            <input type="text" name="dataPedidos" id="dataPedidos" class="data datepicker" onKeyPress="MascaraData(this);" 
                       onBlur="ValidaData(this);" maxlength="10">
            <br>
            <label for="dataEntrega">Data Entrega:</label>
            <input type="text" name="dataEntrega" id="dataEntrega" class="data datepicker" onKeyPress="MascaraData(this);" 
                       onBlur="ValidaData(this);" maxlength="10">
            <br>
            <div class="divClear"></div>
            <input type="button" value="Voltar" onclick="VoltarPagina();">
            <input type="submit" value="Gerar Relatório">
    </form>
</div>
<?php
include_once '../../../inc/rodape.php';
?>
