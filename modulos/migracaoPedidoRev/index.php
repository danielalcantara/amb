<?php
include_once '../../inc/cabecalho.php';

ini_set('max_execution_time', 300);

$header->write();

$cfg['menuCorrente'] = 4;

$util->getTopoPagina($cfg);
echo $util->checkMensagem();
?>
<div id="importacao">
    <div id="topoPagina">
        <form name="formFiltro" class="formFiltroCadastro" action="" method="post" >
            <label for="filtro">Filtrar por: </label>
            <input type="text" name="filtro" id="filtro" size="40" >
            <input type="image" class="botaoBusca" src="../../img/icones/busca.png" >
        </form>
    </div>
    <div id="listaCatalogo" class="listagem">
        <p class="tituloPagina">Migração Pedidos por Revendedor</p>
        <?php
        $pagina = null;
        $filtro = null;
        $where = "";

        if (isset($_POST['filtro'])) {
            $filtro = anti_injection($_POST['filtro']);
        } elseif (isset($_POST['filtroHidden'])) {
            $filtro = anti_injection($_POST['filtroHidden']);
        }

        if ($filtro) {
            $where .= "WHERE nome LIKE '%" . $filtro . "%' ";
            $where .= "OR cpf LIKE '%" . $filtro . "%' ";
            $where .= "OR telefone LIKE '%" . $filtro . "%' ";
            $where .= "OR data_nascimento = '" . $filtro . "' ";
        }

        $sql = "SELECT COUNT(cod_revendedor) AS numLinhas FROM revendedor ";
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
            $tabela .= '<th>CPF</th>';
            $tabela .= '<th>Telefone</th>';
            $tabela .= '<th>Data Nascimento</th>';
            $tabela .= '<th>Ponto de Venda</th>';
            $tabela .= '</tr>';
            $tabela .= ' </thead>';
            $tabela .= '<tbody>';

            /* Bloco referente a obtenção dos dados para a paginação */

            // Limite da página 
            $tamanhoPagina = 30;

            // Examina a página a mostrar e o início do registo a mostrar 
            if (isset($_POST["pagina"])) {
                $pagina = $_POST["pagina"];
                if ($pagina == 1) {
                    $inicio = 0;
                } else {
                    $inicio = ($pagina - 1) * $tamanhoPagina;
                }
            } else {
                $pagina = 1;
                $inicio = 0;
            }

            $totalPaginas = ceil($numLinhas / $tamanhoPagina);

            /* Fim bloco para obtenção de dados para paginação */

            $sql = "SELECT cod_revendedor, nome, cpf, r.telefone, data_nascimento, nome_ponto FROM revendedor AS r ";
            $sql .= "LEFT JOIN pontos pt ON (r.cod_ponto = pt.cod_ponto) ";
            $sql .= $where;
            $sql .= " ORDER BY nome ";
            $sql .= "LIMIT " . $inicio . "," . $tamanhoPagina;

            $rs = mysql_query($sql);

            $cont = 1;

            while ($linha = mysql_fetch_array($rs)) {
                $onclick = 'onclick="RedirecPagina(\'migracao.php?idRevendedorOrigem=';
                $onclick .= $linha['cod_revendedor'] . "');\"";
                if ($cont % 2 == 0) {
                    $tabela .= '<tr class="linhaPar linhaAcao" ' . $onclick . '>';
                } else {
                    $tabela .= '<tr class="linhaImpar linhaAcao" ' . $onclick . '>';
                }
                $tabela .= '<td>' . $linha['cod_revendedor'] . '</td>';
                $tabela .= '<td class="alignLeft">' . $linha['nome'] . '</td>';
                $tabela .= '<td>' . $linha['cpf'] . '</td>';
                $tabela .= '<td>' . $linha['telefone'] . '</td>';
                $tabela .= '<td>' . FormatDataBDView($linha['data_nascimento']) . '</td>';
                $tabela .= '<td class="alignLeft">' . $linha['nome_ponto'] . '</td>';
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
