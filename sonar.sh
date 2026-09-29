cat > sonar.sh << 'SCRIPT'
#!/bin/bash
# Analyse SonarQube pour ARTIPLAST Inventory
# Usage: ./sonar.sh

export MSYS_NO_PATHCONV=1

# Token depuis variable d'environnement (recommandé) ou en dur (déconseillé)
TOKEN="${SONAR_TOKEN:-sqp_7a903cb6ea2599ea148caa59eeb50ced08320f73}"

PROJECT_DIR="/c/Users/MSI/Downloads/Rapport de Stage/Inventryx-main"

echo "🔍 Analyse SonarQube en cours..."
echo "📁 Projet : $PROJECT_DIR"
echo "🌐 Serveur : http://host.docker.internal:9000"
echo ""

docker run --rm \
  -e SONAR_HOST_URL=http://host.docker.internal:9000 \
  -e SONAR_TOKEN="$TOKEN" \
  -v "$PROJECT_DIR":/usr/src \
  -w /usr/src \
  sonarsource/sonar-scanner-cli \
  -Dsonar.projectKey=artiplast-inventory \
  -Dsonar.projectName="ARTIPLAST Inventory" \
  -Dsonar.projectVersion=1.0 \
  -Dsonar.sources=app,routes,resources/views,database,config \
  -Dsonar.exclusions="vendor/**,node_modules/**,storage/**,public/build/**,tests/**,bootstrap/cache/**,public/backend/**,public/upload/**" \
  -Dsonar.sourceEncoding=UTF-8 \
  -Dsonar.language=php

echo ""
echo "✅ Analyse terminée"
echo "👉 Résultats : http://localhost:9000/dashboard?id=artiplast-inventory"
SCRIPT

chmod +x sonar.sh
