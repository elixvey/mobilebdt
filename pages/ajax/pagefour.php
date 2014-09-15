<?php
define("RelativePathTop", "../../libs/top/");
include(RelativePathTop."top_lib.php");

$page = "
    <div data-role=\"content\" class=\"ui-bar ui-bar-a\" style=\"text-align: center;\">
        <h1>Joueurs</h1>
        <img id=\"completude\" src=\"images/red_light.png\" width=\"20\" style=\"float: right\">
        <img id=\"completude_v\" src=\"images/green_light.png\" width=\"20\" style=\"float: right; display: none\">
    </div>
    <bloc::ligne_joueur>
    <div class=\"ui-grid-{nb_joueur}\">
        <bloc::poste>
        <div class=\"ui-block-{pos_joueur}\">
            <div class=\"ui-bar ui-bar-f\">{joueur}</div>
        </div>
        </bloc::poste>
    </div>
    </bloc::ligne_joueur>

    <div data-role=\"content\" class=\"ui-bar ui-bar-a\" style=\"text-align: center;\">
        <h1>Entraineur</h1>
    </div>

    <div class=\"ui-grid-c\">
        <div class=\"ui-block-a\">
            <div class=\"ui-bar ui-bar-f\">{entraineur}</div>
        </div>
        <div class=\"ui-block-b\">
            <div class=\"ui-bar ui-bar-f\"></div>
        </div>
        <div class=\"ui-block-c\">
            <div class=\"ui-bar ui-bar-f\"></div>
        </div>
        <div class=\"ui-block-d\">
            <div class=\"ui-bar ui-bar-f\"></div>
        </div>
    </div>

    <div data-role=\"content\" class=\"ui-bar ui-bar-a\" style=\"text-align: center;\">
        <h1>Remplaçants</h1>
    </div>
    <div class=\"ui-grid-c\">
        <div class=\"ui-block-a\">
            <div class=\"ui-bar ui-bar-f\">{remp1}</div>
        </div>
        <div class=\"ui-block-b\">
            <div class=\"ui-bar ui-bar-f\">{remp2}</div>
        </div>
        <div class=\"ui-block-c\">
            <div class=\"ui-bar ui-bar-f\">{remp3}</div>
        </div>
        <div class=\"ui-block-d\">
            <div class=\"ui-bar ui-bar-f\"></div>
        </div>
    </div>
    <div data-role=\"content\" class=\"ui-bar ui-bar-a\" style=\"text-align: center;\">
        <h1>Capital</h1>
    </div>
    <div id=\"valeurportefeuille\" data-role=\"content\" data-theme=\"b\">
        <h5>{portefeuille}</h5>
        <h5>{achat}&nbsp;/&nbsp;{vente}</h5>
    </div>
  </div>";

$cookie = GetFromPost('cookie');
$action = GetFromPost('action');

function miseEnForme($tableau,$type) {
    Global $Tpl;
    $tab_nb_joueur = array(null,'b','a','b','c','d');
    $tab_pos_joueur = array(null,'a','b','c','d','e');
    $nb = count($tableau);

    if($nb == 1) {
        $str = '<img id="'.$type.'a1" width="30" border="0" src="'.$tableau[0]['maillot'].'" value="'.$tableau[0]['num'].'" ><p>'.$tableau[0]['nom'].'</p>';
        $Tpl->Parse('ligne_joueur.nb_joueur',$tab_nb_joueur[3]);

        $Tpl->Parse('ligne_joueur.poste.pos_joueur','a');
        $Tpl->Parse('ligne_joueur.poste.joueur','');
        $Tpl->Loop('ligne_joueur.poste');    
        $Tpl->Parse('ligne_joueur.poste.pos_joueur','b');
        $Tpl->Parse('ligne_joueur.poste.joueur',$str);
        $Tpl->Loop('ligne_joueur.poste');
        $Tpl->Parse('ligne_joueur.poste.pos_joueur','c');
        $Tpl->Parse('ligne_joueur.poste.joueur','');
        $Tpl->Loop('ligne_joueur.poste');    
    }
    if($nb > 1) {
        for($i = 0; $i < $nb; $i++) {
            $j = $i + 1;
            $str = '<img id="'.$type.'a'.$j.'" width="30" border="0" src="'.$tableau[$i]['maillot'].'" value="'.$tableau[$i]['num'].'" ><p>'.$tableau[$i]['nom'].'</p>';
            $Tpl->Parse('ligne_joueur.nb_joueur',$tab_nb_joueur[$nb]);
            
            $Tpl->Parse('ligne_joueur.poste.pos_joueur',$tab_pos_joueur[$j]);
            $Tpl->Parse('ligne_joueur.poste.joueur',$str);
            $Tpl->Loop('ligne_joueur.poste');
        }
    }
    $Tpl->Loop('ligne_joueur');
}

