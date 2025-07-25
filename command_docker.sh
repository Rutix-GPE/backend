#!/bin/bash

CONTAINER_NAME_OR_ID="backend"
ERROR_LOG="docker_command_errors.log"

# Réinitialise le fichier log
> "$ERROR_LOG"

# Vérifie si le conteneur est en cours d'exécution
if ! docker ps --format '{{.Names}}' | grep -q "^${CONTAINER_NAME_OR_ID}$"; then
  echo "Erreur : Le conteneur '${CONTAINER_NAME_OR_ID}' n'est pas en cours d'exécution." | tee -a "$ERROR_LOG"
  exit 1
fi

# Fonction pour exécuter une commande dans le conteneur
execute_command() {
  local cmd="$1"
  echo "Exécution de la commande : $cmd"
  
  if ! docker exec -i ${CONTAINER_NAME_OR_ID} /bin/bash -c "$cmd"; then
    echo "Erreur lors de l'exécution de la commande : $cmd" | tee -a "$ERROR_LOG"
  fi
}

# Migrations pour la base principale (rutix_db)
COMMANDS_MAIN=(
  "php bin/console make:migration"
  "php bin/console doctrine:migrations:migrate --no-interaction"
  "php bin/console lexik:jwt:generate-keypair"
  ""
)

# Migrations pour la base de test (rutix_db_test) via DATABASE_URL temporaire
COMMANDS_TEST=(
  "export DATABASE_URL='mysql://root:root@db:3306/rutix_db_test' && php bin/console doctrine:migrations:migrate --no-interaction"
)

# Exécution des commandes principales
echo "==== Migrations pour la base principale ===="
for cmd in "${COMMANDS_MAIN[@]}"; do
  execute_command "$cmd"
done

# Exécution des commandes pour la base de test
echo "==== Migrations pour la base de test ===="
for cmd in "${COMMANDS_TEST[@]}"; do
  execute_command "$cmd"
done

# Résultat
if [ -s "$ERROR_LOG" ]; then
  echo "Certaines commandes ont échoué. Consultez le fichier '$ERROR_LOG' pour plus de détails."
else
  echo "Toutes les commandes ont été exécutées avec succès."
fi
