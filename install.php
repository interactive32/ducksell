<?php
/**
 * Simple installer/updater script
 *
 */

if (! defined('APP_VERSION')) {
	echo "Error: No direct access. Please point your browser to index.php instead.";
	die;
}

ini_set('memory_limit', '5120M');
ini_set('max_execution_time', 0);
error_reporting(E_ALL);
	
if (! extension_loaded('mysqli')) {
	echo "Error: mysqli extension required for the install script is not loaded.";
	die;
}

/**
 * 
 * Display setup
 */
function display_header()
{
	?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Setup Configuration File</title>
<link href="./AdminLTE/bootstrap/css/bootstrap.min.css" type="text/css"	rel="stylesheet">
</head>
<body>
	<br />
	<div class="container well">
		<h1>DuckSell Installer</h1>
		<?php
}

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

/**
 * 
 * Remove comments from dump
 */
function remove_comments(&$output)
{
	$lines = explode("\n", $output);
	$output = "";
	
	// try to keep mem. use down
	$linecount = count($lines);
	
	$in_comment = false;
	for ($i = 0; $i < $linecount; $i ++) {
		if (preg_match("/^\/\*/", preg_quote($lines[$i]))) {
			$in_comment = true;
		}
		
		if (! $in_comment) {
			$output .= $lines[$i] . "\n";
		}
		
		if (preg_match("/\*\/$/", preg_quote($lines[$i]))) {
			$in_comment = false;
		}
	}
	
	unset($lines);
	return $output;
}


/**
 * 
 *  remove_remarks will strip the sql comment lines out of an uploaded sql file
 */
function remove_remarks($sql)
{
	$lines = explode("\n", $sql);
	
	// try to keep mem. use down
	$sql = "";
	
	$linecount = count($lines);
	$output = "";
	
	for ($i = 0; $i < $linecount; $i ++) {
		if (($i != ($linecount - 1)) || (strlen($lines[$i]) > 0)) {
			if (isset($lines[$i][0]) && $lines[$i][0] != "#") {
				$output .= $lines[$i] . "\n";
			} else {
				$output .= "\n";
			}
			// Trading a bit of speed for lower mem. use here.
			$lines[$i] = "";
		}
	}
	
	return $output;
}


/**
 * 
 * split_sql_file will split an uploaded sql file into single sql statements.
 * 
 * Note: expects trim() to have already been run on $sql.
 */
function split_sql_file($sql, $delimiter)
{
	// Split up our string into "possible" SQL statements.
	$tokens = explode($delimiter, $sql);
	
	// try to save mem.
	$sql = "";
	$output = array();
	
	// we don't actually care about the matches preg gives us.
	$matches = array();
	
	// this is faster than calling count($oktens) every time thru the loop.
	$token_count = count($tokens);
	for ($i = 0; $i < $token_count; $i ++) {
		// Don't wanna add an empty string as the last thing in the array.
		if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))) {
			// This is the total number of single quotes in the token.
			$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
			// Counts single quotes that are preceded by an odd number of backslashes,
			// which means they're escaped quotes.
			$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
			
			$unescaped_quotes = $total_quotes - $escaped_quotes;
			
			// If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
			if (($unescaped_quotes % 2) == 0) {
				// It's a complete sql statement.
				$output[] = $tokens[$i];
				// save memory.
				$tokens[$i] = "";
			} else {
				// incomplete sql statement. keep adding tokens until we have a complete one.
				// $temp will hold what we have so far.
				$temp = $tokens[$i] . $delimiter;
				// save memory..
				$tokens[$i] = "";
				
				// Do we have a complete statement yet?
				$complete_stmt = false;
				
				for ($j = $i + 1; (! $complete_stmt && ($j < $token_count)); $j ++) {
					// This is the total number of single quotes in the token.
					$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
					// Counts single quotes that are preceded by an odd number of backslashes,
					// which means they're escaped quotes.
					$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
					
					$unescaped_quotes = $total_quotes - $escaped_quotes;
					
					if (($unescaped_quotes % 2) == 1) {
						// odd number of unescaped quotes. In combination with the previous incomplete
						// statement(s), we now have a complete statement. (2 odds always make an even)
						$output[] = $temp . $tokens[$j];
						
						// save memory.
						$tokens[$j] = "";
						$temp = "";
						
						// exit the loop.
						$complete_stmt = true;
						// make sure the outer loop continues at the right point.
						$i = $j;
					} else {
						// even number of unescaped quotes. We still don't have a complete statement.
						// (1 odd and 1 even always make an odd)
						$temp .= $tokens[$j] . $delimiter;
						// save memory.
						$tokens[$j] = "";
					}
				} // for..
			} // else
		}
	}
	
	return $output;
}


