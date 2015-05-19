var APIkey = false;

jQuery(document).ready(function () {
    if(jQuery('input[name="apiKey"]').val()==""){
        jQuery(".first_choice").show();
    }else{
        jQuery(".register_new").hide();
        jQuery(".api_table").show();
    }
    jQuery(".register").on("click",function(event) {
        jQuery(".reg_api").hide();
        jQuery('input[name="mail"]').attr('required','required');
        jQuery(".register_new").show();
        jQuery(".register_table").show();

    });
    jQuery(".api").on("click",function(event) {
        jQuery(".register_new").hide();
        jQuery(".register_table").hide();
        jQuery('input[name="mail"]').val("");
        jQuery('input[name="mail"]').removeAttr('required');
        jQuery(".register_new").show();
        jQuery(".reg_api").show();
    });

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

    jQuery('form#sdpCookiesForm').submit(function(event) {
        event.preventDefault();
        if(jQuery('input[name="mail"]').val()==""){
            jQuery(".first_choice").hide();
            jQuery(".register_new").hide();
            jQuery(".loading").show();

            if(APIkey == false && jQuery('input[name="apiKey"]').val()!=""){
                var apikey = jQuery('input[name="apiKey"]').val();

                validateAPI(jQuery('input[name="apiKey"]').val());
                var posting = jQuery.post( "http://test2.smartdataprotection.eu/es/services/validateapi/"+apikey );
                jQuery('span.errorAPI').hide();
                posting.done(function( data ) {
                    if(typeof data.license != 'undefined' && data.license > 0){
                        APIkey = true;
                        jQuery('form#sdpCookiesForm').submit();
                    }
                    else{
                        alert("error validando api");
                        if(typeof data.error != 'undefined' && data.error != ""){
                            jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>"+data.error+"</span>");
                        }
                        else{
                            jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>El valor introducido no es válido</span>");
                        }
                        return -1;
                    }
                });
                posting.fail(function( data ) {
                    jQuery(".loading").hide();
                    jQuery(".first_choice").show();
                    jQuery(".register_new").show();


                });
            }
        }else{
            //alert("REGISTRANDO");
            jQuery(".first_choice").hide();
            jQuery(".register_new").hide();
            jQuery(".loading").show();
            //Send data to the server
            var dataj = {
                mail: jQuery('input[name="mail"]').val(),
                style: jQuery('select[name="style"] option:selected').val(),
                consent: jQuery('select[name="consentmodel"] option:selected').val()
            };
            var api = getAPI(dataj);
            jQuery('input[name="apiKey"]').val(api);
        }
        jQuery(this)[0].submit();
    });


});
function getAPI(dataj) {
    var api = "";
    var url = "http://test2.smartdataprotection.eu/es/services/wdpr";
    jQuery.ajax({
        async: false,
        type: 'POST',
        url: url,
        jsonp: "response",
        dataType: 'json',
        data: JSON.stringify(dataj)
    }).done(function (apiresponse) {
        //We have received the apikey.
        api = apiresponse.apikey;
    }).fail(function (xhr, ajaxOptions, thrownError) {
        //alert("Error registering user: " + xhr.responseText);
        console.log(thrownError);
        //Send second petition
        var url = "http://test2.smartdataprotection.eu/es/services/wdpr";
        jQuery.ajax({
            async: false,
            type: 'POST',
            url: url,
            jsonp: "response",
            dataType: 'json',
            data: JSON.stringify(dataj)
        }).done(function (apiresponse) {
            //We have received the apikey.
            api = apiresponse.apikey;
        }).fail(function (xhr, ajaxOptions, thrownError) {
            alert("Error registering user: " + xhr.responseText);
            console.log(thrownError);
        });
    });
    return api;


}



function validateAPI(api) {
    var posting = jQuery.post( "http://test2.smartdataprotection.eu/es/services/validateapi/"+api );
    jQuery('span.errorAPI').hide();
    posting.done(function( data ) {
        if(typeof data.license != 'undefined' && data.license > 0){
            jQuery(".addtionalInfo").hide();
            APIkey = true;
            jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: green'>APIKey correcta</span>");
            jQuery('input[name="mail"]').removeAttr('required');
            jQuery('input[name="notice"]').removeAttr('required');


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