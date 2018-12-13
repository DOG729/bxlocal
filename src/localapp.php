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

    function _encode($text) {
        $text = json_encode($text);
        $config = self::config('app.bitrix_core');
        $ekey = hash('SHA256', $config['api_token'], true);
        srand(); $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
        if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
        $encoding = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $ekey, $text . md5($text), MCRYPT_MODE_CBC, $iv));
        return $iv_base64 . $encoding;
      }
       
      function _decode($code) {
        $config = self::config('app.bitrix_core');
        $ekey = hash('SHA256', $config['api_token'], true);
        $iv = base64_decode(substr($code, 0, 22) . '==');
        $code = substr($code, 22);
        $text = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $ekey, base64_decode($code), MCRYPT_MODE_CBC, $iv), "\0\4");
        $hash = substr($text, -32);
        $text = substr($text, 0, -32);
        if (md5($text) != $hash) return false;
        $text = json_decode($text);
        return $text;
      }
}

?>