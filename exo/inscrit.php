<?php
    session_start();
    if($_SESSION["autoriser"]!="oui"){
        header("location:index.html");
        exit();
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="connexion.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>you_11</title>
</head>
<body>
    <div class="main">
        <div class="navbar">
            <div class="icon">
                <h2 class="logo">Ter3</h2>
            </div>
            <nav>
                <div class="logo">Ter3</div>
                    <ul>
                        <li><a href="index.html">Acceuil</a></li>
                        <li><a href="#">Divers</a></li>
                        <li><a href="#">Vente</a></li>
                        <li><a href="#">Depot</a></li>
                        <li><a href="#">Payement</a></li>
                        <li><a href="inscri.html">Inscription</a></li>
                        <li><a href="deconnexion.php">Deconnexion</a></li>
                    </ul>
            </nav>
            
        </div>
        <div class="content">
            <h1><span>FELECITATION!</span> <br>Vous êtes bien inscrit!</h1>
            
            <!--
                <p class="par">Lorem ipsum dolor sit amet consectetur, <br>adipisicing elit. Error hic consectetur similique <br>sapiente sequi dolore blanditiis. <br>Aliquid recusandae quidem eum quis debitis optio! <br> In omnis quae culpa quaerat nam veritatis.</p>
            <button class="cn"><a href="#">JOIN US</a></button>
            
            -->
           
        </div>
    </div>
</body>
</html>