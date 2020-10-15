# Explication fournisseur
## `supplier-edit.php`   (user lvl >=2) (formulaire du fournisseur)
1. un formulaire avec ajouter ou modifier suivant si on met un id de fournisseur ou non
2. Si modification (id fournisseur existant) --> supplier-process.php
   Si ajout (id fournisseur null ou non existant) --> supplier-process.php

## `supplier-process.php` user lvl >=2 ou 3 ??) (commande sql pour modifier un fournisseur)
1. vérification (il y a bien un nom et une adresse ) + récupération des champs du formulaire
2. connexion à la base de donnée + récupération du bon fournisseur (commande sql avec l'id du fournisseur )
--> si user lvl >=3 on affiche la commande sql et on l'éxécute
 ( pb ? au début du document on demande un user >=2 et pour éxécuter >=3 ???)

## `supplier-process.php` (pas de lvl demandé) (pour ajouter un fournisseur à la bdd)
1. vérification (il y a bien un nom et une adresse ) + récupération des champs du formulaire
2. connexion à la bdd + éxécution de la commande sql qui ajoute un fournisseur à la bdd
 (pb ? pas de gestion des lvl du user ?)

## `supplier-del.php`   (pas de lvl demandé) (pour supprimer un user de la bdd)
1. un bouton pour demander si on est sûr de supprimer un fournisseur n°x
2. si on répond oui, éxécute un ecommande sql pour supprimer le fournisseur
