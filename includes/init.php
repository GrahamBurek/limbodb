<?php 
# CONNECT TO MySQL DATABASE.
# Otherwise fail gracefully and explain the error. 

$dbc = @mysqli_connect ( 'localhost', 'root', '', 'limbo_db' );

# Set encoding to match PHP script encoding.

# Populate database, if necessary, then connect to database
if ($dbc == false) {
	$command = "\"C:\\Program Files (x86)\\EasyPHP-DevServer-14.1VC11\\binaries\\mysql\\bin\\mysql\" -u root < \"C:\\Program Files (x86)\\EasyPHP-DevServer-14.1VC11\\data\\localweb\\includes\limbo.sql\"";
	shell_exec($command);
	$dbc = @mysqli_connect ( 'localhost', 'root', '', 'limbo_db' );

	mysqli_set_charset( $dbc, 'utf8' );
}

?>