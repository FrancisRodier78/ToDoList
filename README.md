ToDoList

Amélioration et documentation d'un projet existant ToDo & Co.

Installation
    Clonez ou téléchargez le repository GitHub dans le dossier voulu :
    git clone https://github.com/FrancisRodier78/ToDoList.git

    Configurez vos variables d'environnement tel que la connexion à la base de données dans le fichier .env.local qui devra être crée à la racine du projet en réalisant une copie du fichier .env ainsi que la connexion à la base de données de test dans le fichier env.test.

    Téléchargez et installez les dépendances du projet avec Composer :
    composer install

    Créez la base de données si elle n'existe pas déjà, taper la commande ci-dessous en vous plaçant dans le répertoire du projet :
    php bin/console doctrine:database:create

    Créez les différentes tables de la base de données en appliquant les migrations :
    php bin/console doctrine:migrations:migrate

    Installez les fixtures pour avoir une démo de données fictives en développement :
    php app/console doctrine:fixtures:load --env=dev --group=dev

    Exécuter la commande anonyme afin de transférer les tâches anonymes à l'admin
    php bin/console app:anonyme

    Félicitations le projet est installé correctement, vous pouvez désormais commencer à l'utiliser à votre guise !
