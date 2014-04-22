<?php
global $idPedido;

$nParcelas = 0;
if (isset($idPedido)) {
    $parcelas = $facade->listarParcelas($idPedido);
    $nParcelas = count($parcelas);
}
?>
<div id="divAdParcelas" class="windows-popup">
    <div title="Fechar" class="close-popup"></div>
    <div class="title-popup"><h3>Parcelas lançadas:</h3></div>
    <div class="content-popup">
        <div id="parcelas-contain" class="ui-widget">
            <input type="hidden" id="nParcelas" value="<?php echo $nParcelas; ?>">
            <table id="parcelas" class="ui-widget ui-widget-content">
                <thead>
                    <tr class="ui-widget-header">
                        <th>Parcela</th>
                        <th>Valor</th>
                        <th>Data</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($nParcelas > 0) {
                        $cont = 0;
                        $linhasParcelas = '';
                        foreach ($parcelas as $parcela) {
                            $cont++;
                            $linhasParcelas .= '<tr>';
                            $linhasParcelas .= '<td class="nParcela">' . $cont . '</td>';
                            $linhasParcelas .= '<td><input type="text" class="valorParcela" name="valorParcela[]" readonly value="';
                            $linhasParcelas .= FormataRealParaMoeda($parcela['valor']) . '"></td>';
                            $linhasParcelas .= '<td><input type="text" class="valorData" name="dataParcela[]" readonly value="';
                            $linhasParcelas .= FormatDataBDView($parcela['data']) . '"></td>';
                            $linhasParcelas .= '<td><img src="../../img/icones/menos.png" alt="Excluir" onclick="exlcuirParcela(this);" ';
                            $linhasParcelas .= 'title="Excluir" class="btnImg"></td>';
                            $linhasParcelas .= '</tr>';
                        }
                        echo $linhasParcelas;
                    } else {
                        ?>
                        <tr id="trSemParcelas">
                            <td colspan="4">Não há parcelas lançadas</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <button id="create-parcela">Criar parcela</button>
        <button id="sair-parcela">Sair</button>
    </div>
</div>

<div class="mask-popup"></div>

<div id="dialog-form" class="ui-dialog" title="Adicionar parcela">
    <p class="validateTips">Todos os campos são requeridos.</p>

    <form>
        <fieldset>
            <label for="name">Valor</label>
            <input type="text" name="valor" id="valor" onKeyPress="return MascaraMoeda(this, '.', ',', event);" 
                   class="text ui-widget-content ui-corner-all ui-campoNumero" />
            <label for="email">Data</label>
            <input type="text" name="data" id="data" onKeyPress="MascaraData(this);" maxlength="10"
                   class="text ui-widget-content ui-corner-all datepicker ui-campoData" />
        </fieldset>
    </form>
</div>
