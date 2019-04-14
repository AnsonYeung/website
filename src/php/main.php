<?php

/**
 * This file is written by Anson Yeung except the tail_custom function
 *
 * @author Yeung Sin Hang <s151204@tanghin.edu.hk>
 * @author Torleif Berger, Lorenzo Stanco
 */

include_once __DIR__ . "/../vendor/autoload.php";

$backtrace = debug_backtrace();

/**
 * Convert error number to string
 * 
 * @param int errno
 * @return string the error string
 */

function errno_tostring($errno) {
    switch ($errno) {
        case E_ERROR:              $err_message = "ERROR";               break;
        case E_WARNING:            $err_message = "WARNING";             break;
        case E_PARSE:              $err_message = "PARSE";               break;
        case E_NOTICE:             $err_message = "NOTICE";              break;
        case E_CORE_ERROR:         $err_message = "CORE_ERROR";          break;
        case E_CORE_WARNING:       $err_message = "CORE_WARNING";        break;
        case E_COMPILE_ERROR:      $err_message = "COMPILE_ERROR";       break;
        case E_COMPILE_WARNING:    $err_message = "COMPILE_WARNING";     break;
        case E_USER_ERROR:         $err_message = "USER_ERROR";          break;
        case E_USER_WARNING:       $err_message = "USER_WARNING";        break;
        case E_USER_NOTICE:        $err_message = "USER_NOTICE";         break;
        case E_STRICT:             $err_message = "STRICT";              break;
        case E_RECOVERABLE_ERROR:  $err_message = "RECOVERABLE_ERROR";   break;
        case E_DEPRECATED:         $err_message = "DEPRECATED";          break;
        case E_USER_DEPRECATED:    $err_message = "USER_DEPRECATED";     break;
        case E_ALL:                $err_message = "ALL";                 break;
        default:                   $err_message = "Error code ".$errno;  break;
    }
    return $err_message;
}

/**
 * Custom error handler
 * 
 * @param int errno
 * @param string errstr
 * @param string (optional) errfile
 * @param int (optional) errline
 * @param array (optional) errcontext
 * @return bool should futher php action be prevented
 * 
 */

function err_handler($errno, $errstr, $errfile, $errline, $errcontext) {
    // Allow suppressing errors through @(stfu)
    if (error_reporting() !== 0) {
        slog(errno_tostring($errno), " at ", $errfile, ":", $errline, " ", $errstr);
    }
}

set_error_handler("err_handler", E_ALL);

/**
 * get formatted JSON
 * 
 * @param mixed[] the JSON object to pass in
 * @param int optional=4: the indent level
 */

function formatJSON($obj,  $indent = 4, $level = 0) {
    $indent_unit = str_repeat(" ", $indent);
    $cindent = str_repeat($indent_unit, $level);
    $output = "";
    if (is_array($obj) && array_keys($obj) === range(0, count($obj) - 1)) {
        if (count($obj) === 0) {
            $output = "[]";
        } else {
            $output = "[" . PHP_EOL;
            $cindent = str_repeat($indent_unit, ++$level);
            foreach ($obj as $item) {
                $output .= $cindent . formatJSON($item, $indent, $level) . "," . PHP_EOL;
            }
            $cindent = str_repeat($indent_unit, --$level);
            $output = substr($output, 0, strlen($output) - 2) . PHP_EOL . $cindent . "]";
        }
    } else if (is_array($obj)) {
        if (count($obj) === 0) {
            $output = "{}";
        } else {
            $output = "{" . PHP_EOL;
            $cindent = str_repeat($indent_unit, ++$level);
            foreach ($obj as $key => $val) {
                $output .= $cindent . json_encode($key) . ": " . formatJSON($val, $indent, $level) . "," . PHP_EOL;
            }
            $cindent = str_repeat($indent_unit, --$level);
            $output = substr($output, 0, strlen($output) - 2) . PHP_EOL . $cindent . "}";
        }
    } else if (is_string($obj) or is_bool($obj) or is_numeric($obj) or is_null($obj)) {
        $output = json_encode($obj);
    } else {
        throw new Exception("Function formatJSON error: unknown type");
    }
    return $output;
}

