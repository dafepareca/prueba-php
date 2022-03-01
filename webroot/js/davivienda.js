"use strict";
$(document).ajaxStart(function () {
    //$( ".daterangepicker" ).remove();
    ajaxindicatorstart(textLoading);
    //$("html, body").animate({ scrollTop: 0 }, "slow");
}).ajaxStop(function () {
    ajaxindicatorstop();
}).ajaxError(function (event, request, settings) {

    $("#modalErrorAjax .modal-title").html("<i class=\"fa fa-ban\"></i> " + lang.Error + " " + request.status + " : " + request.statusText);
    if (request.status == 403) {
        $("#modalErrorAjax .modal-title").html("<i class=\"fa fa-ban\"></i> " + "Error de Sesión");
        $('#modalErrorAjax .modal-body').html("<h2>" + "Su sesión ha expirado" + "</h2>" +
            "<pre><br>No hemos detectado actividad en su sesión y su sessión ha sido eliminada,<br>por favor inicie sesión de nuevo<br></pre>"
        );
        $('#modalErrorAjax .modal-footer').html("<a href=\"" + urlSite + "\" class=\"btn btn-danger\" >" + "Iniciar Sesión" + "</a>");
    } else {
        // $('#modalErrorAjax .modal-body').html( request.responseText);
        $('#modalErrorAjax .modal-body').html(request.responseText);
    }

    $('#modalErrorAjax').modal('show');
});

function ajaxindicatorstart(text) {
    if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading') {
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="' + urlImageLoading + '" style="width: 100px;"><div>' + text + '</div></div><div class="bg"></div></div>');
    }
    jQuery('#resultLoading').css({
        'width': '100%',
        'height': '100%',
        'position': 'fixed',
        'z-index': '10000000',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto'
    });
    jQuery('#resultLoading .bg').css({
        'background': '#000000',
        'opacity': '0.7',
        'width': '100%',
        'height': '100%',
        'position': 'absolute',
        'top': '0'
    });
    jQuery('#resultLoading>div:first').css({
        'width': '250px',
        'height': '75px',
        'text-align': 'center',
        'position': 'fixed',
        'top': '0',
        'left': '0',
        'right': '0',
        'bottom': '0',
        'margin': 'auto',
        'font-size': '14px',
        'z-index': '10',
        'color': '#ffffff'
    });
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(100);
    jQuery('body').css('cursor', 'wait');
}

function ajaxindicatorstop() {
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(100);
    jQuery('body').css('cursor', 'default');
}

function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
}

function updateCookie(cname, cvalue) {
    document.cookie = cname + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/davivienda_negociador/";
    document.cookie = cname + "=" + cvalue + ";path=/davivienda_negociador/";
  }


$(document).on('hidden.bs.modal', function (e) {
    $(e.target).removeData('bs.modal');
});

$(document).on('click', '.load-file', function () {
    var loadurl = $(this).attr('href');
    $.get(loadurl, function (data) {
        if (data.success) {
            window.open(data.url, '_blank');
        }
    });
    return false;
});

$(document).on('change', '.check_pago_total_vehiculo_bk', function () {
    if($(this).is(':checked')) {
        $('#continuar').removeClass('continuar');
        $('#continuar').addClass('continuarVehiculo');
    }else {
        $('#continuar').removeClass('continuarVehiculo');
        $('#continuar').addClass('continuar');
    }
});


$(document).on('click', '.continuarVehiculo', function (e) {

    var content = $(this).attr('update');
    var cant = 0;
    $(".obligaciones").each(function () {
        if ($(this).is(':checked') && $(this).attr('data-type-obligation') == 4) {
            cant++;
        }
    });

    if (cant == 0) {
        bootboxAlert('ERROR','Seleccione obligaciones a negociar.');
    }else{

        //$(this).attr('disabled', 'disabled');
        //$(this).removeClass('btn-primary');
        //$(this).addClass('btn-default');

        pagoTotalVehiculo(content);

        $('#customerinfo').css('display', 'block');
        $('.collapseInfo').collapse('hide');

        return true;
    }

});

