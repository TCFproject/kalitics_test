# kalitics_test
#Prérequis
- Avoir installer PHP 7 minimum (vérifier avec la cmd php -version)
- Avoir composer d'installer (vérifier avec la cmd composer --version)
#Création du projet
- Dans le dossier contenant les projets créer ouvrir une console de cmd et taper "symfony new --full kalitics_test" pour créer le projet
#Création des différents composants
    - Lancement du projet avec la commande "symfony server:start"
\
Etapes 1 
- Création des entités avec la commande "symfony console make:entity"
- Entrée le nom de l'entité puis des variables
- Faire une migration avec les commandes "symfony console make:migration" puis "symfony console doctrine:migrations:migrate"\

Etape 2
- Création des CRUD de chaque entité avec la commande "symfony console make:crud"
