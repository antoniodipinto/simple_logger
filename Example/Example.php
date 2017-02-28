<?php
/**
 * Project Name: simple_logger
 * File Name: example.php
 */
require_once "../SimpleLogger/SimpleLoader.php";

use SimpleLogger\Simple as Simple;

class Example {
	
	private $_log = null;
	
	function __construct() {
		$this->_log = Simple::logger();
	}
	
	public function firstExampleMethod() {
		$this->_log->info( "Message from first method" , array ( "dummy" => "params" ) , __FILE__ , __METHOD__ );
	}
	
	public function secondExampleMethod() {
		$this->_log->error( "Message from second method" , array ( "dummy" => "params" ) , __FILE__ , __METHOD__ );
	}
	
	public function thirdExampleMethod() {
		$this->_log->warning( "Message from third method" , array ( "dummy" => "params" ) , __FILE__ , __METHOD__ );
	}
	
	public function fourthExampleMethod() {
		$this->_log->exception( "Message from fourth method" , array ( "dummy" => "params" ) , __FILE__ , __METHOD__ );
	}
}
