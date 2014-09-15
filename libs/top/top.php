<?php
define('RelativePathLibs',ReducPath(RelativePathTop.'../'));
define('RelativePathConf',ReducPath(RelativePathTop.'../../config/'));
include_once(RelativePathLibs.'class_erreur.php');
include_once(RelativePathLibs.'class_config.php');
include_once(RelativePathLibs.'class_tpl.php');
include_once(RelativePathLibs.'commun.php');

function ReducPath($in) {
    $tab_out = array();
    $idx = 0;
    $tab_in = explode('/',$in);
    foreach($tab_in as $item) {
        switch ($item) {
            case '..' :
                if(isset($tab_out[$idx]) && $tab_out[$idx] != '..') {
                    unset($tab_out[$idx--]);
				} else $tab_out[++$idx] = $item;
                break;
			case '.' :
				break;
            default :
                $tab_out[++$idx] = $item;
        }
    }
    $out = implode('/',$tab_out);
	return $out;
}

$LogErreur = LogErreur::getInstance();
$ConfigApp = ConfigApp::getInstance();
$Tpl = Tpl::getInstance();

$templatefilename = $ConfigApp->dir_top.'top.html';
$Tpl->Open($templatefilename);
$Tpl->Parse('url_js',$ConfigApp->url_js);
$Tpl->Write();