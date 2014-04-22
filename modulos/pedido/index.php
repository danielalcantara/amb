<?php
include_once '../../inc/cabecalho.php';

$header->write();

$cfg = Array();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

if (isset($_POST['acao']) and isset($_POST['id'])) {
    if (is_numeric($_POST['id']) and $_POST['acao'] == 'deletar') {
        $id = $_POST['id'];
        $msg = null;

        $sql = "DELETE FROM pedido WHERE Id = " . $id;
        mysql_query($sql);
        if (mysql_error()) {
            $msg = "Falha ao deletar registro. SQL erro: " . mysql_error();
            $util->setMsgErro($msg);
        } else {
            $msg = "Registro deletado com sucesso!";
            $util->setMsgSucesso($msg);
        }
    }
}
?>
<p class="tituloPagina">Relação de pedidos</p>
<div id="pedido">
    <?php
    $divMsg = "<div class='divMsg'>";
    $divMsg .= $util->checkMensagem();
    $divMsg .= "</div>";
    echo $divMsg;
    ?>
    <div id="topoPagina">
        <a href="adicionar.php">
            <img alt="Adicionar" title="Adicionar" class="botaoAdd" src="../../img/icones/botaoNovo.png">
        </a>
        <form name="formFiltroCadastro" class="formFiltroCadastro" action="" method="post" >
            <label for="filtro">Filtrar por: </label>
            <input type="text" name="filtro" id="filtro" size="40" >
            <input type="image" class="botaoBusca" src="../../img/icones/busca.png" >
        </form>
    </div>
    <div id="listaCatalogo" class="listagem">
        <?php
        // Verifica se há filtro para busca
        $where = "";
        $filtro = "";

        if (isset($_POST['filtro'])) {
            $filtro = anti_injection($_POST['filtro']);
        } elseif (isset($_POST['filtroHidden'])) {
            $filtro = anti_injection($_POST['filtroHidden']);
        }

        if (is_numeric($filtro)) {
            $where .= "WHERE Id = " . $filtro . " ";
            $where .= "OR CodPedido = " . $filtro . " ";
            $where .= "OR IdCatalogo = " . $filtro . " ";
            $where .= "OR IdRevendedor = " . $filtro . " ";
        }

        $sql = "SELECT COUNT(Id) AS numLinhas FROM pedido ";
        $sql .= $where;

        $rs = mysql_query($sql);
        $numLinhas = mysql_result($rs, 0);
        if ($numLinhas == 0) {
            echo '<p class="msgAlerta">Não foram encontrados registros.</p>';
        } else {
            $tabela = '<table class="tbListagem">';
            $tabela .= '<thead>';
            $tabela .= '<tr>';
            $tabela .= '<th>Código</th>';
            $tabela .= '<th>Cód. Fab.</th>';
            $tabela .= '<th>Catalogo</th>';
            $tabela .= '<th>Revendedor</th>';
            $tabela .= '<th>Data Pedido</th>';
            $tabela .= '<th>Data Entrega</th>';
            $tabela .= '<th>Total Venda</th>';
            $tabela .= '<th>Situação</th>';
            $tabela .= '<th>Ações</th>';
            $tabela .= '</tr>';
            $tabela .= ' </thead>';
            $tabela .= '<tbody>';

            /* Bloco referente a obtenção dos dados para a paginação */

            // Limite da página 
            $tamanhoPagina = 30;

            // Examina a página a mostrar e o início do registo a mostrar 
            $pagina = 1;
            $inicio = 0;

            if (isset($_POST["pagina"])) {
                if (is_numeric($_POST["pagina"])) {
                    $pagina = $_POST["pagina"];
                    $inicio = $pagina == 1 ? 0 : ($pagina - 1) * $tamanhoPagina;
                }
            }

            $totalPaginas = ceil($numLinhas / $tamanhoPagina);

            /* Fim bloco para obtenção de dados para paginação */

            $sql = "SELECT `Id`, `CodPedido`, c.`identificacao`, r.`nome`, TotalVendas, DataPedido, DataEntrega, Situacao ";
            $sql .= "FROM `pedido` AS p ";
            $sql .= "LEFT JOIN `catalogos` AS c ON (p.`IdCatalogo` = c.`cod_catalogo`) ";
            $sql .= "LEFT JOIN `revendedor` AS r ON (p.`IdRevendedor` = r.`cod_revendedor`) ";
            $sql .= $where;
            $sql .= "ORDER BY `Id` DESC ";
            $sql .= "LIMIT " . $inicio . "," . $tamanhoPagina;

            $rs = mysql_query($sql);

            $cont = 1;

            while ($linha = mysql_fetch_array($rs)) {
                if ($cont % 2 == 0) {
                    $tabela .= '<tr class="linhaPar">';
                } else {
                    $tabela .= '<tr class="linhaImpar">';
                }
                $tabela .= '<td class="center">' . $linha['Id'] . '</td>';
                $tabela .= '<td class="center">' . $linha['CodPedido'] . '</td>';
                $tabela .= '<td align="center">' . $linha['identificacao'] . '</td>';
                $tabela .= '<td align="left">' . $linha['nome'] . '</td>';
                $tabela .= '<td align="center">' . FormatDataBDView($linha['DataPedido']) . '</td>';
                $tabela .= '<td align="center">' . FormatDataBDView($linha['DataEntrega']) . '</td>';
                $tabela .= '<td align="center">' . FormataRealParaMoeda($linha['TotalVendas']) . '</td>';
                $tabela .= '<td align="center">' . ConvertMaiusculo($linha['Situacao']) . '</td>';
                $tabela .= '<td width="7%">' . $util->CriarFormEditar($linha['Id'], $filtro, $pagina);
                $tabela .= $util->CriarFormDeletar($linha['Id'], $filtro, $pagina) . '</td>';
                $tabela .= '</tr>';
                $cont++;
            }
            $tabela .= '</tbody>';
            $tabela .= '</table>';
            echo $tabela;

            // Criando a paginação
            if ($totalPaginas > 1) {
                Paginacao($pagina, $totalPaginas, $filtro);
            }
            mysql_free_result($rs);
        }
        ?>
    </div>
</div>
<?php
include_once '../../inc/rodape.php';
?>
