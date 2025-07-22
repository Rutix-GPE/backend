# pour utilisé docker les commandes à executer

Installer docker ( vaut mieux suivre les commandes sur le site officel)
 # sous windows
 https://docs.docker.com/desktop/setup/install/windows-install/
 # sous linux / debian 

 https://docs.docker.com/engine/install/ubuntu/
# init le .env
Il faut mettre la DB de 127.0.0.1:3306 => db:3306
 # lancemant du projet

 Une fois installer il faut se mettre à la racine du projet /backEnd et lancer la commande
 `docker compose up --build ` 
# quelques commandes pour mieux connaitre docker

 une fois lancer on peut voir les container lancer avec la commande 
 `docker ps` 
pour se connecter à container pour executer des commandes directement dessus 
`docker exec -it {nom_ou_id_container} bash` 
pour arreter les container 
`docker compose down -v`
en plus pour etre sur
`docker volume prune`
# exectuer le fichier
pour une premiere connexion je vous conseil de supprimer les versions de vos migrations pour que le programme crée des nouvelles versions
faire un `chmod +x command_docker.sh` pour donner les droits d'exec le fichier
 pour l'execution
 `./command_docker.sh`
 

 # lancer les test de coverage

 Pour lancer les test de converage il faut donné les droits à 

 `chmod +x test-coverage.sh`
 Pour l'executé 
 `./test-coverage.sh`
 # Pour une version text la voici 

`docker exec -it backend vendor/bin/phpunit --coverage-text`
 # projet lancer 

 une fois le projet lancer depuis un moment vous pouvez tjr exec le fichier sa permet de crée de nouvelles migrations entre autre comme ça si y a des changements dans les db ça permet d'etre à jour 
