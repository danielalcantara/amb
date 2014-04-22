<?php

/**
 * Description of Pedido
 *
 * @author Daniel
 */
class Pedido {

    private $Id;
    private $CodPedido;
    private $IdCatalogo;
    private $IdRevendedor;
    private $TotalVendas;
    private $Desconto;
    private $DataPedido;
    private $DataEntrega;
    private $DataAtualizacao;
    private $DataCadastro;
    private $Situacao;
    private $DataSituacao;
    private $parcelas = Array();
    
    public function getParcelas() {
        return $this->parcelas;
    }

    public function setParcelas($parcela) {
        $this->parcelas[] = $parcela;
        return $this;
    }

    public function getId() {
        return $this->Id;
    }

    public function getCodPedido() {
        return $this->CodPedido;
    }

    public function getIdCatalogo() {
        return $this->IdCatalogo;
    }

    public function getIdRevendedor() {
        return $this->IdRevendedor;
    }

    public function getTotalVendas() {
        return $this->TotalVendas;
    }

    public function getDesconto() {
        return $this->Desconto;
    }

    public function getDataPedido() {
        return $this->DataPedido;
    }

    public function getDataEntrega() {
        return $this->DataEntrega;
    }

    public function getDataAtualizacao() {
        return $this->DataAtualizacao;
    }

    public function getDataCadastro() {
        return $this->DataCadastro;
    }

    public function getSituacao() {
        return $this->Situacao;
    }

    public function getDataSituacao() {
        return $this->DataSituacao;
    }

    public function setId($Id) {
        $this->Id = $Id;
        return $this;
    }

    public function setCodPedido($CodPedido) {
        $this->CodPedido = $CodPedido;
        return $this;
    }

    public function setIdCatalogo($IdCatalogo) {
        $this->IdCatalogo = $IdCatalogo;
        return $this;
    }

    public function setIdRevendedor($IdRevendedor) {
        $this->IdRevendedor = $IdRevendedor;
        return $this;
    }

    public function setTotalVendas($TotalVendas) {
        $this->TotalVendas = $TotalVendas;
        return $this;
    }

    public function setDesconto($Desconto) {
        $this->Desconto = $Desconto;
        return $this;
    }

    public function setDataPedido($DataPedido) {
        $this->DataPedido = $DataPedido;
        return $this;
    }

    public function setDataEntrega($DataEntrega) {
        $this->DataEntrega = $DataEntrega;
        return $this;
    }

    public function setDataAtualizacao($DataAtualizacao) {
        $this->DataAtualizacao = $DataAtualizacao;
        return $this;
    }

    public function setDataCadastro($DataCadastro) {
        $this->DataCadastro = $DataCadastro;
        return $this;
    }

    public function setSituacao($Situacao) {
        $this->Situacao = $Situacao;
        return $this;
    }

    public function setDataSituacao($DataSituacao) {
        $this->DataSituacao = $DataSituacao;
        return $this;
    }
    
    public function getTotalParcelas() {
        $totalParcelas = 0;
        foreach ($this->getParcelas() as $parcela) {
            $totalParcelas += $parcela->getValor();
        }
        return $totalParcelas;
    }

}
