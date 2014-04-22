<?php

/**
 * Description of PedidoParcela
 *
 * @author Daniel
 */
class PedidoParcela {
    private $id;
    private $idPedido;
    private $valor;
    private $data;
    
    public function getId() {
        return $this->id;
    }

    public function getIdPedido() {
        return $this->idPedido;
    }

    public function getValor() {
        return $this->valor;
    }

    public function getData() {
        return $this->data;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function setIdPedido($idPedido) {
        $this->idPedido = $idPedido;
        return $this;
    }

    public function setValor($valor) {
        $this->valor = $valor;
        return $this;
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

}
