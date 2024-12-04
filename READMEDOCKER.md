# pour utilisé docker les commandes à executer

Installer docker ( vaut mieux suivre les commandes sur le sites officel)
 # sous windows
 https://docs.docker.com/desktop/setup/install/windows-install/
 # sous linux / debian 

 https://docs.docker.com/engine/install/ubuntu/

 # lancemant du projet

 Une fois installer il faut se mettre à la racine du projet /backEnd et lancer la commande
 `docker compose up --build ` 

 une fois lancer on peut voir les container lancer avec la commande 
 `docker ps` 
pour se connecter à container pour executer des commandes directement dessus 
`docker exec -it {nom_ou_id_container} bash` 
pour une premiere connexion je vous conseil de supprimer les versions de vos migrations pour que le programme crée des nouvelles versions
faire un `chmod +x command_docker.sh`