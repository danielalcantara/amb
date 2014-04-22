
function CarregaPagina() {
    //    $(document).ready(function(){
    //        $("#" + menuCorrente).addClass("menuCorrente");
    //    });
    EscondeDivNoBackLink();
}

function EscondeDivNoBackLink() {
    var el = document.getElementsByTagName('div');

    el[el.length - 1].style.display = 'none';
}

function SetaPagina(pagSet) {
    pagina = document.getElementById('pagina');
    pagina.value = pagSet;
    document.paginacaoForm.submit();
}

function setaAcaoForm(acao) {
    var inputAcao = document.getElementById('acao');
    if (inputAcao) {
        inputAcao.value = acao;
        return true;
    } else {
        return false;
    }
}

function RedirecPagina(pagina) {
    window.location.href = pagina;
}

function voltarParaIndex(idForm) {
    form = document.getElementById(idForm);
    form.action = "index.php";
    form.submit();
}

function VoltarPagina() {
    window.history.back(1);
}

function AvancarPagina() {
    window.history.go(1);
}

function SomenteNumero(e) {
    var tecla = (window.event) ? event.keyCode : e.which;
    if ((tecla > 47 && tecla < 58))
        return true;
    else {
        if (tecla == 8 || tecla == 0)
            return true;
        else
            return false;
    }
}

//adiciona mascara de cnpj
function MascaraCNPJ(cnpj) {
    if (mascaraInteiro(cnpj) == false) {
        event.returnValue = false;
    }
    return formataCampo(cnpj, '00.000.000/0000-00', event);
}

//adiciona mascara de rg
function MascaraRG(rg) {
    if (mascaraInteiro(rg) == false) {
        event.returnValue = false;
    }
    return formataCampo(rg, '00.000.000-0', event);
}

//adiciona mascara de cep
function MascaraCep(cep) {
    if (mascaraInteiro(cep) == false) {
        event.returnValue = false;
    }
    return formataCampo(cep, '00.000-000', event);
}

//adiciona mascara de data
function MascaraData(data) {
    if (mascaraInteiro(data) == false) {
        event.returnValue = false;
    }
    return formataCampo(data, '00/00/0000', event);
}

//adiciona mascara ao telefone
function MascaraTelefone(tel) {
    if (mascaraInteiro(tel) == false) {
        event.returnValue = false;
    }
    return formataCampo(tel, '(00) 0000-0000', event);
}

//adiciona mascara ao CPF
function MascaraCPF(cpf) {
    if (mascaraInteiro(cpf) == false) {
        event.returnValue = false;
    }
    return formataCampo(cpf, '000.000.000-00', event);
}

//valida telefone
function ValidaTelefone(tel) {
    if (tel.value !== '') {
        exp = /\(\d{2}\)\ \d{4}\-\d{4}/;
        if (!exp.test(tel.value)) {
            alert('Numero de Telefone Invalido!');
            tel.value = '';
            return false;
        }
    }
    return true;
}

//valida CEP
function ValidaCep(cep) {
    if (cep.value !== '') {
        exp = /\d{2}\.\d{3}\-\d{3}/;
        if (!exp.test(cep.value)) {
            alert('Numero de Cep Invalido!');
            cep.value = '';
            cep.facus();
            return false;
        }
    }
    return true;
}

//valida RG
function ValidaRG(rg) {
    if (rg.value !== '') {
        exp = /\d{2}\.\d{3}\.\d{3}\-\d{1}/;
        if (!exp.test(rg.value)) {
            alert('Numero de RG Invalido!');
            rg.value = '';
            rg.focus();
            return false;
        }
    }
    return true;
}

//valida data
function ValidaData(campo) {
    if (campo.value !== '') {
        var bissexto = 0;
        var data = campo.value;
        var tam = data.length;
        if (tam === 10)
        {
            var dia = data.substr(0, 2);
            var mes = data.substr(3, 2);
            var ano = data.substr(6, 4);
            if ((ano > 1900) || (ano < 2100))
            {
                switch (mes)
                {
                    case '01':
                    case '03':
                    case '05':
                    case '07':
                    case '08':
                    case '10':
                    case '12':
                        if (dia <= 31)
                        {
                            return true;
                        }
                        break;

                    case '04':
                    case '06':
                    case '09':
                    case '11':
                        if (dia <= 30)
                        {
                            return true;
                        }
                        break;
                    case '02':
                        /* Validando ano Bissexto / fevereiro / dia */
                        if ((ano % 4 == 0) || (ano % 100 == 0) || (ano % 400 == 0))
                        {
                            bissexto = 1;
                        }
                        if ((bissexto == 1) && (dia <= 29))
                        {
                            return true;
                        }
                        if ((bissexto != 1) && (dia <= 28))
                        {
                            return true;
                        }
                        break;
                }
            }
        }
        alert("A Data " + data + " é inválida!");
        campo.value = "";
        return false;
    }
    return true;
}

