<?php
define("RelativePathTop", "../../libs/top/");
include(RelativePathTop."top_lib.php");

$site_ouvert = true;

$action = GetFromPost('action');
foreach(explode('&',GetFromPost('formData')) as $params) {
    $param = explode('=',$params);
    $$param[0]=$param[1];
}

function urlBdt($url,$fields=array()) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.bancdetouche.fr/".$url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if(count($fields) > 0) {
        $fields_string = '';
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
    }
	$resultat = curl_exec ($ch);
    $cookie = substr(extrait($resultat,'Set-Cookie:',';'),12,45);
    $info = curl_getinfo($ch);
    if(isset($info['http_code'])) $code_ret = $info['http_code']; else $code_ret = 302;
	curl_close($ch);
    return array($code_ret,$cookie);
}

if($site_ouvert) {
    $fields = array();
    $fields['nom'] = $username;
    $fields['pass'] = $password;
    // $fields['nom'] = 'elixvey@gmail.com';
    // $fields['pass'] = 'bibounbdt';
    $code = urlBdt('verify.asp',$fields);
    if($code[0]==200) $retour = array('status'=>true,'cookie'=>$code[1],'username'=>$username,'pass'=>$password); else $retour = array('status'=>false);
} else $retour = array('status'=>false);
// $retour = array('status'=>true,'cookie'=>$code[1]);
echo json_encode($retour);
?>