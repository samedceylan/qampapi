<?php


use EF2\Framework;
use EF2\Core\DI;
use EF2\Core\Router;
use EF2\Core\Loader;
use EF2\Http\HttpException;
use EF2\Debug;
use EF2\Db\Eloquent;

/* framework include */
$framework_path=dirname(__FILE__).'/framework';
require_once $framework_path.'/Framework.php';

require_once dirname(__FILE__).'/vendor/autoload.php';
$ef2=new Framework;
$ef2->register();

function options()
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE', true);

    header('Access-Control-Allow-Headers: *', true);

    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        header("HTTP/1.1 200 OK", true);
        die();
    }

    if (array_key_exists('HTTP_ACCESS_CONTROL_REQUEST_HEADERS', $_SERVER)) {
        header('Access-Control-Allow-Headers: ' . $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']);
    } else {
        header('Access-Control-Allow-Headers: *');
    }
}


options();
/* loader */


DI::bind("loader",function(){

    $loader=new Loader;
    $loader->setDir(array(
        dirname(__FILE__)."/public/main/controller/",
        dirname(__FILE__)."/public/main/model/",
        dirname(__FILE__)."/public/main/components/",
    ));
    $loader->register();
    return $loader;

});


DI::bind("db",function() {
    $eloquent=new Eloquent([
        'driver'    => 'mysql',
        'host'      => '127.0.0.1',
        'database'  => 'qamp',
        'username'  => 'root',
        'password'  => 'root',
        'charset'   => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix'    => ''
    ]);

    $eloquent->connect();

    return $eloquent;

});

/* route ayarlama. açılış controller ve action */
$baseUrl=baseUrl();
DI::bind("router",function()use($baseUrl){
    $router = new Router;
    $router->route(dirname(__FILE__)."/route.php");
    $router->handle($baseUrl);
    return $router;
});

DI::singleton("result",function(){
    return new ResultResponse;
})->resolveWhen("Result");

DI::singleton("formvalidation",function(){
    return new FormValidation("tr",dirname(__FILE__)."/public/language/gump");
})->resolveWhen("SValidation");

DI::bind("debug", function () {

    $debug = new Debug;
    $debug->register(Debug::DEVELOPMENT);
    return $debug;

});


function baseUrl()
{

    $uri=str_replace("/index.php", "", $_SERVER["SCRIPT_NAME"]);

    return str_replace($uri, "", $_SERVER['REQUEST_URI']);
}

try{

    $ef2->make();

}catch(HttpException $e)
{
    echo $e->getHttpCode()." ".$e->getMsg();
}