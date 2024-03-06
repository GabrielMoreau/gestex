# GestEx - Gestion des Expérimentations

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
git difftool
git commit README.md # -m "add README"
git push -u origin master
```
Il est préférable de toujours mettre le nom des fichiers que l'on commit.
Le message est à mettre de préférence dans l'éditeur
(`vi` par exemple),
ce qui permet de vérifier le commit et de l'annuler.

## Ancien serveur SVN

### Accès aux sources

On récupère la branche principale sur le repository
```bash
svn checkout https://servcode.legi.grenoble-inp.fr/svn/soft-gestex/trunk soft-gestex
```

'''Attention''' : le 2012/07/19, le projet à changer de nom.
Si vous aviez un accès à l'ancien repository `instru-materiel`,
ceui-ci est maintenant `soft-gestex`
```bash
svn switch --relocate https://servcode.legi.grenoble-inp.fr/svn/instru-materiel https://servcode.legi.grenoble-inp.fr/svn/soft-gestex
```

### Mise à jour de l'application sur le serveur web

On ne développe plus l'application directement sur le serveur,
ni on ne fait de copie externe.
L'idée est de passer par une mise à jour (update) du repository.
C'est un peu lourd mais c'est un mal nécessaire afin d'avoir enfin une vue un peu historique et globale dans le temps.

On se connecte au serveur `legilnx06`,
puis les sources sont synchronisées et pousser dans le bon dossier.

```bash
ssh krialforzh@legilnx06
cd soft-gestex
svn update
sudo rsync -av --exclude .svn ./ /var/www/web-legi/pool/public_html/PoolProject/
sudo chown -R :www-data /var/www/web-legi/pool/public_html/PoolProject/
sudo chown -R www-data:www-data /var/www/web-legi/pool/public_html/PoolProject/data
```

Une procédure plus performante sera mise en place dès que l'application sera de nouveau pleinement opérationnelle.
Chaque chose en son temps !


### Base de donnée

#### Installation

```bash
mysql -u root -p
CREATE DATABASE gestex;

CREATE USER 'gestex-server'@'localhost' IDENTIFIED BY 'ZZZZZZZZZ';
GRANT ALL PRIVILEGES ON gestex . * TO 'gestex-server'@'localhost';
FLUSH PRIVILEGES;
QUIT;

mysql -u root -p gestex < db-schema.sql
```

#### Mot de passe

Les commandes suivantes sont une aide pour générer le premier mot de passe,
ainsi que pour mettre manuellement un nouveau mot de passe à une personne au cas ou la système serait bloqué !

```bash
echo -n XXXXXXXXX | md5sum

mysql -u root -p gestex
INSERT INTO `users`(`id`, `loggin`, `password`, `level`, `nom`, `prenom`, `tel`, `email`, `equipe`, `valid`) VALUES (1,'sys-admin','YYYYYYYYYYYYYYYYY',5,'Sys','Admin',0,0,0,1);
QUIT;

mysql -u root -p gestex
UPDATE users SET password='YYYYYYYYYYYYYYYYY'  WHERE id='1';
QUIT;
```

#### Sauvegarde de la base de donnée

On sauve la base de donnée dans un fichier portant la date du jour.
```bash
mysqldump -u root -p gestex > db-gestex-dump-$(date '+%Y%m%d').sql
```

Pour récupérer la base de donnée ainsi sauvée,
il suffit de faire l'inverse.
Attention cependant que cette opération va annuler toutes les opérations qui auront été faites entre temps...
```bash
mysql -u root -p gestex < db-gestex-dump-YYYYMMDD.sql
```

Pour ne récupérer que le schéma de la base de donnée
```bash
mysqldump --no-data --lock-tables=false  -u pool -p pool  | grep -v '^/\*!' \
  | sed -e 's/ int(/ INT(/; s/ bigint(/ BIGINT(/; s/ char(/ CHAR(/; s/ varchar(/ VARCHAR(/;
            s/ boolean / BOOLEAN /; s/ date / DATE /; s/ text / TEXT /;
            s/ timestamp / TIMESTAMP /; s/ enum(/ ENUM(/;
            s/ AUTO_INCREMENT=[[:digit:]]* / AUTO_INCREMENT=1 /;
            s/ current_timestamp()/ CURRENT_TIMESTAMP/g;' \
  > db-schema-dump-$(date '+%Y%m%d').sql
```

Il est possible de comparer alors ce schéma avec le schéma officiel
(intéressant s'il y a des soucis lors des mises à jour de schéma)...
```bash
meld db-schema.sql db-schema-dump-$(date '+%Y%m%d').sql
```

#### Mise à jour de la base de donnée

Pour connaître la version du schéma nécessaire dans le code
et la version du schéma actuellement utilisé par la base de donnée.
```bash
grep 'define.*GESTEX_DB_VERSION' module/*.php

mysql -u root -p gestex
SELECT * FROM version WHERE soft = 'database';
```

Par exemple, pour passer de la version 3 à la version 4 du schéma
```bash
mysql -u root -p gestex < db-upgrade-3-4.sql
```


### Icônes

Les icônes proviennent du projet [bootstrap](https://getbootstrap.com/),
plus particulièrement de la partie [icons](https://icons.getbootstrap.com/#icons).
Ces images sont libres de droits.
Elles sont au format SVG.
Actuellement, nous avons recopié dans le projet GestEx uniquement les quelques icônes dont nous avons besoin.


### Vocabulaire

Anglais	Français
datasheet	notice / fiche technique
user	utilisateur
team	équipe
equipment	équipement
supplier	fournisseur
category	catégorie
loan	prêt
device	dispositif
platform	plateau expérimental


