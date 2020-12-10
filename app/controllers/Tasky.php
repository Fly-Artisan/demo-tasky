<?php namespace App\Controllers;

use App\Actors\User;
use FLY\Libs\Request;
use FLY\MVC\Controller;

final class Tasky extends Controller {

	static function fetchTask()
	{
		self::add_context(User::viewTask());
	}

	static function addTask(Request $req)
	{
		self::add_context((new User($req))->addTask());
	}

	static function deleteTask(Request $req)
	{
		self::add_context((new User($req))->deleteTask());
	}

	static function updateTask(Request $req)
	{
		self::add_context(User::_($req)->updateTask());
	}

	static function changeStatus(Request $req)
	{
		self::add_context((new User($req))->changeStatus());
	}
}