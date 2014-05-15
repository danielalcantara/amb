<?php

function anti_injection($obj) {
    //$obj = preg_replace(sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"), "" ,$sql);
    $obj = trim($obj);
    $obj = strip_tags($obj);
    $obj = mysql_real_escape_string($obj);
    return $obj;
}

function FormatDataBDView($dataBD) {
    $dataArray = explode('-', $dataBD);

    if (count($dataArray) == 3) {
        $data = $dataArray[2] . '/' . $dataArray[1] . '/' . $dataArray[0];
        return $data;
    }

    return false;
}

function FormatDataViewBD($dataView) {
    $dataArray = explode('/', $dataView);

    if (count($dataArray) == 3) {
        $data = $dataArray[2] . '-' . $dataArray[1] . '-' . $dataArray[0];
        return $data;
    }

    return false;
}

function Paginacao($pagina, $totalPaginas, $filtro) {
    $intervaloPag = 4;
    $paginaAnterior = $pagina - 1;
    $paginaSeguinte = $pagina + 1;
    $paginacao = '<div id="paginacao">';
    $paginacao .= '<form name="paginacaoForm" id="paginacao" method="post" action="index.php">';
    $paginacao .= '<input type="hidden" name="filtroHidden" id="filtroHidden" value="' . $filtro . '">';
    $paginacao .= '<input type="hidden" name="pagina" id="pagina" value="' . $pagina . '">';

    if (($pagina < $totalPaginas and $pagina - $intervaloPag <= 0) or ($totalPaginas < $intervaloPag)) {
        $iniPaginacao = 1;
    } else {
        $iniPaginacao = $pagina - $intervaloPag;
    }

    $fimPaginacao = $pagina + $intervaloPag;

    if ($fimPaginacao > $totalPaginas) {
        $fimPaginacao = $totalPaginas;
    }

    if ($pagina > 1) {
        if ($totalPaginas > $intervaloPag) {
            $paginacao .= '<a href="#" onclick="SetaPagina(1);"><img src="' . pegarRaizSite() . 'img/icones/pagination_first.png" class="inconPaginacao" ';
            $paginacao .= 'alt="Início" title="Início"></a>';
            $paginacao .= '&nbsp;';
        }
        $paginacao .= '<a href="#" onclick="SetaPagina(' . $paginaAnterior . ');"><img src="' . pegarRaizSite() . 'img/icones/pagination_previous.png" ';
        $paginacao .= 'class="inconPaginacao" ';
        $paginacao .= 'alt="Anterior" title="Anterior"></a>|';
    }

    for ($iniPaginacao; $iniPaginacao <= $fimPaginacao; $iniPaginacao++) {
        if ($iniPaginacao == $pagina) {
            $paginacao .= '<a class="linkPaginacaoInativo">' . $iniPaginacao . '</a> ';
        } else {
            $paginacao .= '<a class="linkPaginacaoAtivo" href="#" onclick="SetaPagina(' . $iniPaginacao . ');">' . $iniPaginacao . '</a> ';
        }
    }

    if ($pagina < $fimPaginacao) {
        $paginacao .= '|<a href="#" onclick="SetaPagina(' . $paginaSeguinte . ');"><img src="' . pegarRaizSite() . 'img/icones/pagination_next.png" ';
        $paginacao .= 'class="inconPaginacao" alt="Próxima" title="Próxima"></a>';
        if ($totalPaginas > $intervaloPag) {
            $paginacao .= '&nbsp;';
            $paginacao .= '<a href="#" onclick="SetaPagina(' . $totalPaginas . ');"><img src="' . pegarRaizSite() . 'img/icones/pagination_last.png" ';
            $paginacao .= 'class="inconPaginacao" alt="Fim" title="Fim"></a>';
        }
    }

    $paginacao .= "</form>";
    $paginacao .= "</div>";
    echo $paginacao;
}

function validaDataView($dat) {
    if ($dat and strpos($dat, '/')) {
        $data = explode("/", $dat); // fatia a string $dat em pedados, usando / como referência
        $d = $data[0];
        $m = $data[1];
        $y = $data[2];

        // verifica se a data é válida!
        // 1 = true (válida)
        // 0 = false (inválida)
        return checkdate($m, $d, $y);
    }
    return false;
}

function RedirecPagina($url, $tempo = 0) {
    $url = str_replace('&amp;', '&', $url);

    if ($tempo > 0) {
        header("Refresh: $tempo; URL=$url");
    } else {
        header("Location: $url");
        exit;
    }
}

