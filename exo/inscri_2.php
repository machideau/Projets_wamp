<?php   
session_start();
//
//&& isset($_POST['prenom']) && isset($_POST['sexe']) && isset($_POST['classe']) && isset($_POST['date']) && isset($_POST['tel'])
    if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['nom']) ){
       
        $_SESSION["nom"]=$_POST['nom'];
        $_SESSION["prenom"]=$_POST['prenom'];
        $_SESSION["sexe"]=$_POST['sexe'];
        $_SESSION["classe"]=$_POST['classe'];
        $_SESSION["date"]=$_POST['date'];
        $_SESSION["tel"]=$_POST['tel'];

    }else{
        header("location: index.html");
        exit();
    }


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Connexion et inscription </title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/bootstrap.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='css/all.min.css'>



   
</head>
<body class="bg-light">
    <div class="container ">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top">
                <h2 class="text-center"> Inscription</h2>
                <h3 class="text-center">Etape 2</h3> 
                <p class="text-center text-muted lead"> saisissez vos information confidentiel </p>

                <form action="traitement.php" method="post">
                    <div class="input-group  mb-3">
                        <span class="input-group-text"><i class="fa fa-envelope"></i> </span>
                        <input type="text" name="email" class="form-control"  placeholder="Email ">
                    </div>

                    <div class="input-group  mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"></i> </span>
                        <input type="password" name="pass" class="form-control" placeholder="Mot de passe ">
                    </div>

                    <div class="input-group  mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"></i> </span>
                        <input type="password" name="cpass" class="form-control" placeholder=" confirmer le Mot de passe ">
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="submitact" class="btn btn-success">S’inscrire</button>
                        <p class="text-center text-muted mt-3">
                            En cliquant sur S’inscrire, vous acceptez nos <a href="#">  Conditions générales</a>, notre <a href=""> Politique de confidentialité </a> et notre <a href="#">  Politique d’utilisation</a> des cookies. 
                        </p>
                        <p class="text-center">
                             Avez vous déjà un compte ?<a href="connexion.php"> Connexion </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
</body>
</html> 
<script src='js/bootstrap.js'></script>