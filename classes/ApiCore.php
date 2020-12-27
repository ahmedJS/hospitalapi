<?php  
	namespace classes;


	use classes\ApiRoute;
	use classes\ResponseApi;
	use classes\RequestApi;
	use classes\Middleware;
	interface IApi{
		public function get($uri,$action);
		public function post($uri,$action);
		public function map($methods,$uri,$action);
		public function add($url,$action);
		public function run();
	}

	/**
	 * 
	 */

	class ApiCore extends ApiRoute implements IApi
	{
		private $response   = null;
		private $request    = null;
		public  $container  = null;
		private $di 		= null;
		private $Middleware = [];
		function __construct($container=null)
		{
			$this->container = $container;
			$this->response  = new ResponseApi;
			$this->request   = new RequestApi;
			$this->set_lib();
		}

		// implemented
		public function get($uri,$action)
		{
			$params = ["action" => $action,
						"method" => "GET"];

			$this->addRoute($uri,$params);
				// will contain response object or null
				//$output = $action($this->request,$this->response);
		}

		//implemented
		public function post($uri,$action)
		{
			$params = ["action" => $action,
						"method" => "POST"];

			$this->addRoute($uri,$params);
		}

		//implemented
		public function map($methods,$uri,$action)
		{
			$params = ["action" => $action,
						"method" => "MULTI",
						"multi_methods" => $methods
					  ];

			$this->addRoute($uri,$params);
		}


	    function uri_middleware_filter($uri)
	    {
	        $uri = preg_replace("/\//","\/",$uri);
	        $uri = "/^".$uri."[\D]*"."$/";
	        return $uri;
	    }


		//implemented 
		public function add($url,$action)
		{
			// 'AR' already request uri home/name
			$request_uri = $_SERVER["REQUEST_URI"];

			//convert middleware url into regx
			$url_logic = $this->uri_middleware_filter($url);

			if(preg_match($url_logic, $request_uri))
			{
				foreach ($this->routes as $route) {
					$filtered_user_route_api = $route["route"];
					if(preg_match($filtered_user_route_api, substr($request_uri, 1)))
					{
						$this->Middleware[]= new Middleware($action);
					}
				}
			}
		}


		//implemented
		public function run()
		{
			$url = substr($_SERVER["REQUEST_URI"], 1);
			if($this->match($url))
			{
				// get the params which is set during match to route
				$params = $this->getParams();

				//already method requested
				$already_method = $_SERVER["REQUEST_METHOD"];
				// derived from operation used (get, post,multi, put,delete,patch)
				$request_method = $params["additional"]["method"];

				//extract params from uri into new associative array named $req_prm
				foreach ($params as $key => $value)
				{
					if ($key!="additional" && is_string($key)) {
						$req_prm[$key] = $value;
					}
				}

				switch ($request_method) {
					case 'GET':
						if($this->check_similarity($request_method,$already_method,0)){
							$action = ($params)["additional"]["action"];
							break;
						}
					case 'MULTI':
						//methods set by user
						$methods = $params["additional"]["multi_methods"];
						//convert to array
						$methods = $this->extract_multi_request($methods);
						foreach ($methods as $value)
						{
							if($this->check_similarity($value,$already_method))
							{
								$action = ($params)["additional"]["action"];		
							}
						break;
						}
					case "POST":
						if($this->check_similarity($request_method,$already_method,0)){
							$action = ($params)["additional"]["action"];
						}
						break;
					default:
						# code...
						break;
				}

				// if the request it's ok
				if (isset($action))
				{

					//load library of dependency injection
					$lib = $this->get_lib();

					//result returned object of response as well as perform the function
					$result = ($action)($this->request->setParam(isset($req_prm)?$req_prm:null),$this->response,$lib);

					// middleware at first
					$this->perform_middleware();

					// process the casched response
					$result->processResponse();
				}
				else
				{
					// in future handle exception with special class
					die("the request is not exists");
				}

			}
		}

	public function set_lib()
	{
		// if the container is not null
		if($this->container != null)
		{
			$libs = $this->container->getlibs();
			// if the libs is not null
			if($libs != null)
			{
				foreach ($libs as $l_name => $l_value)
				{
					$this->di[$l_name] = $l_value;
				}
			}
		}
	}

	private function get_lib()
	{
		return ($this->di != null) ? $this->di : null ;
	}

	public function check_similarity($param1 ,$param2,$strict = 0)
	{
		if($strict == 0){
			if($param1 != $param2)
			{
				return false;
			}		
		}else
		{
		if($param1 !== $param2)
			{
				return false;
			}
		}
		return true;
		
	}

	public function extract_multi_request(string $methods) : array
	{
		$array = explode(",",preg_replace("/[\s]+/","",$methods));
		return $array;
	}

	public function perform_middleware()
	{
		if($this->Middleware != null)
		{
			$mid_ = array_reverse($this->Middleware);
			foreach ($mid_ as $midd_class)
			{
				$midd_class->run();
			}
		}

	}

	/*

			// if the url is matched to the routes
			if($this->match($url))
			{
				$params = $this->getParams();
				var_dump($params);
				$action = isset($params["action"])?$params["action"]:null;
				$params = isset($params["params"])?$params["params"]:null;
				if(isset($action))
				{
					$response = ($action)($this->request,$this->response);
					$response->processResponse();
				}else
				{
					throw new \Exception("must pass an action into your route", 1);
					
				}
			}


	*/
	}
?>