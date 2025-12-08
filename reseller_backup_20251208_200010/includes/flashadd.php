<?php

if(isset($_SESSION['CREDIT']))
{
echo '<div class="alert alert-info alert-dismissable">
   		<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a><h2>Jumlah Total Credit '. $_SESSION['CREDIT'].'
  	  </h2></div>';
  unset($_SESSION['CREDIT']);
}
 ?>