<?php  
	
	namespace classes;


	
	interface IResponseApi {
		public function withHeader($header_type,$header_value);
		public function withStatus($status);
		public function processResponse();
		public function write($output);
	}

	class ResponseApi 
	{
		private $body   = "";
		private $headers = array();


		// will improved in future 
		// 200 is the default response status
		private $status = 200;

		function __construct()
		{
			
		}

		 //implemented
		public function withHeader($header_type,$header_value){
			$this->headers[$header_type] = $header_value;
		}

		 //implemented
		public function withStatus($status){
			$this->status = $status;
		}

		// optional ** is'nt implemented **
		public function withJson($array){
			$this->body = json_encode($array);
			$this->withHeader("Content-Type","application/json");
		}

		 //implemented
		public function processResponse(){
			//process headers
			foreach ($this->headers as $hname => $hvalue) {
				header($hname.":".$hvalue);
			}
			//process status
			http_response_code($this->status);
			// process the body
			echo $this->body;
		}

		//implemented
		public function write($output){
			$this->body.= $output;
		}

		
	}
?>