// Função para transformar strings em Maiúscula com acentos
// $palavra = a string propriamente dita
function ConvertMaiusculo($term) {
    $palavra = strtr(strtoupper($term), "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ", "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    return $palavra;
}

// Função para transformar strings em Minúscula com acentos
// $palavra = a string propriamente dita
function ConvertMinusculo($term) {
    $palavra = strtr(strtolower($term), "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß", "àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
    return $palavra;
}

function FormataMoedaParaReal($valor) {
    if ($valor) {
        if (strpos($valor, '.') and strpos($valor, ',')) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        } elseif (strpos($valor, ',')) {
            $valor = str_replace(',', '.', $valor);
        }
        $valor = (double) $valor;
    }
    return $valor;
}

function FormataRealParaMoeda($valor) {
    if ($valor) {
        $valor = number_format($valor, 2, ',', '.');
    }
    return $valor;
}

function pegarRaizSite() {
    $raiz = '';
    $caminho = $_SERVER['PHP_SELF'];
    $contador = count_chars($caminho, 1);
    $barras = $contador["47"];

    while ($barras > 0) {
        if (file_exists($raiz . "inc/funcoes.php")) {
            break;
        }
        $raiz = "../" . $raiz;
        $barras--;
    }
    return $raiz;
}

function infoUsuario() {
    $sql = "select uid from tbAdmSessao where Id='" . session_id() . "' and Logado = 1";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
        $uid = mysql_result($result, 0);
        $sql = "select Id, Nome, Login "
                . " from tbAdmUsuario where Id=" . $uid;
        $result = mysql_query($sql);
        $retorno = mysql_fetch_array($result);
    } else {
        return false;
    }

    return $retorno;
}

function ListaFalhas($falhas) {
    $tabListagem = "<table>";
    $tabListagem .= "<tr>";
    $tabListagem .= "<th style='width: 10%;'>Ordem</th>";
    $tabListagem .= "<th>Falha</th>";
    $tabListagem .= "</tr>";
    $cont = 1;
    foreach ($falhas as $falha) {
        $tabListagem .= "<tr>";
        $tabListagem .= "<td style='text-align: center;'>" . $cont . "</td>";
        $tabListagem .= "<td>" . $falha . "</td>";
        $tabListagem .= "</tr>";
        $cont++;
    }
    $tabListagem .= "</table>";
    return $tabListagem;
}

function RemoveExcessoEspacos($string) {
    $string = trim(preg_replace('/[[:space:]]+/', ' ', $string));
    return $string;
}

