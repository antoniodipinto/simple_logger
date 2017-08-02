<?php

/**
 * Project Name: simple_logger
 * File Name: Logger.php
 */

namespace SimpleLogger;

/**
 *
 * Class Simple
 * @package SimpleLogger
 * @author Antonio Di Pinto <a.dipinto@codeincode.it>
 * @url https://github.com/antoniodipinto/simple_logger
 *
 */
class Simple {
	
	// -------------------------------
	//  fields
	// -------------------------------
	/**
	 * Instance of the class
	 *
	 * @var Simple $__instance
	 */
	private static $__instance;
	
	/**
	 * Log array
	 *
	 * @var array $_log
	 */
	private $_log = array ();
	
	/**
	 * Log file path
	 *
	 * @var null|string $_log_file
	 */
	private $_log_file = null;
	
	// -------------------------------
	//  constructor
	// -------------------------------
	/**
	 * Simple constructor.
	 */
	private function __construct() {
		set_exception_handler( array ( $this , "exception" ) );
		set_error_handler( array ( $this , "simple_error_handler" ) );
		date_default_timezone_set( SimpleConfigs::LOG_TIMEZONE );
		
		$this->_prepare();
	}
	
	// -------------------------------
	// public
	// -------------------------------
	
	/**
	 * Method for INFO log type
	 *
	 * @param string|array $message Message to show in log
	 * @param null|string|array $params Optional parameters or additional data to show in log
	 * @param null|string $file Default PHP __FILE__ to show the path of the file that request the log
	 * @param null|string $method Default PHP __METHOD__ to show the method that request the log
	 */
	public function info( $message , $params = null , $file = null , $method = null ) {
		$this->_setLog( SimpleConfigs::LOG_TYPE_INFO , $message , $params , $file , $method );
	}
	
	
	/**
	 * Method for WARNING log type
	 *
	 * @param string|array $message Message to show in log
	 * @param null|string|array $params Optional parameters or additional data to show in log
	 * @param null|string $file Default PHP __FILE__ to show the path of the file that request the log
	 * @param null|string $method Default PHP __METHOD__ to show the method that request the log
	 */
	public function warning( $message , $params = null , $file = null , $method = null ) {
		$this->_setLog( SimpleConfigs::LOG_TYPE_WARNING , $message , $params , $file , $method );
	}
	
	
	/**
	 * Method for ERROR log type
	 *
	 * @param string|array $message Message to show in log
	 * @param null|string|array $params Optional parameters or additional data to show in log
	 * @param null|string $file Default PHP __FILE__ to show the path of the file that request the log
	 * @param null|string $method Default PHP __METHOD__ to show the method that request the log
	 */
	public function error( $message , $params = null , $file = null , $method = null ) {
		$this->_setLog( SimpleConfigs::LOG_TYPE_ERROR , $message , $params , $file , $method );
	}
	
	
	/**
	 * Method for EXCEPTION log type
	 *
	 * @param \Exception $exception Exception object
	 * @param null|string $method Default PHP __METHOD__ to show the method that request the log
	 */
	public function exception( \Exception $exception , $method = null ) {
		$this->_setLog( SimpleConfigs::LOG_TYPE_EXCEPTION , $exception->getMessage() , $exception->getTrace() , '[Line: ' . $exception->getLine() . '] ' . $exception->getFile() , $method );
	}
	
	
	/**
	 * Returns all the log registered until that call
	 *
	 * @return array $_log
	 */
	public function getLog() {
		return $this->_log;
	}
	
	/**
	 * @param null $error_number Error type number E_ERROR, E_USER_NOTICE etc..
	 * @param mixed $error_message Error message
	 * @param string $error_file Error file path
	 * @param string $error_line Error file line
	 */
	public function simple_error_handler( $error_number = null , $error_message , $error_file , $error_line ) {
		$this->_setLog( SimpleConfigs::LOG_TYPE_ERROR , '[' . $this->_getErrorType($error_number) . '] ' . $error_message , null , '[Line: ' . $error_line . '] ' . $error_file , null );
	}
	
