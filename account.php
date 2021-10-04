<?php
// Initialize the session
session_start();
require_once "config.php";

$display_alert = "";
$username = $_SESSION['username'];
$currentEmail = $_SESSION['email'];
$currentPassword = $_SESSION['password'];
?>
<!-- UPDATE ACCOUNT -->

<?php
// Define variables and initialize with empty values
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // UPDATE email & password

    if(!empty(trim($_POST["email"]))&& !empty(trim($_POST["password"]))){
      $email = $_POST['email'];
      $password = trim($_POST["password"]);
          // Validate confirm password
      if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe";     
      } else{
          $confirm_password = trim($_POST["confirm_password"]);
          if(empty($password_err) && ($password != $confirm_password)){
              $confirm_password_err = "Mot de passe différent";
          }else{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Check input errors before inserting in database
            if(empty($email_err) && empty($password_err) && empty($confirm_password_err)){
      
              $req = "UPDATE users SET email='$email', password='$hashed_password' WHERE username='$username'";
              mysqli_query($link, $req);
      
              // Envoi mail
              $admin_mail = "Website@admin.com";
              $to = $email;
              $subject = "Modification du compte ".$username;
              $from = $admin_mail;
              $body = 'Vos informations de compte ont été changées, voici le détail des changements: <br> Nouvelle adresse mail : '. $email . '<br> Nouveau mot de passe : '. $password;
              $headers  = 'MIME-Version: 1.0' . "\n";
              $headers .= 'Content-type: text/html; charset=utf-8' . "\n";
              $headers .= "De: $from \n";
              $headers .= "Répondre à: $from ";
              mail($to, $subject, $body, $headers);
              
      
              $_SESSION["email"] = $email;
              $_SESSION["password"] = $password;
              $display_alert = '<p class="green_alert">Informations mises à jour !</p>';
      
            } else{
                    echo '<div class="red_alert">Oups! Il y a eu une erreur. veuillez réessayer plus tard.</div>';
                  }
          }
        }
    }

    // UPDATE email only
    elseif(!empty(trim($_POST["email"]))){
      $email = $_POST['email'];

      // Check input errors before inserting in database
      if(empty($email_err)){

        $req = "UPDATE users SET email='$email' WHERE username='$username'";
        mysqli_query($link, $req);

        // Envoi mail
        $admin_mail = "Website@admin.com";
        $to = $email;
        $subject = "Modification du compte ".$username;
        $from = $admin_mail;
        $body = 'Vos informations de compte ont été changées, voici le détail des changements: <br> Nouvelle adresse mail : ' . $email;
        $headers  = 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\n";
        $headers .= "De: $from \n";
        $headers .= "Répondre à: $from ";
        mail($to, $subject, $body, $headers);

        $_SESSION["email"] = $email;
        $display_alert = '<p class="green_alert">Adresse e-mail mise à jour !</p>';

      } else{
              echo '<div class="red_alert">Oups! Il y a eu une erreur. veuillez réessayer plus tard.</div>';
            }
    }

    // UPDATE password only

    elseif(!empty(trim($_POST["password"]))){
      $password = trim($_POST["password"]);
          // Validate confirm password
      if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe";     
      } else{
          $confirm_password = trim($_POST["confirm_password"]);
          if(empty($password_err) && ($password != $confirm_password)){
              $confirm_password_err = "Mot de passe différent";
          }else{
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            // Check input errors before inserting in database
            if(empty($password_err) && empty($confirm_password_err)){
      
              $req = "UPDATE users SET password='$hashed_password' WHERE username='$username'";
              mysqli_query($link, $req);
      
              // Envoi mail
              $admin_mail = "Website@admin.com";
              $to = $email;
              $subject = "Modification du compte ".$username;
              $from = $admin_mail;
              $body = 'Vos informations de compte ont été changées, voici le détail des changements: <br> Nouveau mot de passe : ' . $password;
              $headers  = 'MIME-Version: 1.0' . "\n";
              $headers .= 'Content-type: text/html; charset=utf-8' . "\n";
              $headers .= "De: $from \n";
              $headers .= "Répondre à: $from ";
              mail($to, $subject, $body, $headers);
      
              $_SESSION["password"] = $password;
              $display_alert = '<p class="green_alert">Mot de passe mis à jour !</p>';
      
            } else{
                    echo '<div class="red_alert">Oups! Il y a eu une erreur. veuillez réessayer plus tard.</div>';
                  }
          }
        }

    }elseif(empty(trim($_POST["data"]))){
      $data_err = "Please enter a sign.";
     } else{

      $data = $_POST['data'];
     
      $req = "UPDATE users SET base64sign='$data' WHERE username='$username'";
              mysqli_query($link, $req);

      $display_alert = '<p class="green_alert">Signature mise à jour !</p>';

          }
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./style.css" />
    <title>Document</title>
  </head>
  <body>
    <h1>Paramètres</h1>
    <p><a href="./welcome.php">Quitter les paramètres</a></p>
    <h2 class="titleBlock">Informations du compte <?php echo $username ?> :</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
      <div class="formGroup accountFormGroup">
        <div class="inputGrp">
          <div class="labelElement"><label>Modifier l'Email</label></div>
          <input type="email" name="email" placeholder="<?php echo $currentEmail ?>" class=" accountInput form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
        </div>
        <span class="invalid-feedback"><?php echo $email_err; ?></span>
      </div> 
      <div class="formGroup accountFormGroup">
        <div class="inputGrp">
          <div class="labelElement"><label>Modifier le mot de passe</label></div>
          <input type="password" name="password" class=" accountInput form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
        </div>
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
      </div>
      <div class="formGroup accountFormGroup">
        <div class="inputGrp">
          <div class="labelElement"><label>Confirmez le mot de passe</label></div>
          <input type="password" name="confirm_password" class=" accountInput form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
        </div>
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
      </div>
      <div class="btnGroup">
          <input type="submit" class="btn submitBtn" value="Valider">
          <input type="reset" class="btn resetBtn" value="Reset">
      </div>
  </form>
  <form id="myForm" enctype="multipart/form-data" method="post">
    <div class="signSection">
      <h2 class="titleBlock">Modifier votre signature</h2>
      <div class="mise-en-page">
        <div class="bloc-mise-en-page">
          <h2>Signer (<span id="bt-clear">nettoyer</span>)</h2>
          <canvas id="canvas"></canvas>
        </div>
        <div class="bloc-mise-en-page bloc2">
          <h2>Capture de la signature</h2>
          <div id="capture"></div>
        </div>
        <input class="btn submitBtn b64signBtn" name="test" type="submit" value="Valider">
      </div>
    </div>
  </form>
  <div class="alertCheck">
    <?php echo $display_alert;  ?>
    <div id="alertSign"><p class="green_alert">Signature mise à jour !</p></div>
  </div>
    <script src="./sign.js"></script>
  </body>
</html>