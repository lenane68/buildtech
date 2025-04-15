<?php
session_start();
setcookie("email", "", time() - 3600); //send browser command remove sid from cookie
setcookie("password", "", time() - 3600);
session_destroy(); //remove sid-login from server storage
session_write_close();
header('Location: no_log_in.php');
