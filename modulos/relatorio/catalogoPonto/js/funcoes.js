function gerarRelatorio() {
    var nome = null;
    var ponto = null;
    var catalogo = null;
    var pagina = null;
    var dataPedidos = document.getElementById("dataPedidos").value;
    var dataEntrega = document.getElementById("dataEntrega").value;

    nome = "Relatório de catálogos por ponto de venda";
    ponto = document.getElementById("ponto").value;
    catalogo = document.getElementById("idCatalogo").value;
    pagina = "relatorio.php?catalogo=" + catalogo + "&ponto=" + ponto + "&dataPedidos=" + dataPedidos + "&dataEntrega=" + dataEntrega;
    
    if (catalogo == null) {
        alert('Faltam dados para gerar o relatório. Favor informar.');
        return false;
    }
    AbrirPopup(pagina, nome, 950, 650, "yes");
}