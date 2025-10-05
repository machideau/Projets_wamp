  <?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>boutique</title>
</head>
<body>
    <!-- afficher le nombre de produits dans le panier -->
    <a href="panier.php" class="link">Panier </span></a>
    <section class="produit_list">
        <?php
        // inclure la page de connexion
        include_once "bdd.php";
        // afficher la liste des produits
        $req = mysqli_query($con, "SELECT * FROM produits");
        while($row = mysqli_fetch_assoc($req)){
        ?>
        <form action="" method="post" class="product">
            <div class="image_product">
                <a href="./images/<?=$row['img']?>"><img src="./images/<?=$row['img']?>" alt="chaussures"></a>
            </div>
            <div class="content">
                <h4 class="name"><?=$row['name']?></h4>
                <h2 class="price"><?=$row['price']?>$</h2>
                <a href="add.php?id=<?=$row['id']?>" class="id_product">Ajouter au panier</a>
            </div>
        </form>
        <?php } ?>
    </section>
</body>
</html>