//valida o CPF digitado
function ValidarCPF(Objcpf) {
    var cpf = Objcpf.value;
    if (cpf !== '') {
        exp = /\.|\-/g;
        cpf = cpf.toString().replace(exp, "");
        var digitoDigitado = eval(cpf.charAt(9) + cpf.charAt(10));
        var soma1 = 0, soma2 = 0;
        var vlr = 11;

        for (var i = 0; i < 9; i++) {
            soma1 += eval(cpf.charAt(i) * (vlr - 1));
            soma2 += eval(cpf.charAt(i) * vlr);
            vlr--;
        }
        soma1 = (((soma1 * 10) % 11) == 10 ? 0 : ((soma1 * 10) % 11));
        soma2 = (((soma2 + (2 * soma1)) * 10) % 11);

        var digitoGerado = (soma1 * 10) + soma2;
        if (digitoGerado != digitoDigitado) {
            alert('CPF Invalido!');
            Objcpf.value = '';
            Objcpf.focus();
            return false;
        }
    }

    return true;
}

//valida numero inteiro com mascara
function mascaraInteiro() {
    if (event.keyCode < 48 || event.keyCode > 57) {
        event.returnValue = false;
        return false;
    }
    return true;
}

//valida o CNPJ digitado
function ValidarCNPJ(ObjCnpj) {
    var cnpj = ObjCnpj.value;

    if (cnpj !== '') {
        var valida = new Array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);
        var dig1 = new Number;
        var dig2 = new Number;

        exp = /\.|\-|\//g;
        cnpj = cnpj.toString().replace(exp, "");
        var digito = new Number(eval(cnpj.charAt(12) + cnpj.charAt(13)));

        for (var i = 0; i < valida.length; i++) {
            dig1 += (i > 0 ? (cnpj.charAt(i - 1) * valida[i]) : 0);
            dig2 += cnpj.charAt(i) * valida[i];
        }
        dig1 = (((dig1 % 11) < 2) ? 0 : (11 - (dig1 % 11)));
        dig2 = (((dig2 % 11) < 2) ? 0 : (11 - (dig2 % 11)));

        if (((dig1 * 10) + dig2) != digito) {
            alert('CNPJ Invalido!');
            return false;
        }
    }

    return true;
}

//formata de forma generica os campos
function formataCampo(campo, mascara, evento) {
    var boleanoMascara;

    var digitado = evento.keyCode;
    exp = /\-|\.|\/|\(|\)| /g;
    campoSoNumeros = campo.value.toString().replace(exp, "");

    var posicaoCampo = 0;
    var NovoValorCampo = "";
    var TamanhoMascara = campoSoNumeros.length;

    if (digitado != 8) { // backspace 
        for (var i = 0; i <= TamanhoMascara; i++) {
            boleanoMascara = ((mascara.charAt(i) == "-") || (mascara.charAt(i) == ".")
                    || (mascara.charAt(i) == "/"));
            boleanoMascara = boleanoMascara || ((mascara.charAt(i) == "(")
                    || (mascara.charAt(i) == ")") || (mascara.charAt(i) == " "));
            if (boleanoMascara) {
                NovoValorCampo += mascara.charAt(i);
                TamanhoMascara++;
            } else {
                NovoValorCampo += campoSoNumeros.charAt(posicaoCampo);
                posicaoCampo++;
            }
        }
        campo.value = NovoValorCampo;
        return true;
    } else {
        return true;
    }
}

