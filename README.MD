# SimpleLogger


Using this class it is possible to create a simple log file or log (info, warning, error, exception) at runtime.


## Features

* Log instant and print a JSON string (Debug mode enabled)
* Store Logs in custom folder and files
* Divide logs for datetime format, you can create a log file everyday or every second, all organized by time format

## Installation

In order to use SimpleLogger you just need to download the repository, put into the root of you project then:
```php
    <?php
     
    require_once "SimpleLogger/SimpleLoader.php";
    
    use SimpleLogger\Simple as Simple;
```
    
## Customization
In [SimpleConfigs.php] you can edit the following variables:
        
   ```php
        <?php

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
   ```

## Basic Usage
```php
    <?php
    
    $log = Simple::logger();
    
    //Info log with custom message, optional string parameter and the file where the log function is called
    $log->info( "Info Message" , "Optional param" , __FILE__ );
    
    //Error log with custom message and optional array parameter
    $log->error( "Error Message" , array ( "optional_params" => "value" ) );
    
    //Warning log with custom message, optional array parameter and the file where the log function is called and the optional method
    $log->warning( "Warning Message" , array ( "key" => "value" ) , __FILE__ , __METHOD__ );
    
    //Exception log based on standard exception class
    try {
    	throw new Exception( "Dummy Exception" );
    } catch ( Exception $exception ) {
    	$log->exception( $exception , __METHOD__ );
    }
    
    //Returns the instance log array
    $log->getLog();
    
  ```
    
    
## Result

If you enable debug mode you can see an instant JSON log printed on screen like this

                                {
                                    "time": "2017\/02\/23 14:46:43",
                                    "type": "ERROR",
                                    "filename": null,
                                    "method": null,
                                    "message": "Error Message",
                                    "params": {
                                        "optional_params": "value"
                                    }
                                }

You can also see the log in the **"simple_log"** folder created automatically and will be like this:

        			======================= START ======================= 
                            
                                        Type: ERROR
                                        Time: 2017/02/23 14:46:43
                                        File: 
                                        Method: 
                                        Message: Error Message
                                        Params: {
                                            "optional_params": "value"
                                        }
        
        			======================= END ==========================


##Example
You can use this [example] to understand how SimpleLogger works

[example]:                 Example/
[SimpleConfigs.php]:                SimpleLogger/SimpleConfigs.php