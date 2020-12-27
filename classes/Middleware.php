<?php
namespace classes;

	/**
	 * 
	 */
	class Middleware
	{
		private $closuer;
		
		function __construct($closuer)
		{
			$this->closuer = $closuer;
		}

		public function run()
		{
			($this->closuer)();
		}
	}

?>