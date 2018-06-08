<?php

//ici on appelle notre connexion à la BDD//////////////
include("../inc/init.inc.php");
///////////////////////////////////////////////////////

//Si l'utilisateur force le gestion_boutique.php, on lui fait une redirection direct sur son profil et s'il n'est pas connecté c'est une redirection vers connexion.php
//cela evite de trouver des acces à ma BDD

if(!utilisateur_est_admin())
{
    $chemin = URL . "connexion.php";
    header("Location:$chemin");
    exit();// permet de bloquer l'execution du code, sécurité ;)
}

//A NOTER : Le ou les fichiers joints via un formulaire seront dans la super global $_FILES car ce ne sont pas de saisies classique. Donc il passe le fichier joint dans ce tableau array multidimenionnel qui se nomme $_FILES. 

$reference = "";
$categorie = "";
$titre = "";
$description = "";
$couleur = "";
$public = "";
$taille = "";
$prix = "";
$stock = "";
$nouvelle_categorie = "";

///////////////////////////////////////////////////////////////////////////////////////////
/////AJOUTER PRODUIT///////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST["reference"]) && isset($_POST["categorie"]) && isset($_POST["titre"]) && isset($_POST["description"]) && isset($_POST["couleur"]) && isset($_POST["public"]) && isset($_POST["taille"]) && isset($_POST["prix"]) && isset($_POST["stock"]) && isset($_POST["ajouterproduit"]))
{
    $reference = $_POST["reference"];
    $categorie = $_POST["categorie"];
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $couleur = $_POST["couleur"];
    $public = $_POST["public"];
    $taille = $_POST["taille"];
    $prix = $_POST["prix"];
    $stock = $_POST["stock"];

    $erreur = false;

    //verifier que la reference est unique //////////////////////////
    $verif_dispo_reference = $pdo->prepare("SELECT reference FROM produit WHERE reference= :reference");
    $verif_dispo_reference->bindParam(':reference', $reference, PDO::PARAM_STR);
    $verif_dispo_reference->execute();

    if($verif_dispo_reference->rowCount() > 0)
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Référence indisponible.</div>";
    }
    ////////////////////////////////////////////////////////////////
    
    //verifier que la reference est entrée //////////////////////////

    if(empty($_POST["reference"]))
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Référence à renseigner obligatoirement.</div>";
    }
    ////////////////////////////////////////////////////////////////

    $photo_bdd = "";

    //recuperation de la photo
    if(!empty($_FILES['photo']['name']))
    {
        $photo_bdd = 'img/produits/' . $reference . $_FILES['photo']['name'];

        $chemin = RACINE_SERVEUR . $photo_bdd;
        // copy() est une fonction predefinie permettant de copier un fichier depuis un emplacement (1er argument) vers un emplacement cible (argument 2)

        copy($_FILES['photo']['tmp_name'], $chemin);
    } else {
        
        $photo_bdd = 'img/produits/img-default.png';
        
    }

    //Notre requete d'ajout de produit////////////////////////////

    if($erreur == false)
    {
        $ajouter_produit = $pdo->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, '$photo_bdd', :prix, :stock)");
        $ajouter_produit->bindParam(":reference", $reference, PDO::PARAM_STR);
        $ajouter_produit->bindParam(":categorie", $categorie, PDO::PARAM_STR);
        $ajouter_produit->bindParam(":titre", $titre, PDO::PARAM_STR);
        $ajouter_produit->bindParam(":description", $description, PDO::PARAM_STR);
        $ajouter_produit->bindParam(":couleur", $couleur, PDO::PARAM_STR);
        $ajouter_produit->bindParam(":taille", $taille, PDO::PARAM_STR);
        $ajouter_produit->bindParam(":public", $public, PDO::PARAM_STR);
        $ajouter_produit->bindParam(":prix", $prix, PDO::PARAM_STR);
        $ajouter_produit->bindParam(":stock", $stock, PDO::PARAM_STR);

        $ajouter_produit->execute();
    }
///////////////////////////////////////////////////////////////////////////////
}//////////////// FIN D'AJOUT PRODUIT//////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
//AJOUTER CATEGORIES///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////

