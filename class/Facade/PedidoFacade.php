<?php

require_once pegarRaizSite() . 'class/DAO/PontoDao.php';
require_once pegarRaizSite() . 'class/DAO/PedidoDao.php';
require_once pegarRaizSite() . 'class/DAO/PedidoParcelaDao.php';
require_once pegarRaizSite() . 'class/BO/PedidoBO.php';
require_once pegarRaizSite() . 'class/DAO/RevendedorDao.php';

/**
 * Description of PedidoFacade
 *
 * @author Daniel
 */
class PedidoFacade {

    public function CadastrarPedido($pedido) {
        $pedidoBO = new PedidoBO();
        return $pedidoBO->Cadastrar($pedido);
    }

    public function listarComboPonto() {
        $pontoDao = new PontoDao();
        $dadosCombo = $pontoDao->listarCombo();

        return $dadosCombo;
    }

    public function listarComboRevendedor($idPonto) {
        $revendedorDao = new RevendedorDao();
        $dadosCombo = $revendedorDao->listarCombo($idPonto);

        return $dadosCombo;
    }

    public function buscarInfoRevendedor($idRevendedor) {
        $revendedorDao = new RevendedorDao();
        $dados = $revendedorDao->buscar($idRevendedor);

        return $dados;
    }

    public function buscarPedido($idPedido) {
        $pedidoDao = new PedidoDao();
        $revendedorDao = new RevendedorDao();
        $pedido = $pedidoDao->buscar($idPedido);
        $revendedor = $revendedorDao->buscar($pedido['IdRevendedor']);
        $pedido[] = $revendedor['cod_ponto'];

        return $pedido;
    }

    public function listarParcelas($idPedido) {
        $pedidoParcelaDao = new PedidoParcelaDao();
        $parcelas = $pedidoParcelaDao->listarPorPedido($idPedido);
        return $parcelas;
    }

}
