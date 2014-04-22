<?php

class Util {

    // Guarda uma instância da classe
    static private $instance;
    // Atributos da classe
    private $msgSucesso = null;
    private $msgErro = null;
    private $raizSite = null;
    private $estados = Array('AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG'
        , 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO');

    // Um construtor privado
    private function __construct() {
        
    }

    // O método singleton 
    static public function getInstance() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    // Metódos para setar e retornar a raiz dos arquivos do sistema
    public function getRaizArquivos() {
        $pastaRaiz = "inc";
        $raiz = '';
        $caminho = $_SERVER["PHP_SELF"];
        $contadorChars = count_chars($caminho, 1);
        $numBarras = $contadorChars[47];

        for ($cont = 1; $cont <= $numBarras; $cont++) {
            if (file_exists($raiz . $pastaRaiz)) {
                break;
            } else {
                $raiz .= '../';
            }
        }

        return $raiz;
    }

    // Métodos para setar e retornar raiz do site que está na sessão do usuário
    public function setRaizSite($raizSite) {
        $this->raizSite = $raizSite;
    }

    public function getRaizSite() {
        return $this->raizSite;
    }

    // Métodos para para setar e retornar o menu corrente
    public function setMenuCorrente($menu) {
        $_SESSION['menuCorrent'] = $menu;
    }

    public function getMenuCorrente() {
        return $_SESSION['menuCorrent'];
    }

    // Método para setar mensagem de sucesso
    public function setMsgSucesso($msg) {
        $this->msgSucesso = $msg;
        $this->msgErro = null;
    }

    public function getMsgSucesso() {
        return $this->msgSucesso;
    }

    public function setMsgErro($msg) {
        $this->msgSucesso = null;
        $this->msgErro = $msg;
    }

    public function getMsgErro() {
        return $this->msgErro;
    }

    public function checkMensagem() {
        $msg = null;

        if ($this->getMsgErro()) {
            $msg = '<p class="msgErro">Erro: ' . $this->getMsgErro() . '</p>';
        } elseif ($this->getMsgSucesso()) {
            $msg = '<p class="msgSucesso">' . $this->getMsgSucesso() . '</p>';
        }
        $this->msgErro = null;
        $this->msgSucesso = null;
        return $msg;
    }

    // Metódos para gerar os formulários de ediçãoe exclusão de registro nas páginas de listagem
    public function CriarFormDeletar($id, $filtro, $pagina) {
        $form = '<form action="" method="post" onsubmit="return ConfirmaExcReg();" class="formAcao">';
        $form .= '<input type="hidden" name="filtro" value="' . $filtro . '">';
        $form .= '<input type="hidden" name="pagina" value="' . $pagina . '">';
        $form .= '<input type="hidden" name="acao" value="deletar">';
        $form .= '<input type="hidden" name="id" value="' . $id . '">';
        $form .= '<input type="image" src="' . $this->getRaizSite() . 'img/icones/delete.png" class="botao" title="Deletar" alt="Deletar">';
        $form .= '</form>';
        return $form;
    }

    public function CriarFormEditar($id, $filtro, $pagina) {
        $form = '<form action="editar.php" method="post" class="formAcao">';
        $form .= '<input type="hidden" name="id" value="' . $id . '">';
        $form .= '<input type="hidden" name="filtro" value="' . $filtro . '">';
        $form .= '<input type="hidden" name="pagina" value="' . $pagina . '">';
        $form .= '<input type="image" src="' . $this->getRaizSite() . 'img/icones/editar.png" class="botao" title="Editar" alt="Editar">';
        $form .= '</form>';
        return $form;
    }

    public function montarComboBox($valor, $display, $tabela, $id = 0, $obrigatorio = false) {
        
        $class = $obrigatorio ? 'class="obr"' : '';
        
        $combo = '<select ' . $class . ' name="' . $valor . '" id="' . $valor . '">';
        $combo .= '<option value="">Selecione</option>';
        $sql = "SELECT " . $valor . ", " . $display . " FROM " . $tabela . " ORDER BY " . $display;
        $rs = mysql_query($sql);
        while ($linha = mysql_fetch_assoc($rs)) {
            if ($linha[$valor] == $id) {
                $combo .= '<option value="' . $linha[$valor] . '" selected="selected">' . $linha[$display] . '</option>';
            } else {
                $combo .= '<option value="' . $linha[$valor] . '">' . $linha[$display] . '</option>';
            }
        }
        $combo .= '</select>';
        return $combo;
    }

    public function montarComboBoxEstado($estado = null, $obrigatorio = false) {
        
        $class = $obrigatorio ? 'class="obr selecUF"' : 'class="selecUF"';
        
        $combo = '<select ' . $class . ' name="estado" id="estado">';
        $combo .= '<option value="">--</option>';
        for ($cont = 0; $cont < count($this->estados); $cont++) {
            if ($estado == $this->estados[$cont]) {
                $combo .= '<option value="' . $this->estados[$cont] . '" selected="selected">' . $this->estados[$cont] . '</option>';
            } else {
                $combo .= '<option value="' . $this->estados[$cont] . '">' . $this->estados[$cont] . '</option>';
            }
        }
        $combo .= '</select>';

        return $combo;
    }

    public function montarComboBoxSexo($sexo = null, $obrigatorio = false) {
        
        $class = $obrigatorio ? 'class="obr sexo"' : 'class="sexo"';
        
        $combo = '<select ' . $class . ' name="sexo" id="sexo">';
        $combo .= '<option value="">--</option>';
        
        if ($sexo == 'M') {
            $combo .= '<option value="M" selected="selected">M</option>';
        } else {
            $combo .= '<option value="M">M</option>';
        }
        
        if ($sexo == 'F') {
            $combo .= '<option value="F" selected="selected">F</option>';
        } else {
            $combo .= '<option value="F">F</option>';
        }
        
        $combo .= '</select>';

        return $combo;
    }

    public function alertaPaginaConstrucao() {
        $alerta = '<div id="construcao">';
        $alerta .= '<p>';
        $alerta .= '<img src="' . $this->getRaizSite() . 'img/icones/construcao.png" title="Página em constução" alt="Página em constução">';
        $alerta .= '</p>';
        $alerta .= '<p>';
        $alerta .= '<span class="alertaConstrucao">Página em construção!</span>';
        $alerta .= '</p>';
        $alerta .= '</div>';

        return $alerta;
    }

    public function getTopoPagina($cfg = null) {
        $menuCorrente = 'current';
        $classMenu1 = $cfg['menuCorrente'] == 1 ? $menuCorrente : '';
        $classMenu2 = $cfg['menuCorrente'] == 2 ? $menuCorrente : '';
        $classMenu3 = $cfg['menuCorrente'] == 3 ? $menuCorrente : '';
        $classMenu4 = $cfg['menuCorrente'] == 4 ? $menuCorrente : '';
        
        $cabecalho = '<body">';
        $cabecalho .= '<div id="corpoPagina">';
        $cabecalho .= '<div id="topo">';
        $cabecalho .= '<img alt="SDC" title="SDC" src="' . $this->getRaizSite() . 'img/topo/logo.png" />';
        $cabecalho .= '</div>';
        $cabecalho .= '<div id="meio">';
        $cabecalho .= '<div id="navBar">';
        $cabecalho .= '<ul id="nav">';
        $cabecalho .= '<li class="' . $classMenu1 . '">';
        $cabecalho .= '<a href="#">Cadastro</a>';
        $cabecalho .= '<ul>';
        $cabecalho .= '<li><a href="' . $this->getRaizSite() . 'modulos/catalogo/index.php">Catálogo</a></li>';
        //$cabecalho .= '<li><a href="' . $this->getRaizSite() . 'modulos/distribuidor/index.php"><span>Distribuidor</span></a></li>';
        $cabecalho .= '<li><a href="' . $this->getRaizSite() . 'modulos/pedido/index.php">Pedido</a></li>';
        $cabecalho .= '<li><a href="' . $this->getRaizSite() . 'modulos/ponto/index.php">Ponto</a></li>';
        $cabecalho .= '<li><a href="' . $this->getRaizSite() . 'modulos/revendedor/index.php">Revendedor</a></li>';
        $cabecalho .= '</ul>';
        $cabecalho .= '</li>';
        $cabecalho .= '<li class="' . $classMenu2 . '"><a href="' . $this->getRaizSite() . 'modulos/importacao/index.php">Importação</a></li>';
        $cabecalho .= '<li class="' . $classMenu3 . '"><a href="#" class="parent last">Relatórios</a>';
        $cabecalho .= '<ul>';
        $cabecalho .= '<li><a href="' . $this->getRaizSite() . 'modulos/relatorio/catalogoPonto/index.php">Catálogo por ponto</a></li>';
        $cabecalho .= '<li><a href="' . $this->getRaizSite() . 'modulos/relatorio/revendedor/index.php">Pedidos por Revendedor</a></li>';
        $cabecalho .= '</ul>';
        $cabecalho .= '</li>';
        $cabecalho .= '<li class="' . $classMenu4 . '"><a href="#" class="parent last">Ferramentas</a>';
        $cabecalho .= '<ul>';
        $cabecalho .= '<li><a href="' . $this->getRaizSite() . 'modulos/migracaoPedidoRev/index.php">Migração Pedidos por Revendedor</a></li>';
        $cabecalho .= '</ul>';
        $cabecalho .= '</li>';
        $cabecalho .= '</ul>';
        $cabecalho .= '</div>';
        $cabecalho .= '<div id="paginas">';

        echo $cabecalho;
    }

}
