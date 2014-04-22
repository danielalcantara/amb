<?php

require_once 'ImportacaoPedido.class.php';

/**
 * Description of ImportLayout1
 *
 * @author Daniel Alcântara
 */
class ImportLayoutMiroStar extends ImportacaoPedido {

    public function LerArquivo($arquivo) {

        $count = 0;
        $dadosRevendedor = array();

        if (!strpos($arquivo[0], 'DATA PEDIDO:') and !strpos($arquivo[0], 'TABELA:')) {
            return false;
        } else {
            foreach ($arquivo as $linha) {
                $linha = trim($linha);
                if (strpos($linha, 'DATA PEDIDO:') and strpos($linha, 'TABELA:')) {
                    $linhaArray = explode('DATA PEDIDO:', $linha);
                    $codPedido = substr($linhaArray[0], strpos($linhaArray[0], ':') + 1);
                    $this->pedidos[$count]['codPedido'] = trim($codPedido);
                } elseif (strpos($linha, 'REVENDEDOR..:') === 0) {
                    $linhaArray = explode('-', $linha);
                    $nomeRevendedor = trim($linhaArray[1]);
                } elseif (strpos($linha, 'ENDERECO....:') === 0) {
                    $dadosRevendedor['endereco'] = trim(substr($linha, strpos($linhaArray[0], ':') + 1));
                } elseif (strpos($linha, 'BAIRRO......:') === 0) {
                    $linha = RemoveExcessoEspacos($linha);
                    $linhaArray = explode(':', $linha);
                    $bairro = trim(str_replace(' CIDADE', '', $linhaArray[1]));
                    $cidade = trim(str_replace(' UF', '', $linhaArray[2]));
                    $estado = trim($linhaArray[3]);
                    $dadosRevendedor['bairro'] = $bairro;
                    $dadosRevendedor['cidade'] = $cidade;
                    $dadosRevendedor['estado'] = $estado;
                } elseif (strpos($linha, 'DATA NASCTO.:') === 0) {
                    $dadosRevendedor['dataNascimento'] = trim(substr($linha, 14, 10));
                    $revendedor = $this->BuscaIncRevendedor($nomeRevendedor, $this->pedidos[$count]['codPedido'], false, $dadosRevendedor);
                    $this->pedidos[$count]['idRevendedor'] = $revendedor;
                } elseif (strpos($linha, 'TOTAL VENDAS.....:') === 0) {
                    $totalVenda = trim(substr($linha, 18));
                    $totalVenda = FormataMoedaParaReal($totalVenda);
                    $this->pedidos[$count]['totalVenda'] = $totalVenda;
                } elseif (strpos($linha, 'DESCONTO.........:') === 0) {
                    $desconto = trim(substr($linha, 18));
                    $desconto = FormataMoedaParaReal($desconto);
                    $this->pedidos[$count]['desconto'] = $desconto;
                    $count++;
                }
            }
            return true;
        }
    }

}

?>
