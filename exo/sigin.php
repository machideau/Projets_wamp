<?php
$connexion = new PDO('mysql:host=localhost;dbname=exo','root','');
if($connexion)
{
    echo "connete<br>";
}
if(isset($_POST['valider']))
{
    if(!empty($_POST['mail']) AND !empty($_POST['password']))
    {
        $mail = htmlspecialchars($_POST['mail']);
        $password = ($_POST['password']);

        $req = $connexion->prepare("SELECT * FROM users WHERE mail = ? AND pwd = ?");
        $req->execute(array($mail, $password));
        $cpt = $req->rowCount();

        if($cpt == 1)
        {
            $message = "compte trouve !!! vous etes connecte<br>";
           header('Location: acceuil.php');
        }else{
            $message = " compte non existant <br>";
        }

    }else{
        $message = "remplissez tous les champs<br>";
    }
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
                <h2 class="text-center"> Connexion</h2>
                <p class="text-center text-muted lead"> Se connecter à WWW </p>

                <form action="" method="POST">
                    <div class="input-group  mb-3">
                        <input type="email" class="form-control" placeholder="Adresse e-mail " name="mail">
                    </div>
                
                    <div class="input-group  mb-3">
                        <input type="password" class="form-control" placeholder="Mot de passe " name="password">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="valider" class="btn btn-success">Se connecter</button>
                       
                        <p class="text-center">
                            <i style="color:red">
                                <?php
                                if(isset($message))
                                {
                                    echo $message;
                                }
                                ?>
                            </i>
                              vous n'avez pas de compte ?<a href="index.php"> Inscription </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
</body>
</html> 
<script src='js/bootstrap.js'></script>