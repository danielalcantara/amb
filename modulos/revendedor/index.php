<?php
include_once '../../inc/cabecalho.php';
set_time_limit(1000);

$header->write();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

if (isset($_POST['acao']) and isset($_POST['id'])) {
    if (is_numeric($_POST['id']) and $_POST['acao'] == 'deletar') {
        $id = $_POST['id'];
        $msg = null;

        $sql = "DELETE FROM `revendedor` WHERE `cod_revendedor`=" . $id;
        mysql_query($sql);
        if (mysql_error()) {
            $msg = "Falha ao deletar revendedor. Verifique se não há pedidos vinculados "
                    . "ao mesmo, caso haja, favor realizar migração dos pedidos antes de excluí-lo.";
            $util->setMsgErro($msg);
        } else {
            $msg = "Registro deletado com sucesso!";
            $util->setMsgSucesso($msg);
        }
    }
}
?>
<p class="tituloPagina">Relação de revendedores</p>
<div id="revendedor">
    <?php
    $divMsg = "<div class='divMsg'>";
    $divMsg .= $util->checkMensagem();
    $divMsg .= "</div>";
    echo $divMsg;
    ?>
    <div id="topoPagina">
        <a href="adicionar.php">
            <img alt="Adicionar" title="Adicionar" class="botaoAdd" src="<?php echo $util->getRaizArquivos(); ?>img/icones/botaoNovo.png">
        </a>
        <form name="formFiltroCadastro" class="formFiltroCadastro" action="" method="post" >
            <label for="filtro">Filtrar por: </label>
            <input type="text" name="filtro" id="filtro" size="40" >
            <input type="image" class="botaoBusca" src="<?php echo $util->getRaizArquivos(); ?>img/icones/busca.png" >
        </form>
    </div>
    <div id="listaRevendedor" class="listagem">
        <?php
        $pagina = null;
        $filtro = null;

        // Verifica se há filtro para busca
        $where = "";

        if (isset($_POST['filtro'])) {
            $filtro = anti_injection($_POST['filtro']);
        } elseif (isset($_POST['filtroHidden'])) {
            $filtro = anti_injection($_POST['filtroHidden']);
        }

        if ($filtro) {
            $where .= "WHERE nome LIKE '" . $filtro . "%' ";
            $where .= "OR rg = '" . $filtro . "' ";
            $where .= "OR cpf = '" . $filtro . "' ";
        }

        $sql = "SELECT COUNT(cod_revendedor) AS numLinhas FROM `revendedor` ";
        $sql .= $where;       

        $rs = mysql_query($sql);
        $numLinhas = mysql_result($rs, 0);
        if ($numLinhas == 0) {
            echo '<p class="msgAlerta">Não foram encontrados registros.</p>';
        } else {
            $tabela = '<table class="tbListagem">';
            $tabela .= '<thead>';
            $tabela .= '<tr>';
            $tabela .= '<th>Nome</th>';
            $tabela .= '<th>CPF</th>';
            $tabela .= '<th>RG</th>';
            $tabela .= '<th>Data Nascimento</th>';
            $tabela .= '<th>Ponto de Venda</th>';
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

            $sql = "SELECT cod_revendedor, nome, cpf, rg, data_nascimento, nome_ponto FROM `revendedor` AS r ";
            $sql .= "LEFT JOIN `pontos` AS p ON (r.`cod_ponto` = p.`cod_ponto`) ";
            $sql .= $where;
            $sql .= "ORDER BY nome ";
            $sql .= "LIMIT " . $inicio . "," . $tamanhoPagina;

            $rs = mysql_query($sql);

            $cont = 1;

            while ($linha = mysql_fetch_array($rs)) {
                if ($cont % 2 == 0) {
                    $tabela .= '<tr class="linhaPar">';
                } else {
                    $tabela .= '<tr class="linhaImpar">';
                }
                $tabela .= '<td align="left">' . $linha['nome'] . '</td>';
                $tabela .= '<td align="center">' . $linha['cpf'] . '</td>';
                $tabela .= '<td align="center">' . $linha['rg'] . '</td>';
                $tabela .= '<td align="center">' . FormatDataBDView($linha['data_nascimento']) . '</td>';
                $tabela .= '<td align="left">' . $linha['nome_ponto'] . '</td>';
                $tabela .= '<td width="7%">' . $util->CriarFormEditar($linha['cod_revendedor'], $filtro, $pagina);
                $tabela .= $util->CriarFormDeletar($linha['cod_revendedor'], $filtro, $pagina) . '</td>';
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
