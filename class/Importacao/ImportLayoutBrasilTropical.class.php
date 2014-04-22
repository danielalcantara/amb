<?php

require_once 'ImportacaoPedido.class.php';

/**
 * Description of ImportLayout4
 *
 * @author Daniel Alcântara
 */
class ImportLayoutBrasilTropical extends ImportacaoPedido {

    public function LerArquivo($arquivo) {

        $countPedido = 0;
        $dadosRevendedor = array();

        if (strpos($arquivo[0], 'COD..:') === false and !strpos($arquivo[0], 'LOTE.PRINC:')) {

            return false;
        } else {

            for ($count = 0; $count < count($arquivo); $count++) {

                $linha = trim($arquivo[$count]);

                if (strpos($linha, 'COD..:') !== false) {

                    $linhaArray = explode('LOTE.PRINC:', $linha);
                    $codPedido = (int) trim(substr($linhaArray[0], 6));
                } elseif (strpos($linha, 'REVENDEDORA') !== false) {

                    $linhaArray = explode("|", $linha);
                    $codPedido .= (int) trim($linhaArray[1]);

                    $linha = $arquivo[$count + 1];
                    $linhaArray = explode('    ', $linha);
                    $nomeRevendedor = trim($linhaArray[0]);

                    $this->pedidos[$countPedido]['codPedido'] = $codPedido;
                } elseif (strpos($linha, 'Posto.:') !== false) {

                    $linhaArray = explode('|', $linha);
                    $endereco = trim($linhaArray[0]);
                    $dadosRevendedor['endereco'] = $endereco;
                } elseif (strpos($linha, 'Fone:') !== false) {

                    $linhaArray = explode('   ', $linha);
                    $bairro = trim($linhaArray[0]);
                    $cidade = trim(str_replace('"', '', $linhaArray[1]));
                    $estado = trim(substr($linhaArray[2], 0, 2));

                    $dadosRevendedor['bairro'] = $bairro;
                    $dadosRevendedor['cidade'] = $cidade;
                    $dadosRevendedor['estado'] = $estado;

                    $revendedor = $this->BuscaIncRevendedor($nomeRevendedor, $this->pedidos[$countPedido]['codPedido'], true, $dadosRevendedor);
                    $this->pedidos[$countPedido]['idRevendedor'] = $revendedor;
                } elseif (strpos($linha, '* TOTAL') !== false) {

                    $desconto = 0;
                    $totalVenda = 0;

                    while (strpos($arquivo[$count], '* TOTAL') !== false) {

                        if (strpos($arquivo[$count], ':') !== false) {

                            $linhaArray = explode(':', $arquivo[$count]);
                            $valores = trim(RemoveExcessoEspacos($linhaArray[1]));
                            $linhaArray = explode(' ', $valores);
                            $totalVenda += FormataMoedaParaReal($linhaArray[0]);
                            $desconto += FormataMoedaParaReal($linhaArray[1]);
                        }
                        $count++;
                    }

                    $this->pedidos[$countPedido]['desconto'] = $desconto;
                    $this->pedidos[$countPedido]['totalVenda'] = $totalVenda;
                    $countPedido++;
                }
            }
            return true;
        }
    }

}

?>
