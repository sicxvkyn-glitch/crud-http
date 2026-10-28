# CRUD de productos con Laravel 12 y Bootstrap 5

Proyecto escolar de la materia Aplicaciones Web para practicar las peticiones HTTP en Laravel.

## Tecnologías utilizadas

- Laravel 12
- PHP
- Bootstrap 5
- Vite
- Blade
- JavaScript

## Funcionalidades

- Listar productos.
- Registrar productos.
- Editar productos.
- Eliminar productos con confirmación.
- Buscar productos por nombre.
- Filtrar productos por categoría.
- Validar los datos de los formularios.

## Métodos HTTP

- **GET:** consultar productos y mostrar formularios.
- **POST:** registrar productos.
- **PUT/PATCH:** actualizar productos.
- **DELETE:** eliminar productos.

## Ejecución del proyecto

Instalar dependencias:

```bash
composer install
npm install
```

Configurar el archivo `.env` y generar la clave:

```bash
php artisan key:generate
```

Compilar los recursos de frontend:

```bash
npm run build
```

Iniciar el servidor:

```bash
php artisan serve
```

Abrir `http://127.0.0.1:8000` en el navegador.

**Nota:** los productos se almacenan en la sesión de Laravel; no se utiliza una base de datos para este CRUD.
