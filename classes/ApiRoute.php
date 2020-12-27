<?php
namespace classes;


use classes\Route;
/**
 * 
 */
class ApiRoute extends Route
{
	public function match($route_url)
	{
		foreach ($this->routes as $route)
		{
			$params = $route["params"];
			$route = $route["route"];

			if($_SERVER["REQUEST_METHOD"] !== $params["method"])
			{
				continue;
			}
			$state = $this->match_logic($route_url,$route,$params);
			if($state == true){
				return true;
			}
		}
	}

	public function addRoute($route,$params = [])
	{
		$filtered = $this->filter_route($route);
		$this->routes[] = array("params"=>$params,
								"route"=>$filtered);
	}
}
?>