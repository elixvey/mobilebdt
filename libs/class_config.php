<?php 

/**
  * Classe contenant des fonctions communes
  */

class ConfigApp
{

    // Instance pour application du singleton
    protected static $instance = null;
    protected static $data = array();
    
    /**
      * Méthode d'instanciation de l'objet en singleton
      *
      * design pattern singleton
      *
      * @author Frédéric Laguitton
      * @since 2014-05-01
      * @version v1
      */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
			self::$instance = new ConfigApp();
        }
        return self::$instance;
    }
    
    private function __construct()
    {
		$fic_app = RelativePathConf.'app.txt';
		$tableauApp = parse_ini_file($fic_app);
		foreach($tableauApp as $cle=>$valeur) {
			self::$data[$cle] = $valeur;
		}

        self::$data['admin'] = file_exists(RelativePathConf.'nimda.txt') ? 1 : 0;
        
        self::$data['dir_libs'] = dirname(__FILE__);
        self::$data['dir_top'] = self::file_build_path(dirname(__FILE__),'top');
        self::$data['url_base'] = substr(self::file_build_path('http://'.$_SERVER['HTTP_HOST'],self::$data['racine']),0,-1);
        self::$data['url_libs'] = self::file_build_path(self::$data['url_base'],'libs');
        self::$data['url_js'] = self::file_build_path(self::$data['url_base'],'libs','js');
        self::$data['url_pages'] = self::file_build_path(self::$data['url_base'],'pages');
        // self::$data['url_image'] = self::file_build_path(self::$data['url_base'],'lib','img');
        // self::$data['url_data'] = self::file_build_path(self::$data['url_base'],'data');
    }

    public function __get($var)
    {       
		if (!array_key_exists($var,self::$data)) {
			throw new Exception('La variable '.$var.' de la classe ConfigApp n\'existe pas.');
		}
        return self::$data[$var];
    }

    public function __set($var,$value)
    { 
        self::$data[$var] = $value;
    }    
    
    private function file_build_path()
    {
        $sep = self::isUrl(func_get_arg(0)) ? '/' : DIRECTORY_SEPARATOR;      
        $ret = join($sep, func_get_args());
        if(substr($ret,-1) != $sep) $ret .= $sep;
        return $ret;
    }
    
    private function isUrl($in)
    {
        return (substr($in,0,7) == 'http://') || (substr($in,0,8) == 'https://');
    }
}

?>