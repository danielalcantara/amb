<?php

require_once 'ImportacaoPedido.class.php';

/**
 * Description of ImportLayout4
 *
 * @author Daniel Alcântara
 */
class ImportLayoutHiroshima extends ImportacaoPedido {

    public function LerArquivo($arquivo) {
        
        $count = 0;
        
        if (strpos($arquivo[0], 'REV.:') === false) {
            return false;
        } else {
            for ($cont = 0; $cont < count($arquivo); $cont++) {
                $linha = $arquivo[$cont];
                $linha = trim($linha);
                if (strpos($linha, 'REV.:')) {
                    $linhaArray = explode('-', $linha);
                    $nomeRevendedor = trim(substr($linhaArray[0], strpos($linhaArray[0], 'REV.:') + 5));
                    $linha = substr($linhaArray[1], strpos($linhaArray[1], 'PED:') + 4);
                    $linha = RemoveExcessoEspacos($linha);
                    $linhaArray = explode(' ', $linha);
                    $codPedido = trim($linhaArray[0]);
                    $this->pedidos[$count]['codPedido'] = $codPedido;
                    $revendedor = $this->BuscaIncRevendedor($nomeRevendedor, $this->pedidos[$count]['codPedido'], true);
                    $this->pedidos[$count]['idRevendedor'] = $revendedor;
                } elseif (strpos($linha, 'SUBTOTAL........:') !== false) {
                	$linha = trim(substr($linha, strpos($linha, '.:') + 2));
                	$linha = RemoveExcessoEspacos($linha);
                	$linhaArray = explode(' ', $linha);
                	$totalVenda = trim($linhaArray[0]);
                	$this->pedidos[$count]['totalVenda'] = $totalVenda;
                } elseif (strpos($linha, 'DESC.REV.') !== false) {
                	$linha = trim(substr($linha, strpos($linha, '%:') + 2));
                	$linha = RemoveExcessoEspacos($linha);
                	$linhaArray = explode(' ', $linha);
                	$desconto = trim($linhaArray[0]);
                	$this->pedidos[$count]['desconto'] = $desconto;
                    $count++;
                }
            }
            return true;
        }
    }

}

?>