	// -------------------------------
	// private
	// -------------------------------
	
	/**
	 *  Prepare the folder and the file for the log
	 */
	private function _prepare() {
		
		$today         = date( SimpleConfigs::LOG_FILE_DATE_FORMAT );
		$log_file_name = $today . SimpleConfigs::LOG_FILE_FORMAT;
		$log_file      = SimpleConfigs::LOG_FILE_FOLDER . DIRECTORY_SEPARATOR . $log_file_name;
		
		if ( ! file_exists( SimpleConfigs::LOG_FILE_FOLDER ) ) {
			mkdir( SimpleConfigs::LOG_FILE_FOLDER . DIRECTORY_SEPARATOR );
		}
		
		if ( ! file_exists( $log_file ) ) {
			$this->_setLogFile( $log_file );
		} else {
			$this->_log_file = $log_file;
		}
		
	}
	
	/**
	 * Prepare the file.log
	 *
	 * @param string $log_file Log file path
	 */
	private function _setLogFile( $log_file ) {
		
		try {
			fopen( $log_file , "w" );
			$this->_log_file = $log_file;
		} catch ( \Exception $e ) {
			$this->_log_file = null;
			$this->exception( $e , __METHOD__ );
		}
	}
	
	/**
	 * Set the log in the instance
	 * format the log properly
	 * write the log in the file
	 *
	 * @param string $type The type of the log
	 * @param string|array $message Message to show in log
	 * @param null|string|array $params Optional parameters or additional data to show in log
	 * @param null|string $file Default PHP __FILE__ to show the path of the file that request the log
	 * @param null|string $method Default PHP __METHOD__ to show the method that request the log
	 */
	private function _setLog( $type , $message , $params = null , $file = null , $method = null ) {
		
		try {
			
			$data = $this->_formatLog( $type , $message , $params , $file , $method );
			$this->_writeLog( $data );
			$this->_debugLog( $type , $message , $params , $file , $method );
			
		} catch ( \Exception $e ) {
			$this->exception( $e , __METHOD__ );
		}
	}
	
	/**
	 * Store the log in files
	 *
	 * @param array $data Array of formatted log
	 */
	private function _writeLog( $data ) {
		
		if ( null != $this->_log_file ) {
			
			if ( ! is_null( $data ) && is_array( $data ) ) {
				
				try {
					
					$log = "\t======================= START ======================= \n\n";
					
					$log .= "\t\tType: " . $data[ 'type' ] . "\n";
					$log .= "\t\tTime: " . $data[ 'time' ] . "\n";
					$log .= "\t\tFile: " . $data[ 'filename' ] . "\n";
					$log .= "\t\tMethod: " . $data[ 'method' ] . "\n";
					$log .= "\t\tMessage: " . $data[ 'message' ] . "\n";
					
					if ( null != $data[ 'params' ] ) {
						$log .= "\t\tParams: " . $data[ 'params' ] . "\n";
					}
					
					$log .= "\n\t======================= END ======================= \n";
					
					file_put_contents( $this->_log_file , $log , FILE_APPEND );
					
				} catch ( \Exception $e ) {
					
					//You can see this exception enabling "DEBUG MODE"
					$this->exception( $e , __METHOD__ );
				}
			}
		}
	}
	
