<?php

require_once 'ImportacaoPedido.class.php';

/**
 * Description of ImportLayout1
 *
 * @author Daniel Alcântara
 */
class ImportLayoutLuzon extends ImportacaoPedido {

    public function LerArquivo($arquivo) {
        
        $count = 0;
        
        if (!strpos($arquivo[0], 'Remessa:')) {
            return false;
        } else {
            for ($cont = 0; $cont < count($arquivo); $cont++) {
                $linha = trim($arquivo[$cont]);
                if (strpos($linha, 'Pedido:') === 0) {
                    $linhaArray = explode('-', $linha);
                    $codPedido = trim(substr($linhaArray[0], strpos($linhaArray[0], ':') + 1));
                    $codPedido = str_replace(' / ', '', $codPedido);
                    $this->pedidos[$count]['codPedido'] = $codPedido;
                    $linhaArray = explode("Digitado por:", $linhaArray[1]);
                    $nomeRevendedor = trim($linhaArray[0]);
                    $revendedor = $this->BuscaIncRevendedor($nomeRevendedor, $this->pedidos[$count]['codPedido']);
                    $this->pedidos[$count]['idRevendedor'] = $revendedor;
                } elseif (strpos($linha, 'Valor do Desconto  =>') === 0) {
                    $desconto = trim(substr($linha, 21));
                    $desconto = FormataMoedaParaReal($desconto);
                    $this->pedidos[$count]['desconto'] = $desconto;
                } elseif (strpos($linha, 'Valor a Pagar  =>') === 0) {
                    $valorPago = substr($linha, 17);
                    $valorPago = trim($valorPago);
                    $valorPago = FormataMoedaParaReal($valorPago);
                    $totalVenda = $valorPago + $desconto;
                    $this->pedidos[$count]['totalVenda'] = $totalVenda;
                    $count++;
                }
            }
            return true;
        }
    }

}

?>
