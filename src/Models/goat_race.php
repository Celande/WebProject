<?php
use Illuminate\Database\Eloquent\Model as Eloquent;
class GoatRace extends Eloquent {
	protected $fillable = ['title'];
	public $timestamps = false;
}
