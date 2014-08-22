var APIkey = false;

jQuery(document).ready(function () {
    jQuery(".showStyles").on("click",function(event){
        event.preventDefault();
        jQuery(".showStyles").hide();
        jQuery(".hideStyles").show();
        jQuery("#showStyles").show("slow");
    });
    jQuery(".hideStyles").on("click",function(event){
        event.preventDefault();
        jQuery(".showStyles").show();
        jQuery(".hideStyles").hide();
        jQuery("#showStyles").hide("slow");
    });
    jQuery('input[name="automatic_page"]').on("change",function(){
        if(jQuery(this).val()==0){
            jQuery(".noticeUrl").show();
        }
        else{
            jQuery(".noticeUrl").hide();
        }
    });
    jQuery('input[name="apiKey"]').change(function () {
        validateAPI(jQuery(this).val());
    });
    jQuery('form#sdpCookiesForm').submit(function(event) {
        if(APIkey == false){
            event.preventDefault();
            var posting = jQuery.post( "http://smartdataprotection.eu/es/services/validateapi/"+jQuery('input[name="apiKey"]').val() );
            jQuery('span.errorAPI').hide();
            posting.done(function( data ) {
                if(typeof data.license != 'undefined' && data.license > 0){
                    APIkey = true
                    jQuery('form#sdpCookiesForm').submit();
                }
                else{
                    if(typeof data.error != 'undefined' && data.error != ""){
                        jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>"+data.error+"</span>");
                    }
                    else{
                        jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>El valor introducido no es válido</span>");
                    }
                    return -1;
                }
            })
        }
    });
    jQuery(".addtionalInfo").on("click",function(event){
        event.preventDefault();
        var apival = jQuery('input[name="apiKey"]').val();
        validateAPI(apival);
    });

});

function validateAPI(api) {
    var posting = jQuery.post( "http://smartdataprotection.eu/es/services/validateapi/"+api );
    jQuery('span.errorAPI').hide();
    posting.done(function( data ) {
        if(typeof data.license != 'undefined' && data.license > 0){
            jQuery(".addtionalInfo").hide();
            APIkey = true;
            jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: green'>APIKey correcta</span>");

            var aString = '<table data-role="table" id="firstcookiesTable" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Mostrar..." data-column-popup-theme="a"><thead><tr class="ui-bar-d"><th>Tipo</th><th>Nombre</th><th>Clave</th><th>Valor</th><th data-priority="1">Propósito</th></tr></thead><tbody>';

            jQuery(".getLicense").hide();

            return data.license;
        }
        else{
            if(typeof data.error != 'undefined' && data.error != ""){
                jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>"+data.error+"</span>");
            }
            else{
                jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>El valor introducido no es válido</span>");
            }
            return -1;
        }
    })
    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
        jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>"+textStatus+"</span>");
        return -1;
    });
}