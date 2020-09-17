# ShareFood
  
This application allows the sharing of food between people in order to limit the food waste.

# languages and framework
* **Langage** : PHP, HTML, CSS, JQuery
* **Framework** : Symfony 5.1
* **Bundles** : fzaninotto/faker,  Easyadmin 3, Symfony Mailer, Symfony Notifier.
* **Environnement**: Docker with PHP 7.4-FPM, Nginx, MySql 8 
* **Tests**: Behat, PHPUnit

## Installation
* 1 - clone project -> git clone https://github.com/cedflam/sharefood-docker.git 
* 2 - Install dépendencies -> composer install 
* 3 - Modify .env file,  setting up the database and MAILER_DSN line depending on your mail server 
* 4 - Create database -> php bin/console doctrine:database:create
* 5 - Add migration -> php bin/console make:migration 
* 6 - Load migration -> php bin/console doctrine:migrations:migrate
* 7 - Load fixtures -> php bin/console doctrine:fixtures:load 


Enjoy :-) 
