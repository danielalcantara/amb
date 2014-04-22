<?php
require_once '../../../inc/conn.php';
require_once '../../../inc/funcoes.php';
require_once '../../../inc/security.php';

session_start();

protegePagina();

$dataInicio = null;
$dataFim = null;

if (isset($_GET['idRevendedor'])) {
    $idRevendedor = anti_injection($_GET['idRevendedor']);
    $where = " where IdRevendedor = " . $idRevendedor;

    $sql = "SELECT nome, nome_ponto FROM revendedor AS r ";
    $sql .= "INNER JOIN pontos AS p ON r.cod_ponto = p.cod_ponto ";
    $sql .= "WHERE cod_revendedor = " . $idRevendedor;
    $result = mysql_query($sql);
    $dadosRevendedor = mysql_fetch_array($result);
} else {
    echo '<script type="text/javascript">FecharJanela();</script>';
}

if (isset($_GET['dataInicio'])) {
    $dataInicio = anti_injection($_GET['dataInicio']);
    $dataInicioBD = FormatDataViewBD($dataInicio);
}

if (isset($_GET['dataFim'])) {
    $dataFim = anti_injection($_GET['dataFim']);
    $dataFimBD = FormatDataViewBD($dataFim);
}

if ($dataInicio and $dataFim) {
    $where .= " AND DataPedido BETWEEN '" . $dataInicioBD . "' AND '" . $dataFimBD . "'";
} elseif ($dataInicio) {
    $where .= " AND DataPedido >= '" . $dataInicioBD . "'";
} elseif ($dataFim) {
    $where .= " AND DataPedido <= '" . $dataFimBD . "'";
}

$sql = "SELECT CodPedido, descricao, DataPedido, DataEntrega, TotalVendas - Desconto AS valor, Situacao ";
$sql .= "FROM pedido AS p ";
$sql .= "INNER JOIN catalogos AS c ON p.IdCatalogo = c.cod_catalogo ";
$sql .= $where;
$sql .= " ORDER BY DataPedido";
$result = mysql_query($sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Ralatório de pedidos por data</title>
        <link type="text/css" rel="stylesheet" href="<?php echo pegarRaizSite(); ?>css/relatorios.css">
    </head>
    <body>
        <table class="tabRelatorio">
            <tr>
                <td id="tdLogo" rowspan="4">
                    <img src="<?php echo pegarRaizSite(); ?>img/topo/logo.png">
                </td>
                <td class="tdTitulo" colspan="4">
                    Relatório de catálogo por revendedor
                </td>
            </tr>
            <tr class="trCabDados">
                <td style="width: 80px;">
                    Revendedor:
                </td>
                <td style="width: 250px;">
                    <?php echo $dadosRevendedor['nome']; ?>
                </td>
                <td style="width: 112px;">
                    Ponto de Venda:
                </td>
                <td>
                    <?php echo $dadosRevendedor['nome_ponto']; ?>
                </td>
            </tr>
            <tr class="trCabDados">
                <td class="tdTitulo2" colspan="4">
                    Período
                </td>
            </tr>
            <tr class="trCabDados">
                <td>
                    Data Início:
                </td>
                <td>
                    <?php echo $dataInicio; ?>
                </td>
                <td>
                    Data Fim:
                </td>
                <td>
                    <?php echo $dataFim; ?>
                </td>
            </tr>
        </table>
    <tbody>
        <?php
        if (mysql_num_rows($result) > 0) {
            ?>

        <table class="tabRelatorio">
            <thead>
                <tr>
                    <th style="width: 15%;">
                        N.º Pedido
                    </th>
                    <th>
                        Catálogo
                    </th>
                    <th style="width: 15%;">
                        Data Pedido
                    </th>
                    <th style="width: 15%;">
                        Data Entrega
                    </th>
                    <th style="width: 13%;">
                        Valor
                    </th>
                    <th style="width: 13%;">
                        Situação
                    </th>
                </tr>
            </thead>
            <?php
            $qtdTotal = 0;
            $qtdRetirado = 0;
            $qtdDevolvido = 0;
            $qtdExtraviado = 0;
            $totalRetirado = 0;
            $totalExtraviado = 0;
            $totalDevolvido = 0;
            $valorTotal = 0;
            $totalRetirado = 0;

            while ($linha = mysql_fetch_array($result)) {

                $qtdTotal += 1;
                $valorTotal += $linha['valor'];
                $valor = 'R$ ' . FormataRealParaMoeda($linha['valor']);

                echo '<tr>';
                echo '<td align="center">' . $linha['CodPedido'] . '</td>';
                echo '<td align="center">' . $linha['descricao'] . '</td>';
                echo '<td align="center">' . FormatDataBDView($linha['DataPedido']) . '</td>';
                echo '<td align="center">' . FormatDataBDView($linha['DataEntrega']) . '</td>';
                echo '<td align="center">' . $valor . '</td>';

                switch ($linha['Situacao']) {
                    case 'finalizado':
                        $situacao = 'Finalizado';
                        $totalRetirado += $linha['valor'];
                        $qtdRetirado += 1;
                        break;
                    case 'devolvido':
                        $situacao = 'Devolvido';
                        $qtdDevolvido += 1;
                        $totalDevolvido += $linha['valor'];
                        break;
                    case 'extraviado':
                        $situacao = 'Extraviado';
                        $qtdExtraviado += 1;
                        $totalExtraviado += $linha['valor'];
                        break;
                    case 'aberto':
                        $situacao = 'Aberto';
                        break;
                    default :
                        $situacao = 'Indefinido';
                        break;
                }
                echo '<td align="center">' . $situacao . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            ?>
            <br>
            <table class="tbTotais">
                <tr>
                    <td class="tdTotalTitulo">
                        Total:
                    </td>
                    <td>
                        R$ <?php echo FormataRealParaMoeda($valorTotal); ?>
                    </td>
                    <td class="tdTotalTitulo">
                        Quant. Total:
                    </td>
                    <td>
                        <?php echo $qtdTotal; ?>
                    </td>
                </tr>
                <tr>
                    <td class="tdTotalTitulo">
                        Total Retirado:
                    </td>
                    <td>
                        R$ <?php echo FormataRealParaMoeda($totalRetirado); ?>
                    </td>
                    <td class="tdTotalTitulo">
                        Quant. Retirado:
                    </td>
                    <td>
                        <?php echo $qtdRetirado; ?>
                    </td>
                </tr>
                <tr>
                    <td class="tdTotalTitulo">
                        Total Devolvido:
                    </td>
                    <td>
                        R$ <?php echo FormataRealParaMoeda($totalDevolvido); ?>
                    </td>
                    <td class="tdTotalTitulo">
                        Quant. Devolvido:
                    </td>
                    <td>
                        <?php echo $qtdDevolvido; ?>
                    </td>
                </tr>
                <tr>
                    <td class="tdTotalTitulo">
                        Total Extraviado:
                    </td>
                    <td>
                        R$ <?php echo FormataRealParaMoeda($totalExtraviado); ?>
                    </td>
                    <td class="tdTotalTitulo">
                        Quant. Extraviado:
                    </td>
                    <td>
                        <?php echo $qtdExtraviado; ?>
                    </td>
                </tr>
            </table>
            <?php
        } else {
            echo '<p class="msgErro">';
            echo 'Não há registros para esse revendedor ou período.';
            echo '</p>';
        }
        ?>
    </body>
</html>
<?php
mysql_close($conn);
?>
