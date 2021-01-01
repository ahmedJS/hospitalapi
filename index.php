<?php 

use classes\Route;
use classes\ApiCore;
use classes\ApiContainer;

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

$system->group("home",[
	["completed_url" => "/xnxx","method"=>"GET","action"=>function($req,$res){
		$res->write("hello from group");
		return $res;
	}]
]);

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