<?php

require_once pegarRaizSite() . 'class/DAO/IGenericDao.php';

/**
 * Description of pedidoDao
 *
 * @author daniel.cordeiro
 */
class pedidoDao implements IGenericDao {

    private $con = null;

    function __construct() {
        $this->con = Conexao::getInstance();
    }

    public function insert($pedido) {
        $statement = "INSERT INTO pedido (CodPedido, IdCatalogo, IdRevendedor, TotalVendas, DataPedido, DataEntrega, Desconto, Situacao, "
                . "DataSituacao, DataCadastro) VALUES (?,?,?,?,?,?,?,?,?,NOW())";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(1, $pedido->getCodPedido());
        $stmt->bindValue(2, $pedido->getIdCatalogo());
        $stmt->bindValue(3, $pedido->getIdRevendedor());
        $stmt->bindValue(4, $pedido->getTotalVendas());
        $stmt->bindValue(5, $pedido->getDataPedido());
        $stmt->bindValue(6, $pedido->getDataEntrega());
        $stmt->bindValue(7, $pedido->getDesconto());
        $stmt->bindValue(8, $pedido->getSituacao());
        $stmt->bindValue(9, $pedido->getDataSituacao());

        return $stmt->execute();
    }

    public function update($pedido) {
        $statement = "UPDATE pedido SET CodPedido = ?, IdCatalogo = ?, IdRevendedor = ?, TotalVendas = ?,";
        $statement .= "DataPedido = ?, DataEntrega = ?, Desconto = ?, DataAtualizacao = NOW(), Situacao = ?, DataSituacao = ? "
                . "WHERE Id = ?";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(1, $pedido->getCodPedido());
        $stmt->bindValue(2, $pedido->getIdCatalogo());
        $stmt->bindValue(3, $pedido->getIdRevendedor());
        $stmt->bindValue(4, $pedido->getTotalVendas());
        $stmt->bindValue(5, $pedido->getDataPedido());
        $stmt->bindValue(6, $pedido->getDataEntrega());
        $stmt->bindValue(7, $pedido->getDesconto());
        $stmt->bindValue(8, $pedido->getSituacao());
        $stmt->bindValue(9, $pedido->getDataSituacao());
        $stmt->bindValue(10, $pedido->getId());

        return $stmt->execute();
    }

    public function delete($model) {
        
    }

    public function buscar($id, $returnObject = false) {
        $statement = "SELECT Id, CodPedido, IdCatalogo, IdRevendedor, TotalVendas, Desconto, DataCadastro, "
                . "DataPedido, DataEntrega, DataAtualizacao, Situacao, DataSituacao FROM pedido WHERE Id = :Id";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue(':Id', $id);
        $result = array();

        if ($stmt->execute()) {
            $result = $returnObject ? $stmt->fetchObject() : $stmt->fetch();
        }

        return $result;
    }

    public function listarPorCatalogoPontoData($idCatalogo, $idPonto, $dataPedido, $dataEntrega, $returnObjetc = false) {
        $statement = "SELECT CodPedido, r.nome, r.telefone, TotalVendas, Desconto, TotalVendas - Desconto AS valor, Situacao";
        $statement .= ", nome_ponto, pt.cod_ponto FROM pedido AS p ";
        $statement .= "LEFT JOIN revendedor AS r ON p.IdRevendedor = r.cod_revendedor ";
        $statement .= "LEFT JOIN pontos AS pt ON pt.cod_ponto = r.cod_ponto ";
        $statement .= "WHERE IdCatalogo = :IdCatalogo";

        if ($dataPedido != '' or $dataEntrega != '') {
            $condicao = ($dataPedido != '' and $dataEntrega != '') ? 'AND' : 'OR';
            $statement .= " AND DataPedido = :DataPedido " . $condicao . " DataEntrega = :DataEntrega";
        }

        if ($idPonto) {
            $statement .= " AND r.cod_ponto = :IdPonto";
            $statement .= " ORDER BY r.nome";
        } else {
            $statement .= " ORDER BY nome_ponto, r.nome";
        }
        
        $stmt = $this->con->prepare($statement);
        
        $stmt->bindValue(':IdCatalogo', $idCatalogo);
        
        if ($dataPedido != '' or $dataEntrega != '') {
            $stmt->bindValue(':DataPedido', $dataPedido);
            $stmt->bindValue(':DataEntrega', $dataEntrega);
        }
        
        if($idPonto) {
            $stmt->bindValue(':IdPonto', $idPonto);
        }
        
        $result = Array();

        if ($stmt->execute()) {
            $result = $returnObjetc ? $stmt->fetchAll(PDO::FETCH_CLASS, "Pedido") : $stmt->fetchAll();
        }

        return $result;
    }
    
    public function migrarPorRevendedor($idRevendedorOrigem, $idRevendedorDestino) {
        $statement = "UPDATE pedido SET IdRevendedor = :IdRevendedorDestino WHERE IdRevendedor = :IdRevendedorOrigem";
        $stmt = $this->con->prepare($statement);
        $stmt->bindValue('IdRevendedorOrigem', $idRevendedorOrigem);
        $stmt->bindValue('IdRevendedorDestino', $idRevendedorDestino);
        
        return $stmt->execute();
    }

}
