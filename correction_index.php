<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////

//requete de recuperation de toutes les categories
$liste_categorie = $pdo->query("SELECT DISTINCT categorie FROM produit ORDER BY categorie");

/*//requete de recuperation de tous mes produits
$liste_produit = $pdo->query("SELECT * FROM produit ORDER BY titre");*/

//requete de recuperation de tous mes produits ou de mes produits selon une categorie ! 
if(isset($_GET['categorie']))
{
    $liste_produit = $pdo->prepare("SELECT * FROM produit WHERE categorie = :categorie ORDER BY titre");
    
    $liste_produit->bindParam(":categorie", $_GET['categorie'], PDO::PARAM_STR);
    $liste_produit->execute();
} else { // ici sont affiché tous mes produits , sans filtre
    
    $liste_produit = $pdo->query("SELECT * FROM produit ORDER BY titre");
    
}


//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");
?>

<div class="container">



    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-home"></span> Boutique </h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>
    
    <div class="row">
        <!---AFFICHAGE MENU CATEGORIE----------------------------->
        <div class="col-sm-2">
            <?php
                echo '<ul class="list-group">';
                while($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC))
                {
                    echo  "<li class='list-group-item'><a href='?categorie=" . $categorie['categorie'] . "'>" . $categorie['categorie'] . "</a></li>"; 
                }
                echo '</ul>';
            ?>
        </div>
        <!---FIN AFFICHAGE MENU CATEGORIE------------------------->
        
        <!---AFFICHAGE PRODUITS----------------------------------->
        <div class="col-sm-10">
           <div class="row">
                <?php
               
                    $compteur = 0; //pour le probleme de flaot, on va dire : tous les 4 tour, tu vas ouvrir un nouveau div class = row
               
                    //boucle while pour traiter notre objet liste_produit et les afficher
                    while($produit_en_cours = $liste_produit->fetch(PDO::FETCH_ASSOC))
                    {
                        /*var_dump($produit_en_cours); echo "<hr>";*/
                        
                        if($compteur%4 == 0 && $compteur != 0)//tous les multiple de 4, donc tous les 4 tours
                        {
                            echo '</div><div class="row">';//ici je ferme le 1er row que j'ai ouvert en dehors de ma boucle, et j'en recreer un, pour construire une nouvelle ligne que ne sera plus casser par mon flux de float. (qui est generé pas les col-sm-3)
                        }
                        $compteur++;
                        
                        echo "<div class='col-sm-3'>";
                        
                        echo '<div class="panel panel-default">
                        
                                    <div class="panel-heading"><img src="img/logo.png" alt="logo" class="img-responsive"></div>
                                
                                    <div class="panel-body">
                                        <h3>' . $produit_en_cours['titre'] . '</h3>
                                        <hr>
                                        <img src="' . URL . $produit_en_cours['photo'] . '" alt="logo" class="img-responsive">
                                        <a href="fiche_produit.php?id_produit=' . $produit_en_cours['id_produit'] . '" class="btn btn-success col-sm-12">Voir la fiche</a>
                                    </div>
                              </div>';
                        
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <!---FIN AFFICHAGE PRODUITS----------------------------->

    </div>

</div><!-- /.container -->


<?php
include("inc/footer.inc.php");