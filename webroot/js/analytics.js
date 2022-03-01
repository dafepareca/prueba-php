$(document).ajaxStart(function() {
    //$( ".daterangepicker" ).remove();
    ajaxindicatorstart(textLoading);
    //$("html, body").animate({ scrollTop: 0 }, "slow");
}).ajaxStop(function(){
    ajaxindicatorstop();
}).ajaxError(function(event, request, settings){
    // console.log(JSON.stringify(request));
    $("#modalErrorAjax .modal-title").html("<i class=\"fa fa-ban\"></i> " + lang.Error + " " + request.status + " : " + request.statusText);
    if(request.status == 403){
        $('#modalErrorAjax .modal-body').html("<h2>" + lang.AccessDenied + " ("+ request.status +")</h2>" +
            "<p class=\"error\"><strong>" + lang.Error + ": </strong>" + lang.SessionExpiredInactivity + "</p>" +
            "<p class=\"error\"><strong>" + lang.Error + ": </strong>" + lang.UnauthorizedZone + "</p>" +
            "<pre><br>" + lang.CheckAdmin + "<br><br></pre>"
        );
        $('#modalErrorAjax .modal-footer').html("<a href=\""+urlSite+"\" class=\"btn btn-danger\" >" + lang.Close + "</a>");
    }else{
        // $('#modalErrorAjax .modal-body').html( request.responseText);
        $('#modalErrorAjax .modal-body').html(request.responseText);
    }
    //console.log(JSON.stringify(event));
    $('#modalErrorAjax').modal('show');
});
function ajaxindicatorstart(text){
    if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
        jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="'+urlImageLoading+'" style="width: 100px;"><div>'+text+'</div></div><div class="bg"></div></div>');
    }
    jQuery('#resultLoading').css({
        'width':'100%',
        'height':'100%',
        'position':'fixed',
        'z-index':'10000000',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto'
    });
    jQuery('#resultLoading .bg').css({
        'background':'#000000',
        'opacity':'0.7',
        'width':'100%',
        'height':'100%',
        'position':'absolute',
        'top':'0'
    });
    jQuery('#resultLoading>div:first').css({
        'width': '250px',
        'height':'75px',
        'text-align': 'center',
        'position': 'fixed',
        'top':'0',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'font-size':'14px',
        'z-index':'10',
        'color':'#ffffff'
    });
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeIn(100);
    jQuery('body').css('cursor', 'wait');
}
function ajaxindicatorstop(){
    jQuery('#resultLoading .bg').height('100%');
    jQuery('#resultLoading').fadeOut(100);
    jQuery('body').css('cursor', 'default');
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

function readURL(input, view) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+view).attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}