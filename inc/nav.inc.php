 <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">LiliBoutique</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav">
                   
                    <li>
                        <a class="page-scroll" href="<?php echo URL; ?>index.php">Boutique</a>
                    </li>
                    <?php
                    //Pour le menu, si l'utilisateur est connecté, j'affiche ce menu, sinon ... inscription.php & connexion.php
                    if(utilisateur_est_connecte())
                        { ?> 
                        <li><a class="page-scroll" href="<?php echo URL; ?>profil.php">Profil</a> </li>
                        <li><a class="page-scroll" href="<?php echo URL; ?>connexion.php?action=deconnexion">Déconnexion</a></li>
                        <?php }
                    else{ ?>
                    <li><a class="page-scroll" href="<?php echo URL; ?>inscription.php">Inscription</a> </li>
                    <li><a class="page-scroll" href="<?php echo URL; ?>connexion.php">Connexion</a></li>
                    <?php  
                    }
                    // si l'utilisateur est admin, je rajoute ces liens dans le menu
                    if(utilisateur_est_admin())
                    {?>
                    <li><a class="page-scroll" href="<?php echo URL; ?>admin/gestion_boutique.php">Gestion boutique</a></li>
                    <li><a class="page-scroll" href="#">Gestion membre</a></li>
                    <?php 
                    }
                    ?>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>