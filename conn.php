<?php
 date_default_timezone_set("Asia/Bangkok");
 $conn = new mysqli('localhost', 'root', '', 'project');

 if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
 }
 $conn->set_charset("utf8");
?>