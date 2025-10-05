<?php
    session_start();
   
$nom=$_SESSION["nom"];
$prenom=$_SESSION["prenom"];
$sexe=$_SESSION["sexe"];
$classe=$_SESSION["classe"];
$date=$_SESSION["date"];
$tel=$_SESSION["tel"];



    $bdpdo=new PDO('mysql:host=localhost;dbname=exo', 'root', '');
    $bdpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        if(isset($_POST['submitact'])){
            $pass=sha1($_POST['pass']);
            $cpass=sha1($_POST['cpass']);
            $email=sha1($_POST['email']);
            //$pass=($_POST['pass']);
            //$cpass=($_POST['cpass']);
            //$email=($_POST['email']);
 

            if($pass==$cpass && !empty($pass) && !empty($cpass) && !empty($email)){
                $requete=$bdpdo->prepare('INSERT INTO users(nom, Prenom, mail, pwd, classe, sexe, date, comment) VALUES (:nom, :prenom, :email, :pass, :classe, :sexe, :date_naissance, :tel)');
                $requete->bindParam(':nom', $nom);
                $requete->bindParam(':prenom', $prenom);
                $requete->bindParam(':email', $email);
                $requete->bindParam(':pass', $pass);
                $requete->bindParam(':classe', $classe);
                $requete->bindParam(':sexe', $sexe); 
                $requete->bindParam(':date_naissance', $date);
                $requete->bindParam(':tel', $tel);
                
                $result=$requete->execute();

                if(!$result){
                    echo "il ya un probleme";
                }else{
                    $_SESSION["id"]=$bdpdo->lastInsertId();
                    echo "bien votre identifiant est " . $_SESSION["id"];
                    
                    $_SESSION["autoriser"]="oui";
                    header("location:inscrit.php"); // avoir acces à la page session.php
                }

            }else{
                echo "remplisser tous les champs";
            }
        }


?>



