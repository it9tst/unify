<?php

class Post{
	public function get($key){
		if (isset($_POST[$key]))
			return $_POST[$key];
	}
}