<?php
$length=20;
$key = '';
	list($usec, $sec) = explode(' ', microtime());
	mt_srand((float) $sec + ((float) $usec * 100000));
	
   	$inputs = array_merge(range('z','a'),range(0,9),range('A','Z'));

   	for($i=0; $i<$length; $i++)
	{
   	    $key .= $inputs[mt_rand(0,61)];
	}
echo $key;


// echo keygen(40);  // JVP8xGk4hsX2cZd0L3NQwYbI0mf4exPiSoAhVYnz , your results will vary
?>