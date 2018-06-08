<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////

//pour la déconnexion, via le get

if(isset($_GET['action']) && $_GET['action'] == 'deconnexion')
{
    //unset($_SESSION['membre']);//on détruit immédiatement la session, mais juste le membre pour la connexion, sinon il faudrait redemarrer une session avec un start_session()
    session_destroy();

}

//Si l'utilisateur force le profil.php, on lui fait une redirection direct sur la connexion s'il n'est pas connecté
//cela evite de trouver des acces à ma BDD
if(utilisateur_est_connecte())
{
    header('Location:profil.php');
    exit();// permet de bloquer l'execution du code, sécurité ;)
    //si il y a eu deconnexion, c'est juste apres le exit qu'il est executé. Car le exit represente la fin du script.
    //donc le header nous renvoie sur profil.php, mais notre script de profil nous indique que si la session est vide, alors on est renvoyer sur la page connexion. et donc on revient bien sur la page connexion. Un aller retour a été appliqué mais on a pas eu le temps de le voir.
}
////////////////////////////////////

if(isset($_POST["pseudo"]) && isset($_POST["mdp"]))
{
    $pseudo = $_POST["pseudo"];
    $mdp = $_POST["mdp"];
    
    //requete en BDD selon le pseudo
    
    $selection_membre = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $selection_membre->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    $selection_membre->execute();
    
    //on vérifie s'il y a au moins une ligne, cela veut dire que le pseudo existe.
    if($selection_membre->rowCount() > 0)
    {
        $info_membre = $selection_membre->fetch(PDO::FETCH_ASSOC); //correspond au tableau array avec toutes les infos sur le membre. on n'a pas fait une boucle car le pseudo est unique, donc 1 seule ligne obligée
            
        //Vérification du MDP avec la fonction prédéfinie password_verify() qui fonctionne avec password_hash(), voir sur l'inscription
        if(password_verify($mdp, $info_membre['mdp']))// sous entendu renverra true ou false
        //true, cela veut dire que c'est bon. False que ce n'est pas bon.
        {
            //si on rentre dans cette condition, alors le mdp et le pseudo sont valide, dans ce cas on enregistre les informations dans la session
            $_SESSION['membre'] = array(); 
            $_SESSION['membre']['id_membre'] = $info_membre['id_membre'];
            $_SESSION['membre']['pseudo'] = $info_membre['pseudo'];
            $_SESSION['membre']['nom'] = $info_membre['nom'];
            $_SESSION['membre']['prenom'] = $info_membre['prenom'];
            $_SESSION['membre']['email'] = $info_membre['email'];
            $_SESSION['membre']['sexe'] = $info_membre['sexe'];
            $_SESSION['membre']['ville'] = $info_membre['ville'];
            $_SESSION['membre']['code_postal'] = $info_membre['code_postal'];
            $_SESSION['membre']['adresse'] = $info_membre['adresse'];
            $_SESSION['membre']['statut'] = $info_membre['statut'];
            
            
            //et ensuite on le redirige vers sa page "profil.php"
            header('Location: profil.php');
            exit();
            
            
        } else {
        $message .= "<div class='alert alert-danger'>Attention, le pseudo ou le mot de passe est incorrect.</div>";
    }
    } else {
        $message .= "<div class='alert alert-danger'>Attention, le pseudo ou le mot de passe est incorrect.</div>";
    }
}

//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");

echo "<pre style='margin-top:50px';>" ; print_r($_SESSION); echo "</pre>";
?>

<div class="container">



    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-user"></span> Connexion</h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <form method="post" action="">
                <div class="form-group">				
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo..." value="" >
                </div>
                <div class="form-group">				
                    <label for="mdp">Mot de passe</label>
                    <input value="" type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe">
                </div>
                <hr>
                <button type="submit" class="btn btn-default col-sm-12" name="connexion">Connexion</button>
            </form>
        </div>
    </div>


</div><!-- /.container -->


<?php
include("inc/footer.inc.php");