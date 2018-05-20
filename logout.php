<?php

include_once '_config.php';

session_unset();
session_destroy();

header("location: " . $config['page']['index']);

?>