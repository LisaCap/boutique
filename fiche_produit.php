<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////

if(!isset($_GET['id_produit']))
{
    header("location:index.php"); //on vire les gens qui force l'url sur id_produit.php
}

$produit = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
$produit->bindParam(":id_produit", $_GET['id_produit'], PDO::PARAM_STR);
$produit->execute();

if($produit->rowCount() < 1)
{
    header("location:correction_index.php"); // on vire les gens qui force l'ecriture d'un identifiant inconnu sur l'id_produit
}

$info_produit = $produit->fetch(PDO::FETCH_ASSOC);

//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");

echo "<pre>"; print_r($info_produit); echo "</pre>";

?>

<div class="container">

    

    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-tag"></span> Fiche Produit <?php echo $_GET['id_produit'] ?></h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>

</div><!-- /.container -->


<?php
include("inc/footer.inc.php");