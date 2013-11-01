<?php

class practice_controller extends base_controller
{

	public function test_db()
	{
		$q = "INSERT INTO users SET first_name = 'Albert', last_name = 'Einstein'";

		echo $q;

		DB::instance(DB_NAME)->query($q);
	}

	public function test1()
	{
		require(APP_PATH.'/libraries/Image.php');
		$imageObj = new Image('http://placekitten.com/500/500');
		$imageObj->resize(200, 200);
		$imageObj->display();
	}
}

?>