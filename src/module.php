<?
namespace local\module;

class BxLocal {

    private $status = '';

    function __construct(){

        $this->status = 'ok';

        $phprs = glob($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/vender/*/.php");
        $dirs = glob($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/vender/*/");
        $includeModule = $this->dirPath($dirs,$phprs);
        foreach($includeModule as $value_modul){
            require_once($value_modul);
        }
    }

    function dirPath($dr,$php=array()){
        foreach($dr as $dir){
            $rsdir = glob($dir.'*/');
            $rsphp = glob($dir.'*.php');
            $arPhp[] = array_merge($rsphp, $this->dirPath($rsdir,array()));
        }
        foreach($arPhp as $phps){
            $php = array_merge($phps,$php);
        }
        return $php;
    }

    function autho(){
        return $this->status;
    }

}

?>