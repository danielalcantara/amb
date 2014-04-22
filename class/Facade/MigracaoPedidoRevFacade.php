<?php

require_once pegarRaizSite() . 'class/DAO/RevendedorDao.php';
require_once pegarRaizSite() . 'class/DAO/PontoDao.php';

/**
 * Description of MigracaoPedidoRev
 *
 * @author Daniel
 */
class MigracaoPedidoRevFacade {

    public function burcarRevendedor($idRevendedor) {
        $revendedorDao = new RevendedorDao();
        $revendedor = $revendedorDao->buscar($idRevendedor);
        return $revendedor;
    }
    
    public function buscarPonto($idPonto) {
        $pontoDao = new PontoDao();
        $ponto = $pontoDao->buscar($idPonto);
        return $ponto;
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

}