// unpack zip file
if (file_exists('data.zip')) {
	
	// unpack
	if (! is_writable(getcwd())) {
		display_header();
		echo 'Error: current directory not writable!';
		die;
	}
	
	if (! extension_loaded('zip')) {
		display_header();
		echo 'Error: PHP Zip extension is missing!';
		die;
	}



	$filename = 'data.zip';

	if (!is_writable($filename)) {
		echo 'Error: data.zip is not writable';
		die;
	}

	$zip = new ZipArchive();

	if ($zip->open($filename) === true) {

		if (file_exists(__DIR__ . '/inc/storage/framework/down')) {
			echo 'Maintenance mode is on!';
			die;
		}
		@touch(__DIR__ . '/inc/storage/framework/down');
		$zip->extractTo('.');
		$zip->close();
		@unlink(__DIR__ . '/inc/storage/framework/down');

		if (! unlink($filename)) {
			echo 'Error: Cannot remove '.$filename.' file! Please check file permissions.';
			die;
		};

	} else {
		echo 'Error: Cannot open data file: ' . $filename . ' Still uploading?';
		die;
	}
	
	header('Location: ' . $_SERVER['REQUEST_URI']);
}

require __DIR__ . '/requirements.php';

$step = isset($_GET['step']) ? (int) $_GET['step'] : 0;

// create config file?
if (! $step) {
	display_header();
	$msg = '<br /><p><strong>There doesn\'t seem to be a <code>/inc/.env</code> file. I need this before we can get started.</strong></p>';
	$msg .= '<p><br /><br /><a class="btn btn-primary btn-lg" href="?step=1">Create configuration file</a></p>';
	echo $msg;
	die;
}

$dbname = trim(isset($_POST['dbname']) ? $_POST['dbname'] : '');
$uname = trim(isset($_POST['uname']) ? $_POST['uname'] : '');
$pwd = trim(isset($_POST['pwd']) ? $_POST['pwd'] : '');
$dbhost = trim(isset($_POST['dbhost']) ? $_POST['dbhost'] : '');
$timezone = trim(isset($_POST['timezone']) ? $_POST['timezone'] : '');

