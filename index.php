<?php 
use classes\Route;
use classes\ApiCore;
use classes\ApiContainer;

// error handling testing

// error_reporting(E_ALL | E_STRICT);

// ini_set("display_errors", 1);

// var_dump($clasa);

spl_autoload_register(function($class_name){
	require_once $class_name.".php";
});

// dependecy injection
class testing{};
$container = new ApiContainer;
$container->addlib("testing",new testing);
// dependecy injection

$system = new ApiCore($container);

$system->post("home/name",function($req,$res){
	$res->write("hello from post");
	return $res;
});

$system->get("home/interview/excellent",function($req,$res,$di){
	$res->write("home/interview/excellent ok ");
	return $res;
});

$system->add("/home",function(){
	echo "middleware1";
});


$system->get("home/name",function($req,$res,$di){
	$res->write("hello from get");
	return $res;
});

$system->run();




/*
**** route class usage ****
$route = new Route;
// ******* usage of route class  *****
// here to adding route to system which specify the base rule of requests
$route->addRoute("{controller}/{action}");

// here test the uri if accepted by routes setted
$route->patchRoute("controller/action");

*/


/*
	here is tasting waste

	$system->map("GET,POST, PUT","home/name",function($req,$res){
	$res->write("its ok");
	return $res;
});
*/
?>