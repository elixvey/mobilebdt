<?php
define("RelativePathTop", "../../libs/top/");
include(RelativePathTop."top_lib.php");

$page = "<div align=\"center\">{pageone}</div>
         <a id=\"link_page2\" href=\"#pagetwo\">Identifiez vous</a>";

$edito = edito();
$part1 = extrait($edito,'<table cellpadding','</table>');
$val = extrait($edito,'getElementById("prochmatch',';');

preg_match('#value=\'([0-9]*)([^0-9]*)([0-9]*)([^0-9]*)([0-9]*)([^0-9]*)([0-9]*)([^0-9]*)([0-9]*)#',$val,$match);
$date = padLeft($match[1])." / ".padLeft($match[3])." / ".padLeft($match[5])." ".padLeft($match[7]).":".padLeft($match[9]);
$part2 = '<script type="text/javascript">document.getElementById("prochmatch").value=\''.$date.'\';</script>';
$part3 = str_replace('<marquee ','<marquee style="width: 400px;" ',extrait($edito,'<marquee','</marquee>'));
$part3 = str_replace('marquee ','div',$part3);
$contenu = $part1.$part2.$part3;
$contenu = str_replace('style="','style="text-align: center;',$contenu);
preg_match('#(J[0-9]{1,2})#',$part3,$match);
$journee = trim(substr($match[1],1));

$Tpl->chargeBuffer($page);
$Tpl->Parse('pageone',$contenu);

$resultat = array();
$resultat['html'] = $Tpl->output();
$resultat['journee'] = $journee;
echo json_encode($resultat);
?>