<?
namespace local\module;

class Console extends BxLocal {

    private $status = '';
    
    private static $_dir = '';

    function __construct(){

        $this->status = 'ok';

    }

    function commands(){
        return array(
            'set:app' => '[name] - Created app class',
            'delete:app' => '[name] - Delete app class',
            'create:config' => '[name] - Created config'
        );
    }


    function start($command){
        switch ($command[1]) {
            case "set:app":
                if(!empty($command[2])){
                    return self::setApp($command[2]);
                }else{
                    return "\x1b[0;41m error no name \x1b[0m";
                }
                break;
            case "delete:app":
                if(!empty($command[2])){
                    return self::deleteApp($command[2]);
                }else{
                    return  "\x1b[0;41m error no name \x1b[0m";
                }
                break;
            case "create:config":
                if(!empty($command[2])){
                    return self::createConfig($command[2]);
                }else{
                    return self::createConfig(false);
                }
                break;
        }

    }

    function console($function,$dir){
        self::$_dir = $dir;
        $info_command = false;
        $reConfig = require $dir."/config.php";
        if(!empty($reConfig['console'])){
            foreach($reConfig['console'] as $console){
                $arConsole = new $console;
                $arConsole->namespace = $console;
                $resConsole[] = $arConsole;
                $in_command = $arConsole->commands();
                if(!empty($in_command[$function[1]])){
                    $info_command = true;
                    $result = $arConsole->start($function);
                }
            }
        if($info_command){
            return $result;
        }else{
            foreach($resConsole as $code){
                echo "\x1b[0;41m ==============\n No Command: \x1b[0m \n";
                echo "Module ::".$code->namespace." \n";
                foreach($code->commands() as $command => $info){
                    echo "\x1b[0;41m".$command." ".$info."\x1b[0m \n";
                }
                return "\n";
            }
        }
        
        }
        return 'Error';
        
    }

    function install()
    {
        self::createConfig(false);
        return 'Install Local Bitrix';
    }

    function update()
    {
        return 'Update Local Bitrix';
    }

    //Console Function
    public static function setApp($name){
        $newfile = self::$_dir."\app\\".$name."Class.php";
        $file = self::$_dir."\\vendor\\dog729\\bxlocal\\tasks\\__Class.php.ex";
        if (copy($file, $newfile)) {
            BxLocal::EditFile($newfile,'=NAME=',$name);
            return 'Success file created';
        }else{
            return 'Could not create';
        }
        
    }
    public static function deleteApp($name){
        $filename = self::$_dir."\app\\".$name."Class.php";
        if (!file_exists($filename)) {
            return 'Could not deleted';
        }else if(unlink($filename)){
            return 'Success file deleted';
        }else{
            return 'Error';
        }
        
    }
    public static function createConfig($name=false){
        $_mes = '';
        if (!file_exists(self::$_dir.'\\config')) {
        mkdir(self::$_dir.'\\config', 0777);
        $newfile = self::$_dir."\config\\app.php";
        $file = self::$_dir."\\vendor\\dog729\\bxlocal\\tasks\\appconfig.php.ex";
            if (copy($file, $newfile)) {
                BxLocal::EditFile($newfile,'=TOKEN=',md5(rand(11111,99999)));
                if($name){
                    $_mes = "Install config app \n";
                }else{
                    return 'Install config app';
                }
            }
        }
        if($name){
        $newfile = self::$_dir."\config\\".$name.".php";
        $file = self::$_dir."\\vendor\\dog729\\bxlocal\\tasks\\__Config.php.ex";
            if (copy($file, $newfile)) {
                BxLocal::EditFile($newfile,'=NAME=',$name);
                return $_mes.'Success config created';
            }
        }else{
            return 'no name config';
        }

    }


}

?>