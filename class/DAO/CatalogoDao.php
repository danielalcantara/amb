<?php

require_once pegarRaizSite() . 'class/DAO/IGenericDao.php';

/**
 * Description of CatalogoDao
 *
 * @author Daniel
 */
class CatalogoDao implements IGenericDao {

    private $con = null;

    function __construct() {
        $this->con = Conexao::getInstance();
    }

    public function delete($id) {
        
    }

    public function insert($model) {
        
    }

    public function update($model) {
        
    }

    public function buscar($id, $returnObject = false) {
        $statement .= "SELECT cod_catalogo, cod_fabrica, numero_catalogo, identificacao, descricao "
                . "FROM catalogos WHERE cod_catalogo = ?";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(1, $id);
        $result = array();

        if ($stmt->execute()) {
            $result = $returnObject ? $stmt->fetchObject() : $stmt->fetch();
        }

        return $result;
    }

}
