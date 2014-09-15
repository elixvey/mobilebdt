<?php
define("RelativePathTop", "../../libs/top/");
include(RelativePathTop."top_lib.php");

$rel = GetFromPost('rel','external');

$page = "<ul data-role=\"listview\">
        <bloc::liste_menu>
        <li><a href=\"{link_menu}\" ";
if($rel == "external") $page .= "rel=\"external\" ";
$page .= "data-transition=\"slide\">{item_menu}</a></li>
        </bloc::liste_menu>
        </ul>";

$liste_menu = array();
$liste_menu[] = array('Composition','maintwo.php#pagefour');
$liste_menu[] = array('Résultats','#pagenine');
$liste_menu[] = array('Ligue','#');
$liste_menu[] = array('Division','#');
$liste_menu[] = array('Gold Cup','#');
$liste_menu[] = array('Silver Cup','#');

$Tpl->chargeBuffer($page);

foreach($liste_menu as $item_menu) {
    $Tpl->Parse('liste_menu.item_menu',$item_menu[0]);
    $Tpl->Parse('liste_menu.link_menu',$item_menu[1]);
    $Tpl->Loop('liste_menu');
}

echo $Tpl->output_json();
?>