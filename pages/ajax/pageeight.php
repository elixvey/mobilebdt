<?php
define("RelativePathTop", "../../libs/top/");
include(RelativePathTop."top_lib.php");

$ck = GetFromPost('cookie');
$conf = GetFromPost('conf',1);

echo miseAJourConfig($ck,$conf);
?>