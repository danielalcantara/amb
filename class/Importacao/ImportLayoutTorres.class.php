<?php

require_once 'ImportacaoPedido.class.php';

/**
 * Description of ImportLayout4
 *
 * @author Daniel Alcântara
 */
class ImportLayoutTorres extends ImportacaoPedido {

    public function LerArquivo($arquivo) {
        
        $count = 0;
        $dadosRevendedor = array();

        if (strpos($arquivo[0], 'REVEND..:') === false and !strpos($arquivo[0], 'N.PEDIDO:')) {

            return false;
            
        } else {
            foreach ($arquivo as $linha) {

                $linha = trim($linha);

                if (strpos($linha, 'REVEND..:') !== false) {

                    $linhaArray = explode('COD:', $linha);
                    $linha = $linhaArray[0];
                    $linhaArray = explode("N.PEDIDO:", $linhaArray[1]);
                    $codPedido = trim($linhaArray[1]);
                    $this->pedidos[$count]['codPedido'] = $codPedido;
                    $nomeRevendedor = trim(substr($linha, strpos($linha, ':') + 1));
                    
                } elseif (strpos($linha, 'ENDERECO:') !== false) {

                    $linhaArray = explode(':', $linha);
                    $endereco = trim(str_replace('BAIRRO........', '', $linhaArray[1]));
                    $bairro = trim(str_replace('EMISSAO', '', $linhaArray[2]));
                    
                    $dadosRevendedor['endereco'] = $endereco;
                    $dadosRevendedor['bairro'] = $bairro;
                    
                } elseif (strpos($linha, 'CIDADE..:') !== false) {
                    
                    $linhaArray = explode(':', $linha);
                    $cidade = trim(str_replace('UF', '', $linhaArray[1]));
                    $estado = trim(str_replace('CPF', '', $linhaArray[2]));
                    $cpf = trim(str_replace('TELEFONE......', '', $linhaArray[3]));
                    $telefone = trim($linhaArray[4]);
                    
                    $dadosRevendedor['cidade'] = $cidade;
                    $dadosRevendedor['estado'] = $estado;
                    $dadosRevendedor['cpf'] = $cpf;
                    $dadosRevendedor['telefone'] = $telefone;
                    
                    $revendedor = $this->BuscaIncRevendedor($nomeRevendedor, $this->pedidos[$count]['codPedido'], false, $dadosRevendedor);
                    $this->pedidos[$count]['idRevendedor'] = $revendedor;
                    
                } elseif (strpos($linha, 'Total  Bruto') !== false) {

                    $totalVenda = trim(substr($linha, strpos($linha, 'Total  Bruto') + 12));
                    $this->pedidos[$count]['totalVenda'] = str_replace(',', '', $totalVenda);
                    
                } elseif (strpos($linha, 'Seu Ganho') !== false) {

                    $desconto = trim(substr($linha, strpos($linha, 'Seu Ganho') + 9));
                    $this->pedidos[$count]['desconto'] = str_replace(',', '', $desconto);
                    $count++;
                    
                }
            }
            return true;
        }
    }

}

?>
