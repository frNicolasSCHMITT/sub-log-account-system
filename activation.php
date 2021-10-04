<?php
    // Initialize the session
    session_start();
    
    require_once "config.php";
    // Récupère les données de l'URL et vérifie qu'elles ne sont pas vide
    if (isset($_GET['username']) && !empty($_GET['username']) && isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['verification']) && !empty($_GET['verification'])){
        // indiquer les variables
        $username = $_GET['username'];
        $email = $_GET['email'];
        $verification = $_GET['verification'];
        
        // Selectionne les données "verification" et "isactive" de l'utilisateur qui valide son compte.
        $sql = "SELECT verification, isactive FROM users WHERE username='$username'";
        $req = mysqli_query($link, $sql) or die('Erreur SQL !<br>'.$sql.'<br>'.mysqli_error());
        $row = mysqli_fetch_assoc($req);

        // récupère les données "verification" et "isactive" sur BDD
        $verifbdd = $row['verification']; 
        $isactive = $row['isactive']; 

        // Verifie si "isactive" = 0 et que "verification" du lien soit le même que la BDD
        if($isactive == 0 && $verification == $verifbdd) { 
            // si oui change isactive en 1
            $req = "UPDATE users SET isactive=1 WHERE username='$username'";
            mysqli_query($link, $req);

          $alert = '<div class="green_alert">Compte activé avec succès !</div>';
          header("location: login.php?alert=$alert");

        } elseif($isactive == 1) { // sinon si "isactive" est deja = 1
          $alert = '<div class="blue_alert">Compte déjà activé !</div>';
          header("location: login.php?alert=$alert");

        } else { // sinon dire que le lien est invalide. 
          $alert = '<div class="red_alert">Lien d\'activation invalide !</div>';
          header("location: login.php?alert=$alert");
      }
    }

?>