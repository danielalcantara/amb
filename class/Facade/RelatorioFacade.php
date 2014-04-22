<?php

require_once pegarRaizSite() . 'class/Conexao/Conexao.php';
require_once pegarRaizSite() . 'class/DAO/CatalogoDao.php';
require_once pegarRaizSite() . 'class/DAO/PedidoDao.php';

/**
 * Description of FacadeRelatorio
 *
 * @author Daniel
 */
class FacadeRelatorio {
    public function buscarCatalogo($idCatalogo) {
        $catalogoDao = new CatalogoDao();
        $catalogo = $catalogoDao->buscar($idCatalogo);
        
        return $catalogo;
    }
    
    public function listarPedidos($idCatalogo, $idPonto, $dataPedido, $dataEntrega) {
        $pedidoDao = new pedidoDao();
        $pedidos = $pedidoDao->listarPorCatalogoPontoData($idCatalogo, $idPonto, $dataPedido, $dataEntrega);
        
        return $pedidos;
    }
}
