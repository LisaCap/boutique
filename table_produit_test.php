
<!--DEBUT TABLEAU BDD------------------------->
    <?php
    $produit = $pdo->query("SELECT * FROM produit");

    if(isset($_GET['action']) && $_GET['action'] == 'voir')
    {?>

    <div class="container">

        <div class="row">
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

                    </tr>

                    <?php
     // Creation des <td> avec les données qui correspondent au <th>
     while($ligne_en_cours = $produit->fetch(PDO::FETCH_ASSOC))
     {
         /*echo "<pre>" . var_dump($ligne_en_cours) . "</pre>";*/

         echo "<tr>";
         foreach($ligne_en_cours AS $valeur)
         {
             //$a = $ligne_en_cours['id'];
             // recupérer la PK où l'indice se nomme "id" afin de la recuperer la bonne ligne lorse de la suppression

             if( $valeur != 'supprimer')
             {
                 echo "<td style='padding: 5px;'>" . $valeur . "</td>";               
             } else{

                 echo '<td style="padding: 5px"><a href="?ligne=suppression">' . $valeur . '</d></th>';
             }
         }
         echo "</tr>";

     }

                    ?>
                </table> 
            </div>
        </div> <!--Fin de row-->
    </div> <!--FIN DE CONTAINER-->

<?php } ?>