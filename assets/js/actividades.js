$(document).ready(function(){
    $("#fecha").change(function(){
        let value = $(this).val();
        $(".txtFecha").text(value);
    });
});