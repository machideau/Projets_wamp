<?php
session_start();
include_once "bdd.php";

        // supprimer les produits
// si la variable del existe
if(isset($_GET['del']))
{
    $id_del = $_GET['del'];
    unset($_SESSION['panier'][$id_del]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Panier</title>
</head>
<body class="panier">
    <a href="index.php" class="link">boutique</a>
    <section>
        <table>
            <tr>
                <th></th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Quantite</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;
            // liste des produits 
            // recuperer les cles du tableau session
            $ids = array_keys($_SESSION['panier']);
            // s'il n'y a aucune cle dans le tableau
            if(empty($ids))
            {
                echo "votre panier est vide";
            }else{
                // si oui
                $produits = mysqli_query($con, "SELECT * FROM produits WHERE id IN (".implode(',', $ids).")");
                
                // liste des produit avec une boucle foreach

                foreach($produits as $produit):    
                    // calcul du total ( prix unitaire * quantite)
                    $total += $produit['price'] * $_SESSION['panier'][$produit['id']];
             ?>
                <tr>
                    <td><img src="./images/<?=$produit['img']?>" alt="chaussure"></td>
                    <td><?=$produit['name']?></td>
                    <td><?=$produit['price']?>$</td>
                    <td><?=$_SESSION['panier'][$produit['id']]// quantite?></td>
                    <td><a href="panier.php?del=<?=$produit['id']?>"><img src="./images/delete.png" alt="delete"></a></td>
                </tr>
            <?php endforeach;} ?>
            
            <tr class="total">
                <th>Total : <?=$total?>$</th>
            </tr>
        </table>
    </section>
    <a href="#" class="link">buy now</a>
</body>
</html>