<?php
include_once '../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

if (isset($_POST['acao']) and isset($_POST['id'])) {
    if (is_numeric($_POST['id']) and $_POST['acao'] == 'deletar') {
        $id = $_POST['id'];
        $msg = null;

        $sql = "DELETE FROM pontos WHERE cod_ponto = " . $id;
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
<p class="tituloPagina">Relação de pontos de venda</p>
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
            $where .= "WHERE cod_ponto = '" . $filtro . "' ";
            $where .= "OR nome_ponto LIKE '%" . $filtro . "%' ";
            $where .= "OR cidade_ponto LIKE '%" . $filtro . "%' ";
            $where .= "OR nome_grupo LIKE '%" . $filtro . "%' ";
        }

        $sql = "SELECT COUNT(*) AS numLinhas FROM `pontos` AS p ";
        $sql .= "INNER JOIN `grupos_pontos` AS gp ON (p.`cod_grupo` = gp.`cod_grupo`) ";
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
            $tabela .= '<th>Nome</th>';
            $tabela .= '<th>Cidade</th>';
            $tabela .= '<th>Estado</th>';
            $tabela .= '<th>Grupo</th>';
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

            $sql = "SELECT `cod_ponto`, `nome_ponto`, `cidade_ponto`, p.`estado`, gp.`nome_grupo` ";
            $sql .= "FROM `pontos` AS p ";
            $sql .= "INNER JOIN `grupos_pontos` AS gp ON (p.`cod_grupo` = gp.`cod_grupo`) ";
            $sql .= $where;
            $sql .= "ORDER BY `nome_ponto` ";
            $sql .= "LIMIT " . $inicio . "," . $tamanhoPagina;

            $rs = mysql_query($sql);

            $cont = 1;

            while ($linha = mysql_fetch_array($rs)) {
                if ($cont % 2 == 0) {
                    $tabela .= '<tr class="linhaPar">';
                } else {
                    $tabela .= '<tr class="linhaImpar">';
                }
                $tabela .= '<td class="colCenter">' . $linha['cod_ponto'] . '</td>';
                $tabela .= '<td align="left">' . $linha['nome_ponto'] . '</td>';
                $tabela .= '<td align="center">' . $linha['cidade_ponto'] . '</td>';
                $tabela .= '<td align="center">' . $linha['estado'] . '</td>';
                $tabela .= '<td align="center">' . $linha['nome_grupo'] . '</td>';
                $tabela .= '<td width="7%">' . $util->CriarFormEditar($linha['cod_ponto'], $filtro, $pagina);
                $tabela .= $util->CriarFormDeletar($linha['cod_ponto'], $filtro, $pagina) . '</td>';
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
