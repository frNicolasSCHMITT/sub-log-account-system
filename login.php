<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer votre nom d'utilisateur.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer votre mot de passe.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, email, password, fileToUpload, role, isactive FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username; 
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password, $fileToUpload, $role, $isactive);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){

                            // Password is correct, so check if activated

                            if($isactive == true || $isactive == 1){

                                // Account is activated, so start a new session
                                session_start();
                                
                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                                $_SESSION["email"] = $email;
                                $_SESSION["password"] = $password;
                                $_SESSION["fileToUpload"] = $fileToUpload;
                                $_SESSION["role"] = $role;                          
                                
                                // Redirect user to welcome page
                                header("location: welcome.php");
                            } else{
                                echo ('<div class="red_alert"> Compte non activé ! Veuillez consulter vos mails pour activer votre compte</div>');
                            }
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Nom ou mot de passe invalide.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Nom ou mot de passe invalide.";
                }
            } else{
                echo "Oups! Il y a eu une erreur. veuillez réessayer plus tard.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./style.css">
    <title>Login</title>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="formContainer">
        <h2>Connection</h2>
        <p>Veuillez entrer vos identifiants pour vous connecter</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="red_alert">' . $login_err . '</div>';
        }
        if(isset($_GET['alert'])){
            $alert = $_GET['alert'];
            echo($alert);
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="formGroup">
              <div class="inputGrp">
                <div class="labelElement"><label>Nom d'utilisateur</label></div>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
              </div>
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="formGroup">
              <div class="inputGrp">
                <div class="labelElement"><label>Mot de passe</label></div>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
              </div>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="btnGroup">
                <input type="submit" class="btn loginBtn" value="Connection">
            </div>
            <p>Vous n'avez pas de compte? <a href="register.php">Enregistrez vous</a>.</p>
        </form>
    </div>
</body>
</html>