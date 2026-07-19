#!/usr/bin/env bash
# Despliegue idempotente para EC2 (docker-compose.prod.yml).
# Ejecutar desde el directorio raiz del proyecto en la instancia EC2.
#
# Requisitos previos en la instancia:
#   - Docker + docker compose plugin instalados
#   - Repositorio clonado
#   - Archivo .env creado a partir de .env.production.example y editado con APP_URL, DB_PASSWORD, DB_ROOT_PASSWORD

set -euo pipefail

COMPOSE="docker compose -f docker-compose.prod.yml"

echo "==> [1/8] Verificando archivo .env"
if [[ ! -f .env ]]; then
    echo "ERROR: falta .env. Copia .env.production.example y edita los valores requeridos." >&2
    exit 1
fi

echo "==> [2/8] Actualizando codigo (git pull)"
if [[ -d .git ]]; then
    git pull --ff-only
else
    echo "AVISO: no es un repo git, se omite git pull"
fi

echo "==> [3/8] Construyendo imagen de la app"
$COMPOSE build app

echo "==> [4/8] Levantando MySQL"
$COMPOSE up -d mysql

echo "==> [5/8] Instalando dependencias PHP (composer, sin dev)"
$COMPOSE run --rm --no-deps -u "$(id -u):$(id -g)" app \
    composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "==> [6/8] Compilando assets Vite (Node efimero)"
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$PWD":/app \
    -w /app \
    node:20-alpine \
    sh -c "npm ci && npm run build"

echo "==> [7/8] Migraciones y cache de produccion"
$COMPOSE run --rm app php artisan key:generate --force || true
$COMPOSE run --rm app php artisan migrate --force
$COMPOSE run --rm app php artisan config:cache
$COMPOSE run --rm app php artisan route:cache
$COMPOSE run --rm app php artisan view:cache

echo "==> [8/8] Levantando app, nginx y queue"
$COMPOSE up -d app nginx queue

echo ""
echo "Despliegue OK. Comprueba con: curl -I http://localhost/"
$COMPOSE ps
