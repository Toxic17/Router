<?php 
namespace Core;

use Core\Request;

class Request
{
	private array $get_params;
	private array $post_params;

	public function __construct()
	{
		$this->get_params = filter_input_array(INPUT_GET,FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: [];
		$this->post_params = filter_input_array(INPUT_POST,FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: [];
	}

	public function all() : array
	{
		return $this->post_params+$this->get_params;
	}

	public function has(string $key) : bool
	{
		return isset($this->all()[$key]);
	}

	public function exists(array $keys) : bool
	{
		foreach($keys as $key)
		{
			if(array_key_exists($key,$this->all()))
			{
				return true;
			}
		}
		return false;
	}

	public function input(string $key, $default = null)
	{
		return $this->all()[$key] ?? $default;
	}

	public function only(array $keys) : array
	{
		$result_arr = [];
		foreach($keys as $key)
		{
			if(isset($this->all()[$key]))
			{
				$result_arr[$key] = $this->all()[$key];
			}
		}
		return $result_arr;
	}


	public function except(array $keys) : array
	{
		$result_arr = $this->all();

		foreach($keys as $key)
		{
			if(isset($this->all()[$key]))
			{
				unset($result_arr[$key]);
			}
		}
		return $result_arr;
	}

	public function isMethod(string $method) : bool
	{
		return strtoupper($method) === $_SERVER['REQUEST_METHOD'];
	}

	public function method() : string
	{
		return strtolower($_SERVER['REQUEST_METHOD']);
	}

	public function isAjax() : bool
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
	}

	public function path() : string
	{
		return !strpos($_SERVER['REQUEST_URI'],'?') ? $_SERVER['REQUEST_URI'] :  substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?'));
	}

	public function url() : string 
	{
		return isset($_SERVER['HTTPS']) ? 'https://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	}

	public function ip() : string
	{
		   $possibleHeaders = [
	        	'HTTP_CLIENT_IP',
	        	'HTTP_X_FORWARDED_FOR',
	        	'HTTP_X_REAL_IP'
	   	 	];

	    foreach ($possibleHeaders as $header) {
	        if (isset($_SERVER[$header])) {
	            $ips = explode(',', $_SERVER[$header]);
	            return trim($ips[0]);
	        }
	    }

	    return $_SERVER['REMOTE_ADDR'];
	}

	public function header(string $key = null) 
	{
		$key = ucwords($key);
		return getallheaders()[$key] ?? null;
	}

	public function file($key)
	{
		return $_FILE[$key] ?? null;
	}

	public function file_exists($key) : bool
	{
		return isset($_FILE[$key]);
	}

}

?>