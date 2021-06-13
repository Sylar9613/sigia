/**
 * Created by Arián on 4/27/2018.
 */

/* ============================================= Sidenav ================================================= */
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
    document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginLeft= "0";
    document.body.style.backgroundColor = "#e6e6e6";
}
/* ======================================================= End Sidenav ================================================ */

/* =================================================== LOADER =============================================== */
var myVar;

function myFunction() {
    myVar = setTimeout(showPage, 1000);
}

function showPage() {
    document.getElementById("loader").style.display = "none";
    document.getElementById("myDiv").style.display = "block";
}
/* ==================================================== End Loader ================================================*/

/* ============================================= Carrousel =============================================================*/
var slideIndex = 0;
var index = 0;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " active";
    index = slideIndex;
}

carousel();

function carousel() {
    var i;
    var x = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    for (i = 0; i < x.length; i++) {
        x[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    index++;
    if (index > x.length) {index = 1}
    x[index-1].style.display = "block";
    dots[index-1].className += " active";
    setTimeout(carousel, 6000); // Change image every 2 seconds*/
    slideIndex = index;
}

/* ====================================== End Carrousel =================================================== */

/* =========================================== Validate forms ==================================================== */

function validateForm() {
    var x = document.forms["myForm"]["ci"].value;
    if (isNaN(x) || x == "" || x == null) {
        alert("El campo CI debe ser llenado con números");
        return false;
    }
}

function confirmar()
{
    var mensaje;
    var opcion = confirm("¿Está seguro que desea eliminar este elemento?");
    if (opcion === true) {
        return true;
    }
    return false;
}

function passwordConfirm() {
    var nueva = document.forms["changepass"]["pass"].value;
    var confirmar = document.forms["changepass"]["confirmpass"].value;

    if(nueva != confirmar)
    {
        alert("Las contraseñas no coinciden.");
        return false;
    }
}

/* ============================================== End Validate forms =================================== */

/* ============================================== Datepicker =================================== */

$(function(){
    window.prettyPrint && prettyPrint();
    $('#dp1').datepicker({
        format: 'mm-dd-yyyy'
    });
    $('#dp2').datepicker();
    $('#dp3').datepicker();
    $('#dp3').datepicker();
    $('#dpYears').datepicker();
    $('#dpMonths').datepicker();


    var startDate = new Date(2012,1,20);
    var endDate = new Date(2012,1,25);
    $('#dp4').datepicker()
        .on('changeDate', function(ev){
            if (ev.date.valueOf() > endDate.valueOf()){
                $('#alert').show().find('strong').text('The start date can not be greater then the end date');
            } else {
                $('#alert').hide();
                startDate = new Date(ev.date);
                $('#startDate').text($('#dp4').data('date'));
            }
            $('#dp4').datepicker('hide');
        });
    $('#dp5').datepicker()
        .on('changeDate', function(ev){
            if (ev.date.valueOf() < startDate.valueOf()){
                $('#alert').show().find('strong').text('The end date can not be less then the start date');
            } else {
                $('#alert').hide();
                endDate = new Date(ev.date);
                $('#endDate').text($('#dp5').data('date'));
            }
            $('#dp5').datepicker('hide');
        });

    // disabling dates
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    var checkin = $('#dpd1').datepicker({
        onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() > checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() + 1);
            checkout.setValue(newDate);
        }
        checkin.hide();
        $('#dpd2')[0].focus();
    }).data('datepicker');
    var checkout = $('#dpd2').datepicker({
        onRender: function(date) {
            return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        checkout.hide();
    }).data('datepicker');
});

/* ==================================== End Datepicker ============================================== */

function letras(e)
{
    tecla = (document.all)? e.KeyCode: e.which;
    if(tecla == 8)return true;
    patron =/[á,é,í,ó,ú,Á,É,Í,Ó,ÚA-Za-zñÑ]/;

    te = String.fromCharCode(tecla);

    return patron.test(te);

}
