<?php
session_start();
session_destroy();
header("Location: ../file_visualizzati/login.php");
exit;
?>