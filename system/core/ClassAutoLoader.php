<?php
/* function error_handler($level, $message, $file, $line, $context) {
    //Handle user errors, warnings, and notices ourself
    if($level === E_USER_ERROR || $level === E_USER_WARNING || $level === E_USER_NOTICE) {
        echo '<strong>Error:</strong> '.$message;
        return(true); //And prevent the PHP error handler from continuing
    }
    return(false); //Otherwise, use PHP's error handler
}


function trigger_my_error($message, $level) {
    //Get the caller of the calling function and details about it
    $callee = next(debug_backtrace());
    //Trigger appropriate error
    trigger_error($message.' in <strong>'.$callee['file'].'</strong> on line <strong>'.$callee['line'].'</strong>', $level);
}
//Use our custom handler
set_error_handler('error_handler');

 */
class ClassAutoloader {
	public function __construct() {
		spl_autoload_register(array($this, 'loader'));
		
	}
	private function loader($className) {
		$_classes = array(
			'wcmx_compat' => "wcm_xcompat.php",
			'Notification' => 'system/library/lib_notify.php'
		);
		
		if (!strncmp($className, 'OM', 2)) {
		 //echo 'Trying to load ', $className, ' via ', __METHOD__, "()\n";
			if(is_file(ROOT_DIR .'system/autoload/'.$className.'.php'))
				require_once(ROOT_DIR .'system/autoload/'.$className.'.php');
			elseif(is_file(ROOT_DIR .'system/core/'.$className.'.php'))
				require_once(ROOT_DIR .'system/core/'.$className.'.php');
			else{
				trigger_error (' Trying to load className:['. $className. '] ', E_USER_ERROR);
			}
		}elseif( isset($_classes[$className]) ){
			if(is_file(ROOT_DIR . $_classes[$className]))
				require_once(ROOT_DIR .$_classes[$className]);
			else
				trigger_error (' Trying to load className:['. $className. '] ' .ROOT_DIR .$_classes[$className], E_USER_ERROR);
		}
	}
}

$autoload = new ClassAutoloader();
//Debug 
/*		
	//see all registered stack
	print_r(spl_autoload_functions());
*/

?>