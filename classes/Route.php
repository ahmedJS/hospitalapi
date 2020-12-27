<?php 
namespace classes;

interface IRoute{
	public function addRoute($route);
	public function match($route);
}

class Route implements IRoute
{
	protected $routes = array();
	protected $params = array();

	public function getRoutes(){
		return $this->routes;
	}

	public function getParams(){
		return $this->params;
	}

	// implemented
	public function addRoute($route,$params = []){
		$filtered = $this->filter_route($route);
		$this->routes[$filtered] = $params;
	}

	/**
	 *
	 *
	 * @return true if route url is matched to routes and false if otherwise
	 */
	// implemented
	public function match($route_url)
	{
		foreach ($this->routes as $route)
		{
			$params = $route["params"];
			$route = $route["route"];
			$this->match_logic($route_url,$route,$params);
		}
	}

	protected function filter_route($route){

		$route = preg_replace("/\//", "\/", $route);

		$route = preg_replace("/\{([a-z-]+)\}/", "(?<\\1>[a-z-]+)", $route);

		$route = preg_replace("/\{([a-z-]+):([^\}]+)\}/", "(<\\1>\\2)", $route);

		$route = "/^" . $route . "$/i";

		return $route;
		
	}

	protected function match_logic($route_url,$route,$params){

			$result = preg_match_all($route, $route_url,$match);
			if($result != null)
			{
				foreach ($match as $key => $value)
				{
					// solve provlem of match results
					if(is_string($key))
					{
						if (is_array($value))
						{
							foreach ($value as $val)
							{
								$value = $val;
							}
						}

						// // set the result collected from url into params
						$this->params[$key] = $value;


					}
					
					if($params != null)
					{
						$this->params["additional"] = $params;
					}
				}

				return true;
			}
			
		return false;
	}
}



?>