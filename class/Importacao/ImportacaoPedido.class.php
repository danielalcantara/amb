<?php

/**
 * Description of Importacao
 *
 * @author Daniel Alcântara
 */
abstract class ImportacaoPedido {

    protected $arquivo = array();
    protected $idCatalogo;
    protected $revendedoresNovos = array();
    protected $revendedoresHomonimos = array();
    protected $falhas = array();
    protected $pedidos = array();
    protected $pedidosNaoGravados = array();
    protected $dataPedidos;
    protected $dataEntrega;

    function __construct($arq = "", $pIdCatalogo = 0, $pDataPedidos = null, $pDataEntrega = null) {
        $this->arquivo = $arq;
        $this->idCatalogo = $pIdCatalogo;
        $this->dataPedidos = $pDataPedidos;
        $this->dataEntrega = $pDataEntrega;
    }

    public function SetDataPedidos($dataPedidos) {
        $this->dataPedidos = $dataPedidos;
    }

    public function GetDataPedidos() {
        return $this->dataPedidos;
    }

    public function SetDataEntrega($dataEntrega) {
        $this->dataEntrega = $dataEntrega;
    }

    public function GetDataEntrega() {
        return $this->dataEntrega;
    }

    public function SetFalhas($falha) {
        $this->falhas[] = $falha;
    }

    public function GetFalhas() {
        return $this->falhas;
    }

    public function SetCatalogo($pIdCatalogo) {
        $this->idCatalogo = $pIdCatalogo;
    }

    public function GetCatalogo() {
        return $this->idCatalogo;
    }

    public function SetArquivo($arq) {
        $this->arquivo = $arq;
    }

    public function GetArquivo() {
        return $this->arquivo;
    }

    public function GetRevendedoresHonimos() {
        return $this->revendedoresHomonimos;
    }

    public function GetRevendedoresNovos() {
        return $this->revendedoresNovos;
    }

    public function BuscaIncRevendedor($nome, $codPedido, $like = false, $dadosRevendedor = array()) {
        $result = array();
        $idRevendedor = null;

        $dadosRevendedor['cpf'] = isset($dadosRevendedor['cpf']) ? $dadosRevendedor['cpf'] : '';
        $dadosRevendedor['endereco'] = isset($dadosRevendedor['endereco']) ? $dadosRevendedor['endereco'] : '';
        $dadosRevendedor['bairro'] = isset($dadosRevendedor['bairro']) ? $dadosRevendedor['bairro'] : '';
        $dadosRevendedor['cidade'] = isset($dadosRevendedor['cidade']) ? $dadosRevendedor['cidade'] : '';
        $dadosRevendedor['estado'] = isset($dadosRevendedor['estado']) ? $dadosRevendedor['estado'] : '';
        $dadosRevendedor['dataNascimento'] = isset($dadosRevendedor['dataNascimento']) ? FormatDataViewBD($dadosRevendedor['dataNascimento']) : '';
        $dadosRevendedor['telefone'] = isset($dadosRevendedor['telefone']) ? $dadosRevendedor['telefone'] : '';

        if ($like) {
            $sql = "SELECT cod_revendedor FROM revendedor WHERE nome LIKE '" . $nome . "%'";
        } else {
            $sql = "SELECT cod_revendedor FROM revendedor WHERE nome = '" . $nome . "'";
        }

        $sql .= $dadosRevendedor['cpf'] != '' ? " AND cpf = '" . $dadosRevendedor['cpf'] . "'" : '';
        $sql .= $dadosRevendedor['dataNascimento'] != '' ? " AND data_nascimento = '" . $dadosRevendedor['dataNascimento'] . "'" : '';
        $rs = mysql_query($sql);
        $result['nomeRevendedor'] = $nome;
        
        if (mysql_num_rows($rs) > 0) {
            $idRevendedor = mysql_result($rs, 0);
            if (mysql_num_rows($rs) > 1) {
                $this->revendedoresHomonimos[] = array('nomeRevendedor' => $nome, 'codPedido' => $codPedido);
            }
        } else {
            $sql = "INSERT INTO revendedor (nome, data_cadastro, cpf, logradouro, bairro, cidade, estado, data_nascimento, telefone) values ('";
            $sql .= $nome . "', NOW(), '" . $dadosRevendedor['cpf'] . "', '" . $dadosRevendedor['endereco'] . "', '" . $dadosRevendedor['bairro'];
            $sql .= "', '" . $dadosRevendedor['cidade'] . "', '" . $dadosRevendedor['estado'] . "', '";
            $sql .= $dadosRevendedor['dataNascimento'] . "', '" . $dadosRevendedor['telefone'] . "')";
            mysql_query($sql);
            $idRevendedor = mysql_insert_id();
            $this->revendedoresNovos[] = array('nomeRevendedor' => $nome, 'codPedido' => $codPedido);
        }
        
        return $idRevendedor;
        
    }

    public function GravarRegistros($pedidos) {
        $numRegistros = count($pedidos);
        for ($cont = 0; $cont < $numRegistros; $cont++) {
            $sql = "INSERT INTO `pedido` (IdCatalogo, IdRevendedor, CodPedido, TotalVendas, Desconto, DataCadastro, DataPedido, DataEntrega) ";
            $sql .= "VALUE (" . $this->GetCatalogo() . ", " . $pedidos[$cont]['idRevendedor'] . ", '";
            $sql .= $pedidos[$cont]['codPedido'] . "', " . $pedidos[$cont]['totalVenda'] . ", " . $pedidos[$cont]['desconto'];
            $sql .= ", NOW(), '" . $this->GetDataPedidos() . "', '" . $this->GetDataEntrega() . "');";
            mysql_query($sql);
            if (mysql_error()) {
                $this->pedidosNaoGravados[] = $pedidos[$cont];
            }
        }
    }

    public function Importar($arq = null) {
        if ($arq) {
            $arquivo = $arq;
        } else {
            $arquivo = $this->GetArquivo();
        }
        if ($this->LerArquivo($arquivo)) {
            if (count($this->pedidos) > 0) {
                $this->GravarRegistros($this->pedidos);
            }
        } else {
            $this->SetFalhas("Arquivo de importação inválido!");
        }
    }

    abstract function LerArquivo($arq);
}

?>
