function GerarRelatorio() {
    var pagina;
    var nome = "Relatório de pedidos por revendedor";
    var idRevendedor = document.getElementById("revendedor").value;
    var dataInicio = document.getElementById("dataInicio").value;
    var dataFim = document.getElementById("dataFim").value;
    if (idRevendedor === null) {
        alert('Faltam dados para gerar o relatório. Favor informar.');
        return false;
    }
    pagina = "relatorio.php?idRevendedor=" + idRevendedor + "&dataInicio=" + dataInicio + "&dataFim=" + dataFim + "";
    AbrirPopup(pagina, nome, 950, 650, "yes");
}