function SubmitValidaForm(idForm) {
    var campoObr = false;
    var campoFoco = null;
    var inputs = document.getElementsByTagName('input');
    var selects = document.getElementsByTagName('select');

    for (var cont = 0; cont < inputs.length; cont++) {
        if (inputs[cont].className.indexOf('obr', 0) != -1 && (inputs[cont].value == "")) {
            inputs[cont].style.backgroundColor = '#CCCCCC';
            if (!campoFoco) {
                campoFoco = inputs[cont];
            }
            campoObr = true;
        }
    }

    for (cont = 0; cont < selects.length; cont++) {
        if (selects[cont].className.indexOf('obr', 0) != -1 && (selects[cont].value == "")) {
            selects[cont].style.backgroundColor = '#CCCCCC';
            if (!campoFoco) {
                campoFoco = selects[cont];
            }
            campoObr = true;
        }
    }

    if (campoObr) {
        alert('Por favor preencha todos os campos obrigatórios.');
        campoFoco.focus();
        return false;
    }

    // Verifica se o formulário possui datas de início e fim e testa se são válidas
    var dataInicio = window.document.getElementById('dataInicio');
    var dataFim = window.document.getElementById('dataFim');

    if (dataInicio && dataFim) {
        dataInicio = dataInicio.value.split('/');
        dataFim = dataFim.value.split('/');
        var timeInicio = parseInt(dataInicio[2].toString() + dataInicio[1].toString() + dataInicio[0].toString());
        var timeFim = parseInt(dataFim[2].toString() + dataFim[1].toString() + dataFim[0].toString());

        if (timeInicio > timeFim) {
            alert('Data de início não pode ser maior que a data de finalização!');
            return false;
        }
    }

    if (idForm) {
        window.document.getElementById(idForm).submit();
    }

    return true;
}

function validaFormBasico() {
    var campoObr = false;
    var campoFoco = null;

    $(".obr").each(function() {
        if (this.value === '') {
            if(campoFoco === null) {
                campoFoco = this;
            }
            campoObr = true;
            $(this).css("background", "#CCCCCC");
        }
    });

    if (campoObr) {
        alert('Por favor preencha todos os campos obrigatórios.');
        campoFoco.focus();
        return false;
    }

    // Verifica se o formulário possui datas de início e fim e testa se são válidas
    var dataInicio = window.document.getElementById('dataInicio');
    var dataFim = window.document.getElementById('dataFim');

    if (dataInicio && dataFim) {
        dataInicio = dataInicio.value.split('/');
        dataFim = dataFim.value.split('/');
        var timeInicio = parseInt(dataInicio[2].toString() + dataInicio[1].toString() + dataInicio[0].toString());
        var timeFim = parseInt(dataFim[2].toString() + dataFim[1].toString() + dataFim[0].toString());

        if (timeInicio > timeFim) {
            alert('Data de início não pode ser maior que a data de finalização!');
            return false;
        }
    }

    return true;
}

// Confirmação de exclusão de registro
function ConfirmaExcReg() {
    conf = confirm('Deseja realmente excluir esse registro?');
    if (conf) {
        return true;
    } else {
        return false;
    }
}

function SubmeterForm(nomeForm) {
    form = window.document.getElementById(nomeForm);
    console.dir(form);
    form.submit();
}

function LiparForm(nomeForm) {
    form = window.document.getElementById(nomeForm);
    console.dir(form);
    form.reset();
}

function ValidaExtensao(pIdCampo, pExt) {
    var idCampo = '#' + pIdCampo;
    var ext = $(idCampo).val().split(".")[1].toLowerCase();
    if (ext == pExt) {
        return true;
    } else {
        alert("Extensão incorreta de arquivo!");
        return false;
    }
}

function ValidaFormImportacao() {
    if (($('#dataPedidos').val() == '') || ($('#dataEntrega').val() == '')) {
        alert('É necessário informar as datas para importação');
        return false;
    }
    if ($('#arquivoImport').val() == '') {
        alert('Escolha um arquivo no formato txt para importação.');
        return false;
    }
    return ValidaExtensao('arquivoImport', 'txt');
}

