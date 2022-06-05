<?php

$protocol           = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
$baseURL            = trim($protocol . $_SERVER['HTTP_HOST']);
$currentLocation    = trim($baseURL . $_SERVER['REQUEST_URI']);
$attempts           = 0;

$failfetchssn = false; 
if ( isset( $_COOKIE['failfetchssn'] ) )
{
	$failfetchssn = $_COOKIE['failfetchssn'];
	setcookie( 'failfetchssn', 'expired', time() - 86400 );
}

$warnusrlogin = false; 
if ( isset( $_COOKIE['warnusrlogin'] ) ) 
{
	$warnusrlogin = true;
	setcookie( 'warnusrlogin', 'expired', time() - 86400 );
}

$invalidform = false; 
if ( isset( $_COOKIE['invalidform'] ) ) 
{
	$invalidform = true;
	setcookie( 'invalidform', 'expired', time() - 86400 );
}

$invalidcred = false; 
if ( isset( $_COOKIE['invalidcred'] ) ) 
{
	$invalidcred = true;
	setcookie( 'invalidcred', 'expired', time() - 86400 );
}


// todo - change hardcoded value to parameterized. 
if (isset($_COOKIE['ssn_tkn'])) {

	$validator = new Session();
	$valid = $validator->get_is_valid();

	// var_dump ($valid);

	if (!$valid) {
		setcookie('ssn_tkn', '1', time() - 86400);
	} else {
		header( "Location: " . constant('\eecs647\web_url') );
	}
	
}

# Check for cookie containing the first page to redirect the user too
if(!isset($_COOKIE['SAS400FP'])) {
	$homepage = $baseURL . "/index.php";
	setcookie("SAS400FP", "$homepage", 0);
}

# Check for the number of attempts the user has made to login. 
if (isset($_COOKIE['TC'])) {    
	$attempts = filter_input(INPUT_COOKIE, 'SAS400TC', FILTER_SANITIZE_NUMBER_INT);
}

\eecs647\load_stylesheet( 'index.css', true );
?>
    
<!DOCTYPE html>

<html lang="<?php echo \eecs647\LANG; ?>">

    <head>
        <!-- <link rel="canonical" href="<?php // echo SAS400_URL . 'login.php'; ?>" /> -->
        <?php eecs647\print_header_contents( eecs647\OWNER_NAME . ' AS400'); ?>
    	
    </head>
    
    <body class="centered">
    
        <div id="login">
        
        	<h1>Please sign-in<br>using AS400 credentials:</h1>

			<?php 
			if ( $failfetchssn ) 
			{
				echo '<p><b>Error: </b>';
				echo date('Y-m-d h:i:s a', time()) . " (" . date_default_timezone_get() . ")"; 
				echo '<br/>The server was unable to retrieve your login information. You have been automatically logged out. Please try logging in again.<br/>';
				echo 'If the problem persists, please contact IT.</p>';
				echo '<details style="margin-bottom:25px;"><summary>Information for IT</summary><p>';
				echo $failfetchssn;
				echo '</p></details>';
			}

			if ( $warnusrlogin ) 
			{
				echo '<p><b>Error: </b>';
				echo date('Y-m-d h:i:s a', time()) . " (" . date_default_timezone_get() . ")"; 
				echo '<br/>You must log in before using the application.</p>';
			}

			if ( $invalidform ) 
			{
				echo '<p><b>Error: </b>';
				echo date('Y-m-d h:i:s a', time()) . " (" . date_default_timezone_get() . ")"; 
				echo "<br/>Form field(s) missing. Please try again.</p>"; 
			}

			if ( $invalidcred ) 
			{
				echo '<p><b>Error: </b>';
				echo date('Y-m-d h:i:s a', time()) . " (" . date_default_timezone_get() . ")";
				echo '<br/>Invalid credentials. If you\'re having trouble logging in, please contact IT.</p>'; 
			}

			?>

        	<form action="<?php echo eecs647\sec\url_session_login; ?>" method="post">
        		<input type="text" 		name="username" placeholder="Username" autocomplete="off"  aria-label="Username">
        		<input type="password"	name="password" placeholder="Password" autocomplete="off"  aria-label="Password">
        		<input type="hidden" 	name="location" value="<?php echo $currentLocation; ?>">
        		<input type="submit" 	value="Sign In">
        	</form>
        
        </div>
        
        <footer>
        
        	<p><?php echo \eecs647\format_copyright(); ?></p>
        	
        </footer>
    
    </body>

</html>