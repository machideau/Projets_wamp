
<?php
        session_start();

       // echo $_SESSION["id"];

        $conn=@new mysqli("localhost", "root", "base2010", "formulaire") or die("ereur de connexion");
        if(isset($_POST['valider'])){
            if(!empty($_POST['email']) && !empty($_POST['pass'])){

            $pass=sha1($_POST['pass']);
            $email=sha1($_POST['email']);

            //$pass=($_POST['pass']);
            //$email=($_POST['email']);


            $req="select * from inscription ";
            $id=$_SESSION["id"];
            echo $id;
            $res= mysqli_query($conn, "select * from inscription where id = '$id'");
            echo "<hr />";
            $tab=mysqli_fetch_assoc($res);
            if($pass==$tab["pass"] && $email==$tab["email"]){
                $_SESSION["autoriser"]="oui";
                header("location:inscrit.php");
            }else{
                echo "mauvaise mot de passe";
            }
/*  
            if(!$reslt){
                echo "erreur";
            }else{
                $bien=$reslt->rowCount();
                echo $bien;
            }

              
            $req=$connexion->prepare("select * from info")
                
            $req->excute(array($email, $password));
                $cpt=$req->rowCount();
                if($cpt==1){
                    $message="bien trouver"
                }else{
                    $message="desole"
                }
            }else{
            $message="veiller remplir tous les champs"*/
        }else{
            echo "veiller remplir tous les case";
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

                <form action="connexion.php" method="post">
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-envelope">
                            </i> 
                        </span>
                        <input type="text" name="email" class="form-control" placeholder="Adresse e-mail  ">
                    </div>
                
                    <div class="input-group  mb-3">
                        <span class="input-group-text">
                            <i class="fa fa-lock">
                            </i> 
                        </span>
                        <input type="text" name="pass" class="form-control" placeholder="Mot de passe ">
                    </div>
                    <div class="d-grid">
                        <button type="submit" name="valider" class="btn btn-success">Se connecter</button>
                       
                        <p class="text-center">
                              vous n'avez pas de compte ?<a href="index.html"> Inscription </a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
</body>
</html> 
<script src='js/bootstrap.js'></script>
