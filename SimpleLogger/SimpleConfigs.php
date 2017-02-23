<?php
/**
 * Project Name: simple_logger
 * File Name: SimpleConfigs.php
 */

namespace SimpleLogger;

interface SimpleConfigs{
	
	/**
	 *  Log folder name
	 */
	const LOG_FILE_FOLDER = "simple_log";
	
	/**
	 *  Log file extension
	 */
	const LOG_FILE_FORMAT = ".log";
	
	/**
	 *  Date format of the log file name
	 *  @important this will be part of the file name, take care of the "/" and "."
	 */
	const LOG_FILE_DATE_FORMAT = "Y_m_d";
	
	/**
	 *  Date format in the log details
	 */
	const LOG_DATE_FORMAT = "Y/m/d H:i:s";
	
	/**
	 *  Timezone for the log
	 */
	const LOG_TIMEZONE = "UTC";
	
	/**
	 * Debug mode enabled
	 *
	 * @default true
	 */
	const LOG_IS_DEBUG = true;
	
	/**
	 *  Custom empty var message
	 */
	const LOG_DEFAULT_NO_MESSAGE = "No message";
	
	/**
	 * Custom empty var message
	 */
	const LOG_DEFAULT_NO_PARAMETERS = "No parameters";
	
	/**
	 *  String to define log type INFO
	 */
	const LOG_TYPE_INFO = "INFO";
	
	/**
	 * String to define log type WARNING
	 */
	const LOG_TYPE_WARNING = "WARNING";
	
	/**
	 *  String to define log type ERROR
	 */
	const LOG_TYPE_ERROR = "ERROR";
	
	/**
	 *  String to define log type EXCEPTION
	 */
	const LOG_TYPE_EXCEPTION = "EXCEPTION";
}