<?
namespace local\module;

class BxLocal {

    private $status = '';

    function __construct(){
        $this->status = 'ok';
    }

    function includeModules($vender=false){

        //all class file app
        $installAdminModul = glob($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/app/*.php");
        foreach($installAdminModul as $value_modul){
            if(strpos($value_modul, 'Class') !== false){
                require($value_modul);
            }
        }

        if($vender){
            $phprs = glob($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/sub_vendor/*/.php");
            $dirs = glob($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/sub_vendor/*/");
            $includeModule = $this->dirPath($dirs,$phprs);
            foreach($includeModule as $value_modul){
                require_once($value_modul);
            }
        }
        
    }

    function dirPath($dr,$php=array()){
        foreach($dr as $dir){
            $rsdir = glob($dir.'*/');
            $rsphp = glob($dir.'*.php');
            $arPhp[] = array_merge($rsphp, $this->dirPath($rsdir,array()));
        }
        if(!empty($arPhp)){
        foreach($arPhp as $phps){
            $php = array_merge($phps,$php);
        }}
        return $php;
    }

    function EditFile($filename,$a,$b=''){
        $file = file_get_contents($filename);
        $file = str_replace($a, $b, $file);
        return file_put_contents($filename, $file);
    }

    function autho(){
        return $this->status;
    }

   

}

?>