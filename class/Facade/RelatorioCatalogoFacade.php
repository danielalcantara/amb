<?php

require_once pegarRaizSite() . 'class/DAO/PontoDao.php';

/**
 * Description of RelatorioCatalogoFacade
 *
 * @author Daniel
 */
class RelatorioCatalogoFacade {

    public function listarComboPontoPorCatalogo($idCatalogo) {
        $pontoDao = new PontoDao();
        $dadosCombo = $pontoDao->listarComboPontoPorCatalogo($idCatalogo);
        return $dadosCombo;
    }

}
