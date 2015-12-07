<?php
date_default_timezone_set('Asia/Manila');
register_shutdown_function('fatalHandler');
set_error_handler('runtimeErrorHandler', E_ALL);
register_shutdown_function('fatalHandler');
require_once "../vendor/autoload.php";

use com\glyphstudios\project\common\Utility;

// Create configuration.
$configuration = (object) parse_ini_file("sample.ini", TRUE);

//Fatal Error Handler
function fatalHandler(){
    global $configuration;
    
    $errFile = "unknown file";
    $errAtr  = "shutdown";
    $errNo   = E_CORE_ERROR;
    $errLine = 0;

    $error = error_get_last();

    if( $error !== NULL) {
        $errNo   = $error["type"];
        $errFile = $error["file"];
        $errLine = $error["line"];
        $errStr  = $error["message"];
    }
    
	$message = "An Internal Runtime Error had occurred in the application:\n" . "Error No: " . $errNo . "\n" . "Error Message: " . $errStr . "\n" . "File: " . $errFile . "\n" . "Line No: " . $errLine;
	$exception = new Exception($message);
	
	if($configuration->log['enableTransLogging']){
		Utility::transLogger("mainTransLogger", "An Internal Runtime Error had occurred. Please see the gateway error logs for details.");
	}
	
	if($configuration->log['enableErrorLogging']){
		Utility::errorLogger("mainErrorLogger", $exception);
	}
    
    return TRUE;
}

//Generic Error Handler.
function runtimeErrorHandler($errNo, $errStr, $errFile, $errLine){
    global $configuration;
    
	$message = "An Internal Runtime Error had occurred in the application:\n" . "Error No: " . $errNo . "\n" . "Error Message: " . $errStr . "\n" . "File: " . $errFile . "\n" . "Line No: " . $errLine;
	$exception = new Exception($message);
	
	if($configuration->log['enableTransLogging']){
		Utility::transLogger("mainTransLogger", "An Internal Runtime Error had occurred. Please see the gateway error logs for details.");
	}
	
	if($configuration->log['enableErrorLogging']){
		Utility::errorLogger("mainErrorLogger", $exception);
	}
    
    return TRUE;
}

$app = function ($request, $response) {
    global $configuration;
    
    try{
        $request->on('data', function($data) use ($request, $response, $configuration){
            $headers = array();
            $urlArray = array();
            $urlArray = explode("/", $request->getPath());
            $controllerName = $urlArray[1];
            $action = $urlArray[2];

            $controllerName = ucwords($controllerName);
            $controllerName .= 'Controller';

            $controllerName = "com\\glyphstudios\\project\\controller\\" . $controllerName;
            
            if(class_exists($controllerName)){
                $dispatch = new $controllerName($controllerName, $action);
            }else{
                trigger_error("Invalid Controller.");
                $message = "An Internal Server Error Had Occured.";
                $headers['Content-Type'] = "text/plain";
                $headers['Content-Length'] = $message;
                $headers['Connection'] = "close";
                $response->writeHead(500, $headers);
                $response->end($message);
                return TRUE;
            }

            if((int) method_exists($dispatch, $action)){
                $message = call_user_func(array($dispatch, $action), $data);
                $headers['Content-Type'] = "text/plain";
                $headers['Content-Length'] = $message;
                $headers['Connection'] = "close";
                $response->writeHead(200, $headers);
                $response->end($message);
                return TRUE;
            }else{
                trigger_error("Invalid Controller.");
                $message = "An Internal Server Error Had Occured.";
                $headers['Content-Type'] = "text/plain";
                $headers['Content-Length'] = $message;
                $headers['Connection'] = "close";
                $response->writeHead(500, $headers);
                $response->end($message);
                return TRUE;
            }
        });
    }catch(Exception $ex){
        if($configuration->log['enableTransLogging']){
            Utility::transLogger("mainTransLogger", $ex->getMessage());
        }

        if($configuration->log['enableErrorLogging']){
            Utility::errorLogger("mainErrorLogger", $ex);
        }
        
        return TRUE;
    }
};

if(count($argv) == 1){
    echo "Missing host argument!\n";
    exit;
}

if(count($argv) == 2){
    echo "Missing port argument!\n";
    exit;
}

$host = $argv[1];
$port = $argv[2];

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);
$http = new React\Http\Server($socket, $loop);

$http->on('request', $app);
echo "Server running at http://$host:$port\n";

$socket->listen($port, $host);
$loop->run();