/**
 * Writes the backtrace to the handle
 * 
 * @param int the fopened handle
 * @param object optional=debug_backtrace(): the backtrace
 */
function writeBacktrace($handle, $backtrace = NULL) {
    if (is_null($backtrace)) {
        $backtrace = debug_backtrace();
    }
    $str = "";
    // ignore the first two item (the caller and callee of the function)
	for ($i = 2; $i < count($backtrace); $i++) {
        $curItem = $backtrace[$i];
        $str .= "    at ";
        if (isset($curItem["class"]))
            $str .= $curItem["class"];
        if (isset($curItem["type"]))
            $str .= $curItem["type"];
        if (isset($curItem["function"]))
            $str .= $curItem["function"];
        if (isset($curItem["args"]) && $curItem["function"] != "err_handler") {
            $str .= "(";
            for ($j = 0; $j < count($curItem["args"]); $j++) {
                if ($j != 0)
                    $str .= ", ";
                $str .= formatJSON($curItem["args"][$j], 4, 1);
            }
            $str .= ")";
        }
        if (isset($curItem["file"])) {
            $str .= " (";
            $str .= $curItem["file"];
            if (isset($curItem["line"]))
                $str .= ":" . $curItem["line"];
            $str .= ")";
        }
        if (isset($curItem["object"]))
            $str .= PHP_EOL . formatJSON($curItem["object"], 4, 1);
        $str .= PHP_EOL;
    }
    if (is_null($handle)) {
        return $str;
    } else {
        fwrite($handle, $str);
    }
}
/**
 * log the message to the server.log
 *
 * @param string the message
 * @param boolean optional=true: should prints backtrace
 * @return void
 */

function slog() {
    static $handle = NULL;
    if (!$handle) {
        $handle = fopen(__DIR__."/../public_html/databases/server.log", "a");
        if (!$handle) throw new Exception("Couldn't open the server log");
    }
    fwrite($handle, "[" . date(DATE_ATOM) . "] ");
    $boolArg = true;
    foreach (func_get_args() as $value) {
        if (is_bool($value)) {
            $boolArg = $value;
        } else {
            fwrite($handle, $value);
        }
    }
    fwrite($handle, PHP_EOL);
    if ($boolArg) {
        writeBacktrace($handle);
    }
    fwrite($handle, PHP_EOL);
}

/**
 * Get an value out of an array with default value
 *
 * @param mixed[] $array The array to get the value
 * @param string $key The array key to get the value
 * @param mixed $default The fallback value
 */
function get($array, $key, $default) {
    return isset($array[$key]) ? $array[$key] : $default;
}

/**
 * Get an value out of an array or return 404 error
 *
 * @param mixed[] $array The array to get the value
 * @param string $key The array key to get the value
 */
function getorban($array, $key) {
    return isset($array[$key]) ? $array[$key] : ban();
}

/**
 * Return 404 error
 *
 * @param void
 */
function ban() {
    global $school, $localuri, $uri, $style_nonce;
    header("HTTP/1.1 404 Not Found");
    session_write_close();
    if (isset($_GET["REDIRECT_URI"])) {
        $_SERVER["REQUEST_URI"] = $_GET["REDIRECT_URI"];
    }
    require __DIR__ . "/../public_html/_/404.php";
    die();
}

/**
 * Slightly modified version of http://www.geekality.net/2011/05/28/php-tail-tackling-large-files/
 * @author Torleif Berger, Lorenzo Stanco
 * @link http://stackoverflow.com/a/15025877/995958
 * @license http://creativecommons.org/licenses/by/3.0/
 */
