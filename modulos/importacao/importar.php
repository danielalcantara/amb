<?php

include_once '../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 2;

$erroImport = array();

$util->getTopoPagina($cfg);

if (isset($_POST['dataPedidos']) and isset($_POST['dataEntrega']) and isset($_POST['idCatalogo'])) {

    $dataPedidos = anti_injection($_POST['dataPedidos']);
    $dataEntrega = anti_injection($_POST['dataEntrega']);
    $dataArray = explode('/', $dataPedidos);

    if (checkdate($dataArray[1], $dataArray[0], $dataArray[2])) {

        $dataArray = explode('/', $dataEntrega);

        if (checkdate($dataArray[1], $dataArray[0], $dataArray[2])) {
            $dataPedidos = FormatDataViewBD($dataPedidos);
            $dataEntrega = FormatDataViewBD($dataEntrega);

            $idCatalogo = anti_injection($_POST['idCatalogo']);
            $sql = "SELECT Layout FROM LayoutImport WHERE IdFabrica = (SELECT cod_fabrica FROM catalogos WHERE cod_catalogo = ";
            $sql .= $idCatalogo . ")";
            $result = mysql_query($sql);

            if (mysql_num_rows($result) > 0) {

                $classLayout = 'ImportLayout' . mysql_result($result, 0);

                if (is_uploaded_file($_FILES['arquivoImport']['tmp_name']) and $_FILES['arquivoImport']['error'] == 0) {

                    $ext = explode('.', $_FILES['arquivoImport']['name']);

                    if (strtolower($ext[1]) == 'txt') {

                        $uploadDir = 'arqImportTemp/' . $_FILES['arquivoImport']['name'];
                        $uploadFile = $_FILES['arquivoImport']['tmp_name'];

                        if (move_uploaded_file($uploadFile, $uploadDir)) {

                            $arquivo = file($uploadDir, FILE_SKIP_EMPTY_LINES);

                            require_once pegarRaizSite() . 'class/Importacao/' . $classLayout . '.class.php';

                            $class = new ReflectionClass($classLayout);
                            $importacao = $class->newInstance($arquivo, $idCatalogo, $dataPedidos, $dataEntrega);

                            $importacao->Importar();

                            if (count($importacao->GetFalhas()) > 0) {
                                $erroImport = array_merge($erroImport, $importacao->GetFalhas());
                            }

                            unset($arquivo, $class);
                            unlink($uploadDir);
                        } else {
                            $erroImport[] = "Falha no upload do arquivo de importação.";
                        }
                    } else {
                        $erroImport[] = "Extensão de arquivo inválido para importação.";
                    }
                } else {
                    $erroImport[] = "Não foi feito upload do arquivo para importação. Retorne a página anterior e escolha um arquivo para importação.";
                }
            } else {
                $erroImport[] = "Esse catálogo não possui layout para importação. Favor contactar o desenvolvedor.";
            }
        } else {
            $erroImport[] = "Data de entrega dos pedidos inválida.";
        }
    } else {
        $erroImport[] = "Data dos pedidos inválida.";
    }
} else {
    $erroImport[] = "Faltam dados para importação. Preencha todos os campos.";
}

function ListagemRevenderores($revenderores) {
    $tabListagem = "<table class='tabListagem'>";
    $tabListagem .= "<tr>";
    $tabListagem .= "<th>Nome do Revendedor</th>";
    $tabListagem .= "<th>N.º Pedido</th>";
    $tabListagem .= "</tr>";
    foreach ($revenderores as $revendedor) {
        $tabListagem .= "<tr>";
        $tabListagem .= "<td>" . $revendedor['nomeRevendedor'] . "</td>";
        $tabListagem .= "<td class='celCenter'>" . $revendedor['codPedido'] . "</td>";
        $tabListagem .= "</tr>";
    }
    $tabListagem .= "</table>";
    return $tabListagem;
}

$pagina = '<div id="importacao" class="divConteudoPagina">';
$pagina .= '<p class="tituloPagina">Importação de Pedidos</p>';

if (count($erroImport) == 0) {
    $pagina .= "<p class='msgSucesso'>Importação finalizada com sucesso!</p>";
    $revendedoresNovos = $importacao->GetRevendedoresNovos();
    if (count($revendedoresNovos) > 0) {
        $pagina .= "<p class='msgInfo'>Segue abaixo lista dos novos revendedores cadastrador na importação. 
                Favor ir em cadastro de revendedores e editar informação.</p>";
        $pagina .= ListagemRevenderores($revendedoresNovos);
    }

    $revendedoresHomonimos = $importacao->GetRevendedoresHonimos();
    if (count($revendedoresHomonimos) > 0) {
        $pagina .= "<p class='msgInfo'>Segue abaixo lista dos revendedores com homônimos na importação. 
                Favor verificar em cadastro de pedidos se é o revendedor correto.</p>";
        $pagina .= ListagemRevenderores($revendedoresHomonimos);
    }
} else {
    $pagina .= "<p class='msgFalha'>Falha na importação. Abaixo segue uma lista das falhas:</p>";
    $pagina .= ListaFalhas($erroImport);
    $pagina .= '<input type="button" value="Voltar" onclick="VoltarPagina();">';
}

$pagina .= '</div>';

echo $pagina;

include_once '../../inc/rodape.php';
?>