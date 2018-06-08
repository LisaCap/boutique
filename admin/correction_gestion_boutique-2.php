<?php
include("../inc/init.inc.php");

if(!utilisateur_est_admin()) // s'il l'utilisateur n'est pas admin
{
	header("location:../connexion.php");
	exit(); // par sécurité, si on passe dans cette condition, cette ligne bloque l'exécution du code suivant.
}

//SUPPRESSION PRODUIT///////////////////////////////////////

if(isset($_GET["action"]) && $_GET["action"] == 'suppression')
{
    $article_a_supprimer = $_GET['id_produit'];
    
    $suppression = $pdo->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $suppression->bindParam(":id_produit", $article_a_supprimer, PDO::PARAM_STR);
    $suppression->execute();
    
    $_GET['action'] = 'voir';// Pour que le tableau continue d'etre afficher même quand on a supprimer
    
}

//FIN SUPPRESSION PRODUIT//////////////////////////////////

// Le ou les fichiers joints via un formulaire seront dans la superglobale $_FILES car ceux ne sont pas des saisies classiques (donc protocole différent)
// $_FILES est un tableau array multidimensionnel

$reference = "";
$categorie = "";
$titre = "";
$description = "";
$couleur = "";
$taille = "";
$sexe = "";
$prix = "";
$stock = "";

$id_produit = "";
// on utilise cette variable uniquement pour la modification de produit.

///MODIFICATION PRODUIT ///////////////////////////

if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    $produit_a_modifier = $_GET['id_produit'];
    
    $recup_info = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $recup_info->bindParam(":id_produit", $produit_a_modifier, PDO::PARAM_STR);
    $recup_info->execute();
    //ici on a recuperer les infos qui correspondent a l'id produit recuperer (le titre, taile etc.)
    
    $produit_actuel = $recup_info->fetch(PDO::FETCH_ASSOC); //transformation en tableau array
    
    $id_produit = $produit_actuel['id_produit']; // on place dans des variables ce que l'on recupere du tableau
    $reference = $produit_actuel['reference'];
    $categorie = $produit_actuel['categorie'];
    $titre = $produit_actuel['titre'];
    $description = $produit_actuel['description'];
    $couleur = $produit_actuel['couleur'];
    $taille = $produit_actuel['taille'];
    $sexe = $produit_actuel['public'];
    $prix = $produit_actuel['prix'];
    $stock = $produit_actuel['stock'];
}

/////FIN MODIFICATION PRODUIT//////////////////////

