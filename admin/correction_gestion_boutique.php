<?php
include("../inc/init.inc.php");
include_once("../inc/init.inc.php");

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
	if($verif_reference->rowCount() > 0)
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
		$enregistrement = $pdo->prepare("INSERT INTO produit (reference, categorie, titre, description, couleur, taille, public, photo, prix, stock) VALUES (:reference, :categorie, :titre, :description, :couleur, :taille, :public, '$photo_bdd', :prix, :stock)");
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
		
		<!-- FORMULAIRE AJOUT PRODUIT -->
		<?php 
		if(isset($_GET['action']) && $_GET['action'] == 'ajouter')
		{ 
		?>	
		
		<div class="col-sm-4 col-sm-offset-4">
			<form method="post" action="" enctype="multipart/form-data">
			<!-- enctype="multipart/form-data" est obligatoire s'il y a des pièces jointes dans le formulaire -->
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
		?>	
				
		<?php
		} 
		?>
		<!-- / FIN TABLEAU AFFICHAGE PRODUIT -->
	  </div>

    </div><!-- /.container -->


<?php
include("../inc/footer.inc.php");  




 