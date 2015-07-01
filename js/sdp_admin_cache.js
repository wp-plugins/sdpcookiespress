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
    //jQuery('form#sdpCookiesForm').submit(function(event) {
    //    if(jQuery('input[name="mail"]').val()==""){
    //        jQuery(".first_choice").hide();
    //        jQuery(".register_new").hide();
    //        jQuery(".loading").show();
    //
    //        if(APIkey == false && jQuery('input[name="apiKey"]').val()!=""){
    //            var apikey = jQuery('input[name="apiKey"]').val();
    //
    //            validateAPI(jQuery('input[name="apiKey"]').val());
    //
    //            var posting = jQuery.post( "https://smartdataprotection.eu/es/services/validateapi/"+apikey );
    //            jQuery('span.errorAPI').hide();
    //            posting.done(function( data ) {
    //                if(typeof data.license != 'undefined' && data.license > 0){
    //                    APIkey = true;
    //                    updateCacheContent(apikey);
    //                    jQuery('form#sdpCookiesForm').submit();
    //                }
    //                else{
    //                    alert("error validando api");
    //                    if(typeof data.error != 'undefined' && data.error != ""){
    //                        jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>"+data.error+"</span>");
    //                    }
    //                    else{
    //                        jQuery('input[name="apiKey"]').after("<span class='errorAPI' style='color: red'>El valor introducido no es válido</span>");
    //                    }
    //                    return -1;
    //                }
    //            });
    //            posting.fail(function( data ) {
    //                jQuery(".loading").hide();
    //                jQuery(".first_choice").show();
    //                jQuery(".register_new").show();
    //
    //
    //            });
    //        }
    //    }else{
    //        //alert("REGISTRANDO");
    //        jQuery(".first_choice").hide();
    //        jQuery(".register_new").hide();
    //        jQuery(".loading").show();
    //        //Send data to the server
    //        var dataj = {
    //            mail: jQuery('input[name="mail"]').val(),
    //            style: jQuery('select[name="style"] option:selected').val(),
    //            consent: jQuery('select[name="consentmodel"] option:selected').val()
    //        };
    //
    //        var results = getAPI(dataj);
    //        updateCacheContent(results[0]);
    //        jQuery('input[name="apiKey"]').val(results[0]);
    //        jQuery('input[name="dateEnd"]').val(results[1]);
    //        console.log(results);
    //    }
    //    jQuery(this)[0].submit();
    //});
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
        updateCacheContent(results[0]);
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
                    updateCacheContent(apikey);
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
            alert("Error registering user: " + xhr.responseText);
            console.log(thrownError);
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

function updateCacheContent(apiKey) {
    //Global vars

    var req_content ="";
    var res_content ="";
    var common_urls = "";

    //function to call inside ajax callback
    function set_common_urls(x){
        common_urls = x;
    }

    function set_req_content(x){
        req_content = x;
    }

    function set_response_content(x){
        res_content = x;
    }

    //Urls vars

    var pathname = window.location.pathname;
    var path = pathname.split("/");

    var base_url = window.location.protocol+'//'+window.location.host;
    var plugin_url = (window.location.protocol+'//'+window.location.host+'/'+path[1]+"/wp-content/plugins/sdpcookiespress/writer.php");

    //Used to get all the url files
    //var common_url = '/'+path[1]+"/wp-content/cache/supercache/"+window.location.host+'/'+path[1]+'/';
    var common_url = '/'+path[1]+"/wp-content/cache/all/";

    jQuery.ajax({
        async: false,
        type: 'POST',
        url: plugin_url,
        data: {
            'url': common_url
        },
        success: function(msg) {
            set_common_urls( msg.split('||'));

        }});

    //Now we have all the files. Send each file to SDP server

    for(var i= 0; i<common_urls.length; i++){
        //Para evitar urls incorrectas
        if(common_urls[i].length >9 ) {
            //Now we get the content of the file
            var file_url = base_url + common_urls[i];

            //When request finished and response is ready

            jQuery.ajax({
                async: false,
                url: file_url,
                method: 'GET'
            }).done(function (response) {
                set_req_content(response);
                //Now we have the content of the html and send it to SDP

            }).fail(function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.responseText);
            });

            //alert(common_urls[i]);
            //alert(req_content);

            var url= "https://smartdataprotection.eu/es/services/block_cookies_wordpress";
            //var token = 'aaaca744e87c86cffa5bfe9fc4e4c754';
            var token = apiKey;
            var object = {
                "token": token,
                "content": req_content
            };


            jQuery.ajax({
                async: false,
                url: url,
                method: 'POST',
                data: object
            }).done(function (response) {
                set_response_content(response);

            }).fail(function () {
                // Whoops; show an error.
                //alert("Error al recibir el text");
            });

            //Update the cache content
            jQuery.ajax({
                async: false,
                url: plugin_url,
                dataType: "json",
                method: 'POST',
                data: { "content": res_content, "common_url": common_urls[i]}
            }).done(function (response) {
                //alert(response);
            }).fail(function (xhr, ajaxOptions, thrownError) {
                //alert(xhr.responseText);
            });

        }
    }
}