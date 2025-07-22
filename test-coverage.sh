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
  echo "▶️  Exécution : $cmd"

  if ! docker exec -i ${CONTAINER_NAME_OR_ID} /bin/bash -c "$cmd"; then
    echo "❌ Échec : $cmd" | tee -a "$ERROR_LOG"
  fi
}

echo "==== 📦 Génération de la couverture de tests ===="

COMMANDS=(
  "export DATABASE_URL='mysql://root:root@db:3306/rutix_db_test'"
  "vendor/bin/phpunit --coverage-html coverage-html"
)

# Exécution
for cmd in "${COMMANDS[@]}"; do
  execute_command "$cmd"
done

# Résultat
if [ -s "$ERROR_LOG" ]; then
  echo "⚠️  Certaines commandes ont échoué. Consultez '$ERROR_LOG' pour plus de détails."
else
  echo "✅ Couverture générée avec succès. Rapport dans 'coverage-html/index.html'."
fi
