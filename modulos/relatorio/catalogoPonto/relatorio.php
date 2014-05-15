<?php

require_once '../../../inc/conn.php';
require_once '../../../inc/funcoes.php';
require_once '../../../inc/security.php';
require_once '../../../class/Facade/RelatorioFacade.php';

ini_set('max_execution_time', 300);

// Incluindo a classe mpdf
require_once pegarRaizSite() . 'class/Util/mpdf/ImpressorPDF.php';

session_start();

protegePagina();

$facade = new FacadeRelatorio();

$impressorPdf = new ImpressorPDF('', 'A4-L', 0, '', 9, 9, 40, 15, 8, 8, 'L');
$impressorPdf->displayDefaultOrientation = true;

$impressorPdf->SetDisplayMode('fullpage');

// muda o charset para aceitar caracteres acentuados iso 8859-1 utilizados por mim no banco de dados e na geracao do conteudo PHP com HTML
$impressorPdf->allow_charset_conversion = true;
$impressorPdf->charset_in = 'UTF-8';

// carrega uma folha de estilo
$stylesheet = file_get_contents('./css/styles.css');
$impressorPdf->WriteHTML($stylesheet, 1);

$impressorPdf->SetFooter('{DATE j/m/Y - H:i}|{PAGENO}/{nb}|AMB / Distribuidora');

$idPonto = anti_injection($_GET['ponto']);
$idCatalogo = anti_injection($_GET['catalogo']);
$dataPedido = isset($_GET['dataPedidos']) ? anti_injection($_GET['dataPedidos']) : '';
$dataEntrega = isset($_GET['dataEntrega']) ? anti_injection($_GET['dataEntrega']) : '';
$valorTotal = 0;
$totalRetirado = 0;

