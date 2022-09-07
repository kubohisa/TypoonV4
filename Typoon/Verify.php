<?php

class Err {
	public static $error;
	
	public static $key;
	public static $flag;

	public static function set(string $key) {
		self::$error[self::$key][$key] = true;
		self::$flag = true;
	}
	
	public static function init() {
		self::$error = array();
		
		self::$key = "";
		self::$flag = false;
	}
}

class Verify {
	/*
	
	*/
	
	private $value; 

	/*
	
	*/
	public static function set(string $key, string &$data): Verify {
		return new Verify($key, $data);
	}
	
	private function __construct(string $key, string &$data) {
		$data = mb_convert_encoding($data, "UTF-8", "auto");
		$this->value = &$data;
		Err::$key = $key;
	}	
		
	/* 
	
	*/
	
	public function trim() {
		$this->value = preg_replace('#\A[\p{C}\p{Z}]++|[\p{C}\p{Z}]++\z#u', '', $this->value);
		
		return $this;
	}
		
	public function plain() {
		$this->value = preg_replace('#\A[\p{C}\p{Z}]++?(　|)|[\p{C}\p{Z}]#u', '', $this->value);
		
		return $this;
	}
	
	/*
	
	*/
	
	public function space() {
		if (preg_match('#(\s|　)#', $this->value)) {
			Err::set("space");
		}
		
		return $this;
	}
	
	/*
	
	*/
	
	public function required() {
		if (empty($this->value)) {
			Err::set("required");
		}
			
		return $this;
	}
		
	public function in() {
		return self::required();
	}
		
/*	public function notNull() {
		if ($this->value ~== null) {
			Err::set("notNull");
		}
		
		return $this;
	}
*/
	
	//
	public function len($var) {
		if (mb_strlen($this->value) === $var) {
			Err::set("len");
		}
		
		return $this;
	}
	
	public function lenMax($var) {
		if (mb_strlen($this->value) > $var) {
			Err::set("lenMax");
		}
		
		return $this;
	}
	
	public function lenMin($var) {
		if (mb_strlen($this->value) < $var) {	
			Err::set("lenMin");
		}
		
		return $this;
	}
		
	//
	public function max($var) {
		if (! is_numeric($var)) {
			Err::set("digit");	
		}
			
		if ($this->value > (int)$var) {
			Err::set("max");
		}
			
		return $this;
	}
		
	public function min($var) {
		if (! is_numeric($var)) {
			Err::set("digit");
		}
			
		if ($this->value < (int)$var) {
			Err::set("min");
		}
		
		return $this;
	}
	
	//
	public function email() {
		if (! filter_var($this->value, FILTER_VALIDATE_EMAIL)) Err::set("email");
		
		return $this;
	}
	
	/*
	
	*/
	
	// Form token.
	public function formToken() {
		$_SESSION['TyFormToken'] = hash('ripemd160', microtime());
		return $_SESSION['TyFormToken'];
	}
	
	public function formTokenCheck($var) {
		if (empty($_SESSION['TyFormToken'])) {
			return false;
		}
		
		$check = $_SESSION['TyFormToken'];
		unset($_SESSION['TyFormToken']);
		
		if ($check === $var) {
			return true;
		}
		return false;
	}
	
}