<?php

//ici on appelle notre connexion à la BDD//////////////
include("inc/init.inc.php");
///////////////////////////////////////////////////////

$pseudo = "";
$mdp = "";
$nom = "";
$prenom = "";
$email = "";
$sexe = "";
$ville = "";
$code_postal = "";
$adresse = "";

if(isset($_POST["pseudo"]) && isset($_POST["mdp"]) && isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["email"]) && isset($_POST["sexe"]) && isset($_POST["ville"]) && isset($_POST["code_postal"]) && isset($_POST["adresse"]))
{
    $pseudo = $_POST["pseudo"];
    $mdp = $_POST["mdp"];
    $nom = $_POST["nom"] ;
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $sexe = $_POST["sexe"];
    $ville = $_POST["ville"];
    $code_postal = $_POST["code_postal"];
    $adresse = $_POST["adresse"];
    
    $erreur = false ;
    //variable de controle initialisé par défault sur false. Si il y a erreur .. nous placerons une condition qui indiquera $erreur = true;
    
    //controle sur la taille du pseudo
    if(iconv_strlen($pseudo) < 4 || iconv_strlen($pseudo) > 21)
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Attention, le pseudo doit contenir entre 4 et 20 caractères.</div>"; //on appelle la variablee de init.inc.php
    }
    
    //verification du pseudo selon les caractères autorisés via une expression reguliere
    //preg_match() est la fonction predefinie pour l'espression reguliere. Renvoie true si elle correspondent à l'expression , sinon false
    $verif_pseudo = preg_match('#^[a-zA-Z0-9._-]+$#', $pseudo);
    /*
    - Les # indique le debut et la fin de l'expression.
    - Le ^ indique le début de la chaine, sinon la chaine pourrait commencer par autre chose
    - Le $ indique la fin de la chaine, sinon la chaine pourrrait finir par autre chose
    - + indique que l'on peut avoir plusieurs fois les mêmes caractères
    - */
    
    if($verif_pseudo == false)
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Attention, le pseudo doit contenir uniquement des caractères a-z, A-Z, 0-9, '.', '_', ou '-'.</div>"; 
    }
    
    //verification du format du mail
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Le format d'email est incorrect, merci de vérifier votre saisie.</div>"; 
    }
    
    //vérification de la disponibilité du pseudo
    // On se protege d'abord des injections SQL
    $verif_dispo_pseudo = $pdo->prepare("SELECT pseudo FROM membre WHERE pseudo= :pseudo");
    $verif_dispo_pseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $verif_dispo_pseudo->execute();
    
    if($verif_dispo_pseudo->rowCount() > 0)
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Pseudo indisponible.</div>";
    }
    
    //Suite aux verification s'il y a eu au moins un cas d'erreur 
    //Appliquer la requete SQL d'ajout dans la BDD
    if($erreur == false)
    {
        //cryptage du mdp >>>>> hachage avec fonction predefinie
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);
        
        $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :sexe, :ville, :code_postal, :adresse, 0)");
        
        $enregistrement->bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
        $enregistrement->bindParam(":mdp", $mdp, PDO::PARAM_STR);
        $enregistrement->bindParam(":nom", $nom, PDO::PARAM_STR);
        $enregistrement->bindParam(":prenom", $prenom, PDO::PARAM_STR);
        $enregistrement->bindParam(":email", $email, PDO::PARAM_STR);
        $enregistrement->bindParam(":sexe", $sexe, PDO::PARAM_STR);
        $enregistrement->bindParam(":ville", $ville, PDO::PARAM_STR);
        $enregistrement->bindParam(":code_postal", $code_postal, PDO::PARAM_STR);
        $enregistrement->bindParam(":adresse", $adresse, PDO::PARAM_STR);
        
        $enregistrement->execute();
        
        //Redirection vers la page connexion.php
        //attention en phase de test, c'est un parametre a desactiver, car il pourrait cacher des erreurs
        //attention, cette fonction doit etre executé avant le moindre affichage html (comme setCokkie() et session_start())
        header('Location: connexion.php');
        exit();
    }
}

//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("inc/header.inc.php");
include("inc/nav.inc.php");
?>

<div class="container">



    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-pencil"></span> Inscription</h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <form method="post" action="">
                <div class="form-group">				
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo..." value="<?=$pseudo?>">
                </div>
                <div class="form-group">				
                    <label for="mdp">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe..." >
                </div>
                <div class="form-group">				
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom..." value="<?=$nom?>">
                </div>
                <div class="form-group">				
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom..." value="<?=$prenom?>">
                </div>
                <div class="form-group">				
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Email..." value="<?=$email?>">
                </div>
                <div class="form-group">				
                    <label for="sexe">Sexe</label>
                    <select class="form-control" name="sexe" id="sexe">
                        <option value="m">Homme</option>
                        <option value="f" <?php if($sexe == "f"){ echo "selected";}?> > Femme</option>
                    </select>
                </div>
                <div class="form-group">				
                    <label for="ville">Ville</label>
                    <input type="text" class="form-control" id="ville" name="ville" placeholder="Ville..." value="<?=$ville?>">
                </div>
                <div class="form-group">				
                    <label for="code_postal">Code postal</label>
                    <input type="text" class="form-control" id="code_postal" name="code_postal" placeholder="Code postal..." value="<?=$code_postal?>">
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <textarea id="adresse" name="adresse" class="form-control" rows="3"> <?=$adresse?> </textarea>
                </div>
                <hr>
                <button type="submit" class="btn btn-success col-sm-12"><span class="glyphicon glyphicon-ok" name="inscription"></span> S'inscrire</button>
            </form>
        </div>
    </div>

</div><!-- /.container -->


<?php
include("inc/footer.inc.php");