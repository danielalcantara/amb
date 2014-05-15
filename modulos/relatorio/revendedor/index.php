<?php
include_once '../../../inc/cabecalho.php';
require_once '../../../class/DAO/RevendedorDao.php';

$header->addScript('js/funcoes.js', true);

$header->write();

$cfg['menuCorrente'] = 3;

$util->getTopoPagina($cfg);
?>
<p class="tituloPagina">Relatório por revendedores</p>
<div>
    <form name="filtroRelatorio" id="filtroRelatorio" class="formTableless" method="post" action="" onsubmit="return GerarRelatorio();">
        <p class="label">Dados para gerar relatório:</p>
        <label for="IdPonto">Ponto de Venda:</label>
        <select name="IdPonto" id="IdPonto" onchange="CarregaComboRevendedor(this.value, '<?php echo pegarRaizSite(); ?>');">
            <option value="">Selecione</option>
            <?php
            $sql = "SELECT cod_ponto, nome_ponto FROM pontos order by nome_ponto";
            $rs = mysql_query($sql);
            $option = '';
            while ($linha = mysql_fetch_array($rs)) {
                $option .= '<option ';
                if ($linha['cod_ponto'] == $campos["IdPonto"]) {
                    $option .= 'selected="selected" ';
                }
                $option .= 'value="' . $linha['cod_ponto'] . '">' . $linha['nome_ponto'];
                $option .= '</option>';
            }
            echo $option;
            ?>
        </select>
        <br>
        <label for="">
            Revendedor:
        </label>
        <div id="comboRevendedor">
            <select name="revendedor" id="revendedor" class="obr">
                <option value="">Selecione</option>
            </select>
        </div>
        <br>
        <label for="">
            Data Inicio:
        </label>
        <input type="text" name="dataInicio" id="dataInicio" class="data datepicker" onKeyPress="MascaraData(this);" 
               onBlur="ValidaData(this);" maxlength="10">
        <br>
        <label for="">
            Data Fim:
        </label>
        <input type="text" name="dataFim" id="dataFim" class="data datepicker" onKeyPress="MascaraData(this);" 
               onBlur="ValidaData(this);" maxlength="10">
        <br>
        <input type="submit" value="Gerar Relatório">
    </form>
</div>
<?php
include_once '../../../inc/rodape.php';
?>
