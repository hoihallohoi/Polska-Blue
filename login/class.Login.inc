<?php
class Login{

	public function login_start($account_naam, $wachtwoord, $mysqli) {
		if ($stmt = $mysqli->prepare("SELECT id, emailadress , wachtwoord, salt, hash,activation FROM gebruiker WHERE emailadress = ? LIMIT 1")) {
			$stmt->bind_param('s', $account_naam);
			$stmt->execute(); // Execute the prepared query.
			$stmt->store_result();
			$stmt->bind_result($id , $account_naam, $db_wachtwoord, $salt, $confirmatie_code , $activatie); // get variables from result.
			$stmt->fetch();
			$wachtwoord = sha1($wachtwoord);
			if($stmt->num_rows == 1) {
				if($this->checkbrute($account_naam, $mysqli) == true) {
					return 'deze account is tijdelijk geblokkeerd';
				}
				else{
					if($activatie == 1){
						if($db_wachtwoord == $wachtwoord) { // Check if the wachtwoord in the database matches the wachtwoord the user submitted.
							// Password is correct!
							$browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
							$_SESSION['account_naam'] = $account_naam;
							$_SESSION['login_string'] = sha1( $wachtwoord.$browser);
							$_SESSION['LAST_ACTIVITY'] = time();
							$_SESSION['id'] = $id;
							// Login successful.
							return true;
						}
						else{
							echo $wachtwoord.' '.$db_wachtwoord .'</br>';
							// Password is not correct
							// We record this attempt in the database
							$now = time();
							$mysqli->query("INSERT INTO inlog_count (emailadres, tijd) VALUES ('$account_naam', '$now')");
							return '<div id=\'wrongpassword\'><p >wachtwoord was incorrect</p> <p><a href=\'/resetwachtwoord \' style=\'color:#61A9DB;\'>wacht woord vergeten?</a></p></div>';
						}
					}
					else{
						return 'deze account is nog niet geactiveerd ';
					}
				}
			}
			else {
				// No user exists.
				return 'deze wachtwoord gebruiker combinatie bestaat niet';
			}
		}
	}


// stops brute type attacks on accounts
	private function checkbrute($account_naam, $mysqli) {
		// Get timestamp of current time
		$now = time();
		
		// tijd loop de eerste voor uren de 2e voor minuten de 3e seconden
		$valid_attempts = $now - (2 * 60 * 60);
		if ($stmt = $mysqli->prepare("SELECT tijd FROM inlog_count WHERE account_naam = ? AND tijd > '$valid_attempts'")) {
			$stmt->bind_param('s', $account_naam);
			// Execute the prepared query.
			$stmt->execute();
			$stmt->store_result();
			// If there has been more than 5 failed logins
			if($stmt->num_rows > 5) {
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function loguit(){
		if(isset($_POST['uitloggen'])){
			session_destroy();
			header('');
		}
	}
	
	public static function login_check($mysqli) {
		// Check if all session variables are set
		
		//checkt laaste activitijd
            
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
                
		//chect of naam en wachtwoord zijn ingevoert
			if(isset($_SESSION['account_naam'], $_SESSION['login_string'])) {
				$login_string = $_SESSION['login_string'];
				$accountnaam = $_SESSION['account_naam'];
				$db = database::getInstance();
				$mysqli = $db->getConnection();
				$browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
			//checkt of het in de database staat en de query klopt
			if ($stmt = $mysqli->prepare("SELECT wachtwoord  FROM gebruiker WHERE emailadress = ? LIMIT 1")) {
					$stmt->bind_param('s', $accountnaam); // Bind "$user_id" to parameter.
					$stmt->execute(); // Execute the prepared query.
					$stmt->store_result();
					if($stmt->num_rows == 1) { // If the user exists
						$stmt->bind_result($wachtwoord,$admin); // get variables from result.
						$stmt->fetch();
						$login_check = sha1( $wachtwoord.$browser);
						//checkt of het wachtwoord klopt
						if($login_check == $login_string) {
							return true;
							// Logged In!!!!
						} else {
							
							// login string klopt niet
							return false;
						}
					} else {
						//de gebruiker bestaat niet
						return false;
					}
				} else {
					// database fout
					return false;
				}
			} else {
				// niet ingelolgt
				return false;
			}
		} else {
                    echo 'sessie is verlopen';
                }
	}
}

