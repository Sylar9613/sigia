/**
 * Created by Arian-PC on 25/03/2020.
 */
function goBack() {
    window.history.back()
}
function goForward() {
    window.history.forward()
}
$(document).ready(function(){
    $('.dropdown-submenu #test1/*a.test*/').on("click", function(e){
        $(this).next('ul').toggle();
        $('#drop2').hide();
        $('#drop3').hide();
        e.stopPropagation();
        e.preventDefault();
    });
    $('#test2').on("click", function(e){
        $(this).next('ul').toggle();
        $('#drop1').hide();
        $('#drop3').hide();
        e.stopPropagation();
        e.preventDefault();
    });
    $('#test3').on("click", function(e){
        $(this).next('ul').toggle();
        $('#drop2').hide();
        $('#drop1').hide();
        e.stopPropagation();
        e.preventDefault();
    });
});