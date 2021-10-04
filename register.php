<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer un nom";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Un nom ne peut contenir que des nombres, lettres ou underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Ce nom est déjà pris. Veuillez en choisir un nouveau.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oups! Il y a eu une erreur. veuillez réessayer plus tard.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Veuillez entrer une adresse mail valide";
    } else{
        $email = $_POST['email'];
        }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer un mot de passe";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Le mot de passe doit contenir au moins 6 charactères";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Mot de passe différent";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)){

        // Upload User image

            if(!is_dir("./users")){
                mkdir("./users", 0755, true);
            }

            if(!is_dir("./users/$username")){
                mkdir("./users/$username");
            }
            // $target_dir = "./users/$username/";
            // $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            // $uploadOk = 1;
            // $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image

            // if(isset($_POST["submit"])) {
            // $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            //     if($check !== false) {
            //         echo "File is an image - " . $check["mime"] . ".";
            //         $uploadOk = 1;
            //     } else {
            //         echo "File is not an image.";
            //         $uploadOk = 0;
            //     }
            // }

            // Check if file already exists

            // if (file_exists($target_file)) {
            //     echo "Sorry, file already exists.";
            //     $uploadOk = 0;
            // }

            // Check file size

            // if ($_FILES["fileToUpload"]["size"] > 500000) {
            //     echo "Sorry, your file is too large.";
            //     $uploadOk = 0;
            // }

            // Allow certain file formats
            // if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            // && $imageFileType != "gif" ) {
            // echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            // $uploadOk = 0;
            // }

            // Check if $uploadOk is set to 0 by an error

            // if ($uploadOk == 0) {
            //     echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            // } else {
            //     if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //     echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            //     } else {
            //     echo "Sorry, there was an error uploading your file.";
            //     }
            // }
    
        // pour le mail de vérification, génère une string aléatoire qui servira pour comparaison
        $verification = substr(md5(mt_rand()), 0, 15);

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, fileToUpload, base64sign, role, isactive, verification) VALUES (?, ?, ?, ?, ?, 'guest', 0, '$verification')";

        var_dump($sql);
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_username, $param_email, $param_password, $param_file, $param_sign);
            
    
            // Set parameters
            $param_username = $username;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_file = '';
            $param_sign = '';
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                // Envoi mail
                $admin_mail = "Website@admin.com";
                $to = $email;
                $subject = "Code d'activation du compte ".$username;
                $from = $admin_mail;
                $body = ' Veuillez suivre <a href="https://nicolass.promo-90.codeur.online/exo_sub_and_verif_mail/activation.php?username=' . $username . '&email=' . $email . '&verification=' . $verification . '">ce lien</a> pour activer votre compte.';
                $headers  = 'MIME-Version: 1.0' . "\n";
                $headers .= 'Content-type: text/html; charset=utf-8' . "\n";
                $headers .= "De: $from \n";
                $headers .= "Répondre à: $from ";
                mail($to, $subject, $body, $headers);

                // Redirect to login page
                $alert = '<div class="red_alert">Un code d\'activation vous a été envoyé à l\'adresse mail donnée. Veuillez le consulter pour activer votre compte.</div>';
                header("location: login.php?alert=$alert");

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
    <title>Création de compte</title>
    <link rel="stylesheet" href="./style.css" />
</head>
<body>
    <div class="formContainer">
        <h2>Création de compte</h2>
        <p>Remplissez le formulaire pour créer un compte</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="formGroup">
              <div class="inputGrp">
                <div class="labelElement"><label>Nom d'utilisateur</label></div>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
              </div>
              <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>   
            <div class="formGroup">
              <div class="inputGrp">
                <div class="labelElement"><label>Email</label></div>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
              </div>
              <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div> 
            <div class="formGroup">
              <div class="inputGrp">
                <div class="labelElement"><label>Mot de passe</label></div>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
              </div>
              <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="formGroup">
              <div class="inputGrp">
                <div class="labelElement"><label>Confirmez le mot de passe</label></div>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
              </div>
              <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <!-- <div class="formGroup">
                <div class="inputGrp">
                    <div class="labelElement"><label>Chargez votre image de profil</label></div>
                    <input type="file" name="fileToUpload" id="fileToUpload">
                </div>
            </div> -->
            <div class="btnGroup">
                <input type="submit" class="btn submitBtn" value="Valider">
                <input type="reset" class="btn resetBtn" value="Reset">
            </div>
            <p>Vous possédez déjà un compte? <a href="login.php">Connectez-vous</a>.</p>
        </form>
    </div>    
</body>
</html>