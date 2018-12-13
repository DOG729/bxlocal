<?
namespace local\module\light;

use local\module\LocalApp;

class FLight {

    private $code = 'f1';
    

    function __construct(){
        
    }

    function request(){
        return new Request;
    }

}

class Request
{
    public $error = "";

    function __construct()
    {

    }

    public function encode($text,$ext=false,$user=false){
        $data = [
            'data' => $text,
            'ext' => $ext ? $ext : (time() + 86400),
        ];
        return LocalApp::_encode($data);
    }

    public function decode($code)
    {
        $data = LocalApp::_decode($code);

        if($data->ext > time()){
            return $data->data;
        }else{
           $this->error = "Request Expired - $data->ext";
           return false;
        }
    }

}

?>