if(isset($_POST['nouvelle_categorie']) && isset($_POST['ajoutercategorie']))
{
    $nouvelle_categorie = $_POST["nouvelle_categorie"];
    
    $reference = null;

    $verif_dispo_categorie = $pdo->prepare("SELECT categorie FROM produit WHERE categorie= :nouvelle_categorie");
    $verif_dispo_categorie->bindParam(':nouvelle_categorie', $nouvelle_categorie, PDO::PARAM_STR);
    $verif_dispo_categorie->execute();
    
    $erreur = false;
    
    if($verif_dispo_categorie->rowCount() > 0)
    {
        $erreur = true;// un cas d'erreur
        $message .= "<div class='alert alert-danger'>Catégorie déjà existante.</div>";
    }
    
     if($erreur == false)
    {
         $insertion_bdd_ajouter_categorie = $pdo->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, prix, stock) VALUES (:reference, :nouvelle_categorie, :titre, :description, :couleur, :taille, :public, :prix, :stock)");
         $insertion_bdd_ajouter_categorie->bindParam(":nouvelle_categorie", $nouvelle_categorie, PDO::PARAM_STR);
         
         $insertion_bdd_ajouter_categorie->bindParam(":reference", $reference, PDO::PARAM_STR);
         $insertion_bdd_ajouter_categorie->bindParam(":titre", $titre, PDO::PARAM_STR);
         $insertion_bdd_ajouter_categorie->bindParam(":description", $description, PDO::PARAM_STR);
         $insertion_bdd_ajouter_categorie->bindParam(":couleur", $couleur, PDO::PARAM_STR);
         $insertion_bdd_ajouter_categorie->bindParam(":taille", $taille, PDO::PARAM_STR);
         $insertion_bdd_ajouter_categorie->bindParam(":public", $public, PDO::PARAM_STR);
         $insertion_bdd_ajouter_categorie->bindParam(":prix", $prix, PDO::PARAM_STR);
         $insertion_bdd_ajouter_categorie->bindParam(":stock", $stock, PDO::PARAM_STR);
         
         $insertion_bdd_ajouter_categorie->execute();
    }
}


////////////////////////////////////////////////////////////////////////////////
///FIN AJOUTER CATEGORIE ///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

//ON APPELLE ICI LE HEADER ET LE MENU//////////////////
include("../inc/header.inc.php");
include("../inc/nav.inc.php");
?>

<!-----------MAIN-------------------------------------------------------------------------------------------->

<!--CONTAINER BOUTON------------------->
<div class="container">

    <div class="starter-template">
        <h1><span class="glyphicon glyphicon-tag"></span>Gestion Boutique</h1> 
        <?= $message; // affiche le message de init.inc.php?>        
    </div>

    <!--<div class="row">
        <div class="col-sm-12">
            <a href="?action=ajouter_produit" class="btn btn-warning">Ajouter un produit</a>
            <a href="?action=voir" class="btn btn-primary">Voir les produits</a>
            <a href="?action=ajouter_categorie" class="btn btn-primary">Voir les produits</a>
        </div>
    </div>-->

    <div class="margin-btn-admin-gestion-boutique btn-group btn-group-justified" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <a href="?action=ajouter_produit" type="button" class="btn btn-default">Ajouter un produit</a>
        </div>
        <div class="btn-group" role="group">
            <a href="?action=ajouter_categorie" type="button" class="btn btn-default">Ajouter une catégorie</a>
        </div>
        <div class="btn-group" role="group">
            <a href="?action=voir_table_produit" type="button" class="btn btn-default">Table des produits</a>
        </div>
        <div class="btn-group" role="group">
            <a href="?action=voir_produit" type="button" class="btn btn-default">Voir les produits</a>
        </div>
        <div class="btn-group" role="group">
            <a href="?action=voir_categorie" type="button" class="btn btn-default">Voir les catégories</a>
        </div>
    </div>


</div>
<!--FIN de container BOUTON-------------->



