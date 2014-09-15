<?php
/**********************************/
/* CLASSE TPL --> Classe template */
/* hérite de la classe TPLN       */
/**********************************/

setlocale (LC_TIME, 'fr_FR.utf8');
require('TPLN/TPLN.php');

class Tpl extends TPLN
{

    protected static $instance = null;
    
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
			self::$instance = new Tpl();
        }
        return self::$instance;
    }
/*  var $envoi_mail = TRUE;//True : la fonction EnvoiMail envoi les mails;
  var $envoi_test = FALSE;//True : les mails sont envoyés uniquement à $adr_test_mail;
  var $adr_test_mail = "frederic.laguitton@connectassistance.fr";
  public $db_namebase = array();
  public $titre_app = '';
  public $dir_config = '';
  public $dir_includes = '';
  public $dir_pages = '';
  public $url_base = '';
  public $url_includes = '';
  public $url_scripts = '';
  public $url_pages = '';
  public $menu_gauche = '';
  public $url_accueil = '';
  public $priorite_utilisateur = 0;
  protected $fichier_base = 'base_dev.txt';

  function Tpl() {
	$session_sep_systeme = '/';
	$this->dir_config = realpath(dirname(__FILE__).$session_sep_systeme.'..'.$session_sep_systeme.'config');
	$this->dir_includes = realpath(dirname(__FILE__));
	$this->dir_pages = realpath(dirname(__FILE__).$session_sep_systeme.'..'.$session_sep_systeme.'pages');

	//Chargement du fichier app;
	$fic_app = $this->dir_config.$session_sep_systeme.'app.txt';
	$tableauApp = parse_ini_file($fic_app);
	$this->titre_app = $tableauApp['titre_app'];
	if(isset($tableauApp['fichier_base'])) $this->fichier_base = strtolower($tableauApp['fichier_base']).'.txt';
	if(isset($tableauApp['menu_gauche'])) $this->menu_gauche = $tableauApp['menu_gauche'];

	$this->url_base = $tableauApp['url_base'];
	$this->url_includes = $this->url_base.'/includes';
	$this->url_pages = $this->url_base.'/pages';
	$this->url_scripts = $this->url_base.'/scripts';
	$this->url_accueil = $this->url_base.'/accueil.php';
	define('TPLN_WEB_PATH', $this->url_base.'/class/TPLN');
	define('TPLN_ERROR_URI', '');

	// Chargement du fichier de base;
	$fic_base = $this->dir_config.$session_sep_systeme.$this->fichier_base;
	$tableauIni = parse_ini_file($fic_base, true);
	foreach($tableauIni as $base=>$connection) {
		$this->db_namebase[$base] = $connection;
	}
}

function __destruct() {
	foreach($this->db_namebase as $base=>$conn) {
		if(isset($conn['index'])) {
			$this->db_index = $conn['index'];
			$this->db[$this->db_index] = null;
		}
	}
}

function dbSetQueryID($req_index) {
	if($req_index>=0) parent::dbSetQueryID($req_index);
}

function dbSetBase($base) {
	if(isset($this->db_namebase[$base])) {
		if(!isset($this->db_namebase[$base]['index'])) {
			$this->db_index = count($this->db) - 1;
			$h = $this->db_namebase[$base]['host'];
			$p = $this->db_namebase[$base]['port'];
			$l = $this->db_namebase[$base]['login'];
			$pw = $this->db_namebase[$base]['password'];
			$nom_base = $this->db_namebase[$base]['base'];
			$this->DbConnect('pgsql',$h,$l,$pw,$nom_base,$p);
			$this->db_namebase[$base]['index'] = $this->db_index;
		} else {
			$this->db_index = $this->db_namebase[$base]['index'];
		}
	} else {
		trigger_error("Base ".$base." inconnue");
	}
  }
  
function dbCloseBase($base='') {
	if(!$base) {
		foreach($this->db_namebase as $base=>$conn) {
			$this->db_index = $conn['index'];
			$this->dbClose();
		}
	} else {
		if(isset($this->db_namebase[$base]['index'])) {
			$this->db_index = $this->db_namebase[$base]['index'];
			$this->dbClose();
			unset($this->db_namebase[$base]['index']);
		}
	}
}
  
function dbArrangeTableau($arr) {
	foreach($arr as $cle=>$valeur) {
		if(is_null($valeur)) {
			unset($arr[$cle]);
		}
		// if(trim(strtoupper($valeur))=='NULL') $arr[$cle] = NULL;
		if(trim($valeur)=='') unset($arr[$cle]);
	}
	return $arr;
}


function dbInsert($table, $arr, $exclude_fields=array(), $return_last_id=false)  {

	$arr = $this->dbArrangeTableau($arr);

	try {
		parent::dbInsert($table, $arr, $exclude_fields, $return_last_id);
	} catch(Exception $e) {
		echo $e->getMessage();
	}
}

function dbUpdate($table, $arr, $where='', $exclude_fields=array()) {
	$arr = $this->dbArrangeTableau($arr);

	try {
		parent::dbUpdate($table, $arr, $where, $exclude_fields);
	} catch(Exception $e) {
		echo $e->getMessage();
	}
}

  function ParseArray($arr) {
    foreach($arr as $cur_cle=>$cur_val) {
      $i = 1;
      $bloc = $cur_cle;
      $arr1[0] = array();
      foreach($cur_val as $valeur) {
        $arr1[0][$i] = $valeur;
        $i++;
      }
      $this->LoadArrayInBloc($bloc,$arr1);
    }
  }

 function ParseRecord($arr) {
    foreach($arr as $cur_cle=>$cur_val) {
        if($this->ItemExists($cur_cle)) {
            $this->Parse($cur_cle,$cur_val);
        }
    }
 }

  function ParseSelect($var, $arr) {
    foreach($arr as $cur_cle=>$cur_val) {
      if($cur_cle==0) {
        $nom = $cur_val['nom'];
        $ch = "<select name='$nom'>";
      }
      foreach($cur_val as $cle=>$valeur) {
        if($cle=='value') {
          $ch .= "<option value='$valeur'";
          if(isset($cur_val['selected']))
            $ch .= " selected";
          $ch .= ">";
        }
        if($cle=='lib')
          $ch .= "$valeur</option>";
      }
    }
    $ch .= "</select>";
    $this->Parse($var, $ch);
  }

  function ParseColor($var, $ch, $coul) {
    $this->Parse($var, '<font style=\'color:'.$coul.'\'>'.$ch.'</font>');
  }

  function ParseSession($var) {
    $this->Parse($var, $_SESSION[$var]);
  }  
  
  function KeepBloc($var) {
	foreach($this->captureAllBlocs() as $valeur) {
		if($valeur!=$var) $this->EraseBloc($valeur);    
	}
  }
  
function parse($path, $replace, $functions = '') {
	$parse = true;
	if(preg_match("'._p[0-9]{1,2}.'",$path)) {
		$bloc = $this->getFather($path);
		if(preg_match("'._p[0-9]{1,2}$'",$bloc)) {
			$niveau = substr($bloc,-2);
			if(substr($niveau,0,1)=='p') $niveau = substr($niveau,1);
			if($this->priorite_utilisateur < $niveau) {
				if($this->blocExists($bloc)) {$this->eraseBloc($bloc); $parse = false;}
			}
		}
	} 	
	if($parse) parent::parse($path, $replace, $functions);
}

function KeepBlocPrio() {
	foreach($this->captureAllBlocs() as $valeur) {
		if(preg_match("'._p[0-9]{1,2}$'",$valeur)) {
			$niveau = substr($valeur,-2);
			if(substr($niveau,0,1)=='p') $niveau = substr($niveau,1);
			if($this->priorite_utilisateur < $niveau) $this->ParseBloc($valeur,'');
		}
	}
}

  //effectue un ParseDbRow si la requête donne un résultat ou efface le bloc si elle n'en retourne pas ou n'est pas définie
  //s'utilise normalement avec un @ devant
  function ParseDbRowOrClear($sql,$bloc) {
    if ($this->BlocExists($bloc)) {
        if (isset($sql)) {
            $this->DoQuery($sql);
            if ($this->GetRowsCount())
                $this->ParseDbRow($bloc);
            else
                $this->EraseBloc($bloc);
        } else
            $this->EraseBloc($bloc);
    }
  }
  //s'utilise normalement avec un @ devant
  function ParseOrClear($variable,$valeur,$par_defaut = null,$clear = null) {
    $item = explode('.',$variable);
    $v = $item[count($item)-1];
    $bloc = str_replace('.'.$v,'',$variable);
    if ($this->ItemExists($variable)||$this->ItemExists($v,$bloc)) {
        if (isset($valeur) && ($valeur!=$clear))
            $this->Parse($variable,$valeur);
        else
            $this->Parse($variable,'');
    }
  }
  //s'utilise normalement avec un @ devant
  function ParseOrBlocClear($variable,$valeur,$par_defaut = null,$clear = null) {
    $item = explode('.',$variable);
    $v = $item[count($item)-1];
    $bloc = str_replace('.'.$v,'',$variable);
    if ($this->ItemExists($v,$bloc) && $this->BlocExists($bloc)) {
        if (isset($valeur) && ($valeur!=$clear))
            $this->Parse($variable,$valeur);
        else {
            if ($par_defaut)
                $this->Parse($variable,$par_defaut);
            else
                $this->EraseBloc($bloc);
        }
    }
  }
  function GetSingleResult($sql) {
    if($sql) {
        $this->DoQuery($sql);
        return $this->dbGetOne();
    } else
        return null;
  }

public function open($file = '', $cached = '', $cached_time = '') {
    if(empty($file)) {
		$template = substr(basename($_SERVER['PHP_SELF']),0,-4);
		$len_nom = strlen($template);
		$niveau = $this->priorite_utilisateur;
		if(!is_numeric($niveau)) $niveau = 0;
		$ideal = $template.'_p'.$niveau;
		$temp_ok = '';
		if($dir = @opendir('.')) {
			while(($filename = readdir($dir))!==false) {
				if(strlen($filename)>5 && substr($filename,-5)=='.html') {
					$filename = substr($filename,0,-5);
					if(preg_match("'^($template)(_p[0-9]{1,2}){0,1}$'",$filename)) {
						if($filename <= $ideal && $filename > $temp_ok) $temp_ok = $filename;
					}
				}
			}
		} 
		if($temp_ok) $file = $temp_ok.'.html';
	} 
	parent::open($file, $cached, $cached_time);
}   
 */