$(document).on('click', '#continuar', function (e) {
    e.preventDefault();

    var content = $(this).attr('update');
    var cant = 0;
    $(".obligaciones").each(function () {
        if ($(this).is(':checked')) {
            cant++;
        }
    });

    var payment_capacity = $('#customer-payment-capacity');
    var customer_income = $('#customer-income');
    var pago_total = false;
    var cartera_mixta = false;
    var validar_capacidad = false;

    var total_vigente = $('#valor_total_vigente').val();
    var total_castigada = $('#valor_total_castigada').val();

    if(total_castigada > 0 && total_vigente > 0){
        cartera_mixta = true;
    }

    if ($('.check_pago_total').is(':checked') || $('.check_pago_total_vehiculo').is(':checked')) {
        pago_total = true;
    }

    if (!pago_total) {
        validar_capacidad = true;
    }

    if(cartera_mixta || total_vigente > 0){
        validar_capacidad = true;
    }

    if (customer_income.val().trim() === '') {
        customer_income.focus();
        bootboxAlert('ERROR', 'Ingreso Actual del cliente requerida.');
    } else if (payment_capacity.val().trim() === '' && validar_capacidad) {
        payment_capacity.focus();
        bootboxAlert('ERROR', 'Capacidad de pago del cliente requerida.');
    } else if (payment_capacity.val().length < 3 && validar_capacidad) {
        payment_capacity.focus();
        bootboxAlert('ERROR', 'La capacidad de pago no es valida.');
    } else if (cant == 0) {
        bootboxAlert('ERROR', 'Seleccione obligaciones a negociar.');
    } else if ($('#pago_total_consumo_castigada').is(':checked') && $('#customer-initial-payment-punished').val().length < 1) {
        $('#customer-initial-payment-punished').focus();
        bootboxAlert("ERROR", "Pago Único Consumo requerido.");
    } else {

        $(this).attr('disabled', 'disabled');
        $(this).removeClass('btn-primary');
        $(this).addClass('btn-default');

        evaluar(function (data) {

            try {
                if (data.success) {
                    if(data.message.length > 0){
                        bootboxAlert("", data.message);
                    }

                    if(data.pago_total_vehiculo){
                        pagoTotalVehiculo(content,0);
                    }else{
                        oferta(content, 0);
                    }

                    $('#customerinfo').css('display', 'block');
                    $('.collapseInfo').collapse('hide');
                } else {
                    if (data.comite) {
                        bootboxAlert("", data.message, function () {
                            evaluar(
                                function (data) {
                                    if (data.success) {
                                        if (data.pago_total_vehiculo) {
                                            pagoTotalVehiculo(content, 1);
                                        } else {
                                            oferta(content, 1);
                                        }
                                        $("#customerinfo").css(
                                            "display",
                                            "block"
                                        );
                                        $(".collapseInfo").collapse("hide");
                                    } else {
                                        $("#continuar").removeClass(
                                            "btn-default"
                                        );
                                        $("#continuar").addClass("btn-primary");
                                        $("#continuar").removeAttr("disabled");
                                        bootboxAlert("ERROR", data.message);
                                    }
                                },
                                content,
                                1
                            );
                        });
                    } else {
                        $("#continuar").removeClass("btn-default");
                        $("#continuar").addClass("btn-primary");
                        $("#continuar").removeAttr("disabled");
                        
                        bootboxAlert("ERROR", data.message);
                        
                    }
                }
            }
            catch (e) {
                var urlSend = this.url;
                bootboxAlert(
                    "ERROR",
                    "Se ha presentado un error en el sistema. \n" +
                        urlSend +
                        "\n " +
                        e,
                    function () {
                        reportarError(urlSend, e);
                    }
                );
            }

        }, content, 0);

    }

});

$(document).on('click', '#enviar_oferta', function (e) {
    e.preventDefault();

    $.ajax({
        url: urlSite + 'obligations/validarOfertaVehiculo',
        dataType: 'json',
        type: 'post',
        data: $('.evaluar').serialize(),
        success: function (data) {

            if(data.oferta){
                if(data.comite){
                    bootboxAlert("", data.mensaje, function () {
                        oferta($(".oferta_vehiculo"), 1);
                    });
                }else {
                    oferta($('.oferta_vehiculo'),0);
                }

            }else{
                bootboxAlert("", data.mensaje);
            }

        }, beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });

});