function tail_custom($filepath, $lines = 1, $adaptive = true) {
	// Open file
	$f = @fopen($filepath, "rb");
	if ($f === false) return false;
	// Sets buffer size, according to the number of lines to retrieve.
	// This gives a performance boost when reading a few lines from the file.
	if (!$adaptive) $buffer = 4096;
	else $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));
	// Jump to last character
	fseek($f, -1, SEEK_END);
	// Read it and adjust line number if necessary
	// (Otherwise the result would be wrong if file doesn't end with a blank line)
	if (fread($f, 1) != "\n") $lines -= 1;

	// Start reading
	$output = '';
	$chunk = '';
	// While we would like more
	while (ftell($f) > 0 && $lines >= 0) {
		// Figure out how far back we should jump
		$seek = min(ftell($f), $buffer);
		// Do the jump (backwards, relative to where we are)
		fseek($f, -$seek, SEEK_CUR);
		// Read a chunk and prepend it to our output
		$output = ($chunk = fread($f, $seek)) . $output;
		// Jump back to where we started reading
		fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);
		// Decrease our line counter
		$lines -= substr_count($chunk, "\n");
	}
	// While we have too many lines
	// (Because of buffer size we might have read too many)
	while ($lines++ < 0) {
		// Find first newline and remove all text before that
		$output = substr($output, strpos($output, "\n") + 1);
	}
	// Close file and return
	fclose($f);
	return trim($output);
}

/**
 * Print a row of user info on `/admin`
 * 
 * @author Yeung Sin Hang <s151204@tanghin.edu.hk>
 * @param array[string] $data the account array
 * @param boolean $perm whether the checkbox should be disabled because of permission
 * @param boolean $you whether a small word `you` should be added
 */
function echo_admin_r($data, $perm, $you) {
	static $priv_text = array("none", "elevated", "admin", "root");
	$priv = $data["privilegeLevel"];
	echo "<tr", $data["isDeleted"] ? " class='danger'" : "", "><td><input type='checkbox' value='", $data["name"], "'",
		($perm or $data["isDeleted"]) ? " disabled" : "", " /></td><td>",
		$data["name"], $you ? "<small class='text-primary'>(you)</small>" : "", "</td><td>",
		$data["score"], "</td><td>",
		$priv_text[$priv], "</td></tr>", PHP_EOL;
}

/**
 * class Database
 *
 * @author Yeung Sin Hang <s151204@tanghin.edu.hk>
 * @param string $name Name of the database
 *
 */
class Database
{

    private $fhandle, $locked = FALSE;

    /**
     * Database constructor
     */
    public function __construct($name)
    {
        $this->fhandle = fopen(__DIR__ . "/../public_html/databases/" . $name . ".json", "r+");
        if (!($this->fhandle)) throw new Exception("Database " . $name . " couldn't be opened.");
    }

    /**
     * function read
     *
     * @param void
     * @return mixed[] database content
     */
    public function read()
    {
        flock($this->fhandle, LOCK_SH);
        rewind($this->fhandle);
        $result = json_decode(stream_get_contents($this->fhandle), TRUE);
        flock($this->fhandle, LOCK_UN | LOCK_NB);
        return $result;
    }

    /**
     * function is_locked
     *
     * @param void
     * @return bool
     */
    public function is_locked()
    {
        return $this->locked;
    }


    /**
     * function lock
     *
     * @param void
     * @return mixed[] database content
     */
    public function lock()
    {
        if ($this->locked) throw new Exception("Database is already locked");
        $this->locked = flock($this->fhandle, LOCK_EX);
        return $this->read();
    }

    /**
     * function unlock
     *
     * @param mixed[] $data database content
     * @return bool Is operation success
     */
    public function unlock($data = NULL)
    {
        if (!$this->locked) throw new Exception("Unlock is called before locking");
        if (!is_null($data)) {
            // slog(PHP_EOL, "Original data = ", PHP_EOL, json_encode($this->read()));
            ftruncate($this->fhandle, 0);
            rewind($this->fhandle);
            $result = fwrite($this->fhandle, json_encode($data));
        }
        $this->locked = !flock($this->fhandle, LOCK_UN);
        return is_null($data) ? $this->locked : $result and $this->locked;
    }

    /**
     * Database destructor
     *
     * If one of the script forget to unlock the database, unlock the database and slog it.
     */
    public function __destruct()
    {
        if ($this->locked) {
            flock($this->fhandle, LOCK_UN);
            $backtrace = $GLOBALS["backtrace"];
            $output = "Database not closed on end of file".PHP_EOL;
            $output.= writeBacktrace(NULL, $backtrace);
            slog($output, false);
        }
    }
}
?>