<?php

class Post extends Eloquent{
	public function addproperty()
	{
		$position=explode(",", $this->position);
		$property=explode(",", $this->property);
		$this->left=$position[0];
		$this->top=$position[1];
		$this->zindex=$position[2];
		$this->width=$property[0];
		$this->height=$property[1];
	}
	
}