	/**
	 * Prepare the log well formatted for the file
	 *
	 * @param string $type The type of the log
	 * @param string|array $message Message to show in log
	 * @param null|string|array $params Optional parameters or additional data to show in log
	 * @param null|string $file Default PHP __FILE__ to show the path of the file that request the log
	 * @param null|string $method Default PHP __METHOD__ to show the method that request the log
	 *
	 * @return array
	 */
	private function _formatLog( $type , $message , $params = null , $file = null , $method = null ) {
		
		//Exception message can be an array
		if ( null != $message && ! empty( $message ) ) {
			if ( is_array( $message ) ) {
				$message = json_encode( $message , JSON_PRETTY_PRINT );
			} else if ( is_object( $message ) ) {
				$message = json_encode( (array) $message , JSON_PRETTY_PRINT );
			}
		} else {
			$message = SimpleConfigs::LOG_DEFAULT_NO_MESSAGE;
		}
		
		if ( null != $params && ! empty( $params ) ) {
			if ( is_array( $params ) ) {
				$params = json_encode( $params , JSON_PRETTY_PRINT );
			} else if ( is_object( $params ) ) {
				$params = json_encode( (array) $params , JSON_PRETTY_PRINT );
			}
		} else {
			$params = SimpleConfigs::LOG_DEFAULT_NO_PARAMETERS;
		}
		
		return array (
			'time'     => date( SimpleConfigs::LOG_DATE_FORMAT ) ,
			'type'     => $type ,
			'filename' => $file ,
			'method'   => $method ,
			'message'  => $message ,
			'params'   => $params
		);
		
	}
	
	/**
	 * Push the last log in the instance log file and if
	 * LOG_IS_DEBUG it shows directly the last log
	 *
	 *
	 * @param string $type The type of the log
	 * @param string|array $message Message to show in log
	 * @param null|string|array $params Optional parameters or additional data to show in log
	 * @param null|string $file Default PHP __FILE__ to show the path of the file that request the log
	 * @param null|string $method Default PHP __METHOD__ to show the method that request the log
	 */
	private function _debugLog( $type , $message , $params = null , $file = null , $method = null ) {
		
		//Exception message can be an array
		if ( null == $message || empty( $message ) ) {
			$message = SimpleConfigs::LOG_DEFAULT_NO_MESSAGE;
		}
		
		if ( null == $params || empty( $params ) ) {
			$params = SimpleConfigs::LOG_DEFAULT_NO_PARAMETERS;
		}
		
		$log = array (
			'time'     => date( SimpleConfigs::LOG_DATE_FORMAT ) ,
			'type'     => $type ,
			'filename' => $file ,
			'method'   => $method ,
			'message'  => $message ,
			'params'   => $params
		);
		
		array_push( $this->_log , $log );
		
		if ( SimpleConfigs::LOG_IS_DEBUG ) {
			echo json_encode( $log , JSON_PRETTY_PRINT );
		}
	}
	
	
	/**
	 * @param int|string $type Error code
	 * @url http://php.net/manual/en/errorfunc.constants.php#109430
	 *
	 * @return string Formatted error type
	 */
	private function _getErrorType( $type ) {
		
		switch ( $type ) {
			case E_ERROR: // 1 //
				return 'E_ERROR';
			case E_WARNING: // 2 //
				return 'E_WARNING';
			case E_PARSE: // 4 //
				return 'E_PARSE';
			case E_NOTICE: // 8 //
				return 'E_NOTICE';
			case E_CORE_ERROR: // 16 //
				return 'E_CORE_ERROR';
			case E_CORE_WARNING: // 32 //
				return 'E_CORE_WARNING';
			case E_COMPILE_ERROR: // 64 //
				return 'E_COMPILE_ERROR';
			case E_COMPILE_WARNING: // 128 //
				return 'E_COMPILE_WARNING';
			case E_USER_ERROR: // 256 //
				return 'E_USER_ERROR';
			case E_USER_WARNING: // 512 //
				return 'E_USER_WARNING';
			case E_USER_NOTICE: // 1024 //
				return 'E_USER_NOTICE';
			case E_STRICT: // 2048 //
				return 'E_STRICT';
			case E_RECOVERABLE_ERROR: // 4096 //
				return 'E_RECOVERABLE_ERROR';
			case E_DEPRECATED: // 8192 //
				return 'E_DEPRECATED';
			case E_USER_DEPRECATED: // 16384 //
				return 'E_USER_DEPRECATED';
		}
		
		return "";
	}
	
	// -------------------------------
	// STATIC
	// ------------------------------
	
	/**
	 * Instance of the class
	 *
	 * @return Simple $__instance
	 */
	public static function logger() {
		
		if ( is_null( self::$__instance ) ) {
			self::$__instance = new self();
		}
		
		return self::$__instance;
	}
}