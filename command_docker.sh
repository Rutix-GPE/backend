#!/bin/bash
CONTAINER_NAME_OR_ID="backend"

ERROR_LOG="docker_command_errors.log"
> "$ERROR_LOG"
if ! docker ps --format '{{.Names}}' | grep -q "^${CONTAINER_NAME_OR_ID}$"; then
  echo "Erreur : Le conteneur '${CONTAINER_NAME_OR_ID}' n'est pas en cours d'exécution." | tee -a "$ERROR_LOG"
  exit 1
fi
execute_command() {
  local cmd="$1"
  echo "Exécution de la commande : $cmd"
  
  if ! docker exec -it ${CONTAINER_NAME_OR_ID} $cmd; then
    echo "Erreur lors de l'exécution de la commande : $cmd" | tee -a "$ERROR_LOG"
  fi
}
COMMANDS=(
  "php bin/console make:migration"
  "php bin/console doctrine:migrations:migrate --no-interaction"
  "php bin/console lexik:jwt:generate-keypair"
)
for cmd in "${COMMANDS[@]}"; do
  execute_command "$cmd"
done

if [ -s "$ERROR_LOG" ]; then
  echo "Certaines commandes ont échoué. Consultez le fichier '$ERROR_LOG' pour plus de détails."
else
  echo "Toutes les commandes ont été exécutées avec succès."
fi
