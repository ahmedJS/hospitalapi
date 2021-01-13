<?php  
	
	namespace classes;

	interface  Irequest {
		public function setParam($param=null) : Irequest;
		public function collectInformation();
	}

	/**
	 * 
	 */
	class RequestApi implements Irequest
	{
		// this contains body of the request
		private string $body;

		// this contains all the request headers
		private array $headers;

		// array $param contains variables in the url
		// or null
		private  $params;

		function __construct()
		{

		}

		public function getBody(){
			return $this->body;
		}

		public function getAllHeaders(){
			return $this->headers;
		}

		public function getParams(){
			return $this->params;
		}

		public function getRequestMethod(){
			return $_SERVER["REQUEST_METHOD"];
		}

		public function setParam($param=null) : Irequest
		{
			// set the parameters coming from uri
			$this->params = $param;
			// collect information about (headers, body) of request
			$this->collectInformation();
			// return the class is_self to use i
			return $this;
		}
		
		//implemented
		public function collectInformation()
		{
			// set body to RequestApi::$body;
			$this->collectBody();
		}

		public function collectBody()
		{
			$request_method = $this->getRequestMethod();
			switch ($request_method)
			{
				case 'POST':
						$this->body = file_get_contents("php://input");
					break;
				case 'PUT':
					$this->body = file_get_contents("php://input");
					break;
			}
		}


	}
?>