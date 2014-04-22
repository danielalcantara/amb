<?php

/**
 * Description of RevendedorDao
 *
 * @author daniel.cordeiro
 */
class RevendedorDao {

    private $con = null;

    function __construct() {
        $this->con = Conexao::getInstance();
    }

    public function buscar($id, $returnObjetc = false) {
        $result = Array();
        if ($id) {
            $statement = "SELECT cod_revendedor, nome, data_nascimento, sexo, cpf, rg, telefone, cod_ponto"
                    . " FROM revendedor WHERE cod_revendedor = :CodRevendedor";
            $stmt = $this->con->prepare($statement);
            $stmt->bindValue(':CodRevendedor', $id);

            if ($stmt->execute()) {
                $result = $returnObjetc ? $stmt->fetchObject() : $stmt->fetch();
            }
        }

        return $result;
    }

    public function listarCombo($idPonto, $returnObjetc = false) {
        $statement = "SELECT cod_revendedor, nome FROM revendedor WHERE cod_ponto = :IdPonto ";
        $statement .= "ORDER BY nome";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(':IdPonto', $idPonto);
        $result = Array();

        if ($stmt->execute()) {
            $result = $returnObjetc ? $stmt->fetchAll(PDO::FETCH_CLASS, "Revendedor") : $stmt->fetchAll();
        }

        return $result;
    }

}
