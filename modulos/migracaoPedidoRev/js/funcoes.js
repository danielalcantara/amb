$(function() {
    $("#revendedorDestino").change(function() {
        var idRevendedor = this.value;
        carregaInfRevendedor(idRevendedor, '../../');
    });

    $("#ponto").change(function() {
        CarregaComboRevendedor(this.value, '../../');
    });
});