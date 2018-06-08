<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////

//Si l'utilisateur force le profil.php, on lui fait une redirection direct sur la connexion s'il n'est pas connecté
//cela evite de trouver des acces à ma BDD
if(!utilisateur_est_connecte())
{
    header('Location:connexion.php');
    exit();// permet de bloquer l'execution du code, sécurité ;)
}
////////////////////////////////////

$id_membre = $_SESSION['membre']['id_membre'];
$pseudo = $_SESSION['membre']['pseudo'];
$nom = $_SESSION['membre']['nom'];
$prenom = $_SESSION['membre']['prenom'];
$email = $_SESSION['membre']['email'];
$sexe = $_SESSION['membre']['sexe'];
$ville = $_SESSION['membre']['ville'];
$code_postal = $_SESSION['membre']['code_postal'];
$adresse = $_SESSION['membre']['adresse'];
$statut = $_SESSION['membre']['statut'];

//echo "<pre style='margin-top:50px';>" ; print_r( $_SESSION['membre']); echo "</pre>";



//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");
?>

<div class="container">



    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-hand-right"></span>
            Bonjour

            <?php 
            if($sexe == 'f')
            {
                echo "Mme ";

            } else
            {
                echo "M. ";
            }

            echo $pseudo; ?> </h1> 

    </div>

    <div>
        <h2>Voici votre profil :</h2> 

        <div class = "row">
            <div class="col-sm-8">
                <div class="list-group">
                    <p class="list-group-item active"><b>Pseudo : </b><?php echo ucfirst($pseudo) //ucfirst = uppercase first ?></p>
                    <p class="list-group-item"><b>Nom : </b><?= ucfirst($nom) ?></p>
                    <p class="list-group-item"><b>Prénom : </b><?= ucfirst($prenom) ?></p>
                    <p class="list-group-item"><b>Email : </b><?= $email ?></p>
                    <p class="list-group-item"><b>Adresse de livraison : </b> <?= $adresse . " " . $code_postal . " " . $ville ?></p>
                    <p class="list-group-item"><b>Statut : </b>
                        <?php if(utilisateur_est_admin()){ echo "Vous êtes administrateur.";}else { echo "Vous êtes membre.";} ?>
                    </p>
                    
                </div>
            </div>
            <div class="col-sm-4">
                <img src="img/profil.jpg" class="img-thumbnail" alt = "image de profil">
            </div>
        </div>
        <hr>
        <hr>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

</div><!-- /.container -->


<?php
include("inc/footer.inc.php");