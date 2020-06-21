# GestEx - Gestion des Expérimentations

L'application web !GestEx est installé à l'adresse https://www.legi.grenoble-inp.fr/servgestex/

## Inventaire du matériel instrumentation du LEGI

Ce sont des scripts PHP liés à un gestionnaire de bases de données MySQL.
Cet inventaire affiche un listing du matériel instrumentation au LEGI.

Le tri se fait par catégorie d'appareil.
On peut également afficher la liste globale des appareils du LEGI. 
Pour chaque appareil est indiqué le modèle, le fournisseur, la gamme d'utilisation,
l'équipe propriétaire de l'appareil et une notice au format PDF. 
Si on clique sur le nom de l'appareil,
on fait apparaître des renseignements complémentaires comme :
 * la date d'achat,
 * les accessoires,
 * les réparations et/ou les étalonnages,
 * le technicien responsable,
 * le numéro d'inventaire si cet appareil a une somme supérieure à 800 euros.

Il apparaît également le numéro de l'instrument qui est incrémenté dans la liste
et que l'on retrouve sur l'appareil sous la forme d'une étiquette du type : LEGI équipe N° d'instrument.

## Accès aux fiches via WebDAV

Les fiches associées sont maintenant accessible en WebDAV.

 https://www.legi.grenoble-inp.fr/servgestex/dav/

Pour cela, il faut utiliser l'application bitkinex.
C'est en gros pareil que du FTP mais basée sur le protocole HTTP...

Les membres ayant un accès en lecture et écriture (modification) doivent être dans le groupe LDAP : soft-gestex.
Demander au service informatique pour rajouter ou supprimer quelqu'un.

## Sources GIT

### Git global setup

```bash
git config --global user.name "Gabriel Moreau"
git config --global user.email "gabriel.moreau@univ-grenoble-alpes.fr"
```

### Create a new repository

```bash
git clone git@gricad-gitlab.univ-grenoble-alpes.fr:legi/soft/gestex.git
cd gestex
touch README.md
git add README.md
git commit -m "add README"
git push -u origin master
```

## Ancien serveur SVN

### Accès aux sources

On récupère la branche principale sur le repository
{{{
svn checkout https://servcode.legi.grenoble-inp.fr/svn/soft-gestex/trunk soft-gestex
}}}

'''Attention''' : le 2012/07/19, le projet à changer de nom.
Si vous aviez un accès à l'ancien repository {{{instru-materiel}}},
ceui-ci est maintenant {{{soft-gestex}}}
{{{
svn switch --relocate https://servcode.legi.grenoble-inp.fr/svn/instru-materiel https://servcode.legi.grenoble-inp.fr/svn/soft-gestex
}}}

### Mise à jour de l'application sur le serveur web

On ne développe plus l'application directement sur le serveur,
ni on ne fait de copie externe.
L'idée est de passer par une mise à jour (update) du repository.
C'est un peu lourd mais c'est un mal nécessaire afin d'avoir enfin une vue un peu historique et globale dans le temps.

On se connecte au serveur {{{legilnx06}}},
puis les sources sont synchronisées et pousser dans le bon dossier.
{{{
ssh krialforzh@legilnx06
cd soft-gestex
svn update
sudo rsync -av --exclude .svn ./ /var/www/web-legi/pool/public_html/PoolProject/
sudo chown -R :www-data /var/www/web-legi/pool/public_html/PoolProject/
sudo chown -R www-data:www-data /var/www/web-legi/pool/public_html/PoolProject/data
}}}

Une procédure plus performante sera mise en place dès que l'application sera de nouveau pleinement opérationnelle.
Chaque chose en son temps !