if (is_numeric($idCatalogo)) {

    $dataPedidoBd = FormatDataViewBD($dataPedido);
    $dataEntregaBd = FormatDataViewBD($dataEntrega);

    $catalogo = $facade->buscarCatalogo($idCatalogo);

    $pedidos = $facade->listarPedidos($idCatalogo, $idPonto, $dataPedidoBd, $dataEntregaBd);

    $numPedidos = count($pedidos);

    if ($numPedidos > 0) {
        $cont = 0;
        while ($cont < $numPedidos) {
            $cabecalho = '<table class="tabRelatorio" border="1">
            <tr>
                <td id="tdLogo" rowspan="4">
                    <img src="' . pegarRaizSite() . 'img/topo/logo_relatorio.png">
                </td>
                <td class="tdTitulo" colspan="4">
                    Relat&oacute;rio de cat&aacute;logo por ponto
                </td>
            </tr>
            <tr class="trCabDados">
                <td style="width: 122px;">
                    Cat&aacute;logo:
                </td>
                <td>'
                    . $catalogo['identificacao'] .
                    '</td>
                <td style="width: 112px;">
                    Cod.:
                </td>
                <td>'
                    . $catalogo['numero_catalogo'] .
                    '</td>
            </tr>
            <tr class="trCabDados">
                <td>
                    Descri&ccedil;&atilde;o:
                </td>
                <td>'
                    . $catalogo['descricao'] .
                    '</td>
                <td>
                    Ponto de Venda:
                </td>
                <td>'
                    . $pedidos[$cont]['nome_ponto'] .
                    '</td>
            </tr>
            <tr class="trCabDados">
                <td>
                    Data Pedidos:
                </td>
                <td>'
                    . $dataPedido .
                    '</td>
                <td>
                    Data Entrega:
                </td>
                <td>'
                    . $dataEntrega .
                    '</td>
            </tr>
        </table>';
            
            $impressorPdf->SetHTMLHeader(utf8_encode($cabecalho), "BLANK", true);

            $dados = '<table class="tabRelatorio" border="1">
            <thead>
                <tr>
                    <th style="width: 15%;">
                        N.&#186; Pedido
                    </th>
                    <th>
                        Consultor(a)
                    </th>
                    <th style="width: 15%;">
                        Telefone
                    </th>
                    <th style="width: 13%;">
                        Valor
                    </th>
                    <th style="width: 13%;">
                        Situa&ccedil;&atilde;o
                    </th>
                </tr>
            </thead>
            <tbody>';

            $qtdTotal = 0;
            $qtdRetirado = 0;
            $qtdDevolvido = 0;
            $qtdExtraviado = 0;
            $totalRetirado = 0;
            $totalExtraviado = 0;
            $totalDevolvido = 0;
            $idPontoAnt = $pedidos[$cont]['cod_ponto'];

            while (($pedidos[$cont]['cod_ponto'] == $idPontoAnt)) {

                $qtdTotal += 1;
                $valorTotal += $pedidos[$cont]['valor'];
                $valor = 'R$ ' . FormataRealParaMoeda($pedidos[$cont]['valor']);

                $dados .= '<tr class="trDados">';
                $dados .= '<td align="center">' . $pedidos[$cont]['CodPedido'] . '</td>';
                $dados .= '<td align="left">' . $pedidos[$cont]['nome'] . '</td>';
                $dados .= '<td align="center">' . $pedidos[$cont]['telefone'] . '</td>';
                $dados .= '<td align="center">' . $valor . '</td>';

                switch ($pedidos[$cont]['Situacao']) {
                    case 'finalizado':
                        $situacao = 'Finalizado';
                        $totalRetirado += $pedidos[$cont]['valor'];
                        $qtdRetirado += 1;
                        break;
                    case 'devolvido':
                        $situacao = 'Devolvido';
                        $qtdDevolvido += 1;
                        $totalDevolvido += $pedidos[$cont]['valor'];
                        break;
                    case 'extraviado':
                        $situacao = 'Extraviado';
                        $qtdExtraviado += 1;
                        $totalExtraviado += $pedidos[$cont]['valor'];
                        break;
                    case 'aberto':
                        $situacao = 'Aberto';
                        break;
                    default :
                        $situacao = 'Indefinido';
                        break;
                }
                $dados .= '<td align="center">' . $situacao . '</td>';
                $dados .= '</tr>';

                if ($cont >= $numPedidos) {
                    break;
                }

                $cont++;
            }

            $dados .= '</tbody></table>';

            $totais = '<table class="tbTotais" border="1">
                            <tr>
                                <td class="tdTotalTitulo">
                                    Total:
                                </td>
                                <td>
                                    R$' . FormataRealParaMoeda($valorTotal) .
                    '</td>
                                <td class="tdTotalTitulo">
                                    Quant. Total:
                                </td>
                                <td>'
                    . $qtdTotal .
                    '</td>
                            </tr>
                            <tr>
                                <td class="tdTotalTitulo">
                                    Total Retirado:
                                </td>
                                <td>
                                    R$' . FormataRealParaMoeda($totalRetirado) .
                    '</td>
                                <td class="tdTotalTitulo">
                                    Quant. Retirado:
                                </td>
                                <td>'
                    . $qtdRetirado .
                    '</td>
                            </tr>
                            <tr>
                                <td class="tdTotalTitulo">
                                    Total Devolvido:
                                </td>
                                <td>
                                    R$' . FormataRealParaMoeda($totalDevolvido) .
                    '</td>
                                <td class="tdTotalTitulo">
                                    Quant. Devolvido:
                                </td>
                                <td>'
                    . $qtdDevolvido .
                    '</td>
                            </tr>
                            <tr>
                                <td class="tdTotalTitulo">
                                    Total Extraviado:
                                </td>
                                <td>
                                    R$' . FormataRealParaMoeda($totalExtraviado) .
                    '</td>
                                <td class="tdTotalTitulo">
                                    Quant. Extraviado:
                                </td>
                                <td>'
                    . $qtdExtraviado .
                    '</td>
                            </tr>
                        </table>';
            $html = $dados . $totais;
            $impressorPdf->imprimeHtmlPdf(utf8_encode($html));
            if ($cont < $numPedidos) {
                $impressorPdf->AddPage("L");
            }
        }
    } else {
        $impressorPdf->imprimeHtmlPdf("Não há registros com esse filtro!");
    }
} else {
    $impressorPdf->imprimeHtmlPdf("Catálogo não informado!");
}

// Finalizando
$impressorPdf->Output();
exit();
