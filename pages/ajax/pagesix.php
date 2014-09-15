<?php
define("RelativePathTop", "../../libs/top/");
include(RelativePathTop."top_lib.php");

$page = "<h5>{image}</h5>
        <div class=\"ui-grid-a\">
        <div class=\"ui-block-a\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>{nom}</h5></div>
        </div>
        <div class=\"ui-block-b\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>Dernière J : {journee}</h5></div>
        </div>
        <div class=\"ui-block-a\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>{club}</h5></div>
        </div>
        <div class=\"ui-block-b\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>Saison : {saison}</h5></div>
        </div>
        <div class=\"ui-block-a\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>{poste}</h5></div>
        </div>
        <div class=\"ui-block-b\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>Valeur à l'achat : {achat}</h5></div>
        </div>
        <div class=\"ui-block-a\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>Titulaire : {titulaire}</h5></div>
        </div>
        <div class=\"ui-block-b\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>Remplacant : {remplacant}</h5></div>
        </div>
    </div>
    <div id=\"boutonacheter\" class=\"ui-btn ui-input-btn ui-btn-b ui-corner-all ui-shadow\">Acheter</div>";

$cookie = GetFromPost('cookie');
$action = GetFromPost('action');
$num = GetFromPost('num',0);
$id = GetFromPost('id','5a1');

if($action == 'info' && $num != 0) {
    $statjoueur = statJoueur($cookie,$num);
    $contenu = utf8_decode(extrait($statjoueur,'<table','</table>'));
    $contenu = str_replace('&nbsp;',' ',$contenu);

    preg_match_all('#src="([^"]*)#',$contenu,$match);
    $maillot = $match[1][0];
    preg_match_all('#left">([^<]*)#',$contenu,$match);
    $nom = str_replace(array(",","."),array("",""),$match[1][0]);
    preg_match_all('#\((.*)\)(.*)#',$contenu,$match);
    $club = trim($match[1][0]);
    preg_match_all('#<br>(.*)\(.*#',$contenu,$match);
    $poste = trim($match[1][0]);
    preg_match('#(Cote actuelle : )([^\s]*)#',$contenu,$match);
    $achat = $match[2];
    preg_match('#(Titulaire : )([0-9]{1,2})(<)#',$contenu,$match);
    $titulaire = $match[2];
    preg_match('#(Remplacant : )([0-9]{1,2})(<)#',$contenu,$match);
    $remplacant = $match[2];
    preg_match('#(Points saison : )(\-?[0-9]{1,3})(<)#',$contenu,$match);
    $saison = $match[2];
    preg_match('#(Points derniere journ[^:]*: )(\-?[0-9]{1,3})#',$contenu,$match);
    $journee = $match[2];
   
    $Tpl->chargeBuffer($page);
    $Tpl->Parse('image','<img src="'.$maillot.'" style="margin-left: auto; margin-right: auto; display: block;">');
    $Tpl->Parse('nom',$nom);
    $Tpl->Parse('club',$club);
    $Tpl->Parse('poste',$poste);
    $Tpl->Parse('titulaire',$titulaire);
    $Tpl->Parse('remplacant',$remplacant);
    $Tpl->Parse('journee',$journee);
    $Tpl->Parse('saison',$saison);
    $Tpl->Parse('achat',$achat);

    echo json_encode(array('html'=>utf8_encode($Tpl->output()),'maillot'=>str_replace('drapeaux','maillots/png',$maillot)));
}

if($action == 'acheter' && $num != 0) {
    $poste = intval(substr($id,0,1)); 
    $rang = intval(substr($id,2));
    if($poste == 6) {
        $rang = 0;
        $poste = intval(substr($id,2)) + 1;
    }
    $result = confirmeAchat($cookie,$num,$poste,$rang);
    $membreinfo = membreInfo($cookie);
    $portefeuille = extrait($membreinfo,'Portefeuille','M');        
    $achat = extrait($membreinfo,'Valeur achat','M');        
    $vente = extrait($membreinfo,'Valeur vente','M'); 
    $retour = array();
    $retour['resultat'] = $result;
    $retour['portefeuille'] = '<h5>'.$portefeuille.'</h5><h5>'.$achat.'&nbsp;/&nbsp;'.$vente.'</h5>';
    echo json_encode($retour);
}
?>