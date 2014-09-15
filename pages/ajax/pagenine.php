<?php
define("RelativePathTop", "../../libs/top/");
include(RelativePathTop."top_lib.php");

$page = "<div class=\"ui-grid-b\">
            <bloc::ligne>
            <div class=\"ui-block-a\">
                <h4>Journée {journee}</h4>
            </div>
            <div class=\"ui-block-b\" style=\"text-align: center;\">
                <h4><a href=\"#pageten\" value=\"{journee}\" data-transition=\"slide\">{point}</a></h4>
            </div>
            <div class=\"ui-block-c\" style=\"text-align: center;\">
            </div>
            </bloc::ligne>
        </div>";

$cookie = GetFromPost('cookie');

$courbe = courbeResultat($cookie);
$contenu = extrait($courbe,'l2 = paper.text','</script>');
preg_match_all('#text\(([0-9]*)[^0-9]*([0-9]*)[^0-9]*([0-9]*)#',$contenu,$match);

$result = array();
$retour = array();
$str = '';
$nb = count($match[0]);
for($i = 0; $i < $nb; $i++) {
    $result[$match[1][$i]][$match[2][$i]] = $match[3][$i];
}

foreach($result as $x=>$y) {
    foreach($y as $val) {
        $retour[] = intval($val);
    }
}

$nb = count($retour);
$Tpl->chargeBuffer($page);
for($i = $nb; $i > 0; $i--) {
        $Tpl->Parse('ligne.journee',$i);
        $Tpl->Parse('ligne.point',$retour[$i-1]);
        $Tpl->Loop('ligne');
}

$resultat = array();
if($nb) {
    $resultat['courbe'] = $retour;
    $resultat['html'] = $Tpl->output();
    $resultat['status'] = true;
} else {
    $resultat['status'] = false;
}
echo json_encode($resultat);
?>