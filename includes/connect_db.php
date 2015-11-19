<?php 
# CONNECT TO MySQL DATABASE.
# Otherwise fail gracefully and explain the error. 

$dbc = @mysqli_connect ( 'localhost', 'root', '', 'site_db' )

OR die ( mysqli_connect_error() ) ;

# Set encoding to match PHP script encoding.

mysqli_set_charset( $dbc, 'utf8' ) ;
