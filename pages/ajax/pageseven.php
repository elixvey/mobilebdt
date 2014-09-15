<?php
define("RelativePathTop", "../../libs/top/");
include(RelativePathTop."top_lib.php");

$page = "<div class=\"ui-grid-b\">
            <div class=\"ui-block-a\">
                <h4><img id=\"jprec\" src=\"images/arrow_left.png\" style=\"width: 20px;\"></h4>
            </div>
            <div class=\"ui-block-b\" style=\"text-align: center;\">
                <h4>Journée n°{journee}</h4>
            </div>       
            <div class=\"ui-block-c\"  style=\"text-align: right;\">
                <h4><img id=\"jsuiv\" src=\"images/arrow_right.png\" style=\"width: 20px;\"></h4>
            </div>
            <div class=\"ui-block-a\">&nbsp;</div>
            <div class=\"ui-block-b\" style=\"text-align: center;\">&nbsp;</div>       
            <div class=\"ui-block-c\"  style=\"text-align: right;\">&nbsp;</div>
            <bloc::ligne>
            <div class=\"ui-block-a\">
                <div class=\"ui-bar ui-bar-b\"><h5>{equipe1}</h5></div>
            </div>
            <div class=\"ui-block-b\">
                <div class=\"ui-bar ui-bar-b\" style=\"text-align: center;\"><h5>{score}</h5></div>
            </div>
            <div class=\"ui-block-c\">
                <div class=\"ui-bar ui-bar-b\"><h5>{equipe2}</h5></div>
            </div>
            </bloc::ligne>
        </div>";

$journee = GetFromPost('journee',1);
$calendrierjournee = calendrierJournee($journee);
preg_match_all('#<td class="txt7"([^/]*)#',$calendrierjournee,$match);
$match[1][0] = '';

$temp = str_replace('Saint-Etienne','StEtienne',implode($match[1]));
preg_match_all('#>([^<]*)#',$temp,$match2);

$Tpl->chargeBuffer($page);
for($i = 0; $i < 10; $i++) {
    $j = $i * 3;
    $Tpl->Parse('ligne.equipe1',$match2[1][$j]);
    $Tpl->Parse('ligne.score',$match2[1][$j+1]);
    $Tpl->Parse('ligne.equipe2',$match2[1][$j+2]);
    $Tpl->Loop('ligne');
}
$Tpl->Parse('journee',$journee);
echo utf8_encode($Tpl->output());
?>