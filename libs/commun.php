<?php

function GetFromPost($param, $defaut = "") {
    return isset($_POST[$param]) ? trim($_POST[$param]) : $defaut;
}

function GetFromGet($param, $defaut = "") {
    return isset($_GET[$param]) ? trim($_GET[$param]) : $defaut;
}

function GetFromSession($param, $defaut = "") {
    return isset($_SESSION[$param]) ? trim($_SESSION[$param]) : $defaut;
}

function GetFromParam($param, $defaut = "") {
    return isset($_POST[$param]) ? GetFromPost($param, $defaut) : GetFromGet($param, $defaut);
}

function dump_post() {
	if(isset($_POST)) var_dump($_POST);
}

function is_empty($param) {
    return empty($param);
}

function detectMobile() {
$retour = false;
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
$retour = true;
return $retour;
}

function extrait($in, $debut, $fin) {
    $p1 = strpos($in,$debut);
    if($p1 === false) return "";
    $p2 = strpos($in,$fin,$p1) + strlen($fin);
    if($p2 === false) return "";
    $lg = $p2 - $p1 + 1;
    $retour = substr($in,$p1,$lg);
    return $retour;
}

function extrait_supp(&$in, $debut, $fin) {
    $p1 = strpos($in,$debut);
    if($p1 === false) return "";
    $p2 = strpos($in,$fin,$p1) + strlen($fin);
    if($p2 === false) return "";
    $lg = $p2 - $p1 + 1;
    $retour = substr($in,$p1,$lg);
    $in = substr($in,$p2);
    return $retour;
}

function padLeft($in) {
    return str_pad($in,2,"0",STR_PAD_LEFT);
}

function edito() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/edito.asp"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function stade($ck) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/stade.asp"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function membreInfo($ck) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/membreinfo.asp"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function confirmeVente($ck,$num) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/confirmvente.asp?joueur=".$num."&cal=1&clas="); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function joueurInfo($ck,$url,$num) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/".$url.".asp?poste=1&joueur=".$num); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function coteJoueur($ck,$poste) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/cotejoueur.asp?src=1&posteselect=".$poste."&allselect=0&choix=1&numero=1"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function statJoueur($ck,$num,$ido=0) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/statjoueur.asp?ido=".$ido."&joueurid=".$num); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function calendrierJournee($journee) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/journee.asp?journeeid=".$journee); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function toutVendre($ck) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/toutvendre.asp?src=2"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function miseAJourConfig($ck,$conf) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/miseajourconfig.asp?src=1&conf=".$conf); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function mesResultatIndex($ck) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/mesresultatindex.asp"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function mesResultats($ck,$journee) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/mesresultats.asp?journeeid=".$journee); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function courbeResultat($ck) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/courberesultat.asp"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function trophee($ck) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/trophee.asp"); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function confirmeAchat($ck,$joueurid,$poste,$rang) {
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/confirmachat.asp?joueur=".$joueurid."&poste=".$poste."&numero=".$rang."&cal=1&clas="); 
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}

function miseAJourCapitaine($ck,$joueurid) {echo $joueurid;
    $fields = array();
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/miseajourcapitaine.asp?joueur=".$joueurid);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_COOKIE, $ck);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
	curl_close($ch);
	return $resultat;
}
