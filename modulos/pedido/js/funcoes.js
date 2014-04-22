$(function() {
    var name = $("#name"),
            valor = $("#valor"),
            data = $("#data"),
            allFields = $([]).add(name).add(valor).add(data);

    $("#dialog-form").dialog({
        autoOpen: false,
        height: 300,
        width: 350,
        modal: true,
        buttons: {
            "Adiciona parcela": function() {
                var bValid = true;
                allFields.removeClass("ui-state-error");

                bValid = bValid && checkLength(valor, 'Valor', 1, 100);
                bValid = bValid && checkLength(data, 'Data', 1, 10);

                bValid = bValid && validaMoeda(valor);
                bValid = bValid && validaData(data);

                if (bValid) {
                    var nParcela = Number($("#nParcelas").val()) + 1;
                    $("#nParcelas").val(nParcela);
                    if ($('#trSemParcelas')) {
                        $('#trSemParcelas').remove();
                    }
                    $("#parcelas tbody").append("<tr>" +
                            "<td class='nParcela'>" + nParcela + "</td>" +
                            '<td><input type="text" class="valorParcela" name="valorParcela[]" readonly value="' + valor.val() + '"></td>' +
                            '<td><input type="text" class="valorData" name="dataParcela[]" readonly value="' + data.val() + '"></td>' +
                            '<td><img src="../../img/icones/menos.png" alt="Excluir" onclick="exlcuirParcela(this);" ' +
                            'title="Excluir" class="btnImg"></td>' +
                            "</tr>");
                    $(this).dialog("close");
                }
            },
            Cancel: function() {
                $(this).dialog("close");
            }
        },
        close: function() {
            allFields.val("").removeClass("ui-state-error");
        }
    });

    $("#create-parcela").button().click(function() {
        $("#dialog-form").dialog("open");
        return false;
    });

    $("#sair-parcela").button().click(function() {
        $(".windows-popup").fadeOut();
        $(".mask-popup").fadeOut(200);
        return false;
    });

    $("#situacao").change(function() {
        if (this.value !== 'aberto') {
            $("#campoDataSituacao").removeClass("campoHidden");
            $("#dataSituacao").removeClass("obr").addClass("obr");
        } else {
            $("#campoDataSituacao").removeClass("campoHidden").addClass("campoHidden");
            $("#dataSituacao").removeClass("obr");
        }
    });

    $("#revendedor").change(function() {
        var idRevendedor = this.value;
        carregaInfRevendedor(idRevendedor, '../../');
    });

    $("#ponto").change(function() {
        CarregaComboRevendedor(this.value, '../../');
    });

});

function exlcuirParcela(obj) {
    excluirLinhaTabela(obj);
    $(".nParcela").each(function(index, value) {
        $(this).html(index + 1);
    });
}

function checarParcelas() {
    var parcelas = $('.valorParcela');
    var situacao = $("#situacao").val();
    var venda = parseFloat(document.getElementById('totalVendas').value.replace(/\./g, "").replace(/,/g, '.'));
    var desconto = parseFloat(document.getElementById('desconto').value.replace(/\./g, "").replace(/,/g, '.'));
    var totalVenda = venda - desconto;
    totalVenda = totalVenda.toFixed(2);

    if ((parcelas.length > 0)) {
        var valorTotalParcelas = 0;
        parcelas.each(function() {
            valorTotalParcelas += parseFloat(this.value.replace(/\./g, "").replace(/,/g, '.'));

        });
        valorTotalParcelas = valorTotalParcelas.toFixed(2);
        if (situacao === 'finalizado') {
            if (valorTotalParcelas < totalVenda) {
                alert("Pedido não pode ser finalizado!\nSomatório dos valores das parcelas inferior ao valor total da venda!");
                return false;
            }
            if (valorTotalParcelas > totalVenda) {
                alert("Pedido não pode ser finalizado!\nSomatório dos valores das parcelas superior ao valor total da venda!");
                return false;
            }
        } else if (situacao !== 'finalizado') {
            if (valorTotalParcelas > totalVenda) {
                alert("Somatório dos valores das parcelas superior ao valor total da venda! Favor checar.");
                return false;
            }
        }
    }

    return true;
}

function validaForm() {
    if (validaFormBasico()) {
        if(!checarParcelas()) {
            return false;
        }
    } else {
        return false;
    }
    return true;
}