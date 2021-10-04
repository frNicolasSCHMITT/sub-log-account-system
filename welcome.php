<?php
// Initialize the session
session_start();
require_once "config.php";
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
$profilePic = $_SESSION["fileToUpload"];
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./style.css" />
    <title>Test Session</title>
  </head>
  <body class="chatBody">
    <section class="headerBg">
      <header class="headerSection">

        <!-- Trigger/Open The Modal -->
        <!-- <button id="myBtn">changer l'image</button> -->
        <div id="myBtn" class="profileImgSection" title="changer d'image">
          <img class="miniImg" src="./switchIcon.png" alt="">
          <img class="profilePic" src="<?php 
            if (!empty($profilePic)){
              echo $profilePic;
            }
            else{
              echo './poulou.jpg';
            }
          ?>" alt="Image de profil">
        
          <!-- The Modal -->
          <div id="myModal" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
              <span class="close">&times;</span>
              <p>Changez votre image de profil :</p>
              <form class="picChangeForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" class="btn submitBtn" value="Valider">
              </form>
            </div>
          </div>
        </div>
        <!-- Greetings -->
        <div class="greetings">
          <?php if($_SESSION['role'] == 'admin') : ?>
            <h2>Bienvenue Administrateur 
            <?php echo $username; ?> </h2>

          <?php elseif($_SESSION['role'] == 'guest') : ?>
            <h2>Bienvenue 
            <?php echo $username; ?> </h2>
            
          <?php endif ?>
        </div>
        <a href="./logout.php" class="logoutSection">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            x="0px"
            y="0px"
            width="20"
            height="20"
            viewBox="0 0 172 172"
            style="fill: #000000"
          >
            <g transform="translate(4.73,4.73) scale(0.945,0.945)">
              <g
                fill="none"
                fill-rule="nonzero"
                stroke="none"
                stroke-width="none"
                stroke-linecap="butt"
                stroke-linejoin="none"
                stroke-miterlimit="10"
                stroke-dasharray=""
                stroke-dashoffset="0"
                font-family="none"
                font-weight="none"
                font-size="none"
                text-anchor="none"
                style="mix-blend-mode: normal"
              >
                <g
                  fill="#8b0000"
                  stroke="#8b0000"
                  stroke-width="10"
                  stroke-linejoin="round"
                >
                  <path
                    d="M165.12,86c0,23.37499 -10.21576,44.42752 -26.3711,58.89656c-1.81717,1.72575 -4.42892,2.32705 -6.81778,1.56965c-2.38887,-0.7574 -4.17703,-2.7537 -4.66789,-5.21122c-0.49085,-2.45752 0.39324,-4.98757 2.30786,-6.60453c13.37362,-11.97768 21.7889,-29.27313 21.7889,-48.65047c0,-36.17891 -29.18109,-65.36 -65.36,-65.36c-36.17891,0 -65.36,29.18109 -65.36,65.36c0,19.3811 8.41605,36.67659 21.7889,48.65047c1.91462,1.61696 2.7987,4.14701 2.30785,6.60452c-0.49086,2.45751 -2.27902,4.45382 -4.66788,5.21121c-2.38886,0.7574 -5.0006,0.1561 -6.81778,-1.56964c-16.1561,-14.46596 -26.37109,-35.51846 -26.37109,-58.89656c0,-43.61533 35.50467,-79.12 79.12,-79.12c43.61533,0 79.12,35.50467 79.12,79.12zM90.86543,73.9455c1.31511,1.31511 2.04182,3.10657 2.01457,4.96621v62.51125l12.33563,-12.33562c1.72562,-1.79731 4.28807,-2.52131 6.6991,-1.89277c2.41103,0.62854 4.29388,2.5114 4.92242,4.92242c0.62854,2.41103 -0.09546,4.97347 -1.89277,6.6991l-23.44172,23.44172c-1.29623,1.72381 -3.32575,2.74008 -5.48253,2.74536c-2.15678,0.00528 -4.19125,-1.00105 -5.49591,-2.71849l-23.46859,-23.46859c-2.01031,-1.95106 -2.63703,-4.92612 -1.58466,-7.52237c1.05237,-2.59624 3.57371,-4.29526 6.37513,-4.29592c1.86093,0.00006 3.64248,0.75388 4.93828,2.08953l12.33563,12.33562v-62.51125c-0.02674,-1.82469 0.67247,-3.58527 1.94383,-4.89443c1.27135,-1.30915 3.0107,-2.05964 4.83539,-2.08635c1.85964,-0.02725 3.65111,0.69946 4.96621,2.01457z"
                  ></path>
                </g>
                <path
                  d="M0,172v-172h172v172z"
                  fill="none"
                  stroke="none"
                  stroke-width="1"
                  stroke-linejoin="miter"
                ></path>
                <g fill="#ff0000" stroke="none" stroke-width="1" stroke-linejoin="miter">
                  <path
                    d="M86,6.88c-43.61533,0 -79.12,35.50467 -79.12,79.12c0,23.3781 10.21499,44.43061 26.37109,58.89656c1.81717,1.72574 4.42892,2.32704 6.81778,1.56964c2.38886,-0.7574 4.17702,-2.7537 4.66788,-5.21121c0.49086,-2.45751 -0.39323,-4.98756 -2.30785,-6.60452c-13.37286,-11.97388 -21.7889,-29.26937 -21.7889,-48.65047c0,-36.17891 29.18109,-65.36 65.36,-65.36c36.17891,0 65.36,29.18109 65.36,65.36c0,19.37733 -8.41528,36.67279 -21.7889,48.65047c-1.91462,1.61696 -2.79871,4.14701 -2.30786,6.60453c0.49085,2.45752 2.27902,4.45382 4.66789,5.21122c2.38887,0.7574 5.00061,0.1561 6.81778,-1.56965c16.15534,-14.46904 26.3711,-35.52158 26.3711,-58.89656c0,-43.61533 -35.50467,-79.12 -79.12,-79.12zM85.89922,71.93094c-1.82469,0.02672 -3.56404,0.7772 -4.83539,2.08635c-1.27135,1.30915 -1.97057,3.06973 -1.94383,4.89443v62.51125l-12.33563,-12.33562c-1.2958,-1.33565 -3.07735,-2.08947 -4.93828,-2.08953c-2.80142,0.00066 -5.32276,1.69967 -6.37513,4.29592c-1.05237,2.59624 -0.42565,5.57131 1.58466,7.52237l23.46859,23.46859c1.30466,1.71744 3.33912,2.72377 5.49591,2.71849c2.15678,-0.00528 4.1863,-1.02155 5.48253,-2.74536l23.44172,-23.44172c1.79731,-1.72562 2.52131,-4.28807 1.89277,-6.6991c-0.62854,-2.41103 -2.51139,-4.29388 -4.92242,-4.92242c-2.41103,-0.62854 -4.97347,0.09546 -6.6991,1.89277l-12.33563,12.33562v-62.51125c0.02725,-1.85964 -0.69946,-3.65111 -2.01457,-4.96621c-1.31511,-1.31511 -3.10657,-2.04182 -4.96621,-2.01457z"
                  ></path>
                </g>
                <path
                  d=""
                  fill="none"
                  stroke="none"
                  stroke-width="1"
                  stroke-linejoin="miter"
                ></path>
              </g>
            </g>
          </svg>
          <p class="logout">- Se déconnecter.</p>
        </a>
        <nav>
          <ul>
            <li>
              <a href="./account.php">Mon Compte</a>
            </li>
          </ul>
        </nav>
      </header>
    </section>

    <?php
          // Upload User image
      if($_SERVER["REQUEST_METHOD"] == "POST"){
          if(!is_dir("./users")){
            mkdir("./users", 0755, true);
        }

        if(!is_dir("./users/$username")){
            mkdir("./users/$username");
        }
        $target_dir = "./users/$username/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image

        if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists

        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size

        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";

            $req = "UPDATE users SET fileToUpload='$target_file' WHERE username='$username'";
            mysqli_query($link, $req);
            
            unlink($profilePic);
            $_SESSION["fileToUpload"] = $target_file;

            } else {
            echo "Sorry, there was an error uploading your file.";
            }
        }
      }
    ?>
    <section class="sideSection">contentbox</section>
    <script src="./app.js"></script>
  </body>
</html>
