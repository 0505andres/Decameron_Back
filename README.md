# Decameron Back

Aplicación Symfony contenida con PostgreSQL.

## Requisitos

- Docker
- Docker Compose

## Despliegue

### Local

1. Abrir la carpeta del proyecto:

```bash
cd ruta_descarga/Decameron_Back
```

2. Construir y levantar los contenedores:

```bash
docker compose up -d --build
```

3. Verificar los contenedores:

```bash
docker compose ps
```

4. Si necesitas limpiar la caché de Symfony (recomendado):

```bash
docker compose exec app sh -lc "php bin/console cache:clear --no-warmup"
```

### Despliegue en Render

1. Asegúrate de que el repositorio contiene `Dockerfile` y `render.yaml` en la raíz.
2. Crea una cuenta en Render y conecta el repositorio.
3. Crea un servicio de base de datos PostgreSQL en Render:
   - Engine: `postgresql`
   - Version: `13`
   - Plan: `starter` (o el que necesites)
4. Añade las variables de entorno en la Web Service de Render:
   - `DATABASE_URL=postgresql://USER:PASS@HOST:PORT/DBNAME?serverVersion=13&charset=utf8`
   - `APP_ENV=prod`
   - `APP_DEBUG=0`
   - `APP_SECRET=tu_secreto`
5. Render usará el contenedor Docker y la variable `$PORT` para exponer la app.
6. Si necesitas ejecutar migraciones tras el despliegue:

```bash
php bin/console doctrine:migrations:migrate --no-interaction
```

## Acceso

- Aplicación: `http://localhost:9000/api/docs`
- Base de datos PostgreSQL:
  - Host: `localhost`
  - Puerto: `5432`
  - Usuario: `symfony`
  - Contraseña: `symfony`
  - Base de datos: `symfony`

## Comandos útiles

- Ejecutar consola Symfony:

```bash
docker compose exec app sh -lc "php bin/console <comando>"
```

- Instalar dependencias (dentro del contenedor):

```bash
docker compose exec app sh -lc "composer install"
```

- Verificar rutas:

```bash
docker compose exec app sh -lc "php bin/console debug:router"
```

- Verificar servicios de Symfony:

```bash
docker compose exec app sh -lc "php bin/console debug:container"
```

## Notas

- El contenedor `app` expone el puerto `9000`.
- El contenedor `db` expone el puerto `5432`.
- El proyecto utiliza `DATABASE_URL` apuntando a `postgresql://symfony:symfony@db:5432/symfony`.
