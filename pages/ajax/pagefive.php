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
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>Valeur actuelle : {vente}</h5></div>
        </div>
        <div class=\"ui-block-a\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\"><h5>Remplacant : {remplacant}</h5></div>
        </div>
        <div class=\"ui-block-b\">
            <div class=\"ui-bar ui-bar-a\" style=\"height: 42px;\">{capitaine}</div>
        </div>
    </div>
    <div id=\"boutonvendre\" class=\"ui-btn ui-input-btn ui-btn-b ui-corner-all ui-shadow\">Vendre</div>";
        
$listejoueur = "<div data-role=\"none\">
        <table id=\"tableJoueur\">
        <thead><tr><th><h5>Club</h5></th><th><h5>Nom</h5></th><th><h5>M &euro;</h5></th></tr></thead>
        <tbody><bloc::ligne>
            <tr><td><img src=\"{maillot}\" value={num}></td><td>{nom}</td><td>{achat}</td><td>{equipe}</td></tr></bloc::ligne>
            <bloc::budgetzero><tr><td></td><td>Budget insuffisant</td><td></td><td></td></tr></bloc::budgetzero>
        </tbody></table>
        </div>";              
        
$cookie = GetFromPost('cookie');
$action = GetFromPost('action');
$num = GetFromPost('num',0);
$id = GetFromPost('id','5a1');
// $num = 0;
if($action == 'info' && $num != 0) {
    $joueurinfo = joueurInfo($cookie,'joueurinfo',$num);
    $joueurinfo2 = joueurInfo($cookie,'joueurinfo2',$num);
    $contenu = utf8_decode(str_replace("&nbsp;"," ",extrait($joueurinfo,'<table','</table>')));
    $contenu2 = utf8_decode(str_replace("&nbsp;"," ",extrait($joueurinfo2,'<table','</table>')));

    $contenu = str_replace("<br>","xx",$contenu);
    preg_match_all('#src="(.*)png(.*)#',$contenu,$match);
    $maillot = $match[1][0].'png';
    preg_match_all('#\s(.*)xx(.*)xx#',$contenu,$match);
    $nom = str_replace(array(",","."),array("",""),trim($match[1][0]));
    $club = trim($match[2][0]);
    preg_match_all('#\((.*)\)(.*)#',$contenu,$match);
    $poste = trim($match[1][0]);
    preg_match_all('#>\s(Capitaine)(.*)#',$contenu,$match);
    if(substr($id,0,1) < 5) {
        $str = '<a href="#" id="fairecapitaine" data-role="button" data-mini="true" style="bottom: 10px;">Capitaine</a>';
    } else $str = '&nbsp;';
    $capitaine = (isset($match[1][0])) ? 'Capitaine' : $str;
    preg_match_all('#\Valeur(.*)(: )(.*)(</td>)(.*)#',$contenu,$match);
    $achat = $match[3][0];
    $vente = $match[3][1];

    preg_match('#(titulaire : )([0-9]{1,2})(<)#',$contenu2,$match);
    $titulaire = $match[2];
    preg_match('#(Remplacant : )([0-9]{1,2})(<)#',$contenu2,$match);
    $remplacant = $match[2];
    preg_match('#(journée : )(\-?[0-9]{1,3})(\sPt\(s\))#',$contenu2,$match);
    $journee = $match[2];
    preg_match('#(Saison : )(\-?[0-9]{1,3})(\sPt\(s\))#',$contenu2,$match);
    $saison = $match[2];
   
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
    $Tpl->Parse('vente',$vente);
    $Tpl->Parse('capitaine',$capitaine);

    echo utf8_encode($Tpl->output());
}

if($action == 'info' && $num == 0) {
    $poste = substr($id,0,1);
    if($poste == "6") $poste = substr($id,2,1)+1;
    $cotejoueur = coteJoueur($cookie,$poste);
    $contenu = utf8_decode(extrait($cotejoueur,'<div id="liste"','</div>'));

    preg_match_all('#title="([a-zA-Z-]*)"(.*)#',$contenu,$match);
    $equipe = $match[1];
    preg_match_all('#src="([^"]*)#',$contenu,$match);
    $maillot = $match[1];
    preg_match_all('#myClick\(([^,]*),([^\)])\);">([^<]*)#',$contenu,$match);
    $num = $match[1];
    $etat = $match[2];
    $nom = str_replace(array(".",","),array("",""),$match[3]);
    preg_match_all('#30">([^<]*)#',$contenu,$match);
    $achat = $match[1];
    $nb = count($equipe);

    $Tpl->chargeBuffer($listejoueur);
    $nbl = 0;
    for($i = 0; $i < $nb; $i++) {
        if($etat[$i] == 0) {
            $Tpl->Parse('ligne.maillot',$maillot[$i]);
            $Tpl->Parse('ligne.num',$num[$i]);
            $Tpl->Parse('ligne.nom',$nom[$i]);
            $Tpl->Parse('ligne.achat',$achat[$i]);
            $Tpl->Parse('ligne.equipe',$equipe[$i]);
            $Tpl->Loop('ligne');
            $nbl++;
        }
    }
    if($nbl) $Tpl->eraseBloc('budgetzero'); else $Tpl->eraseBloc('ligne');
    $html = $Tpl->output();
    echo utf8_encode($html);    
}

if($action == 'vendre' && $num != 0) {
    confirmeVente($cookie,$num);
    $membreinfo = membreInfo($cookie);
    $portefeuille = extrait($membreinfo,'Portefeuille','M');        
    $achat = extrait($membreinfo,'Valeur achat','M');        
    $vente = extrait($membreinfo,'Valeur vente','M'); 
    $retour = '<h5>'.$portefeuille.'</h5><h5>'.$achat.'&nbsp;/&nbsp;'.$vente.'</h5>';
    echo $retour;
}

if($action == 'capitaine' && $num != 0) {
    miseAJourCapitaine($cookie,$num);
}
?>