if($action != 'vendretout') {
    $stade = stade($cookie);
    $membreinfo = membreInfo($cookie);

    //Traitement joueurs
    $contenu = extrait($stade,'<div id="joueur"','</div>');
    $gardien = array();
    $defenseur = array();
    $milieu = array();
    $attaquant = array();
    $nb = 0;
    $p = 0;
    while($nb < 11) {
        $p = strpos($contenu,'href="javascript:',$p);
        if($p!==false) {
            if(substr($contenu,$p+17,6)=='libere') {
                $num = substr($contenu,$p+24,4);
                $p = strpos($contenu,'img id="',$p);
                $id = substr($contenu,$p+8,3);
                $p = strpos($contenu,'src="',$p);
                $p1 = strpos($contenu,'.png',$p);
                $maillot = substr($contenu,$p+5,$p1-$p-1);
                $cap = (substr($maillot,-6,2) == '_c') ? 1 : 0;
                $p = strpos($contenu,'<td width="90" align="center">',$p);
                $p1 = strpos($contenu,'</td>',$p);
                $nom = str_replace(array(".",","),array("",""),substr($contenu,$p+30,$p1-$p-25));
            }
                
            if(substr($contenu,$p+17,7)=='affecte') {
                $num = 0;
                $id = str_replace(",","a",substr($contenu,$p+25,3));
                $p = strpos($contenu,'src="',$p);
                $p1 = strpos($contenu,'.png',$p);
                $maillot = substr($contenu,$p+5,$p1-$p-1);
                $cap = 0;
                $nom = "";
            }

            switch (substr($id,0,1)) {
                case 1:
                    $idx = substr($id,-1) - 1;
                    $gardien[$idx]['num'] = $num;
                    $gardien[$idx]['maillot'] = $maillot;
                    $gardien[$idx]['nom'] = $nom;
                    $gardien[$idx]['cap'] = $cap;
                    break;
                case 2:
                    $idx = substr($id,-1) - 1;
                    $defenseur[$idx]['num'] = $num;
                    $defenseur[$idx]['maillot'] = $maillot;
                    $defenseur[$idx]['nom'] = $nom;
                    $defenseur[$idx]['cap'] = $cap;
                    break;
                case 3:
                    $idx = substr($id,-1) - 1;
                    $milieu[$idx]['num'] = $num;
                    $milieu[$idx]['maillot'] = $maillot;
                    $milieu[$idx]['nom'] = $nom;
                    $milieu[$idx]['cap'] = $cap;
                    break;
                case 4:
                    $idx = substr($id,-1) - 1;
                    $attaquant[$idx]['num'] = $num;
                    $attaquant[$idx]['maillot'] = $maillot;
                    $attaquant[$idx]['nom'] = $nom;
                    $attaquant[$idx]['cap'] = $cap;
                    break;
            }
        }
        $nb++;
    }

    //Traitement entraineur
    $contenu = extrait($stade,'<div id="coach"','</div>');
    $entraineur = array();
    $nb = 0;
    $p = strpos($contenu,'href="javascript:');
    if($p!==false) {
        if(substr($contenu,$p+17,6)=='libere') {
            $num = substr($contenu,$p+24,4);
            $p = strpos($contenu,'img src="',$p);
            $p1 = strpos($contenu,'.png',$p);
            $maillot = 'images/maillots/png/'.substr($contenu,$p+25,$p1-$p-21);
            $p = strpos($contenu,'align="center">',$p);
            $p1 = strpos($contenu,'</td>',$p);
            $nom = str_replace(array(".",","),array("",""),substr($contenu,$p+15,$p1-$p-10));
        }

        if(substr($contenu,$p+17,7)=='affecte') {
            $num = 0;
            $id = str_replace(",","a",substr($contenu,$p+25,3));
            $p = strpos($contenu,'src="',$p);
            $p1 = strpos($contenu,'.png',$p);
            $maillot = str_replace('entraineur','vide',substr($contenu,$p+5,$p1-$p-1));
            $nom = "";
        }
        
        $entraineur[0]['num'] = $num;
        $entraineur[0]['maillot'] = $maillot;
        $entraineur[0]['nom'] = $nom;

    }

    //Traitement remplacants
    $contenu = extrait($stade,'<div id="remplacant"','</div>');
    $remplacant = array();
    $nb = 0;
    $p = 0;
    while($nb < 3) {
        $p = strpos($contenu,'href="javascript:',$p);
        if($p!==false) {
            if(substr($contenu,$p+17,6)=='libere') {
                $num = substr($contenu,$p+24,4);
                $p = strpos($contenu,'img id="',$p);
                $id = substr($contenu,$p+8,1);
                $p = strpos($contenu,'src="',$p);
                $p1 = strpos($contenu,'.png',$p);
                $maillot = substr($contenu,$p+5,$p1-$p-1);
                $p = strpos($contenu,'align="center">',$p);
                $p1 = strpos($contenu,'</td>',$p);
                $nom = str_replace(array(".",","),array("",""),substr($contenu,$p+15,$p1-$p-10));
            }
                
            if(substr($contenu,$p+17,7)=='affecte') {
                $num = 0;
                $id = str_replace(",","a",substr($contenu,$p+25,3));
                $p = strpos($contenu,'src="',$p);
                $p1 = strpos($contenu,'.png',$p);
                $maillot = substr($contenu,$p+5,$p1-$p-1);
                $nom = "";
            }
            
            $remplacant[$nb]['num'] = $num;
            $remplacant[$nb]['maillot'] = $maillot;
            $remplacant[$nb]['nom'] = $nom;    
        }
        $nb++;
    }

    //Traitement portefeuille
    $portefeuille = extrait($membreinfo,'Portefeuille','M');        
    $achat = extrait($membreinfo,'Valeur achat','M');        
    $vente = extrait($membreinfo,'Valeur vente','M');        
        
    $Tpl->chargeBuffer($page);

    miseEnForme($gardien,1);
    miseEnForme($defenseur,2);
    miseEnForme($milieu,3);
    miseEnForme($attaquant,4);

    $str = '<img id="5a1" width="30" border="0" src="'.$entraineur[0]['maillot'].'" value="'.$entraineur[0]['num'].'" ><p>'.$entraineur[0]['nom'].'</p>';
    $Tpl->Parse('entraineur',$str);

    $str = '<img id="6a1" width="30" border="0" src="'.$remplacant[0]['maillot'].'" value="'.$remplacant[0]['num'].'" ><p>'.$remplacant[0]['nom'].'</p>';
    $Tpl->Parse('remp1',$str);
    $str = '<img id="6a2" width="30" border="0" src="'.$remplacant[1]['maillot'].'" value="'.$remplacant[1]['num'].'" ><p>'.$remplacant[1]['nom'].'</p>';
    $Tpl->Parse('remp2',$str);
    $str = '<img id="6a3" width="30" border="0" src="'.$remplacant[2]['maillot'].'" value="'.$remplacant[2]['num'].'" ><p>'.$remplacant[2]['nom'].'</p>';
    $Tpl->Parse('remp3',$str);

    $Tpl->Parse('portefeuille',$portefeuille);
    $Tpl->Parse('achat',$achat);
    $Tpl->Parse('vente',$vente);

    echo utf8_encode($Tpl->output());
}

if($action == 'vendretout') {
    toutVendre($cookie);
    $membreinfo = membreInfo($cookie);
    $portefeuille = extrait($membreinfo,'Portefeuille','M');        
    $achat = extrait($membreinfo,'Valeur achat','M');        
    $vente = extrait($membreinfo,'Valeur vente','M');
    $retour = '<h5>'.$portefeuille.'</h5><h5>'.$achat.'&nbsp;/&nbsp;'.$vente.'</h5>';
    echo $retour;    
}
?>