function filterPost($var, $idArray = false) {
    if ($var and $idArray) {
        $var = filter_input(INPUT_POST, $var, FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    } elseif ($var) {
        $var = filter_input(INPUT_POST, $var, FILTER_SANITIZE_STRING);
    }

    $var = empty($var) ? false : $var;

    return $var;
}

function filterGet($var, $idArray = false) {
    if ($var and $idArray) {
        $var = filter_input(INPUT_GET, $var, FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    } elseif ($var) {
        $var = filter_input(INPUT_GET, $var, FILTER_SANITIZE_STRING);
    }

    $var = empty($var) ? false : $var;

    return $var;
}

function filterServer($var, $idArray = false) {
    if ($var and $idArray) {
        $var = filter_input(INPUT_SERVER, $var, FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
    } elseif ($var) {
        $var = filter_input(INPUT_SERVER, $var, FILTER_SANITIZE_STRING);
    }

    $var = empty($var) ? false : $var;

    return $var;
}

/**
 * Função para montar combobox HTML
 * @param type $tabela Nome da tabela no banco de dados
 * @param type $campoChave Campo com o valor do id do registro
 * @param type $camposValor Campo com o valor de descrição do registro
 * @param type $cfg Parametros de configuração para o combo
 * id => No caso de página de editção é usado para informar o valor que já se encontra no registro
 * class => Classes css para personalizar o combo (colocá-las entre espaços)
 */
function montaCombo($tabela, $campoChave, $camposValor, $cfg = Array()) {
    $class = isset($cfg['class']) ? $cfg['class'] : null;
    $event = isset($cfg['event']) ? $cfg['event'] : null;
    $id = isset($cfg['id']) ? $cfg['id'] : null;
    $order = isset($cfg['order']) ? $cfg['order'] : null;
    $campoOrder = isset($cfg['campoOrder']) ? $cfg['campoOrder'] : null;
    $optionComplemento = isset($cfg['optionComplemento']) ? $cfg['optionComplemento'] : null;
    $filtro = isset($cfg['filtro']) ? ' WHERE ' . $cfg['filtro'] : '';
    $sql = "SELECT " . $campoChave . ", " . $camposValor . " FROM " . $tabela . $filtro . " ORDER BY ";
    if (!$campoOrder and strpos($camposValor, ',')) {
        $camposValorArray = explode(',', $camposValor);
        $sql .= $camposValorArray[0];
    } elseif ($campoOrder) {
        $sql .= $campoOrder;
    } else {
        $sql .= $camposValor;
    }
    $sql .= ' ' . $order;
    $rs = mysql_query($sql);
    $options = (is_null($id) or $id == '') ? '<option value="">Selecione</option>' : '';
    while ($linha = mysql_fetch_array($rs, MYSQL_NUM)) {
        $options .= '<option ';
        if ($linha[0] == $id) {
            $options .= 'selected="selected" ';
        }
        $options .= 'value="' . $linha[0] . '">' . $optionComplemento . ' ' . $linha[1];
        if (count($linha) > 2) {
            for ($cont = 2; $cont < count($linha); $cont++) {
                $options .= ' - ' . $linha[$cont];
            }
        }
        $options .= '</option>';
    }
    $combo = '<select id="' . $campoChave . '" name="' . $campoChave . '" class="' . trim($class) . '" ' . $event . '>';
    $combo .= $options;
    $combo .= '</select>';
    echo $combo;
}

function montaComboNovo($dados, $cfg) {
    $id = isset($cfg['id']) ? $cfg['id'] : '';
    $name = isset($cfg['name']) ? $cfg['name'] : '';
    $idOption = isset($cfg['idOption']) ? $cfg['idOption'] : '';
    $atributos = isset($cfg['atributos']) ? $cfg['atributos'] : '';
    $obrigatorio = isset($cfg['obrigatorio']) ? 'class="obr"' : '';
    $optionDescricao = isset($cfg['optionDescricao']) ? $cfg['optionDescricao'] : '';
    $comboFiltro = isset($cfg['comboFiltro']) ? $cfg['comboFiltro'] : '';

    $options = '';

    if ($comboFiltro) {
        $options = '<option value="">Todos</option>';
    } elseif (is_null($idOption) or $idOption == '') {
        $options = '<option value="">Selecione</option>';
    }

    foreach ($dados as $linha) {
        $options .= '<option ';
        if ($linha[0] == $idOption) {
            $options .= 'selected="selected" ';
        }
        $options .= 'value="' . $linha[0] . '">' . $optionDescricao . ' ' . utf8_encode($linha[1]);
        if (count($linha) > 4) {
            for ($cont = 2; $cont < (count($linha) / 2); $cont++) {
                $options .= ' - ' . utf8_encode($linha[$cont]);
            }
        }
        $options .= '</option>';
    }
    $combo = '<select id="' . $id . '" name="' . $name . '" ' . $obrigatorio . ' ' . $atributos . '>';
    $combo .= $options;
    $combo .= '</select>';
    echo $combo;
}

function imprimeDadosRevendedor($dadosRevendedor) {
    if (count($dadosRevendedor) > 0) {
        $infoRevendedor = '<p class="dadosRevendedor">';
        $infoRevendedor .= 'CPF: ' . $dadosRevendedor['cpf'];
        $infoRevendedor .= '</p>';
        $infoRevendedor .= '<p class="dadosRevendedor">';
        $infoRevendedor .= 'Data Nascimento: ' . FormatDataBDView($dadosRevendedor['data_nascimento']);
        $infoRevendedor .= '</p>';
        $infoRevendedor .= '<p class="dadosRevendedor">';
        $infoRevendedor .= 'Telefone: ' . $dadosRevendedor['telefone'];
        $infoRevendedor .= '</p>';
        echo $infoRevendedor;
    } else {
        echo 'Dados não localizados!';
    }
}

function validarDataBD($data) {
    if ($data and strpos($data, '-')) {
        $data = explode('-', $data);
        return checkdate($data[1], $data[2], $data[0]);
    }
    return false;
}

function floatcmp($f1, $f2) {
    return bccomp($f1, $f2) == 0;
}

function imprimeMensagem($msg, $erro = false) {
    if ($msg) {
        if ($erro) {
            echo '<p class="msgErro">' . $msg . '</p>';
        } else {
            echo '<p class="msgSucesso">' . $msg . '</p>';
        }
    }
}
