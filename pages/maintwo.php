<?php
define("RelativePathTop", "../libs/top/");
include(RelativePathTop."top.php");

$Tpl->Open();
$Tpl->Parse('url_js',$ConfigApp->url_js);
$Tpl->Write();
?>