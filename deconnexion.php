<?php
session_start();

include 'utils/utils.php';

session_destroy();
header('Location: index.php');
exit();
?>