<!--FORMULAIRE AJOUTER UN PRODUIT----------------------------->
<?php
if(isset($_GET['action']) && $_GET['action'] == 'ajouter_produit')
{ ?>
<div class="container">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <form method="post" action="" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="reference">Référence</label>
                    <input type="text" class="form-control" id="reference" name="reference" placeholder="reference..." value="<?=$reference?>">
                </div>

                <div class="form-group">
                    
                    <label for="categorie">Catégorie</label>
                    <select class="form-control" name="categorie" id="categorie">
                        <?php

                            $select_categorie = $pdo->query("SELECT DISTINCT categorie FROM produit");

                            while($select_categorie_en_cours = $select_categorie->fetch(PDO::FETCH_ASSOC))
                            {?>
                                 <option value="<?php echo $select_categorie_en_cours['categorie']; ?>" <?php if($categorie == $select_categorie_en_cours['categorie']){ echo "selected";} ?> > <?php echo $select_categorie_en_cours['categorie']; ?> </option>

                      <?php } ?>
                    </select>
                    
                    
                </div>

                <div class="form-group">
                    <label for="titre">Nom du produit</label>
                    <input type="text" class="form-control" id="titre" name="titre" placeholder="nom du produit..." value="<?=$titre?>">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3" value="<?=$description?>">  </textarea>
                </div>

                <div class="form-group">
                    <label for="couleur">Couleur</label>
                    <input type="text" class="form-control" id="couleur" name="couleur" placeholder="couleur..." value="<?=$couleur?>">
                </div>

                <div class="form-group">
                    <label for="taille">Taille</label>
                    <input type="text" class="form-control" id="taille" name="taille" placeholder="taille..." value="<?=$taille?>">
                </div>

                <div class="form-group">				
                    <label for="public">Public</label>
                    <select class="form-control" name="public" id="public">
                        <option value="m">Homme</option>
                        <option value="f" <?php if($public == "f"){ echo "selected";}?> > Femme</option>
                        <option value="mixte" <?php if($public == "mixte"){ echo "selected";}?> > Mixte</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo">
                </div>


                <div class="form-group">
                    <label for="prix">Prix</label>
                    <input type="text" class="form-control" id="prix" name="prix" placeholder="prix..." value="<?=$prix?>">
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="text" class="form-control" id="stock" name="stock" placeholder="stock..." value="<?=$stock?>">
                </div>

                <button type="submit" class="btn btn-success col-sm-12" name="ajouterproduit" ><span class="glyphicon glyphicon-ok" ></span> Ajouter</button>

            </form>

        </div><!--FIN DE COL-->

    </div><!--FIN DE DIV ROW-->
</div> <!--FIN DE CONTAINER-->
<?php }
?>

<!--FIN FORMULAIRE AJOUTER UN PRODUIT----------------------------->

<!--BOUTON AJOUTER CATEGORIE ---------------------------------->

<?php
if(isset($_GET['action']) && $_GET['action'] == 'ajouter_categorie')
{ ?>
<div class="container">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <div class="margin row">
                <form method="post" action="">

                    <div class="form-group">
                        <label for="nouvelle_categorie">Nouvelle catégorie</label>
                        <input type="text" class="form-control" id="nouvelle_categorie" name="nouvelle_categorie" placeholder="chaussette..." value="<?=$nouvelle_categorie?>">
                    </div>
                    
                    <button type="submit" class="btn btn-success col-sm-12" name="ajoutercategorie"><span class="glyphicon glyphicon-ok" ></span> Ajouter</button>
                    
                </form>

            </div>
        </div><!--FIN DE COL-->
    </div><!--FIN DE DIV ROW-->
</div><!--FIN DE CONTAINER-->

<?php }
?>

<!-- FIN BOUTON AJOUTER CATEGORIE ----------------------------->

<!--DEBUT TABLEAU BDD--------------------------------------------->
    <?php
    $produit = $pdo->query("SELECT * FROM produit WHERE reference IS NOT null ORDER BY reference");

    if(isset($_GET['action']) && $_GET['action'] == 'voir_table_produit')
    {?>

    <div class="container">

        <div class="margin row">
            <div class="col-sm-12">

                <table class="table table-hover">

                    <?php $nb_col = $produit->columnCount(); ?>

                    <!--Création des <th> avec le nom des colonnes-->
                    <tr>

                        <?php
     for($i = 0; $i < $nb_col; $i++)
     {
         $colonne_en_cours = $produit->getColumnMeta($i);
         echo '<th style="padding: 5px">' . $colonne_en_cours['name'] . '</th>';

     }
                        ?>
                    <th>Modifier </th>
                    <th>Supprimer </th>

                    </tr>

                    <?php
     // Creation des <td> avec les données qui correspondent au <th>
     while($ligne_en_cours = $produit->fetch(PDO::FETCH_ASSOC))
     {
         /*echo "<pre>" . var_dump($ligne_en_cours) . "</pre>";*/

         echo "<tr>";
         foreach($ligne_en_cours AS $valeur)
         {
             echo "<td style='padding: 5px;'>" . $valeur . "</td>";
             
         }
         echo "</tr>";

     }

                    ?>
                </table> 
            </div>
        </div> <!--Fin de row-->
    </div> <!--FIN DE CONTAINER-->

<?php } ?>

