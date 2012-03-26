<?php
  
   // Import the FTP Monitor
   require_once('rawlist_dump.php');
    
   // Connection parameters
   $host = "ftp.example.com";
   $user = "example1";
   $pass = "exmp1";
   
	// Set up basic connection
	$conn_id = ftp_connect($host);

	// Login using connection parameters from above
	$login_result = ftp_login($conn_id, $user, $pass);
   
   // Call rawlist and generate array
   rawlist_dump($conn_id);
?>