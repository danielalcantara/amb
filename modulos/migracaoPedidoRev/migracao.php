<?php
include_once '../../inc/cabecalho.php';
require_once pegarRaizSite() . 'class/Facade/MigracaoPedidoRevFacade.php';

$facade = new MigracaoPedidoRevFacade();

$header->addScript('js/funcoes.js', true);
$header->addStyle('css/styles.css', false);

$header->write();

$cfg['menuCorrente'] = 3;

$util->getTopoPagina($cfg);

$idRevendedorOrigem = filterGet('idRevendedorOrigem');
$idPonto = filterPost('ponto');
$idRevendedorDestino = filterPost('idRevendedorDestino');
$msgErro = filterGet('msgErro');
$msgSucesso = filterGet('msgSucesso');

if (!is_numeric($idRevendedorOrigem)) {
    RedirecPagina('index.php');
}

$revendedor = $facade->burcarRevendedor($idRevendedorOrigem);
$ponto = $facade->buscarPonto($revendedor['cod_ponto']);

echo $msgErro ? imprimeMensagem($msgErro, true) : imprimeMensagem($msgSucesso);
?>
<p class="tituloPagina">Migração Pedidos por Revendedor</p>
<div>
    <form name="filtroRelatorio" id="filtroRelatorio" class="formTableless" method="post" action="migrar.php">
        <div id="divEsquerda">
            <input type="hidden" name="idRevendedorOrigem" id="idRevendedorOrigem" value="<?php echo $idRevendedorOrigem; ?>">
            <p class="label">Dados Revendedor Origem:</p>
            <label>Nome:</label>
            <input type="text" id="nomeRevDestino" readonly="readonly" value="<?php echo $revendedor['nome']; ?>">
            <br>
            <label>CPF:</label>
            <input type="text" readonly="readonly" value="<?php echo $revendedor['cpf']; ?>">
            <br>
            <label>Telefone:</label>
            <input type="text" readonly="readonly" value="<?php echo $revendedor['telefone']; ?>">
            <br>
            <label>Data de Nascimento:</label>
            <input type="text" readonly="readonly" value="<?php echo FormatDataBDView($revendedor['data_nascimento']); ?>">
            <br>
            <label>Ponto:</label>
            <input type="text" readonly="readonly" value="<?php echo $ponto['nome_ponto']; ?>">
            <br>
            <br>
            <p class="label">Revendedor Destino:</p>
            <label>Ponto:</label>
            <?php
            $dadosComboPonto = $facade->listarComboPonto();
            montaComboNovo($dadosComboPonto, Array('id' => 'ponto', 'name' => 'ponto'));
            ?>
            <br>
            <label>Revendedor:</label>
            <div id="comboRevendedor">
                <?php
                $dadosComboRevendedor = $facade->listarComboRevendedor($idPonto);
                montaComboNovo($dadosComboRevendedor, Array('idOption' => $idRevendedorDestino, 'name' => 'revendedor', 
                    'id' => 'revendedor'))
                ?>
            </div>
        </div>
        <div id="divDireita">
            <div id="divInfRevendedor">
                <p class="tituloInfRevendedor">
                    Informações revendedor destino:
                </p>
                <div id="infRevendedor">
                    <?php
                    $dadosRevendedor = $facade->buscarInfoRevendedor($idRevendedorDestino);
                    imprimeDadosRevendedor($dadosRevendedor);
                    ?>
                </div>
            </div>
        </div>
        <div id="botoesAcao" class="botoesAcao">
            <input type="button" value="Voltar" onclick="VoltarPagina();">
            <input type="submit" value="Migrar Pedidos">
        </div>
    </form>
</div>
<?php
include_once '../../inc/rodape.php';
?>
