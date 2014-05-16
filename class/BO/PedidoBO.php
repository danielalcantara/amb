<?php

require_once pegarRaizSite() . 'class/DAO/PedidoDao.php';
require_once pegarRaizSite() . 'class/DAO/PedidoParcelaDao.php';

/**
 * Description of PedidoBO
 *
 * @author Daniel
 */
class PedidoBO {
    private $pedido;

    public function Cadastrar($pedido) {
        $this->pedido = $pedido;
        $totalVenda = $this->pedido->getTotalVendas() - $this->pedido->getDesconto();
        $totalParcelas = $this->pedido->getTotalParcelas();
        $sucess = true;
        if (($totalParcelas > $totalVenda and !floatcmp($totalParcelas, $totalVenda)) or ($this->pedido->getSituacao() != 'aberto' 
                and !$this->pedido->getDataSituacao()) or ($this->pedido->getSituacao() == 'finalizado' and
                        ($totalParcelas < $totalVenda) and !floatcmp($totalParcelas, $totalVenda))) {
            $sucess = false;
        }
        if ($sucess) {
            $pedidoDao = new PedidoDao();
            Conexao::getInstance()->beginTransaction();
            try {
                $this->persistePedido($sucess, $this->pedido, $pedidoDao);
                $this->persisteParcelas($this->pedido->getParcelas());
            } catch (PDOException $ex) {
                echo "Erro ao cadastrar o pedido. Mensagem de erro: " . $ex->getMessage() . " - Código: " . $ex->getCode();
                Conexao::getInstance()->rollBack();
                $sucess = false;
            }
            Conexao::getInstance()->commit();
        }
        return $sucess;
    }

    private function persistePedido(&$sucess, &$pedido, &$pedidoDao) {
        if ($pedido->getId()) {
            $sucess = $pedidoDao->update($pedido);
        } else {
            $sucess = $pedidoDao->insert($pedido);
            $pedido->setId(Conexao::getInstance()->lastInsertId());
        }
    }

    private function persisteParcelas($parcelas) {
        $pedidoParcelaDao = new PedidoParcelaDao();
        $pedidoParcelaDao->deletePorPedido($this->pedido->getId());
        foreach ($parcelas as $parcela) {
            $parcela->setIdPedido($this->pedido->getId());
            $pedidoParcelaDao->insert($parcela);
        }
    }

}