switch ($step) {
	case 1:
		
		display_header();
		?>

		<p>Welcome to Installation. Before getting started, we need some
			information on the database. You will need to know the following
			items before proceeding.</p>
		<ol>
			<li>Database name</li>
			<li>Database username</li>
			<li>Database password</li>
			<li>Database host</li>
		</ol>
		<p>
			<strong>If for any reason this automatic file creation doesn't work,
				don't worry. All this does is fill in the database information to a
				configuration file. You may also simply open <code>/inc/.env-sample</code>
				in a text editor, fill in your information, and save it as <code>/inc/.env</code>
			</strong>
		</p>
		<p>In all likelihood, these items were supplied to you by your Web
			Host. If you do not have this information, then you will need to
			contact them before you can continue. If you're all ready...</p>

		<p>
			<br /> <a class="btn btn-primary btn-lg" href="?step=2">Let's go!</a>
		</p>
		<?php
		break;
	
	case 2:
		display_header();
		?>
		<form method="post" action="?step=3">
			<p>Below you should enter your database connection details. If you're
				not sure about these, contact your host.</p>
			<table>
				<tr class="form-group">
					<th><label for="dbname">Database Name</label></th>
					<td><input name="dbname" id="dbname" type="text" size="25"
						value="ducksell" /></td>
					<td>The name of the database you want to run ducksell in.</td>
				</tr>
				<tr>
					<th scope="row"><label for="uname">User Name</label></th>
					<td><input name="uname" id="uname" type="text" size="25"
						value="username" /></td>
					<td>Your MySQL username</td>
				</tr>
				<tr>
					<th scope="row"><label for="pwd">Password</label></th>
					<td><input name="pwd" id="pwd" type="text" size="25"
						value="password" /></td>
					<td>Your MySQL password.</td>
				</tr>
				<tr>
					<th scope="row"><label for="dbhost">Database Host</label></th>
					<td><input name="dbhost" id="dbhost" type="text" size="25"
						value="127.0.0.1" /></td>
					<td>You should be able to get this info from your web host, if <code>127.0.0.1</code> or <code>localhost</code>
						does not work.
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="timezone">TimeZone</label></th>
					<td><select name="timezone" id="timezone">
							<option value="UTC">UTC</option>
							<?php foreach(timezone_identifiers_list() as $key => $timezone):?>
								<option value="<?php echo $timezone?>"><?php echo $timezone?></option>
							<?php endforeach; ?>
						</select>
					<td>
					</td>
				</tr>
			</table>
			<br /> <br />
			<p class="step">
				<input name="submit" type="submit" value="Submit"
					class="btn btn-primary btn-lg" />
			</p>
		</form>
		<?php
		break;
	
	case 3:
		$tryagain_link = '</p><br /><br /><p class="step"><a class="btn btn-primary btn-lg" href="?step=2" onclick="javascript:history.go(-1);return false;">Try again</a>';
		
		@$test_conn = new mysqli($dbhost, $uname, $pwd, $dbname);
		
		if (mysqli_connect_error()) {
			display_header();
			echo '<strong>Error connecting to database.</strong> ' . $tryagain_link;
			die;
		}

		$random_string = generateRandomString(32);
		
		$content = "APP_ENV=production\nAPP_DEBUG=false\nAPP_KEY={$random_string}\nAPP_TIMEZONE={$timezone}\n\nDB_HOST={$dbhost}\nDB_DATABASE={$dbname}\nDB_USERNAME={$uname}\nDB_PASSWORD={$pwd}\n\nLOAD_PLUGINS=true\n\nCACHE_DRIVER=file\nSESSION_DRIVER=database\nQUEUE_DRIVER=sync\n";
		
		display_header();
		
		$ret = file_put_contents(__DIR__.'/inc/.env', $content);
		
		if (! $ret) :
			
			?>

		<p>
			Sorry, but I can't write the
			<code>/inc/.env</code>
			file
		</p>
		<p>
			You can create the
			<code>/inc/.env</code>
			manually and paste the following text into it
		</p>
		<textarea id="wp-config" cols="98" rows="15" class="code"
			readonly="readonly"><?php echo $content?>
		</textarea>
		<?php else: ?>
		<p>
			<strong>All right, ducksell can now communicate with your
				database.</strong>
		</p>

		<p>
			<br />
			<form method="post" action="?step=4">
				<input hidden name="dbhost" id="dbhost" type="text" size="25" value="<?php echo $dbhost?>" />
				<input hidden name="dbname" id="dbname" type="text" size="25" value="<?php echo $dbname?>" />
				<input hidden name="uname" id="uname" type="text" size="25" value="<?php echo $uname?>" />
				<input hidden name="pwd" id="pwd" type="text" size="25" value="<?php echo $pwd?>" />
				<input hidden name="timezone" id="timezone" type="text" size="25" value="<?php echo $timezone?>" />

				<input name="submit" type="submit" value="Next: Import SQL file into Database"
					   class="btn btn-primary btn-lg" />
				<a class="btn btn-default btn-lg" href="?">Skip</a>
			</form>

		</p>


		
		<?php

		endif;
		
		break;
	case 4:
		
		display_header();
		
		if (! file_exists(__DIR__.'/inc/.env')) {
			echo "Error: File /inc/.env missing.";
			die;
		}


		$test_conn = new mysqli($dbhost, $uname, $pwd, $dbname);
		
		if (mysqli_connect_error()) {
			echo '<strong>Error: connecting to database.</strong>';
			die;
		}
		
		$res = $test_conn->query("SHOW TABLES LIKE 'options'");
		
		if (isset($res->num_rows) && $res->num_rows == 1) {
			
			?>
		<strong>Error: database is not empty.</strong>
		<p>
			<br /> <a class="btn btn-default btn-lg" href="?">Skip</a>
		</p>
		<?php
			die();
		}
		
		$dbms_schema = __DIR__ . '/database/database.sql';
		$sql_query = file_get_contents($dbms_schema);
		if (! $sql_query) {
			echo 'Error reading /database/database.sql file';
			die;
		}

		$sql_query = remove_remarks($sql_query);
		$sql_query = split_sql_file($sql_query, ';');
		
		foreach ($sql_query as $sql) {
			if ($test_conn->query($sql) !== true) {
				echo 'Error in databse query: '.mysqli_error($test_conn);
				die;
			}
		}
		
		?>
		<h3>Successfully installed!</h3>
		<p>
			You can now sign in with email <strong>admin@example.com</strong> using password <strong>admin123</strong>
		</p>
		<p>Please read the documentation next.</p>


		<p>
			<br /> <a class="btn btn-primary btn-lg" href="?">Go!</a>
		</p>
		<?php
		
		break;
}
?>

	</div>
</body>
</html>
