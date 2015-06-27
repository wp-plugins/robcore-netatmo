<?php
$emailErr = $passwordErr = "";
$password = $email = "";

if (($_SERVER["REQUEST_METHOD"] == "POST") && ($_POST["submit"] == "Confirm login and options")) { //caso conferma login e opzioni
   
   if (empty($_POST["email"])) {
     $emailErr = "Email is required";
   } else {
     $email = rnpfw_test_input($_POST["email"]);
     // check if e-mail address is well-formed
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $emailErr = "Invalid email format"; 
     }
   }
     
   if (empty($_POST["password"])) {
     $password = "";
   } else {
     $password = rnpfw_test_input($_POST["password"]);
   }

   if (isset($email) && isset($password)) {
    $array_account = array($email, $password);
    update_option("robcore_nap_account", $array_account);
   }

    $array_opzioni_press_temp = array($_POST["misura-gradi"], $_POST["misura-press"]);
    update_option("robcore_nap_options_press_temp", $array_opzioni_press_temp);

   echo "<script>location.reload();</script>"; //ricarico pagina epr caricare nuovi dati login
}

if (($_SERVER["REQUEST_METHOD"] == "POST") && ($_POST["clear_cache"] == "yes")) { // caso clear database cache
  global $wpdb;
  $tabella_prefixata = $wpdb->prefix.'robcore_netatmo_data';
  $vuoto_tabella_misurazioni = $wpdb->query("TRUNCATE TABLE $tabella_prefixata");
}

function rnpfw_test_input($data) {
   $data = stripslashes($data);
   $data = trim($data);
   $data = htmlspecialchars($data);
   return $data;
}
$email_da_db = get_option('robcore_nap_account')[0];
$pass_da_db = get_option('robcore_nap_account')[1];

// ########################################################
// CONTROLLO OPZIONI ATTUALI
// ########################################################

  if (get_option('robcore_nap_options_press_temp')[0] == "celsius") {
    $unita_misura_temp_attuale = "Celsius";
  } else {
    $unita_misura_temp_attuale = "Fahrenheit";
  }

  if (get_option('robcore_nap_options_press_temp')[1] == "hPa") {
    $unita_misura_press_attuale = "hPa";
  } else {
    $unita_misura_press_attuale = "mmHg";
  }

// ########################################################
// FINE CONTROLLO OPZIONI ATTUALI
// ########################################################

?>

<form method="post" action=""> 
  <h4>Login credentials</h4>
   E-mail: <input type="text" name="email" value="<?php echo $email_da_db; ?>">
   <span class="error"><?php echo $emailErr;?></span>
   <br><br>
   Password: <input type="password" name="password" value="<?php echo $pass_da_db;?>">
   <span class="error"><?php echo $passwordErr;?></span>
   <br><br>
   <hr>
   <h4>Units of measurement (Now: Temperature in <span style="color:#0073aa"><?php echo $unita_misura_temp_attuale; ?></span> and Pressure in <span style="color:#0073aa"><?php echo $unita_misura_press_attuale; ?></span>)</h4>
      <select name="misura-gradi">
        <option value="celsius" selected="selected">Celsius</option>
        <option value="fahrenheit">Fahrenheit</option>
      </select>
       <select name="misura-press">
        <option value="hPa" selected="selected">hPa</option>
        <option value="mmHg">mmHg</option>
      </select>
    <br><br>
   <input type="submit" name="submit" value="Confirm login and options">
</form>
<br>
<hr>

<form method="post" action=""> 
  <h4>Clear database measurements cache</h4>
  <input type="hidden" name="clear_cache" value="yes">
<input type="submit" name="submit" value="Clear DB Cache">
</form>
