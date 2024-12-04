#!/bin/bash

# Nom ou ID du conteneur Docker
CONTAINER_NAME_OR_ID="backend"

# Fichier de cache pour enregistrer les erreurs
ERROR_LOG="docker_command_errors.log"

# Réinitialiser le fichier de log
> "$ERROR_LOG"

# Vérifiez si le conteneur est en cours d'exécution
if ! docker ps --format '{{.Names}}' | grep -q "^${CONTAINER_NAME_OR_ID}$"; then
  echo "Erreur : Le conteneur '${CONTAINER_NAME_OR_ID}' n'est pas en cours d'exécution." | tee -a "$ERROR_LOG"
  exit 1
fi

# Fonction pour exécuter une commande Docker et gérer les erreurs
execute_command() {
  local cmd="$1"
  echo "Exécution de la commande : $cmd"
  
  if ! docker exec -it ${CONTAINER_NAME_OR_ID} $cmd; then
    echo "Erreur lors de l'exécution de la commande : $cmd" | tee -a "$ERROR_LOG"
  fi
}

# Liste des commandes à exécuter
COMMANDS=(
  "php bin/console make:migration"
  "php bin/console doctrine:migrations:migrate --no-interaction"
  "php bin/console lexik:jwt:generate-keypair"
)

# Exécuter chaque commande
for cmd in "${COMMANDS[@]}"; do
  execute_command "$cmd"
done

# Vérifier si des erreurs ont été enregistrées
if [ -s "$ERROR_LOG" ]; then
  echo "Certaines commandes ont échoué. Consultez le fichier '$ERROR_LOG' pour plus de détails."
else
  echo "Toutes les commandes ont été exécutées avec succès."
fi
