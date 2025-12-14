<?php

$url_prefix = URL_PREFIX ?: '';
header('Location:' . $url_prefix . '/dashboard/login.php');
exit();
