<?php
/*
phpGrace.com 轻快的实力派！ 
*/
class indexController extends grace{
	
	public function index(){
		$this->json('welcome to grace api');
	}
	
}