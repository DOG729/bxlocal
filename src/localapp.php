<?
namespace local\module;

class LocalApp {

    function __construct(){
        
    }

    function getLocalApp(){

    }

    function setLocalApp(){

    }

    function config($code,$params){

        global $GLOBALS;

        $global_config = $GLOBALS['config'] ? $GLOBALS['config'] : array(); 

        $result = false;

        $config_dir = $_SERVER["DOCUMENT_ROOT"]."/local/php_interface/config/";
        
        if($config_file = glob($config_dir."*.php")){
            foreach($config_file as $confile){

                unset($glob_array);

                if(!empty($glob_array = $global_config[basename($confile, ".php")])){
                    $config[basename($confile, ".php")] = $glob_array;  
                }else{
                    $config[basename($confile, ".php")] = require $confile;
                }
                
            }
        }

        if(!empty($code) && is_array($config)){

            if(is_array($code) && count($code) == 1){
                //create config
                $recode = explode(".", key($code));
                if(empty($recode[1])){
                    $recode[1] = $recode[0];
                    $recode[0] = 'app';
                }
                if(!empty($config[$recode[0]])){
                    $GLOBALS['config'][$recode[0]][$recode[1]] = $code[key($code)];
                    $result = $code[key($code)];
                }
            }else{
                //get config
                $recode = explode(".", $code);
                if(empty($recode[1])){
                    $recode[1] = $recode[0];
                    $recode[0] = 'app';
                }
                if(!empty($value = $config[$recode[0]][$recode[1]])){
                    $result = $value;
                }else if(!empty($params)){
                    $result = $params;
                }
            }
        
        }

        return $result;
    }
}

?>