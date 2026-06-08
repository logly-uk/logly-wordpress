# Deploy a WordPress.org (SVN sin instalar nada)

WordPress.org publica plugins vía SVN, no Git. Este tooling usa un contenedor
Docker efímero con `subversion` para no ensuciar la máquina. La contraseña SVN
**nunca se guarda**: la pide el cliente por prompt.

## Estructura del repo SVN

```
logly-analytics/   (slug del SVN; el folder/textdomain del plugin es loglyuk-privacy-analytics)
├── trunk/        # plugin actual (loglyuk-privacy-analytics.php + admin.js + readme.txt)
├── tags/1.0.0/   # release congelada — esto es lo que instalan los usuarios
└── assets/       # banner / icono / screenshots de la ficha (NO se empaqueta)
```

## Publicar una versión

Desde la raíz del repo `logly-wordpress`:

```sh
docker run --rm -it \
  -e SVN_USER=loglyuk \
  -v "$PWD/loglyuk-privacy-analytics:/plugin:ro" \
  -v "$PWD/wp-org-assets:/assets:ro" \
  -v "$PWD/.svn-deploy:/deploy:ro" \
  alpine:3 sh /deploy/deploy.sh 1.0.0
```

- `SVN_USER` = tu usuario de WordPress.org (**case-sensitive**, no el email).
- SVN pedirá tu **password SVN** (distinta de la del login web). Se obtiene/regenera en:
  https://profiles.wordpress.org/me/profile/edit/group/3/?screen=svn-password
- Para una versión nueva: actualiza `Version:` en `loglyuk-privacy-analytics.php` y
  `Stable tag:` en `readme.txt`, y cambia el último argumento (`1.0.0` → `1.1.0`).

## Assets de la ficha (icono/banner)

Viven en `../wp-org-assets/` y se suben a `/assets` del SVN (mutables, fuera del
zip del plugin). Ya generados desde el branding de Logly:

- `icon-256x256.png` + `icon-128x128.png` — logomark (la "l" sobre navy).
- `banner-772x250.png` + `banner-1544x500.png` — banner con fondo desenfocado.

Para regenerarlos ver el branding fuente en `SimpleFastAnalitics/landing/assets`
(`favicon.svg` = logomark, `logly-banner-4200x700.png` = banner).
