<?php
include_once '../../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 3;

$util->getTopoPagina($cfg);
?>
<p class="tituloPagina">Relatório catálogo por ponto</p>
<div id="importacao">
    <?php
    $divMsg = "<div class='divMsg'>";
    $divMsg .= $util->checkMensagem();
    $divMsg .= "</div>";
    echo $divMsg;
    ?>
    <div id="topoPagina">
        <form name="formFiltroCadastro" class="formFiltroCadastro" action="" method="post" >
            <label for="filtro">Filtrar por: </label>
            <input type="text" name="filtro" id="filtro" size="40" >
            <input type="image" class="botaoBusca" src="<?php echo pegarRaizSite(); ?>img/icones/busca.png" >
        </form>
    </div>
    <div id="listaCatalogo" class="listagem">
        <p class="tituloPagina">Relação de catálogos</p>
        <?php
        $pagina = null;
        $filtro = null;
        
        $where = "WHERE cod_catalogo IN (SELECT DISTINCT(IdCatalogo) FROM pedido) ";

        // Verifica se há filtro para busca
        if (isset($_POST['filtro'])) {
            $filtro = anti_injection($_POST['filtro']);
        } elseif (isset($_POST['filtroHidden'])) {
            $filtro = anti_injection($_POST['filtroHidden']);
        }

        if ($filtro) {
            $where .= "AND (nome_fabrica LIKE '%" . $filtro . "%' ";
            $where .= "OR identificacao LIKE '%" . $filtro . "%' ";
            $where .= "OR numero_catalogo LIKE '%" . $filtro . "%') ";
        }

        $sql = "SELECT COUNT(*) AS numLinhas FROM catalogos AS c ";
        $sql .= "INNER JOIN fabricas AS f ON (c.cod_fabrica = f.cod_fabrica) ";
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
            $tabela .= '<th>Numero</th>';
            $tabela .= '<th>Nome</th>';
            $tabela .= '<th>Descrição</th>';
            $tabela .= '<th>Fábrica</th>';
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

            $sql = "SELECT cod_catalogo, numero_catalogo, identificacao, descricao, nome_fabrica FROM catalogos AS c ";
            $sql .= "INNER JOIN fabricas AS f ON (c.cod_fabrica = f.cod_fabrica) ";
            $sql .= $where;
            $sql .= "ORDER BY cod_catalogo DESC ";
            $sql .= "LIMIT " . $inicio . "," . $tamanhoPagina;

            $rs = mysql_query($sql);

            $cont = 1;

            while ($linha = mysql_fetch_array($rs)) {
                $onclick = 'onclick="RedirecPagina(\'filtro.php?idCatalogo=';
                $onclick .= $linha['cod_catalogo'] . "');\"";
                if ($cont % 2 == 0) {
                    $tabela .= '<tr class="linhaPar linhaAcao" ' . $onclick . '>';
                } else {
                    $tabela .= '<tr class="linhaImpar linhaAcao" ' . $onclick . '>';
                }
                $tabela .= '<td>' . $linha['cod_catalogo'] . '</td>';
                $tabela .= '<td>' . $linha['numero_catalogo'] . '</td>';
                $tabela .= '<td>' . $linha['identificacao'] . '</td>';
                $tabela .= '<td>' . $linha['descricao'] . '</td>';
                $tabela .= '<td>' . $linha['nome_fabrica'] . '</td>';
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
include_once '../../../inc/rodape.php';
?>
