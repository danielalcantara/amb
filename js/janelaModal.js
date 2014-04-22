$(function() {
    $("a[rel=modal]").click( function(ev){
       var id = $(this).attr("href");
       launchWindow(id);
    });
    
    // Close the window popup
    // Adicionar o evento click para a mascara no script específico da página
    // caso queira uma ação ao clicá-lo
    $('.mask-popup').click(function() {
        //$(".windows-popup").fadeOut();
        //$(this).fadeOut(200);
    });
    $('.close-popup').click(function(ev) {
        ev.preventDefault();
        $('.windows-popup').fadeOut();
        $('.mask-popup').fadeOut(200);
    });
});

function launchWindow(id) {
    //Get the screen height and width
    var maskHeight = $(document).height();
    var maskWidth = $(window).width();
    //Set heigth and width to mask to fill up the whole screen
    $('.mask-popup').css({
        'width': maskWidth,
        'height': maskHeight
    });
    //transition effect		
    $('.mask-popup').fadeIn(1000);
    $('.mask-popup').fadeTo("slow", 0.85);
    //Get the left and top alignments      
    var windowLeft = ($(window).width() / 2) - ($(id).width() / 2);
    var windowTop = ($(window).height() / 2) - ($(id).height() / 2);
    //Set window to center in screen
    $(id).css({
        'top': windowTop,
        'left': windowLeft
    });
    //transition effect
    $(id).fadeIn(1000);
}