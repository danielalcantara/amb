<?php

/**
 * Description of PedidoParcela
 *
 * @author daniel.cordeiro
 */
class PedidoParcelaDao implements IGenericDao {

    private $con = null;

    function __construct() {
        $this->con = Conexao::getInstance();
    }

    public function listarPorPedido($idPedido, $returnObjetc = false) {
        $statement = "SELECT id, idPedido, valor, data FROM PedidoParcela WHERE idPedido = :IdPedido";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(':IdPedido', $idPedido);
        $result = Array();

        if ($stmt->execute()) {
            $result = $returnObjetc ? $stmt->fetchAll(PDO::FETCH_CLASS, "PedidoParcela") : $stmt->fetchAll();
        }

        return $result;
    }
    
    public function insert($parcela) {
        $statement = "INSERT INTO PedidoParcela (idPedido, valor, data) VALUES (?,?,?)";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(1, $parcela->getIdPedido());
        $stmt->bindValue(2, $parcela->getValor());
        $stmt->bindValue(3, $parcela->getData());

        return $stmt->execute();
    }

    public function update($parcela) {
        
    }
    
    public function delete($id) {
        $statement = "DELETE FROM PedidoParcela WHERE id = ?";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(1, $id);
        
        return $stmt->execute();
    }
    
    public function deletePorPedido($idPedido) {
        $statement = "DELETE FROM PedidoParcela WHERE idPedido = ?";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(1, $idPedido);
        
        return $stmt->execute();
    }

}
