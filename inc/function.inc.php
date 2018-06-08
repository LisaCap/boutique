<?php

//pour savoir si l'utilisateur est connecté
function utilisateur_est_connecte()
{
    if(!empty($_SESSION['membre']))
    {
        //si l'indice membre dans session n'est pas vide alors forcément l'utilisateur est passé par connexion et s'est connecté
        return true;
    }
    
    return false;
}

//pour savoir si l'utilisateur est connecté et a le statut administrateur
function utilisateur_est_admin()
{
    if(utilisateur_est_connecte() && $_SESSION['membre']['statut'] == 1 )
    {
        return true;
    }
    return false;
}