<!--FIN TABLEAU PRODUIT----------------------------------->

<!--BOUTON VOIR PRODUIT ---------------------------------->

<?php
if(isset($_GET['action']) && $_GET['action'] == 'voir_produit')
{ ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                
                <?php

                    $affichage_produits = $pdo->query("SELECT categorie, reference, titre, photo FROM produit WHERE reference IS NOT null ORDER BY reference");

                    while($produit_en_cours = $affichage_produits->fetch(PDO::FETCH_ASSOC))
                    {?>                        
                                  <div class="col-sm-4">
                                    <div class="thumbnail">
                                      <img class="img-responsive col-sm-12 max-height-img-produit" src='<?php echo '../' . $produit_en_cours['photo']; ?>' alt = "<?php echo $produit_en_cours['titre']; ?>">
                                        
                                      <div class="caption">
                                            <h3><?php echo " " . $produit_en_cours['titre']; ?></h3>
                                          
                                            <p><span class="glyphicon glyphicon-barcode"></span><?php echo " " . $produit_en_cours['reference']; ?> <br><span class="glyphicon glyphicon-tag"></span><?php echo " " . $produit_en_cours['categorie']; ?>
                                            </p>
                                          
                                            <p class="row ">
                                                <a href="#" class="margin-min btn btn-default col-sm-4" role="button">Fiche produit</a>
                                                <a href="#" class="btn btn-primary col-sm-offset-1 col-sm-3" role="button">Modifier</a>
                                                <a href="#" class="btn btn-danger col-sm-3" role="button">Supprimer</a>
                                            </p>
                                      </div>
                                    </div>
                                  </div>                        
                    <?php } ?>
                 </div>
        </div><!--FIN DE COL-->
    </div><!--FIN DE DIV ROW-->
</div><!--FIN DE CONTAINER-->

<?php }
?>

<!-- FIN BOUTON VOIR PRODUIT ----------------------------->

<!--BOUTON VOIR CATEGORIE ----------------------------->

<?php
if(isset($_GET['action']) && $_GET['action'] == 'voir_categorie')
{ ?>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="row">
                <table class="table table-hover">
                
                    <?php

                    $nb_produits_par_categorie = $pdo->query("SELECT categorie, COUNT(*) AS 'Nombre de produit' FROM produit WHERE reference IS NOT null GROUP BY categorie");
    
                    $nom_col = $nb_produits_par_categorie->columnCount(); ?>

                    <!--Création des <th> avec le nom des colonnes-->
                    <tr>

                        <?php
                             for($i = 0; $i < $nom_col; $i++)
                             {
                                 
                                 $colonne_en_cours = $nb_produits_par_categorie->getColumnMeta($i);
                                 echo '<th ';
                                 if($colonne_en_cours['name'] == 'Nombre de produit')
                                 {
                                     echo "class='text-right' ";
                                 }
                                 echo ' style="padding: 5px">' .  ucfirst($colonne_en_cours['name']) . '</th>';
                             }                       
                        ?>
                    </tr>
               
                    <!--Creation des <td> avec les données qui correspondent au <th>-->
                    
                    <?php
                    while($nombre_produit_en_cours = $nb_produits_par_categorie->fetch(PDO::FETCH_ASSOC))
                    {?>
                    <tr>
                        
                        <td class='categorie col-sm-9'> <?php echo $nombre_produit_en_cours['categorie']; ?> </td>
                        <td class='nb_categorie col-sm-3 text-right'> <?php echo $nombre_produit_en_cours['Nombre de produit']; ?> </td>
                        
                    </tr>
                    <?php } ?>
                    
                    
                </table> 
            </div>
        </div><!--FIN DE COL-->
    </div><!--FIN DE DIV ROW-->
</div><!--FIN DE CONTAINER-->

<?php }
?>

<!--FIN BOUTON VOIR CATEGORIE ----------------------------->

<!--FOOTER ------------------------------------------------>

<?php
include("../inc/footer.inc.php");   