/****************************************************/
/* Surcharge de la fonction Write()                 */
/****************************************************/
/* Les fichiers TOP et MENU sont stockés dans       */
/* un tableau tab_tpl.                              */
/* Lorsque Write() est appelé par un autre fichier  */
/* on affiche le contenu de tab_tpl puis on appelle */
/* la fonction Write() pour le fichier en cours     */
/****************************************************/
 function Write() {
    $nom_template = str_replace('\\','/',$this->f[$this->f_no]['name']);
    if(strpos($nom_template,'/top.html')||strpos($nom_template,'/menu.html')) {
      $this->tab_tpl[] = $this->output();
    } else {
      if(isset($this->tab_tpl)) {
        foreach($this->tab_tpl as $valeur) {
           echo $valeur;
        }
        unset($this->tab_tpl);
      }
      parent::Write();
    }
  }

 function chargeBuffer($in) 
    {
        $in = preg_replace('#({)(bloc::)(.*)(})#','<$2$3>',$in);
        $in = preg_replace('#({/)(bloc::)(.*)(})#','</$2$3>',$in);
        
        $this->f_no++;
        $this->initTemplateVars();
        $this->f[$this->f_no]['buffer'] = $in;
        $this->f[$this->f_no]['shortcut_blocs']['all'] = $this->captureAllBlocs();
        $this->endBlocVerify();
		$this->dualBlocVerify();
		$this->f[$this->f_no]['items'] = $this->captureItems();
		$this->captureItemsInEachBloc();
    }
    
 function output_utf8()
    {
        return utf8_encode($this->output());
    }

 function output_json()
    {
        return json_encode(utf8_encode($this->output()));
    }
} // Fin de la classe;
?>