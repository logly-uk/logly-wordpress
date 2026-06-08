#!/bin/sh
# Deploy del plugin a WordPress.org SVN desde un contenedor efímero.
# No instala nada en la máquina host. La contraseña la pide SVN por prompt
# interactivo (no se guarda en disco ni en variables).
set -eu

VERSION="${1:?Uso: deploy.sh <version>   (ej: deploy.sh 1.0.0)}"
SVN_URL="https://plugins.svn.wordpress.org/logly-analytics"
: "${SVN_USER:?Falta SVN_USER (tu usuario de WordPress.org, case-sensitive)}"

# Archivos del plugin que SÍ se publican (todo lo demás se ignora).
FILES="loglyuk-privacy-analytics.php admin.js readme.txt"

apk add --no-cache subversion >/dev/null

WORK=/tmp/svn
echo ">> Checkout de $SVN_URL"
svn checkout "$SVN_URL" "$WORK" --username "$SVN_USER"

echo ">> Sincronizando trunk/"
mkdir -p "$WORK/trunk"
rm -f "$WORK/trunk"/*
for f in $FILES; do cp "/plugin/$f" "$WORK/trunk/$f"; done

echo ">> Creando tags/$VERSION"
if [ -d "$WORK/tags/$VERSION" ]; then
  echo "!! tags/$VERSION ya existe en el repo. Las tags son inmutables; sube una versión nueva." >&2
  exit 1
fi
mkdir -p "$WORK/tags/$VERSION"
for f in $FILES; do cp "/plugin/$f" "$WORK/tags/$VERSION/$f"; done

# Assets de la ficha (icono/banner). Mutables: se sincronizan en cada deploy
# si el directorio existe montado. NO se empaquetan con el plugin.
if [ -d /assets ]; then
  echo ">> Sincronizando assets/ (icono + banner)"
  mkdir -p "$WORK/assets"
  cp /assets/*.png "$WORK/assets/" 2>/dev/null || true
fi

cd "$WORK"
svn add --force trunk tags assets >/dev/null 2>&1 || true
# Marcar para borrado lo que se haya eliminado del plugin (releases futuras).
svn status | awk '/^!/{print $2}' | while read -r p; do svn rm "$p"; done

echo ">> Cambios a publicar:"
svn status

echo ">> Commit (SVN pedirá tu password de WordPress.org)"
svn commit -m "Release $VERSION" --username "$SVN_USER"
echo ">> Hecho. Ficha pública: https://wordpress.org/plugins/logly-analytics"
