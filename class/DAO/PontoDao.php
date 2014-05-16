<?php

require_once pegarRaizSite() . 'class/DAO/IGenericDao.php';

/**
 * Description of PontoDao
 *
 * @author daniel.cordeiro
 */
class PontoDao implements IGenericDao {

    private $con = null;

    function __construct() {
        $this->con = Conexao::getInstance();
    }
    
    public function buscar($id, $returnObjetc = false) {
        $result = Array();
        if ($id) {
            $statement = "SELECT * FROM pontos WHERE cod_ponto = :CodPonto";
            $stmt = $this->con->prepare($statement);
            $stmt->bindValue(':CodPonto', $id);

            if ($stmt->execute()) {
                $result = $returnObjetc ? $stmt->fetchObject() : $stmt->fetch();
            }
        }

        return $result;
    }

    public function listarCombo($returnObjetc = false) {
        $statement = "SELECT cod_ponto, nome_ponto FROM pontos";
        $stmt = $this->con->prepare($statement);
        $result = Array();

        if ($stmt->execute()) {
            $result = $returnObjetc ? $stmt->fetchAll(PDO::FETCH_CLASS, "Ponto") : $stmt->fetchAll();
        }

        return $result;
    }

    public function listarComboPontoPorCatalogo($idCatalogo, $returnObjetc = false) {
        $statement = "SELECT cod_ponto, nome_ponto FROM pontos ";
        $statement .= "WHERE cod_ponto IN (SELECT pt.cod_ponto FROM pedido AS pd ";
        $statement .= "INNER JOIN revendedor AS r ON r.cod_revendedor = pd.IdRevendedor ";
        $statement .= "INNER JOIN pontos AS pt ON pt.cod_ponto = r.cod_ponto ";
        $statement .= "WHERE pd.IdCatalogo = ?)";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(1, $idCatalogo);
        $result = Array();

        if ($stmt->execute()) {
            $result = $returnObjetc ? $stmt->fetchAll(PDO::FETCH_CLASS, "Ponto") : $stmt->fetchAll();
        }

        return $result;        
    }

    public function delete($id) {
        
    }

    public function insert($model) {
        
    }

    public function update($model) {
        
    }

}
