<?php namespace App\Controllers;
use App\Actors\VDT\UserVdt;
use FLY\Libs\Request;
use FLY\MVC\Controller;

final class Tasky extends Controller {

	static function fetchTask()
	{
		self::add_context(UserVdt::viewTask());
	}

	static function addTask(Request $req)
	{
		return (new UserVdt($req))->addTask();
	}

	static function deleteTask(Request $req)
	{
		return (new UserVdt($req))->deleteTask();
	}

	static function updateTask(Request $req)
	{
		return UserVdt::_($req)->updateTask();
	}

	static function changeStatus(Request $req)
	{
		return (new UserVdt($req))->changeStatus();
	}
}