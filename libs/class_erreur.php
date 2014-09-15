<?php 

/**
  * Classe contenant des fonctions communes
  */

class LogErreur
{

    // Instance pour application du singleton
    protected static $instance = null;
    
    /**
      * Méthode d'instanciation de l'objet en singleton
      *
      * design pattern singleton
      *
      * @author Vincent Chaillou <vincent.chaillou@connectassistance.fr>
      * @since 2012-06-26
      * @version v1
      */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new LogErreur();
        }
        return self::$instance;
    }
    
    private function __construct()
    {
        ini_set('display_errors',0);
        set_error_handler(array($this,'MyErrorHandler'),E_ALL);
        set_exception_handler(array($this,'MyExceptionHandler'));
        register_shutdown_function(array($this,'MyFatalError'));
    }
    
    // Définit une fonction utilisateur de gestion d'erreurs 
    public function MyErrorHandler($errno, $errstr, $file, $ligne)
    {
        // Ecriture dans un fichier
        $error_string = date('d/m/Y H:i:s').' [ERREUR]: '.self::NumErreurToString($errno).' - '.$errstr.' - Script: '.$file.' - Ligne '.$ligne."\r\n";
        $handle = fopen(str_replace('\\','/',dirname(__FILE__)).'/log_erreur/'.date('Ymd').'.txt', 'a');
        fwrite($handle, $error_string);
        fclose($handle);
        
        // Affichage de l'erreur
        exit(self::displayMsg($errstr));
        
        return true;
    }
    
    // Définit une fonction utilisateur de gestion d'exceptions
    public function MyExceptionHandler($exception)
    {
        // Ecriture dans un fichier
        $exception_string = date('d/m/Y H:i:s').' [EXCEPTION]: '.$exception->getMessage().' - Script: '.$exception->getFile().' - Ligne '.$exception->getLine()."\r\n";
        $handle = fopen(str_replace('\\','/',dirname(__FILE__)).'/log_erreur/'.date('Ymd').'.txt', 'a');
        fwrite($handle, $exception_string);
        fclose($handle);
        
        // Affichage de l'erreur
        exit(self::displayMsg($exception->getMessage()));
        
        return true;
    }
    
    // Définit une fonction utilisateur de gestion des erreurs fatales
    public function MyFatalError()
    {
        $error = error_get_last();
        
        if (!empty($error)) {
            // Ecriture dans un fichier
            $exception_string = date('d/m/Y H:i:s').' [ERREUR FATALE]: '.self::NumErreurToString($error['type']).' - '.$error['message'].' - Script: '.$error['file'].' - Ligne '.$error['line']."\r\n";
            $handle = fopen(str_replace('\\','/',dirname(__FILE__)).'/log_erreur/'.date('Ymd').'.txt', 'a');
            fwrite($handle, $exception_string);
            fclose($handle);
            
            // Affichage de l'erreur
            exit(self::displayMsg($error['message']));
        }
    }
    
    // Correspondance du type d'erreur avec le numéro d'erreur
    private function NumErreurToString($num_err)
    {
        $errorlevels = array(
                             2047 => 'E_ALL',
                             1024 => 'E_USER_NOTICE',
                             512  => 'E_USER_WARNING',
                             256  => 'E_USER_ERROR',
                             128  => 'E_COMPILE_WARNING',
                             64   => 'E_COMPILE_ERROR',
                             32   => 'E_CORE_WARNING',
                             16   => 'E_CORE_ERROR',
                             8    => 'E_NOTICE',
                             4    => 'E_PARSE',
                             2    => 'E_WARNING',
                             1    => 'E_ERROR'
                            );
        $result = '';
        foreach ($errorlevels as $number => $name) {
            if ($num_err == $number) {
                $result .= $name; 
            }
        }
        if (empty($result)) $num_err;
        return $result;
    }
    
    // Affiche les erreurs
    public function displayMsg($message)
    {
        $msg_a_afficher = '<div align="center" valign="middle" style="color: red;">';
        $tab_msg = explode(':',$message);
        if (preg_match("/TPLN DB Error/i",$message)) {
            $db_err = trim(htmlentities(str_replace('TPLN DB Error ','',$tab_msg[0])));
            $db_err = substr($db_err,9);
            $list_db_err['0']    = "Problème de connexion à la base";
            $list_db_err['0.1']  = 'Pas de connection trouvé';
            $list_db_err['1']    = "Problème de fermeture à la base";
            $list_db_err['2']    = "Problème de requête";
            $list_db_err['2.1']  = "Index du changement de connection";
            $list_db_err['2.11'] = "Index du changement de requête";
            $list_db_err['2.2']  = 'Pas de requête trouvée';
            $list_db_err['2.3']  = 'Colonne de résultats non valide';
            $list_db_err['3']    = 'SELECT non trouvé dans votre requête';
            $list_db_err['4']    = 'FROM non trouvé dans votre requête';
            $list_db_err['5']    = 'Showrecords() doit avoir un entier en second paramètre';
            $list_db_err['5.1']  = 'Showrecords() doit avoir un entier supérieur à zéro en second paramètre';
            foreach ($list_db_err as $key => $value) {
                if ($key == $db_err) {
                    $msg_a_afficher .= '[TPLN DB ERREUR] : '.$value;
                    break;
                }
            }
        } else if (preg_match("/TPLN error/i",$message)) {
            $msg_a_afficher .= $message;
        } else {
            $msg_a_afficher .= utf8_encode($message);
        }
        $msg_a_afficher .= '</div>';
        echo $msg_a_afficher;
    }
    
}

?>