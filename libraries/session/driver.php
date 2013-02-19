<?php

interface SessionDriver {

	public function set($key, $value);
	
	public function get($key);
	
	public function delete($key);
	
}