<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////



//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");
?>

<div class="container">

    <div class="col-sm-12 starter-template">
        <h1><span class="glyphicon glyphicon-home"></span> Bienvenue sur LiliBoutique</h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>

</div>

<div class="container">

    <div class="row">

        <aside class="col-sm-2">
            <ul class="list-group">
                <li class="list-group-item"><b>Produit par catégorie</b></li>
                <li class="list-group-item"><a href="?categorie=">Tous</a></li>
                <?php
                    // Création du menu sur le coté avec les catégories

                    $nom_categorie = $pdo->query("SELECT DISTINCT categorie FROM produit ORDER BY  categorie");

                         while($ligne_en_cours = $nom_categorie->fetch(PDO::FETCH_ASSOC))
                         {                             
                             foreach($ligne_en_cours AS $valeur)
                             {
                                 echo  "<li class='list-group-item'><a href='?categorie=" . $valeur . "'>" . $valeur . "</a></li>";
                             }
                         }  

                ?>
            </ul>
            
            
        </aside>

        <div class="col-sm-10">
           <div class="row">
                <?php
                //if($_GET['categorie'] == "tous")
                //{

               $condition = '';
               
               if(!empty($_GET['categorie']))
               {
                   $condition = "AND categorie='" . $_GET['categorie'] . "'";
               }
               //////////////////A SECURISER AVEC UN PREPARE////////////////////////////////////
               
               // Si categorie n'est pas vide, donc sous entendu, si il contient un nom de catégorie, on va lui rajouter une condition qui sera inseré dans notre requete plus bas.
               //à noter : on peut faire un switch case quand on a toute une suite de condition possible, prix plus grand, plus bas, à partir d'une taille... etc. 
               
               $affichage_produits = $pdo->query("SELECT categorie, reference, titre, photo FROM produit WHERE reference IS NOT null $condition ORDER BY reference");

                    while($produit_en_cours = $affichage_produits->fetch(PDO::FETCH_ASSOC))
                    {?>                        
                                <div class="col-sm-4">
                                    <div class="thumbnail">
                                        <img class="img-responsive col-sm-12 max-height-img-produit" src='<?php echo $produit_en_cours['photo']; ?>' alt = "<?php echo $produit_en_cours['titre']; ?>">

                                        <div class="caption">
                                            <h3><?php echo " " . $produit_en_cours['titre']; ?></h3>

                                            <p><span class="glyphicon glyphicon-barcode"></span><?php echo " " . $produit_en_cours['reference']; ?> <br><span class="glyphicon glyphicon-tag"></span><?php echo " " . $produit_en_cours['categorie']; ?>
                                            </p>

                                            <p class="row">
                                                <a href="#" class="margin-min btn btn-default col-sm-5" role="button">Fiche produit</a>
                                                <?php if(utilisateur_est_connecte()){ ?>
                                                <a href="#" class="margin-min btn btn-primary col-sm-6" role="button">Ajouter au panier</a>
                                                <?php } ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>                        
                    <?php }
                //} 
               
               ?>
            </div>
        </div>

    </div>


</div><!-- /.container -->


<?php
include("inc/footer.inc.php");