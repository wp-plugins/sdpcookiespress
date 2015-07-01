var APIkey = false;
jQuery(document).ready(function () {
    if(jQuery('input[name="apiKey"]').val()==""){
        jQuery(".first_choice").show();
    }else{
        jQuery(".register_new").hide();
        jQuery(".api_table").show();
        jQuery(".dateend").show();
    }
    jQuery('#pricingLink').on("click",function(event){
        event.preventDefault();
        jQuery("#pricingTable").toggle();
    });
    jQuery(".register").on("click",function(event) {
        jQuery(".reg_api").hide();
        jQuery(".register_new").show();
        jQuery(".register_table").show();
    });
    jQuery(".api").on("click",function(event) {
        jQuery(".register_new").hide();
        jQuery(".register_table").hide();
        jQuery('input[name="mail"]').val("");
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
    jQuery('#pricingLink').on("click",function(event){
        event.preventDefault();
        jQuery("#pricing").toggle();
    });
    jQuery('form#sdpCookiesForm').submit(function(event) {
        jQuery(".first_choice").hide();
        jQuery(".register_new").hide();
        jQuery(".loading").show();
        //Send data to the server
        var dataj = {
            mail: jQuery('input[name="mail"]').val(),
            style: jQuery('select[name="style"] option:selected').val(),
            consent: jQuery('select[name="consentmodel"] option:selected').val()
        };
        var results = getAPI(dataj);
        jQuery('input[name="apiKey"]').val(results[0]);
        jQuery('input[name="dateEnd"]').val(results[1]);
        console.log(results);
        jQuery(this)[0].submit();

    });
    jQuery('form#sdpApiForm').submit(function(event) {
        var rawFormElement = this;
        event.preventDefault();
        jQuery(".first_choice").hide();
        jQuery(".register_new").hide();
        jQuery(".loading").show();
        if(APIkey == false && jQuery('#api').val()!=""){
            var apikey = jQuery('#api').val();
            validateAPI(jQuery('#api').val());
            var posting = jQuery.post( "https://smartdataprotection.eu/es/services/validateapi/"+apikey );
            jQuery('span.errorAPI').hide();
            posting.done(function( data ) {
                if(typeof data.license != 'undefined' && data.license > 0){
                    APIkey = true;
                    rawFormElement.submit();
                }
                else{
                    alert("error en api");
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
                event.preventDefault();
                jQuery(".loading").hide();
                jQuery(".first_choice").show();
                jQuery(".register_new").show();
            });
        }

    });
});
function getAPI(dataj) {
    var api = "";
    var dateend = "";
    var results = [];
    var url = "https://smartdataprotection.eu/es/services/wdpr";
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
        dateend = apiresponse.dateend;
        results[0] = api;
        results[1] = dateend;
    }).fail(function (xhr, ajaxOptions, thrownError) {
        //alert("Error registering user: " + xhr.responseText);
        console.log(thrownError);
        //Send second petition
        var url = "https://smartdataprotection.eu/es/services/wdpr";
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
            alert("Error registering user");
            console.log(thrownError);
            var url = "https://smartdataprotection.eu/es/services/wdpr/error";
            jQuery.ajax({
                async: false,
                type: 'POST',
                url: url,
                jsonp: "response",
                dataType: 'json',
                data: JSON.stringify(dataj)
            }).done(function (apiresponse) {
                console.log("Notificado error");
            }).fail(function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError);
            });
        });
    });
    return results;

}
function validateAPI(api) {
    var posting = jQuery.post( "https://smartdataprotection.eu/es/services/validateapi/"+api );
    jQuery('span.errorAPI').hide();
    posting.done(function( data ) {
        if(typeof data.license != 'undefined' && data.license > 0){
            jQuery(".addtionalInfo").hide();
            APIkey = true;
            jQuery('#api').after("<span class='errorAPI' style='color: green'>APIKey correcta</span>");
            //jQuery('input[name="mail"]').removeAttr('required');
            //jQuery('input[name="notice"]').removeAttr('required');


            var aString = '<table data-role="table" id="firstcookiesTable" data-mode="columntoggle" class="ui-body-d ui-shadow table-stripe ui-responsive" data-column-btn-theme="b" data-column-btn-text="Mostrar..." data-column-popup-theme="a"><thead><tr class="ui-bar-d"><th>Tipo</th><th>Nombre</th><th>Clave</th><th>Valor</th><th data-priority="1">Propósito</th></tr></thead><tbody>';

            jQuery(".getLicense").hide();

            return data.license;
        }
        else{
            if(typeof data.error != 'undefined' && data.error != ""){
                jQuery('#api').after("<span class='errorAPI' style='color: red'>"+data.error+"</span>");
            }
            else{
                jQuery('#api').after("<span class='errorAPI' style='color: red'>El valor introducido no es válido</span>");
            }
            return -1;
        }
    })
        .fail(function(XMLHttpRequest, textStatus, errorThrown) {
            jQuery('#api').after("<span class='errorAPI' style='color: red'>"+textStatus+"</span>");
            return -1;
        });
}
function getDateend(apiKey){

    var pathname = window.location.pathname;
    var path = pathname.split("/");

    var base_url = window.location.protocol+'//'+window.location.host;
    var plugin_url = (window.location.protocol+'//'+window.location.host+'/'+path[1]+"/wp-content/plugins/sdpcookiespress/scripts.php");

    var dataj = {
        apiKey: apiKey
    };
    var url = "https://smartdataprotection.eu/es/services/getDateend";
    var dateend = "";

    jQuery.ajax({
        async: false,
        type: 'POST',
        url: url,
        jsonp: "response",
        dataType: 'json',
        data: JSON.stringify(dataj)
    }).done(function (apiresponse) {
        //We have received the apikey.
        dateend = apiresponse.dateend;
        //Now save dateend into php variable

        jQuery.ajax({
            async: false,
            type: 'POST',
            url: plugin_url,
            data: {
                'dateend': dateend
            },
            success: function(msg) {
                console.log(msg);
                document.location.href = document.location.href;
            }});


    }).fail(function (xhr, ajaxOptions, thrownError) {
        console.log("Error receiving dateend");
    });

}