<?php 
namespace classes;


abstract class IApiContainer {
	protected $libraries;
	abstract function addlib($lib_name,$lib_class);
	abstract function getlibs();
}

/**
 * 
 */
class ApiContainer extends IApiContainer
{
	function __construct()
	{	
	}
	function addlib($lib_name,$lib_class)
	{
		$this->libraries[$lib_name] = $lib_class;
	}
	function getlibs(){
		return ($this->libraries != null)?$this->libraries:null;
	}
}
 ?>