if( isset($_POST['reference']) && 
	isset($_POST['categorie']) && 
	isset($_POST['titre']) && 
	isset($_POST['description']) && 
	isset($_POST['couleur']) && 
	isset($_POST['taille']) && 
	isset($_POST['sexe']) && 
	isset($_POST['prix']) && 
	isset($_POST['stock']) )
{
    $id_produit = $_POST['id_produit']; // pour la modif
	$reference = $_POST['reference'];
	$categorie = $_POST['categorie'];
	$titre = $_POST['titre'];
	$description = $_POST['description'];
	$couleur = $_POST['couleur'];
	$taille = $_POST['taille'];
	$sexe = $_POST['sexe'];
	$prix = $_POST['prix'];
	$stock = $_POST['stock'];	
	
	// vérification de la disponibilité de la référence
	$erreur = false;
	
	$verif_reference = $pdo->prepare("SELECT * FROM produit WHERE reference = :reference");
	$verif_reference->bindParam(":reference", $reference, PDO::PARAM_STR);
	$verif_reference->execute();
	
	// s'il y a une ligne dans $verif_reference alors la reference existe déjà !
	if($verif_reference->rowCount() > 0 && empty($id_produit)) // empty($id_produit) car on ne veut pas regarder si la reference existe pour une modification
	{
		$erreur = true;
		$message .= '<div class="alert alert-danger" style="">Attention,<br>La référence existe déjà, veuillez en choisir une autre</div>';
	}
	
	
	// vérification s'il n'y a pas eu d'erreur sur les contrôle au dessus.
	if(!$erreur)
	{
		$photo_bdd = '';
		
		// récupération de la photo
		if(!empty($_FILES['photo']['name']))
		{
			// mise en place du src
			$photo_bdd = 'img/' . $reference . $_FILES['photo']['name'];
			
			$chemin = RACINE_SERVEUR . $photo_bdd;
			// copy() est une fonction prédéfinie permettant de copier un fichier depuis un emplacement (1er argument) vers un emplacement cible (2eme argument)
			copy($_FILES['photo']['tmp_name'], $chemin);			
		}
		
		// enregistrement en BDD du produit
        
        if(empty($id_produit)) // si id_produit est vide, c'est un ajout, sinon c'est un update
        {
            
		$enregistrement = $pdo->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, '$photo_bdd', :prix, :stock)");
            
        } else{
            $enregistrement = $pdo->prepare("UPDATE produit SET reference = :reference, categorie = :categorie, titre = :titre, description = :description, couleur = :couleur, taille = :taille, public = :public, photo = '$photo_bdd', prix = :prix, stock = :stock WHERE id_produit = :id_produit");
            //le WHERE pour ne pas modifier tous les produits de la table...
            
            $enregistrement->bindParam(":id_produit", $id_produit, PDO::PARAM_STR);// pour que le nombre de UPDATE CORRESPONDE
        }
        
        
		$enregistrement->bindParam(":reference", $reference, PDO::PARAM_STR);
		$enregistrement->bindParam(":categorie", $categorie, PDO::PARAM_STR);
		$enregistrement->bindParam(":titre", $titre, PDO::PARAM_STR);
		$enregistrement->bindParam(":description", $description, PDO::PARAM_STR);
		$enregistrement->bindParam(":couleur", $couleur, PDO::PARAM_STR);
		$enregistrement->bindParam(":taille", $taille, PDO::PARAM_STR);
		$enregistrement->bindParam(":public", $sexe, PDO::PARAM_STR);
		$enregistrement->bindParam(":prix", $prix, PDO::PARAM_STR);
		$enregistrement->bindParam(":stock", $stock, PDO::PARAM_STR);
		$enregistrement->execute();
	}	
	
}
include("../inc/header.inc.php");
include("../inc/nav.inc.php");
// echo '<pre>'; print_r($_POST); echo '</pre>';
// echo '<pre>'; print_r($_FILES); echo '</pre>';
// echo '<pre>'; print_r($_SERVER); echo '</pre>';
?>
    <div class="container">

      <div class="starter-template">
        <h1><span class="glyphicon glyphicon-th-list"></span> Gestion boutique</h1>
		<?php echo $message; // affichage des messages utilisateur  ?>
      </div>
	  
	  <div class="row">
		<div class="col-sm-12 text-center">
			<a href="?action=ajouter" class="btn btn-warning">Ajouter un produit</a>
			<a href="?action=voir" class="btn btn-primary">voir les produits</a>
			<hr>
		</div>
		
		<!-- FORMULAIRE AJOUT  OU MODIFICATIOON DE PRODUIT -->
		<?php 
		if(isset($_GET['action']) && $_GET['action'] == 'ajouter' || $_GET['action'] == 'modification')
		{ 
		?>	
		
		<div class="col-sm-4 col-sm-offset-4">
			<form method="post" action="" enctype="multipart/form-data">
			<!-- enctype="multipart/form-data" est obligatoire s'il y a des pièces jointes dans le formulaire -->
			
                <!--On rajoute un champ caché (type hidden) pour voir l'Id_produit lors d'une modification-->
	            <input type="hidden" name="id_produit" value="<?php echo $id_produit; ?>" >
	            <!------------------------------------------------>		
			
				<div class="form-group">				
					<label for="reference">Référence</label>
					<input type="text" class="form-control" id="reference" name="reference" placeholder="Référence..." value="<?php echo $reference; ?>" >
				</div>
				<div class="form-group">				
					<label for="titre">Titre</label>
					<input type="text" class="form-control" id="titre" name="titre" placeholder="Titre..." value="<?php echo $titre; ?>" >
				</div>
				<div class="form-group">				
					<label for="categorie">Catégorie</label>
					<input type="text" class="form-control" id="categorie" name="categorie" placeholder="Catégorie..." value="<?php echo $categorie; ?>" >
				</div>
				<div class="form-group">
					<label for="description">Description</label>
					<textarea id="description" name="description" class="form-control" rows="3"><?php echo $description; ?></textarea>
				</div>
				<div class="form-group">				
					<label for="couleur">Couleur</label>
					<input type="text" class="form-control" id="couleur" name="couleur" placeholder="Couleur..." value="<?php echo $couleur; ?>" >
				</div>
				<div class="form-group">				
					<label for="taille">Taille</label>
					<input type="text" class="form-control" id="taille" name="taille" placeholder="Taille..." value="<?php echo $taille; ?>" >
				</div>
				<div class="form-group">				
					<label for="sexe">Sexe</label>
					<select class="form-control" name="sexe" id="sexe">
						<option value="m" >Homme</option>
						<option <?php if($sexe == "f") { echo "selected"; } ?> value="f" >Femme</option>
					</select>
				</div>
				<div class="form-group">				
					<label for="photo">Photo</label>
					<input type="file" class="form-control" id="photo" name="photo" >
				</div>
				<div class="form-group">				
					<label for="prix">Prix</label>
					<input type="text" class="form-control" id="prix" name="prix" placeholder="Prix..." value="<?php echo $prix; ?>" >
				</div>
				<div class="form-group">				
					<label for="stock">Stock</label>
					<input type="text" class="form-control" id="stock" name="stock" placeholder="Stock..." value="<?php echo $stock; ?>" >
				</div>
				<hr>
				<button type="submit" class="btn btn-info col-sm-12"><span class="glyphicon glyphicon-ok" name="ajouter"></span> Ajouter</button>
				
			</form>
			
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>
		
		<?php
		} 
		?>
		<!-- / FIN FORMULAIRE AJOUT PRODUIT -->
		
		<!-- TABLEAU AFFICHAGE PRODUIT -->
		<?php 
		if(isset($_GET['action']) && $_GET['action'] == 'voir')
		{ 
			$les_produits = $pdo->query("SELECT * FROM produit ORDER BY categorie");
			
			echo '<div class="col-sm-12">';			
			echo '<table class="table table-bordered">';
			
			echo '<tr>';
			echo '<th>id_produit</th>';
			echo '<th>Référence</th>';
			echo '<th>Catégorie</th>';
			echo '<th>Titre</th>';
			echo '<th>Description</th>';
			echo '<th>Couleur</th>';
			echo '<th>Taille</th>';
			echo '<th>Public</th>';
			echo '<th>Photo</th>';
			echo '<th>Prix</th>';
			echo '<th>Stock</th>';
			echo '<th>Modif</th>';
			echo '<th>Suppr</th>';
			echo '</tr>';
			
			while($produit = $les_produits->fetch(PDO::FETCH_ASSOC))
			{
				echo '<tr>';
				
				echo '<td>' . $produit['id_produit'] . '</td>';
				echo '<td>' . $produit['reference'] . '</td>';
				echo '<td>' . $produit['categorie'] . '</td>';
				echo '<td>' . $produit['titre'] . '</td>';
				echo '<td>' . substr($produit['description'], 0, 14) . '...</td>';
				echo '<td>' . $produit['couleur'] . '</td>';
				echo '<td>' . $produit['taille'] . '</td>';
				echo '<td>' . $produit['public'] . '</td>';
				echo '<td><img src="' . URL . $produit['photo'] . '" alt="image produit" class="img-responsive" width="100"></td>';
				echo '<td>' . $produit['prix'] . '</td>';
				echo '<td>' . $produit['stock'] . '</td>';
				
				echo '<td><a href="?action=modification&id_produit=' . $produit['id_produit'] . '" class="btn btn-warning"><span class="glyphicon glyphicon-refresh"></span></a></td>';
				
				echo '<td><a href="?action=suppression&id_produit=' . $produit['id_produit'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes vous sur ?\'));" ><span class="glyphicon glyphicon-trash"></span></a></td>';				
				
				echo '</tr>';
			}
			
			echo '</table>';			
			echo '</div>';
	
		} 
		?>
		<!-- / FIN TABLEAU AFFICHAGE PRODUIT -->
	  </div>

    </div><!-- /.container -->


<?php
include("../inc/footer.inc.php");  




 