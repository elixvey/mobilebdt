$(document).on('pagecreate','#pagethree', function() {
    $.ajax({url: 'ajax/pagethree.php',
        data: {rel : 'internal'},
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

$(document).on('pagebeforecreate','#pagefour', function() {
    $.ajax({url: 'ajax/pagefour.php',
        data: {cookie : sessionStorage.cookie},
        type: 'post',                  
        async: 'true',
        success: function (result) {
            chargementPageFour(result);
        },
        error: function (request,error) {
            $('#popupErreur').popup('open');
        }
    }); 
    
    $('#toutvendre').click( function() {
        $('#popup_toutvendre').popup('open');
    });
    
    $('#onvendtout').click( function() {
        $.ajax({url: 'ajax/pagefour.php',
            data: {cookie : sessionStorage.cookie , action : 'vendretout'}, 
            type: 'post',                  
            async: 'true',
            success: function (result) {
                $('#popup_toutvendre').popup('close');
                $('#pagefour img').each( function() {
                    var idjoueur = $(this).attr('id');
                    $('#'+idjoueur).attr('src','images/maillots/png/vide.png');
                    $('#'+idjoueur).attr('value','0');
                    $('#'+idjoueur).next().html('');
                });
                $('#valeurportefeuille').html(result);
                $('#mypanel').panel('close');
            }
        });
    });
});

$(document).on('panelbeforeopen','#mypanel', function() {
    if(joueurPresent() === true) {
        $('#boutondispo').addClass('ui-disabled');
    } else {
        $('#boutondispo').removeClass('ui-disabled');
    }
});

$(document).on('pagebeforecreate','#pageseven', function() {
    var sessionjournee = sessionStorage.journee;
    $.ajax({url: 'ajax/pageseven.php',
        data: {journee: sessionjournee},
        type: 'post',                  
        async: 'true',
        success: function (result) {
            $("#infojournee").html(result);
            bindCalendrier();
        }
    });
    $( "#infojournee" ).on( "swipeleft", swipeleftHandler );
    $( "#infojournee" ).on( "swiperight", swiperightHandler );
});

$(document).on('pagebeforeshow','#pageseven', function() {

});
$(document).on('pagebeforehide','#pageseven', function() {
    $('#mypanel').panel('open');
});

$(document).on('pagebeforecreate','#pageeight', function() {
    $('#pageeight img').click( function() {
        var conf = $(this).attr('value');
        $.ajax({url: 'ajax/pageeight.php',
            data: {cookie : sessionStorage.cookie , conf : conf}, 
            type: 'post',                  
            async: 'true',
            success: function (result) {
                $.ajax({url: 'ajax/pagefour.php',
                    data: {cookie : sessionStorage.cookie},
                    type: 'post',                  
                    async: 'true',
                    success: function (result) {
                        chargementPageFour(result);
                        $('#pageeight').dialog('close');
                    }
                });
            }
        });
        $.mobile.changePage('#pagefour');
    });
});

function chargementPageFour(r) {
    $("#pagefour div:jqmData(role='main')").html(r);
    $('#pagefour img').click(function() {
        $.mobile.loading( 'show',
            {text: "Recherche joueur...",
            textVisible: true,
            theme: "a"
            });
        var numjoueur = $(this).attr('value');
        var idjoueur = $(this).attr('id');
        var tabposte = [null,'Gardien','D&eacute;fenseur','Milieu','Attaquant','Entraineur'];
        var poste = tabposte[(idjoueur.substr(0,1) == 6) ? idjoueur.substr(2,1)*1+1 : idjoueur.substr(0,1)];
        $('#pagefive [data-role="footer"]').children().html(poste);
        $.ajax({url: 'ajax/pagefive.php',
            data: {cookie : sessionStorage.cookie ,action : 'info' ,num : numjoueur ,id : idjoueur},
            type: 'post',                  
            async: 'true',
            success: function (result) {
                $.mobile.loading( "hide" );
                $('#infojoueur').html(result);
                $('#infojoueur').enhanceWithin();
                $('#boutonvendre').click( function() {
                    $.ajax({url: 'ajax/pagefive.php',
                        data: {cookie : sessionStorage.cookie ,action : 'vendre' ,num : numjoueur ,id : idjoueur},
                        type: 'post',                  
                        async: 'true',
                        success: function (result) {
                            $('#'+idjoueur).attr('src','images/maillots/png/vide.png');
                            $('#'+idjoueur).attr('value','0');
                            $('#'+idjoueur).next().html('');
                            $('#valeurportefeuille').html(result);
                            indicateurEquipe();
                            $('#pagefive').dialog('close');
                        },
                        error: function(request,error) {
                            alert(request+' '+error);
                        }
                    });
                });
                $('#fairecapitaine').click( function() {
                    $.ajax({url: 'ajax/pagefive.php',
                        data: {cookie : sessionStorage.cookie ,action : 'capitaine' ,num : numjoueur},
                        type: 'post',                  
                        async: 'true',
                        success: function (result) {
                            $('[id^="1a"]').each(function() {
                                var img = $(this).attr('src').replace('_c.png','.png');
                                $(this).attr('src',img);
                            });
                            $('[id^="2a"]').each(function() {
                                var img = $(this).attr('src').replace('_c.png','.png');
                                $(this).attr('src',img);
                            });
                            $('[id^="3a"]').each(function() {
                                var img = $(this).attr('src').replace('_c.png','.png');
                                $(this).attr('src',img);
                            });
                            $('[id^="4a"]').each(function() {
                                var img = $(this).attr('src').replace('_c.png','.png');
                                $(this).attr('src',img);
                            });
                            var maillot = $('#'+idjoueur).attr('src').replace('.png','_c.png');
                            $('#'+idjoueur).attr('src',maillot);
                            indicateurEquipe();
                            $('#pagefive').dialog('close');
                        },
                        error: function(request,error) {
                            alert(request+' '+error);
                        }
                    });
                });
                $('#tableJoueur').dataTable(
                    {bFilter: false, bInfo: false, bPaginate: false,
                    "aoColumns": [
                        { "iDataSort": 3 },
                        null,
                        null,
                        { "bVisible": false }
                    ]}
                );
                $('#tableJoueur img').click(function() {
                    var numjoueurclick = $(this).attr('value');
                    var nomjoueurclick = $(this).parent().next().html();
                    $.ajax({url: 'ajax/pagesix.php',
                        data: {cookie : sessionStorage.cookie ,action : 'info' ,num : numjoueurclick ,id : idjoueur},
                        type: 'post',                  
                        async: 'true',
                        dataType: 'json',
                        success: function (result) {
                            var maillot = result.maillot;
                            $('#infojoueur6').html(result.html);
                            $('#boutonacheter').click( function() {
                                $.ajax({url: 'ajax/pagesix.php',
                                    data: {cookie : sessionStorage.cookie ,action : 'acheter' ,num : numjoueurclick ,id : idjoueur},
                                    type: 'post',                  
                                    async: 'true',
                                    dataType: 'json',
                                    success: function (result) {
                                        $('#'+idjoueur).attr('src',maillot);
                                        $('#'+idjoueur).attr('value',numjoueurclick);
                                        $('#'+idjoueur).next().html(nomjoueurclick);
                                        $('#valeurportefeuille').html(result.portefeuille);
                                        indicateurEquipe();
                                        $.mobile.changePage('#pagefour');
                                        $('#pagefive').dialog('close');
                                        $('#pagesix').dialog('close');
                                    },
                                    error: function(request,error) {
                                        alert(request+' '+error);
                                    }
                                });
                            });
                        }
                    });
                    $.mobile.changePage('#pagesix');
                    $('#pagefive').dialog('close');
                });
                $.mobile.changePage('#pagefive');
            }
        });     
    });
    
    indicateurEquipe();
}

function indicateurEquipe() {
    var complet = equipeComplete();
    var j = complet[0];
    var c = complet[1];
    var e = complet[2];
    if(j === true && c === true && e === true) {
        $('#completude').hide();
        $('#completude_v').show();
    } else {
        $('#completude').show();
        $('#completude_v').hide();       
    }
    if(j === true) $('#mj').hide(); else $('#mj').show();
    if(c === true) $('#mc').hide(); else $('#mc').show();
    if(e === true) $('#me').hide(); else $('#me').show();

    $('#completude').unbind( "click" );
    $('#completude_v').unbind( "click" );
    $('#completude').click( function() {
        $('#popup_completude').popup('open');
    });
    $('#completude_v').click( function() {
        $('#popup_completude_v').popup('open');
    });
}

function rechercheDispo() {
    var tabdispo = {442:1, 433:2, 451:3, 343:4, 352:5, 541:6};
    var def = $('[id^="2a"]').length;
    var mil = $('[id^="3a"]').length;
    var att = $('[id^="4a"]').length;
    return tabdispo[''+def+mil+att+''];
}

function joueurPresent() {
    var valeur = 0;
    $('[id^="2a"]').each(function() {
        valeur += $(this).attr('value')*1;
    });
    if(valeur === 0) {
        $('[id^="3a"]').each(function() {
            valeur += $(this).attr('value')*1;
        });
    }
    if(valeur === 0) {
        $('[id^="4a"]').each(function() {
            valeur += $(this).attr('value')*1;
        });
    }
    return (valeur !== 0);
}

function equipeComplete() {
    var joueur = true;
    var capitaine = false;
    var entraineur = true;
    $('[id^="2a"]').each(function() {
        if($(this).attr('value')*1 == 0) joueur = false;
        if($(this).attr('src').substr(-6,2) == '_c') capitaine = true;
    });

    $('[id^="3a"]').each(function() {
        if($(this).attr('value')*1 == 0) joueur = false;
        if($(this).attr('src').substr(-6,2) == '_c') capitaine = true;
    });

    $('[id^="4a"]').each(function() {
        if($(this).attr('value')*1 == 0) joueur = false;
        if($(this).attr('src').substr(-6,2) == '_c') capitaine = true;
    });

    if($('#5a1').attr('value')*1 == 0) entraineur = false;
    
    return [joueur,capitaine,entraineur];
}

function bindCalendrier() {
    $("#jprec").click( function() {
        var sessionjournee = sessionStorage.journee;
        if(sessionjournee > 1) {
            sessionjournee--;
            sessionStorage.journee = sessionjournee;
            $.ajax({url: 'ajax/pageseven.php',
                data: {journee: sessionjournee},
                type: 'post',                  
                async: 'true',
                success: function (result) {
                    $("#infojournee").html(result);
                    bindCalendrier();
                }
            });
        }
    });

    $("#jsuiv").click( function() {
        var sessionjournee = sessionStorage.journee;
        if(sessionjournee < 38) {
            sessionjournee++;
            sessionStorage.journee = sessionjournee;
            $.ajax({url: 'ajax/pageseven.php',
                data: {journee: sessionjournee},
                type: 'post',                  
                async: 'true',
                success: function (result) {
                    $("#infojournee").html(result);
                    bindCalendrier();
                }
            });
        }
    });
}

function swipeleftHandler( event ){
    $("#jsuiv").click();
}

function swiperightHandler( event ){
    $("#jprec").click();
}
