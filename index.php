<?php 

use classes\ApiCore;
use classes\ApiContainer;

spl_autoload_register(function($class_name){
	require_once $class_name.".php";
});


$system = new ApiCore($container);


$system->run();

?>