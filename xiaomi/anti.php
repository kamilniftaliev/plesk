<?php 

	function anti_injection($data){
$filter = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));

return $filter;

}
	?>