//-----------------------------------------------------
//Funcao: MascaraMoeda
//Sinopse: Mascara de preenchimento de moeda
//Parametro:
//   objTextBox : Objeto (TextBox)
//   SeparadorMilesimo : Caracter separador de milésimos
//   SeparadorDecimal : Caracter separador de decimais
//   e : Evento
//Retorno: Booleano
//Autor: Gabriel Fróes - www.codigofonte.com.br
//-----------------------------------------------------
function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e) {
    var key = '';
    var i = j = 0;
    var len = len2 = 0;
    var strCheck = '0123456789';
    var aux = aux2 = '';
    var whichCode = (window.Event) ? e.which : e.keyCode;
    if (whichCode == 13)
        return true;
    key = String.fromCharCode(whichCode); // Valor para o código da Chave
    if (strCheck.indexOf(key) == -1)
        return false; // Chave inválida
    len = objTextBox.value.length;
    for (i = 0; i < len; i++)
        if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal))
            break;
    aux = '';
    for (; i < len; i++)
        if (strCheck.indexOf(objTextBox.value.charAt(i)) != -1)
            aux += objTextBox.value.charAt(i);
    aux += key;
    len = aux.length;
    if (len == 0)
        objTextBox.value = '';
    if (len == 1)
        objTextBox.value = '0' + SeparadorDecimal + '0' + aux;
    if (len == 2)
        objTextBox.value = '0' + SeparadorDecimal + aux;
    if (len > 2) {
        aux2 = '';
        for (j = 0, i = len - 3; i >= 0; i--) {
            if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
            }
            aux2 += aux.charAt(i);
            j++;
        }
        objTextBox.value = '';
        len2 = aux2.length;
        for (i = len2 - 1; i >= 0; i--)
            objTextBox.value += aux2.charAt(i);
        objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
    }
    return false;
}

function CarregaComboRevendedor(idPonto, raizSite) {
    $('#comboRevendedor').html("<p style='margin:6px;'>Carregando...</p>");
    $('#infRevendedor').html("<p'> </p>");
    var url = raizSite + "ajax/cbRevendedores.php";
    $.post(url,
            {
                idPonto: idPonto
            },
    function(retorno) {
        $('#comboRevendedor').html(retorno);
        $("#revendedor").change(function() {
            var idRevendedor = this.value;
            carregaInfRevendedor(idRevendedor, raizSite);
        });
    },
            "html"
            );
}

function carregaInfRevendedor(idRevendedor, raizSite) {
    $('#infRevendedor').html("<p'>Carregando...</p>");
    var url = raizSite + "ajax/infoRevendedor.php";
    $.post(url,
            {
                idRevendedor: idRevendedor
            },
    function(retorno) {
        $('#infRevendedor').html(retorno);
    },
            "html"
            );
}

function ChecaDataFiltroRelatorio() {
    var dataInicio = null;
    var dataFim = null;
    var msg = "Período para filtro precisa ter as duas datas preenchidas.";

    dataInicio = document.getElementById('dataInicio');
    dataFim = document.getElementById('dataFim');

    if (dataInicio.value && !dataFim.value) {
        alert(msg);
        dataFim.focus();
        return false;
    } else if (!dataInicio.value && dataFim.value) {
        alert(msg);
        dataInicio.focus();
        return false;
    } else {
        return true;
    }
}

function AbrirPopup(pagina, nome, w, h, scroll) {
    LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
    TopPosition = 10;
    settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable,location=no';
    win = window.open(pagina, nome, settings);
}

/* Funções Jquery */

// Configurações iniciais
$(function() {
    // Setando calendário para campos data
    $(".datepicker").datepicker({constrainInput: true, dateFormat: "dd/mm/yy", changeYear: true, yearRange: "c-50:c+10"});
});

// Funções para tabelas
function excluirLinhaTabela(linha) {
    var par = $(linha).parent().parent(); //tr
    par.remove();
}

// Funções para JqueryUI

function updateTips(t, tips) {
    tips.text(t).addClass("ui-state-highlight");
    setTimeout(function() {
        tips.removeClass("ui-state-highlight", 1500);
    }, 500);
}

function checkLength(o, n, min, max) {
    var tips = $(".validateTips");
    if (o.val().length > max || o.val().length < min) {
        o.addClass("ui-state-error");
        updateTips("Length of " + n + " must be between " +
                min + " and " + max + ".", tips);
        return false;
    } else {
        return true;
    }
}

function checkRegexp(o, regexp, n) {
    var tips = $(".validateTips");
    if (!(regexp.test(o.val()))) {
        o.addClass("ui-state-error");
        updateTips(n, tips);
        return false;
    } else {
        return true;
    }
}

// Validações

function validaMoeda(valor) {
    return checkRegexp(valor, /^\d*[0-9](\.\d*[0-9])*(\,\d*[0-9])?$/,
            'O campo valor deve ser preenchido no formato "xxx.xxx,xx" substituindo "x" por números.');
}

function validaData(data) {
    return checkRegexp(data, /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/, "Password field only allow : a-z 0-9");
}

// Fim de funções para JqeryUI