<div align="center">

<img src="./public/InnovaFood_Logo.png" width="160" alt="InnovaFood Logo" />

<span>CertiCheck - InnovaFood G.C</span>

<p>Gestión y consulta de historial de cursos de forma simple y segura.</p>

<img src="https://img.shields.io/badge/Laravel-13.x-f05340?logo=laravel&logoColor=white" alt="Laravel">
<img src="https://img.shields.io/badge/PHP-8.3+-777bb4?logo=php&logoColor=white" alt="PHP">
<img src="https://img.shields.io/badge/TailwindCSS-3.4-06B6D4?logo=tailwindcss&logoColor=white" alt="TailwindCSS">

</div>

---

<div align="center">
  <img src="./public/github_preview/preview_client.webp" width="100%" alt="Vista previa sección cliente">
  <img src="./public/github_preview/preview_admin.webp" width="100%" alt="Vista previa sección administrador">
</div>

## 🧩 Funcionalidades

- Ingreso de administración.
  - Registro, edición y eliminación de clientes.
- Consulta publica por cédula.
  - Estado de vigencia de cursos (vigente/caducado) según fecha de vencimiento.

## 👥 Equipo de desarrollo

<div align="center">

| **Desarrollador** | **Rol**  |                   **GitHub**                   |
| :---------------: | :------: | :--------------------------------------------: |
|   **Aracelly**    | Frontend | [@Aracelly126](https://github.com/Aracelly126) |
|     **Elías**     | Backend  |  [@JosliBlue](https://github.com/JosliBlue/)   |

</div>

## 🔐 Variables de Entorno Principales
```env
APP_NAME="Nombre app"
APP_URL=http://localhost:8000
APP_KEY=
APP_ENV=local
APP_DEBUG=true

APP_TIMEZONE=America/Guayaquil
APP_LOCALE=es

BCRYPT_ROUNDS=12

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nombre_bd
DB_USERNAME=
DB_PASSWORD=

CACHE_STORE=file
SESSION_DRIVER=cookie
QUEUE_CONNECTION=database
BROADCAST_CONNECTION=log

ADMIN_EMAIL=nombre_admin@gmail.com
ADMIN_PASSWORD=password
```

## 🛠️ Comandos Útiles

```bash
# Descarga todas las dependencias
composer install

# Preparar entorno desde cero (incluye migrate:fresh --seed)
composer run prepare

# Limpiar y recachear configuración/rutas
composer run clear

# Servidor de desarrollo
composer run dev
```

## 📝 Notas

- El seeder administrativo toma `ADMIN_EMAIL` y `ADMIN_PASSWORD` desde `.env`.
- Si no defines esas variables, la creación del usuario admin puede fallar o quedar invalida.
- Si cambias de motor de base de datos, actualiza los valores DB\_\* en `.env`.