function oferta_vehiculo(content, comite) {
    $.ajax({
        url: urlSite + 'obligations/ofertaVehiculo',
        dataType: 'html',
        type: 'post',
        data: {comite: comite},
        success: function (data) {
            try {
                $(content).html(data);
            } catch (e) {
                var urlSend = this.url;
                bootboxAlert('', 'Se ha presentado un error en el sistema. \n'+ urlSend + '\n '+e, function(){
                    reportarError(urlSend,e);
                });
            }
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
}

function nuevas_cuotas() {
    $.ajax({
        url: urlSite + 'obligations/nuevasCuotas',
        dataType: 'json',
        type: 'post',
        data: {propuesta_aceptada: $('#propuesta_aceptada').val(),propuesta_aceptada_castigada: $('#propuesta_aceptada_castigada').val()},
        success: function (data) {
            $('.n-hipotecario').text("$" + numeral(data.hipotecario).format('0,0.'));
            $('.n-vehiculo').text("$" + numeral(data.vehiculo).format('0,0.'));
            $('.n-rotativos').text("$" + numeral(data.rotativos).format('0,0.'));
            $('.n-fijos').text("$" + numeral(data.fijos).format('0,0.'));
            $('.n-castigada').text("$" + numeral(data.castigada).format('0,0.'));
            $('.n-total').text("$" + numeral(data.total).format('0,0.'));
            $('.n-total-f').text("$" + numeral(data.total+data.castigada).format('0,0.'));
        }, beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
}

function reportarError(site_url,error) {
    $.ajax({
        url: urlSite + 'obligations/reportarError',
        dataType: 'json',
        type: 'post',
        data: {url: site_url,error:error},
        success: function (data) {
            location.reload();
        }, beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
}

function evaluar(callback, content, comite) {

    $(content).html('');
    $('#resumen').html('');

    $.ajax({
        url: urlSite + 'obligations/evaluar/' + comite,
        dataType: 'json',
        type: 'post',
        data: $('.evaluar').serialize(),
        success: callback,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });

}

function pagoTotalVehiculo(content, comite) {
    $(content).html('');
    $('#resumen').html('');

    var data = $('.evaluar').serializeArray();
    data.push({name: "comite", value: comite});

    $.ajax({
        url: urlSite + 'obligations/pagoTotalVehiculo',
        dataType: 'html',
        type: 'post',
        data: data,
        success: function (data) {
            try {
                $(content).html(data);
            }
            catch (e) {
                var urlSend = this.url;
                bootboxAlert('', 'Se ha presentado un error en el sistema. \n'+ urlSend + '\n '+e, function(){
                    reportarError(urlSend,e)
                });
            }
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
}

function oferta(content, comite) {
    $.ajax({
        url: urlSite + 'obligations/oferta',
        dataType: 'html',
        type: 'post',
        data: {comite: comite},
        success: function (data) {
            try {
                $(content).html(data);
                nuevas_cuotas();
            }
            catch (e) {
                var urlSend = this.url;
                bootboxAlert('', 'Se ha presentado un error en el sistema. \n'+ urlSend + '\n '+e, function(){
                    reportarError(urlSend,e)
                });
            }
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
}


$(document).on('submit', '#form-aceptar', function (e) {
    e.preventDefault();
    var $form = $(this);
    var content = $(this).attr('update');

    $.ajax({
        url: urlSite + 'obligations/evaluar_oferta',
        dataType: 'json',
        type: 'post',
        data: $form.serialize(),
        success: function (data) {
            try {
                var tab = 'resumen';
                if (data.vista == 'normalizar') {
                    $('.tab-normalizar').css('display', 'block');
                    var tab = 'normalizar';
                }

                $.ajax({
                    url: urlSite + 'obligations/' + data.vista,
                    dataType: 'html',
                    type: 'post',
                    success: function (data) {
                        $('#tab-' + tab).html(data);
                        $('.nav-tabs a[href="#tab-' + tab + '"]').tab('show');
                    },
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
                    }
                });
            }
            catch (e) {
                var urlSend = this.url;
                bootboxAlert('', 'Se ha presentado un error en el sistema. \n'+ urlSend + '\n '+e, function(){
                    reportarError(urlSend,e)
                });
            }
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
});

$(document).on('submit', '#form-acetar-oferta', function (e) {
    e.preventDefault();
    var content = $(this).attr('update');
    $.ajax({
        url: urlSite + 'obligations/resumen',
        dataType: 'html',
        type: 'post',
        data: $('.finalizar').serialize(),
        success: function (data) {
            try {
                $(content).html(data);
                $('html, body').animate({ scrollTop: 600 }, 200);
                $(".check_aceptar").prop("disabled", true);
            }
            catch (e) {
                var urlSend = this.url;
                bootboxAlert('', 'Se ha presentado un error en el sistema. \n'+ urlSend + '\n '+e, function(){
                    reportarError(urlSend,e)
                });
            }
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
});


$(document).on('click', '#aceptar-oferta', function (e) {
    $('#abono_real_unificacion').attr('readonly','readonly');
    $('#abono_real_unificacion').attr('style', 'width: 55%; background-color: #eee; opacity: 1; border-color: #ed1c27; cursor: not-allowed;');
});
/*$(document).on('click', '#aceptar-oferta', function (e) {

    var valor = $('#payment-agreed').val();
    valor.replace('.','');
    valor = parseInt(valor);

    if(valor > 0){
        return true;
    }else {
        bootboxAlert('', "Abono real no es valido.");
        $('#payment-agreed').focus();
        return false;
    }

});*/
$(document).on('click', '#rechazar-oferta', function (e) {
    e.preventDefault();
    var content = $(this).attr('update');

    bootbox.confirm("<h4>Indique la razon de rechazo.</h4>\
            <form id='infos' action=''>\
            <div class=\"form-group\">\
            <textarea required='required' class='form-control' placeholder='Razón de rechazo' id='razon_rechazo' maxlength='100' onpaste='return false' onkeypress='return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 32 || event.charCode == 209 || event.charCode == 241 || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122))' ></textarea>\
            <span class='error' style='display: none; color: red'>Razón de rechazo</span>\
            <span class='errorL' style='display: none; color: red'>Carácteres máximos permitidos:100</span>\
            <span class='errorD' style='display: none; color: red'>Carácteres especiales no válidos</span>\
            </div>\
            </form>", function(result) {
        var valor = $('#razon_rechazo').val();
        var restriccion = /[@"'?¡/*+=´_-]+/g;
        if(result){
            if(valor.length == 0 ){
                $('.error').css('display','block');
                $('#razon_rechazo').focus();
                return false;
            }else if(valor.length == 100){
                $('.errorL').css('display','block');
                $('#razon_rechazo').focus();
                return false;
            }else if (restriccion.test(valor)){
                $('.errorD').css('display','block');
                $('#razon_rechazo').focus();
                return false;
            }else {
                
                $.ajax({
                    url: urlSite + 'obligations/reject_offer',
                    dataType: 'html',
                    type: 'post',
                    data: {'reason_rejection':valor},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.success){
                            bootboxAlert('', data.message);
                            $('#oferta').html('');
                            $('#resumen').html('');
                            $('#continuar').removeAttr('disabled');
                            $('#continuar').removeClass('btn-default');
                            $('#continuar').addClass('btn-primary');
                            $('.collapseInfo').collapse('show');
                        }else {
                            bootboxAlert('', data.message);
                        }
                        
                    },
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
                    }
                });

            }
        }

    });
});

$(document).on('click', '#enviar-comite', function (e) {
    e.preventDefault();
    var content = $(this).attr('update');
    var href = $(this).attr('action');
    $.ajax({
        url: urlSite + 'obligations/send_committee',
        dataType: 'json',
        type: 'post',
        data: $('.finalizar').serialize(),
        success: function (data) {
            try {
                if (data.success) {
                    bootboxAlert('', data.message, function () {
                        location.reload();
                    });
                } else {
                    bootboxAlert("ERROR", data.message);
                }
            }
            catch (e) {
                var urlSend = this.url;
                bootboxAlert(
                    "ERROR",
                    "Se ha presentado un error en el sistema. \n" +
                        urlSend +
                        "\n " +
                        e,
                    function () {
                        reportarError(urlSend, e);
                    }
                );
            }
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
});

$(document).on('submit', '#form-finalizar', function (e) {
    e.preventDefault();
    var $form = $(this);

    var action = urlSite + 'obligations/finalizar';

    if ($('#enviar-comite').val() == 1) {
        action = urlSite + 'obligations/send_committee';
    }

    $.ajax({
        url: action,
        dataType: 'json',
        type: 'post',
        data: $('#form-finalizar, .finalizar').serialize(),
        success: function (data) {
            try {
                if (data.success) {
                    bootboxAlert('', data.message, function () {
                        location.reload();
                    });
                } else {
                    bootboxAlert("ERROR", data.message);
                }
            }
            catch (e) {
                var urlSend = this.url;
                bootboxAlert(
                    "ERROR",
                    "Se ha presentado un error en el sistema. \n" +
                        urlSend +
                        "\n " +
                        e,
                    function () {
                        reportarError(urlSend, e);
                    }
                );
            }

        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
});
$(document).on('click', '.history_detail', function (e) {
    e.preventDefault();
    var href = $(this).attr('href');
    $.ajax({
        url: href,
        dataType: 'html',
        type: 'post',
        success: function (data) {
            $('#detail_history').html(data);
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
});


$(document).on('change', '#pago_total_consumo_castigada', function () {
    if($(this).is(':checked')) {
      $('.text_pago_consumo_castigada').text('Pago Único Consumo');
    }else {
        $('.text_pago_consumo_castigada').text('Pago Inicial Consumo Castigado');
    }
});

var SelectedHip = 0;

$(document).on('change', '*[data-type-obligation="5"][data-company-obligation="9"]', function () {
    if($(this).is(':checked')) {
      SelectedHip+=1;
    }else {
        if(SelectedHip > 0){
            SelectedHip-=1;
        }
    }
    if(SelectedHip > 0){
        $('.text_pago_hipotecario_castigada').text('Pago Único Hipotecario');
    } else {
        $('.text_pago_hipotecario_castigada').text('Pago Inicial Hipotecario Castigado');
    }
    //console.log(SelectedHip)
});

$(document).on('change', '.aceptar_obligacion', function () {
    var dias_mora = $(this).parent().parent().parent().find('.dias_mora_FM').val();
    var tipo = $(this).attr('tipo-obligacion');
    var valor  = $(this).attr('valor');
    var total_minimo = $('#total_minimo').val();
    if ($(this).is(':checked')) {

        if (tipo == 4 || tipo == 5) {
            var valor_actual = $('#minimo_hipVeh').val();
            var nuevo_valor = "$" + numeral(parseInt(valor_actual) + parseInt(valor)).format('0,0.');

            $('.minimo_hipVeh').text(nuevo_valor);

        }

        if (tipo == 3) {
            var valor_actual = $('#minimo_fijos').val();
            var nuevo_valor = "$" + numeral(parseInt(valor_actual) + parseInt(valor)).format('0,0.');
            $('.minimo_fijos').text(nuevo_valor);
        }

        var nuevo_valor_minimo = "$" + numeral(parseInt(valor) + parseInt(total_minimo)).format('0,0.');
        $('.total_minimo').text(nuevo_valor_minimo);
        $('#total_minimo').val(parseInt(valor) + parseInt(total_minimo));

    } else {
        if (tipo == 4 || tipo == 5) {
            var valor_actual = $('#minimo_hipVeh').val();
            var nuevo_valor = "$" + numeral(parseInt(valor_actual)).format('0,0.');
            $('.minimo_hipVeh').text(nuevo_valor);
        }

        if (tipo == 3) {
            var valor_actual = $('#minimo_fijos').val();
            var nuevo_valor = "$" + numeral(parseInt(valor_actual)).format('0,0.');
            $('.minimo_fijos').text(nuevo_valor);
        }

        var nuevo_valor_minimo = "$" + numeral(parseInt(total_minimo) - parseInt(valor)).format('0,0.');
        $('.total_minimo').text(nuevo_valor_minimo);
        $('#total_minimo').val(parseInt(total_minimo) - parseInt(valor));
    }
});

$(document).on('change', '.pago_acordado', function () {
    var total = 0;
    $(".pago_acordado").each(function (index) {
        var val = $(this).val();
        if (val > 0) {
            total = total + parseInt(val);
        }
    });
    $('#pago_acordado').val(total);
    $('.total_acordado').text("$" + numeral(total).format('0,0.'));
});


$(document).on('change', '#ciudad', function (e) {

    $.ajax({
        url: urlSite + 'cityOffices/oficinas_ciudad/' + $(this).val(),
        dataType: 'html',
        type: 'post',
        success: function (data) {
            $('#oficina').val('');
            $('#bodymodaloficinas').html(data);
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });

});

$(document).on('focus', '#oficina', function (e) {

    var ciudad = $('#ciudad').val();
    if (ciudad == '') {
        bootboxAlert('', 'Seleccione la cidad.');
        return false;
    }

    $('#modaloficinas').modal('show');

});

$(document).on('change', '.select_oficina', function (e) {

    $('#oficina').val($(this).attr('oficina'));
    $('#oficina_id').val($(this).val());
    $('#modaloficinas').modal('hide');

});

$(document).on('click', '.aceptar_oferta', function (e) {
    if ($(this).is(':checked')) {

        var pagoSugerido =  $(this).attr('pagoSugerido');
        var abonoReal =  $(this).attr('abonoReal');

        $('#pago_sugerido_unificacion').text("$" + numeral(pagoSugerido).format('0,0.'));
        $('#total_pago_real').val(""); $('#total_pago_real').text("$" + numeral(pagoSugerido).format('0,0.'));
        $('#abono_real_unificacion').val(parseInt(abonoReal));
        $(this).removeClass('aceptar_oferta');
        $('.aceptar_oferta').attr('disabled', false);
        $('.aceptar_oferta').attr('checked', false);
        $(this).attr('checked', true);
        $(this).attr('disabled', true);
        $(this).addClass('aceptar_oferta');
        $('#propuesta_aceptada').val($(this).val());
        nuevas_cuotas();
    }
});

$(document).on('click', '.aceptar_oferta_castigada', function (e) {
    if ($(this).is(':checked')) {
        $(this).removeClass('aceptar_oferta_castigada');
        $('.aceptar_oferta_castigada').attr('disabled', false);
        $('.aceptar_oferta_castigada').attr('checked', false);
        $(this).attr('checked', true);
        $(this).attr('disabled', true);
        $(this).addClass('aceptar_oferta_castigada');
        $('#propuesta_aceptada_castigada').val($(this).val());
        nuevas_cuotas();
    }
});

$(document).on('click', '.aceptar_oferta_comite', function (e) {
    if ($(this).is(':checked')) {
        $(this).removeClass('aceptar_oferta_comite');
        $('.aceptar_oferta_comite').attr('disabled', false);
        $('.aceptar_oferta_comite').attr('checked', false);
        $(this).attr('checked', true);
        $(this).attr('disabled', true);
        $(this).addClass('aceptar_oferta_comite');
        $('#propuesta_aceptada').val($(this).val());
    }
});

$(document).on('click', '.check_ingresos', function (e) {
    if ($(this).is(':checked')) {
        $(this).removeClass('check_ingresos');
        $('.check_ingresos').attr('disabled', false);
        $('.check_ingresos').attr('checked', false);
        $(this).attr('checked', true);
        $(this).attr('disabled', true);
        $(this).addClass('check_ingresos');
        $('#check_ingresos').val($(this).val());
    }
});

$(document).on('click', '.editar-datos', function (e) {
    e.preventDefault();

    var valor = $('#id').val();

    if (valor > 0) {
        $('#name').removeAttr('disabled');
        $('#email').removeAttr('disabled');
        $(this).removeClass('editar-datos');
        $(this).attr('type', 'submit');
        $(this).html('<i aria-hidden="true" class="fa fa-fw fa-save"></i>Guardar');
        document.getElementById("email").focus();
    } else {
        bootboxAlert("ERROR", "Consulte cliente.");
    }

});

$(document).on('click', '#historial_cliente', function (e) {
    e.preventDefault();
    
    var valor = $('#id').val();

    if (valor > 0) {

        $.ajax({
            url: urlSite + 'historyCustomers/history/' + $('#identification').val(),
            dataType: 'html',
            type: 'post',
            success: function (data) {
                $('#conten-history-customer').html(data);
                $('#modal-cliente').modal('show');
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
            }
        });

    } else {
        bootboxAlert("ERROR", "Consulte cliente.");
    }
});


$(document).on('submit', '.actualizar-datos', function (e) {
    e.preventDefault();
    var $form = $(this);

    $.ajax({
        url: urlSite + 'customers/update/' + $('#identification').val(),
        dataType: 'json',
        type: 'post',
        data: $form.serialize(),
        success: function (data) {
            bootboxAlert('', data.message);
            $('#name').attr('disabled', 'disabled');
            $('#email').attr('disabled', 'disabled');
            $(this).addClass('editar-datos');
            $(this).attr('type', 'button');
            $('#btn-editar').html('<i aria-hidden="true" class="fa fa-fw fa-pencil"></i>editar');
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
});


// admin
$(document).on('change', '#user-status-id', function (e) {
    e.preventDefault();

    if ($(this).val() != 1) {
        $('.state_reason_id').css('display', 'block');
        $('#state_reason_id').attr('required', 'required');
    } else {
        $('.state_reason_id').css('display', 'none');
        $('#state_reason_id').removeAttr('required');
        $('#state_reason_id').val('');
    }

});


$(document).on('change', '#select-all', function () {

    var checkboxes = $('.select-all');

    if ($(this).is(':checked')) {
        SelectedHip = $('*[data-type-obligation="5"][data-company-obligation="9"]').length;
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
        SelectedHip = 0;
    }

    if(SelectedHip > 0){
        $('.text_pago_hipotecario_castigada').text('Pago Único Hipotecario');
    } else {
        $('.text_pago_hipotecario_castigada').text('Pago Inicial Hipotecario Castigado');
    }
});


$(document).on('submit', '#form-aceptar-comite', function (e) {
    e.preventDefault();
    var $form = $(this);
    $.ajax({
        url: urlSite + 'committees/aceptar_comite/' + $(this).attr('data-id'),
        dataType: 'json',
        type: 'post',
        data: $form.serialize(),
        success: function (data) {
            try {
                if (data.success) {                    
                    $('#viewModalDetail').modal('hide');
                    bootboxAlert('', data.message, function () {
                        $('#refresh').click();
                    });
                } else {
                    bootboxAlert("ERROR", data.message);
                }
            }
            catch (e) {
                var urlSend = this.url;
                bootboxAlert(
                    "ERROR",
                    "Se ha presentado un error en el sistema. \n" +
                        urlSend +
                        "\n " +
                        e,
                    function () {
                        reportarError(urlSend, e);
                    }
                );
            }
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
        }
    });
});

$(document).on('click', '#rechazar-comite', function (e) {
    e.preventDefault();

    var data_id = $(this).attr('data-id');

    bootbox.confirm("<h4>¿Esta seguro de rechazar la solicitud?</h4>\
            <form id='infos' action=''>\
            <div class=\"form-group\">\
            <textarea required='required' class='form-control' placeholder='Razón de rechazo' id='razon_rechazo' maxlength='100' onpaste='return false' onkeypress='return ((event.charCode >= 48 && event.charCode <= 57) || event.charCode == 32 || event.charCode == 209 || event.charCode == 241 || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122))' ></textarea>\
            <span class='error' style='display: none; color: red'>Razón de rechazo</span>\
            <span class='errorL' style='display: none; color: red'>Carácteres máximos permitidos:100</span>\
            <span class='errorD' style='display: none; color: red'>Carácteres especiales no válidos</span>\
            </div>\
            </form>", function(result) {
         var valor = $('#razon_rechazo').val();
         var restriccion = /[@"'?¡/*+=´_-]+/g;
        if(result){
            if(valor.length == 0 ){
                $('.error').css('display','block');
                $('#razon_rechazo').focus();
                return false;
            }else if(valor.length == 100){
                $('.errorL').css('display','block');
                $('#razon_rechazo').focus();
                return false;
            }else if (restriccion.test(valor)){
                $('.errorD').css('display','block');
                $('#razon_rechazo').focus();
                return false;
            }else {
                $.ajax({
                    url: urlSite + 'committees/rechazar_comite/'+data_id,
                    dataType: 'json',
                    type: 'post',
                    data: {'reason_rejection':valor},
                    success: function (data) {
                        if (data.success) {
                            $('#viewModalDetail').modal('hide');
                            bootboxAlert('', data.message, function () {
                                $('#refresh').click();
                            });
                        } else {
                            bootboxAlert("ERROR", data.message);
                        }
                    },
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
                    }
                });
            }
        }

    });

});

$(document).on('click', '.reset_password', function (e) {
    e.preventDefault();

    var data_id = $(this).attr('data-id');
    var data_name = $(this).attr('data-name');
    var url = $(this).attr('data-action');
    bootbox.confirm('Restablecer contraseña para el usuario <strong>' + data_name + '</strong>', function (result) {
        if (result) {
            $.ajax({
                url: url,
                dataType: 'html',
                type: 'post',
                success: function (data) {
                    $("#page-content-wrapper").html(data);
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
                }
            });
        }
    });

});


$(document).on('submit', '#form-acetar-oferta', function (e) {
    $('#rechazar-oferta').attr('disabled', 'disabled');
    $('.aceptar_oferta').attr('disabled', 'disabled');
});

$(document).on('click', '.consultarVehiculo', function (e) {
    e.preventDefault();

    var id = $(this).attr('data-id');

    $('#modal-vehiculo-'+id).modal('show');

});


$(document).on('click', '.consultarVehiculo2', function (e) {
    e.preventDefault();
    var oldToken = getCookie('csrfToken')
    var valor = $('#id').val();

    if (valor > 0) {

        $.ajax({
            url: urlSite + 'historyCustomers/history/' + $('#identification').val(),
            dataType: 'html',
            type: 'post',
            success: function (data) {
                updateCookie('csrfToken',oldToken)
                $('#conten-history-customer').html(data);
                $('#modal-cliente').modal('show');
            },
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
            }
        });

    } else {
        bootboxAlert("ERROR", "Consulte cliente.");
    }
});

$(document).on('change', '.pago_real', function () {
    var total = 0;
    $(".pago_real").each(function (index) {
        var val = $(this).val();
        val = val.replace(/\./g,"");
        total = total + parseInt(val);

    });
    $('#payment-agreed').val(total);
    //$('.total_pago_real').text("$" + numeral(total).format('0,0.'));
    $('#total_pago_real').val("");$('#total_pago_real').text("$" + numeral(total).format('0,0.'));
});

$(document).on('change', '.entraga_documentos', function () {
    var valor;
    var element = $(this);
    var id = $(this).attr('id');
    if($(this).is(':checked')) {
        valor = 1;
    }else{
        valor = 0;
    }

    bootbox.confirm('¿Confirma que el cliente entrego los documentos para la negociación?', function (result) {
        var oldToken = getCookie('csrfToken')
        if (result) {
            $.ajax({
                url: urlSite + 'historyCustomers/entregaDocumentos',
                dataType: 'json',
                type: 'get',
                data: {'valor': valor, 'id': id},
                success: function (data) {
                    if (data.success) {
                        bootboxAlert("", data.message, function () {
                           updateCookie("csrfToken", oldToken);
                           $(element).attr("disabled", "disabled");
                        });
                    } else {
                        bootboxAlert("ERROR", data.message, function () {
                            $(element).prop("checked", false);
                        });
                    }
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
                }
            });
        }else {
            $(element).prop( "checked", false );
        }
    });
});

$(document).on('change', '.cliente_desiste', function () {
    var valor;
    var element = $(this);
    var id = $(this).attr('id');
    if($(this).is(':checked')) {
        valor = 1;
    }else{
        valor = 0;
    }

    bootbox.confirm('¿Confirma que el cliente desiste de la negociación?', function (result) {
        var oldToken = getCookie('csrfToken')
        if (result) {
            $.ajax({
                url: urlSite + 'historyCustomers/desistenegociacion',
                dataType: 'json',
                type: 'get',
                data: {'valor':valor,'id':id },
                success: function (data) {
                    if (data.success) {
                        bootboxAlert("", data.message, function () {
                            updateCookie('csrfToken',oldToken)
                            $(element).attr( "disabled", 'disabled');
                        });
                    } else {

                        bootboxAlert("ERROR", data.message, function () {
                            $(element).prop('checked', false);
                        });
                    }
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', getCookie('csrfToken'));
                }
            });
         }else {
            $(element).prop( "checked", false );
        }
    });
});



function bootboxAlert(title, message, callback, type="alert") {
    title = (title == "") ? "MENSAJE" : title;
    bootbox.alert({
        title: `<h4 style="color:#fff; margin:0;line-height: 1.42857143;">${title}</h4>`,
        message,
        buttons: {
            ok: {
                label: "Aceptar",
                
            },
        },
        closeButton: false,
        callback,
    });
}