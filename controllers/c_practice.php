<?php

class practice_controller extends base_controller
{
	public function test1()
	{
		require(APP_PATH.'/libraries/Image.php');
		$imageObj = new Image('http://placekitten.com/500/500');
		$imageObj->resize(200, 200);
		$imageObj->display();
	}
}

?>