<?php
session_start();
echo $_SESSION['user_id'];
session_destroy();
echo "desgv";
header('location: ../../index.html');
?>