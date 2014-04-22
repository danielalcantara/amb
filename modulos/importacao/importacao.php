<?php
include_once '../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 2;

$util->getTopoPagina($cfg);

$idCatalogo = $_GET['idCatalogo'];

$redirec = false;

if (is_numeric($idCatalogo)) {
    $sql = "select identificacao, descricao, cod_fabrica from catalogos";
    $sql .= " where cod_catalogo = " . $idCatalogo;
    $catalogo = mysql_fetch_array(mysql_query($sql));
    $sql = "SELECT COUNT(*) FROM LayoutImport WHERE IdFabrica = " . $catalogo['cod_fabrica'];
    $rs = mysql_query($sql);
    if (mysql_result($rs, 0) < 1) {
        $redirec = true;
    }
} else {
    $redirec = true;
}

if ($redirec) {
    RedirecPagina('index.php');
}
?>

<div id="importacao" class="divConteudoPagina">
    <p class="tituloPagina">Importação de Pedidos</p>
    <br>
    <div class="divForm">
        <form id="formImportacao" name="formImportacao" action="importar.php" 
              method="post" enctype="multipart/form-data" onsubmit="return ValidaFormImportacao();">
            <input type="hidden" name="idCatalogo" value="<?php echo $idCatalogo; ?>">
            <table class="tableForm">
                <tbody>
                    <tr>
                        <td>
                            Catálogo:
                        </td>
                        <td>
                            <?php echo $catalogo['identificacao']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Descrição:
                        </td>
                        <td>
                            <?php echo $catalogo['descricao']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Data Pedidos:
                        </td>
                        <td>
                            <input type="text" name="dataPedidos" id="dataPedidos" class="data obr datepicker" onKeyPress="MascaraData(this);" 
                                   onBlur="ValidaData(this);" maxlength="10">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Data Entrega:
                        </td>
                        <td>
                            <input type="text" name="dataEntrega" id="dataEntrega" class="data obr datepicker" onKeyPress="MascaraData(this);" 
                                   onBlur="ValidaData(this);" maxlength="10">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Arquivo:
                        </td>
                        <td>
                            <input type="file" name="arquivoImport" id="arquivoImport"> &nbsp;
                            <span class="msgObservacao">(Somente formato txt)</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div style="clear: both;"></div>
            <div style="margin-top: 10px;">
                <input type="button" value="Voltar" onclick="VoltarPagina();">
                <input type="submit" value="Importar">
            </div>
        </form>
    </div>
</div>

<?php
include_once '../../inc/rodape.php';
?>
