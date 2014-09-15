$(document).on('pagecreate','#pageone', function() {
    $.ajax({url: 'ajax/pageone.php',            
        async: 'true',
        dataType: 'json',
        success: function (result) {
            $("#contenupageone").html(result.html);
            $("#contenupageone").enhanceWithin();
            sessionStorage.journee = result.journee;
        },
        error: function (request,error) {
            $('#popupErreurone').popup('open');
        }
    }); 
});

$(document).on('pagecreate','#pagetwo', function() {
    $('#pagetwo #validation').click(function() {
        if($('#username').val().length > 0 && $('#password').val().length > 0){
            $.ajax({url: 'ajax/pagetwo.php',
                data: {action : 'login', formData : $('#identification').serialize()},
                type: 'post',                  
                async: 'true',
                dataType: 'json',
                success: function (result) {
                    if(result.status) {
                         sessionStorage.cookie = result.cookie;
                         localStorage.username = unescape(result.username);
                        $( ":mobile-pagecontainer" ).pagecontainer( "change", "#pagethree");
                    } else {
                        $('#popupNoLogon').popup('open');
                    }
                },
                error: function (request,error) {
                    $('#popupErreurtwo').popup('open');
                }
            });                  
        }
        return false;
    });
});

$(document).on('pagebeforeshow','#pagetwo', function() {
    $('#pagetwo input').not('[type="button"]').val(''); 
    var username = localStorage.getItem('username');
    if(username!=null) {
        $('#username').val(username);
    }
});

$(document).on('pagecreate','#pagethree', function() {
    $.ajax({url: 'ajax/pagethree.php',
        data: {rel : 'external'},
        type: 'post',     
        async: 'true',
        dataType: 'json',
        success: function (result) {
            $("#contenupagethree").html(result);
            $("#contenupagethree").enhanceWithin();
        },
        error: function (request,error) {
            $('#popupErreurthree').popup('open');
        }
    });
});