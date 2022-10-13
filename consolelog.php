<?php 
	function console_log($data){
		if(is_array($data) || is_object($data)) $console = json_encode($data);
		else $console = "`$data`";

		echo "<script>console.log('===== PHP dice: =====')</script>";
		echo "<script>console.log(".$console.")</script>";
		echo "<script>console.log('=================')</script>";
	}
?>