

Syscom Gestión de Usuarios

Aplicación web desarrollada en **Laravel 10** para la gestión de usuarios y contratos.  
Incluye CRUD de usuarios, roles, generación de contratos en PDF y cálculo de días trabajados.

---

## 🚀 Características
- CRUD de usuarios con validaciones.
- Generación y descarga de contratos en PDF.
- Eliminar usuarios (con `fecha_eliminacion`).
- Cálculo de días trabajados excluyendo fines de semana y festivos (Colombia).
- Interfaz responsive con **Bootstrap 5** y **Font Awesome 6**.

---

## 📦 Librerías utilizadas
- [laravel/framework](https://laravel.com/) – Core del framework.  
- [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf) – Generación de PDFs.  
- [nesbot/carbon](https://carbon.nesbot.com/) – Manejo de fechas.  
- [guzzlehttp/guzzle](https://docs.guzzlephp.org/) – Cliente HTTP para API de festivos.  
- [bootstrap](https://getbootstrap.com/) – Framework CSS para diseño responsive.  
- [fontawesome](https://fontawesome.com/) – Íconos.  

---

## ⚙️ Instalación

bash
git clone https://github.com/sebas124/syscom-gestion-usuarios.git
cd syscom-gestion-usuarios
composer install
npm install && npm run dev
cp .env.example .env
php artisan key:generate


Configura tu .env con la base de datos:

DB_DATABASE=syscom_gestion_usuarios
DB_USERNAME=// Tu usuario
DB_PASSWORD=// Tu contraseña


## Ejecutar script de base de datos de syscom_gestion_usuarios_backup.sql

php artisan serve