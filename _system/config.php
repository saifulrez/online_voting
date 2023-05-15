<?php
     session_start();
     ob_start();
     date_default_timezone_set("Asia/Bangkok");
     // MySQL
     $config["mysql_host"] = "localhost";
     $config["mysql_username"] = "root";
     $config["mysql_password"] = "";
     $config["mysql_dbname"] = "Voting";
?>