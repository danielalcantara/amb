<?php

require_once 'ImportacaoPedido.class.php';

/**
 * Description of ImportLayout4
 *
 * @author Daniel Alcântara
 */
class ImportLayoutViaBlumenau extends ImportacaoPedido {

    public function LerArquivo($arquivo) {
        $count = 0;
        if ((strpos(trim($arquivo[0]), 'Distribuidor:') === false) and strpos($arquivo[0], "(") === false) {
            return false;
        } else {
            for ($cont = 0; $cont < count($arquivo); $cont++) {
                $linha = trim($arquivo[$cont]);
                if (strpos($linha, 'Revendedor:') === 0) {
                    $linhaArray = explode('-', $linha);
                    $nomeRevendedor = trim(substr($linha, strpos($linha, ' ') + 1));
                } elseif (strpos($linha, 'Lote:') === 0) {
                    $linha = str_replace("Lote:", "", $linha);
                    $linha = str_replace("Pedido:", "", $linha);
                    $linha = str_replace("Catálogo:", "", $linha);
                    $linha = RemoveExcessoEspacos($linha);
                    $linha = str_replace(" ", "", $linha);
                    $codPedido = trim($linha);
                    $this->pedidos[$count]['codPedido'] = $codPedido;
                    $revendedor = $this->BuscaIncRevendedor($nomeRevendedor, $this->pedidos[$count]['codPedido'], true);
                    $this->pedidos[$count]['idRevendedor'] = $revendedor;
                } elseif (strpos($linha, 'Valor Desconto:')) {
                    $linha = substr($linha, strpos($linha, 'Desconto:') + 9);
                    $linhaArray = explode('/', $linha);
                    $desconto = FormataMoedaParaReal(trim(str_replace(substr($linhaArray[0], -8), "", $linhaArray[0])));
                    $valorPago = FormataMoedaParaReal(trim(substr($linhaArray[1], 5)));
                    $totalVenda = $valorPago + $desconto;
                    $this->pedidos[$count]['totalVenda'] = $totalVenda;
                    $this->pedidos[$count]['desconto'] = $desconto;
                    $count++;
                }
            }
            /* print_r($this->pedidos);
            exit; */
            return true;
        }
    }

}

?>
