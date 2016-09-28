# StadLine Technical Test

### Tache

Le sujet de base est simple : Il faut créer une page sécurisée qui permet à un utilisateur de se loguer et de faire un commentaire sur un dépôt publique d'un utilisateur GitHub.
La fonctionnalité de commentaire n'existe pas sur Github, vous devrez donc stocker et afficher ces commentaires dans votre espace sécurisé.

### Règles

* Le temps est libre mais il est tout de même conseillé de passer moins de 4h sur le sujet (temps de setup d'environnement compris)
* Il est conseillé de finir les points requis avant de s'attaquer au bonus. 
* Il est aussi conseillé de faire un maximum de commit pour bien détailler les étapes de votre raisonnement au cours du développement.
* N'hésitez pas à nous faire des retours et nous expliquer les éventuelles problématiques bloquantes que vous auriez rencontrées durant le développement vous empéchant de finir.

### Setup

* La charte graphique n'est pas imposée et sera jugée en bonus. L'emploi d'un framework CSS type Twitter Bootstrap est fortement conseillé. 
* Vous aurez besoin d'un environnement php5.5, Symfony2 et un serveur pour l'application. 

### Les pré-requis

* Vous êtes libre d'utiliser un bundle d'authentification externe ou votre propre bundle. OK
* Le formulaire de connexion doit avoir une validation coté serveur. OK
* Toutes les pages doivent être sécurisées et pointer sur la page de login si l'utilisateur n'est pas connecté. OK 
* Le choix du client HTTP est laissé à discrétion pour appeller l'API de GitHub. OK
* Une fois connecté, il est nécessaire d'implémenter un champ de recherche qui permette de chercher les utilisateurs GitHub. La documentation est disponible ici : https://developer.github.com/v3/search/#search-users . OK 
* Vous devez appeller l'API suivante avec q=searchFieldContent :
```
https://api.github.com/search/users
```
* Une fois le champ de recherche validé et l'utilisateur sélectionné, on arrive sur l'url /{username}/comment, on affiche un formulaire qui sera composé des champs suivants : un champ texte pour le nom d'un dépôt ({user}/{repos}, e.g : stadline/sf2-technical-test), un textArea pour le commentaire, un bouton valider permettant d'ajouter un commentaire. OK
* On affichera en dessous la liste des commentaires déjà saisis pour l'utilisateur. OK
* Lors de la validation du formulaire, on vérifiera que le repository sélectionné est bien un dépôt appartenant à l'utilisateur précédement recherché. OK
* On attend aussi de vous que le code soit testable et testé. OK en partie

### Bonus

* On changera le choix du dépôt par un multiselect afin de lister directement dans le formulaire les dépôts de l'utilisateur. NOK
* Utilisation d'un frameworkJS pour afficher les résultats NOK
* Toutes les fonctionnalités que vous aurez le temps d'ajouter seront aussi bonnes à prendre. Un bonus autour de votre créativité pourra être considéré. NOK

### Délivrabilité

* Forkez le projet sur GitHub et codez directement dans le projet forké. 
* Commitez aussi souvent que possible et commentez vos commits pour détailler votre chemin de pensée. 
* Mettez à jour le README pour ajouter le temps passé et tout ce que vous jugerez nécessaire de nous faire savoir. 
* Envoyez le lien avec le projet à recrutement@stadline.com. 

**Bonne chance !**


### Resultats

J'ai passé a peu près 4H pour l'instant.
J'ai été un peu dérangé par d'autres choses sur le début.

Ce qui a été réalisé:

L'install de l'environnement + install de FOSUserBundle + verif si l'utilisateur est connecté sinon redirection
* Durée : < 50 min
* Problème : Aucun si ce n'est de la distraction

Création de la page de recherche + Création de la page commentaire + Redirection de l'une vers l'autre
* Durée : 1H environ 
* Problème : J'ai d'abord tenté de le faire en "vanilla" js, mais je ne sais pas pourquoi ça n'allait pas, j'ai cherché un peu mais j'ai cédé, du coup, jQuery et ça a marché toute suite...

Interface avec l'API Github
* Durée : 1H environ 
* Problème : J'ai eu des soucis avec mon install lamp, les requêtes CURL que j'effectuais ne renvoyaient rien (pas d'erreur, pas de json), j'ai du réinstallé php5-curl et restart apache pour finalement avoir des resultats et voir qu'il me manquait des choses comme le user-agent etc...

Création de l'entité Comment + sauvegarde du commentaire + affichage des commentaires + verification de l'appartenance du repository avant commentaire
* Durée : 1H environ 
* Problème : Pas vraiment de problème si ce n'est que j'ai mal analysé la demande et que je suis beaucoup revenu sur mon entité Comment (2~3 fois)

Etat des lieux:

Pas de tests et pas de front qui fait plaisir aux yeux. Autant pour les tests j'avoue être inexpérimenté et je pense que si j'en fais je vais prendre du temps, autant le front j'ai mal géré, je n'ai même pas eu le temps de mettre bootstrap pour faire un truc potable. Je suis déçu de ne pas avoir réussi à faire quelque-chose de bien, je vais peut-être passer encore un peu de temps dessus pour faire un front et des tests éventuellements. 

---
Suite:

Integration bootstrap pour faire une interface a minima potable
* Durée : < 1H
* Problème : Aucun

Création de tests avec phpunit + Separation controller et service github + Separation tests controller et service
* Durée : 1H environ
* Problème : Je n'arrive pas a tester la redirection vers la homepage lorsque l'on tente d'acceder a un compte github inexistant, j'ai bien un client connecté pour les tests mais la redirection codée dans le controller ne se fait pas. J'ai passé un peu plus de temps sur les tests mais ça vallait le coup

Temps passé sur le test technique : ~4H + ~2H = **entre 6H ~ 6H30**
