<?php
include_once '../../inc/cabecalho.php';

$header->write();

$cfg['menuCorrente'] = 1;

$util->getTopoPagina($cfg);

if (isset($_POST['acao']) and isset($_POST['id'])) {
    if (is_numeric($_POST['id']) and $_POST['acao'] == 'deletar') {
        $id = $_POST['id'];
        $msg = null;

        $sql = "DELETE FROM catalogos WHERE cod_catalogo=" . $id;
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
<div id="catalogo">
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
        <p class="tituloPagina">Relação de catálogos</p>
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
            $where .= "WHERE nome_fabrica LIKE '%" . $filtro . "%' ";
            $where .= "OR identificacao LIKE '%" . $filtro . "%' ";
            $where .= "OR numero_catalogo LIKE '%" . $filtro . "%' ";
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
            $tabela .= '<th>Número</th>';
            $tabela .= '<th>Nome</th>';
            $tabela .= '<th>Fábrica</th>';
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

            $sql = "SELECT cod_catalogo, numero_catalogo, identificacao, nome_fabrica FROM catalogos AS c ";
            $sql .= "INNER JOIN fabricas AS f ON (c.cod_fabrica = f.cod_fabrica) ";
            $sql .= $where;
            $sql .= "ORDER BY cod_catalogo DESC ";
            $sql .= "LIMIT " . $inicio . "," . $tamanhoPagina;

            $rs = mysql_query($sql);

            $cont = 1;

            while ($linha = mysql_fetch_array($rs)) {
                if ($cont % 2 == 0) {
                    $tabela .= '<tr class="linhaPar">';
                } else {
                    $tabela .= '<tr class="linhaImpar">';
                }
                $tabela .= '<td>' . $linha['cod_catalogo'] . '</td>';
                $tabela .= '<td>' . $linha['numero_catalogo'] . '</td>';
                $tabela .= '<td>' . $linha['identificacao'] . '</td>';
                $tabela .= '<td>' . $linha['nome_fabrica'] . '</td>';
                $tabela .= '<td width="7%">' . $util->CriarFormEditar($linha['cod_catalogo'], $filtro, $pagina);
                $tabela .= $util->CriarFormDeletar($linha['cod_catalogo'], $filtro, $pagina) . '</td>';
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
