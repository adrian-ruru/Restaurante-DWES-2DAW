# README del proyecto Restaurante-DWES-2DAW

A continuación se detalla el propósito de cada archivo y carpeta del proyecto. Si algún archivo aún no tiene contenido, se describe su intención prevista.

## Estructura recomendada del proyecto
- index.php: Punto de entrada principal de la aplicación (enrutado básico y carga inicial).
- config/
    - config.php: Configuración general (BD, entorno, rutas).
    - database.php: Conexión PDO/MySQL y helpers de BD.
- public/
    - css/: Hojas de estilo (Bootstrap/propio).
    - js/: Scripts del lado cliente.
    - img/: Imágenes del sitio.
    - .htaccess: Reescritura de URLs para rutas limpias (si Apache).
- src/
    - Controllers/: Controladores (reciben petición, llaman servicios/modelos, devuelven vistas/JSON).
        - RestauranteController.php: Acciones CRUD de restaurantes.
        - ReservaController.php: Acciones CRUD de reservas.
        - UsuarioController.php: Login/registro/perfil.
    - Models/: Modelos de dominio y acceso a datos.
        - Restaurante.php: Entidad y métodos de persistencia.
        - Reserva.php: Entidad y lógica de negocio de reservas.
        - Usuario.php: Entidad y autenticación básica.
        - BaseModel.php: Clase base con utilidades comunes (query, hydration).
    - Services/: Lógica de negocio independiente de controladores/modelos.
        - AuthService.php: Manejo de sesiones, roles y permisos.
        - MailService.php: Envío de correos (confirmación de reservas).
        - ValidationService.php: Validaciones centralizadas.
    - Helpers/: Funciones utilitarias.
        - Response.php: Respuestas JSON/HTML estandarizadas.
        - View.php: Renderizado de vistas con variables.
        - Router.php: Enrutador simple por método y ruta.
- views/
    - layout.php: Plantilla base (cabecera, pie, yield de contenido).
    - restaurante/
        - index.php: Listado de restaurantes.
        - show.php: Detalle de un restaurante.
        - form.php: Alta/edición de restaurante.
    - reserva/
        - index.php: Listado de reservas.
        - form.php: Crear/editar reserva.
        - confirmacion.php: Confirmación de reserva.
    - usuario/
        - login.php: Formulario de acceso.
        - registro.php: Alta de usuario.
        - perfil.php: Datos del usuario.
- tests/
    - Unit/: Pruebas unitarias de modelos/servicios.
    - Integration/: Pruebas de controladores/rutas.
- scripts/
    - seed.php: Datos de ejemplo (restaurantes, usuarios).
    - migrate.php: Migraciones de tablas (crear/alter).
- .env: Variables de entorno (DB_HOST, DB_NAME, DB_USER, DB_PASS, APP_ENV).
- composer.json: Dependencias y autoload PSR-4.
- README.md: Este documento.

## Descripción breve por archivo (si está vacío)
- Controllers: Crear métodos index/show/create/store/edit/update/destroy según recurso.
- Models: Definir propiedades de entidad, reglas de validación y métodos find/all/save/delete.
- Services: Encapsular lógica transversal (auth, correo, validación).
- Helpers: Evitar duplicación (render, respuestas, rutas).
- Vistas: HTML/PHP con datos proporcionados por controladores.
- config.php: Cargar .env y constantes de configuración.
- database.php: Instanciar PDO y manejar errores/excepciones.
- Router.php: Registrar rutas (GET/POST/PUT/DELETE) y resolver a controlador@acción.
- seed.php: Insertar datos mínimos para probar la aplicación.
- migrate.php: Crear tablas: usuarios, restaurantes, reservas con claves foráneas.
- .htaccess: Reescritura hacia public/index.php si se usa Apache.

## Cómo generar este README con tu estructura real
1. Obtén el árbol del proyecto:
     - Windows: usa PowerShell en la carpeta del proyecto
         - Get-ChildItem -Recurse | Format-Table FullName
     - Git Bash: tree -I node_modules -I vendor
2. Pega la estructura y te devuelvo un README ajustado a tus archivos reales.

## Notas
- Si usas XAMPP, coloca index.php y public/ bajo htdocs y configura DocumentRoot a public/.
- Mantén credenciales fuera del código con .env y no lo subas al repositorio.
- Usa composer dump-autoload tras crear clases en src/.
## Descripción de archivos y carpetas

- index.php
    - Punto de entrada de la aplicación. Inicializa autoload, configuración y enruta la petición inicial.

- config/config.php
    - Carga variables de entorno, define constantes y parámetros globales (BD, entorno, rutas).

- config/database.php
    - Crea la conexión PDO a MySQL y expone funciones auxiliares para consultas y transacciones.

- public/css/
    - Hojas de estilo (Bootstrap y estilos propios) servidas al cliente.

- public/js/
    - Scripts del lado cliente (interacciones, validaciones, peticiones AJAX).

- public/img/
    - Recursos gráficos del sitio (logotipos, fotos de restaurantes).

- public/.htaccess
    - Reescritura de URLs y redirección a public/index.php cuando se usa Apache.

- src/Controllers/RestauranteController.php
    - Acciones CRUD para restaurantes: listado, detalle, alta, edición y borrado.

- src/Controllers/ReservaController.php
    - Acciones CRUD de reservas: crear, editar, listar y confirmar.

- src/Controllers/UsuarioController.php
    - Gestión de usuarios: login, registro y perfil.

- src/Models/Restaurante.php
    - Entidad restaurante y métodos de persistencia (find, all, save, delete).

- src/Models/Reserva.php
    - Entidad reserva y lógica de negocio (validaciones de fecha/hora, disponibilidad).

- src/Models/Usuario.php
    - Entidad usuario y autenticación básica (hash de contraseña, verificación).

- src/Models/BaseModel.php
    - Clase base con utilidades comunes: ejecución de queries, mapeo de resultados.

- src/Services/AuthService.php
    - Maneja sesiones, roles y permisos. Verifica acceso a rutas protegidas.

- src/Services/MailService.php
    - Envía correos (confirmación de reservas, notificaciones).

- src/Services/ValidationService.php
    - Reglas y helpers de validación reutilizables.

- src/Helpers/Response.php
    - Respuestas estándar (JSON/HTML) con códigos HTTP.

- src/Helpers/View.php
    - Renderizado de vistas y paso de variables.

- src/Helpers/Router.php
    - Enrutador simple por método y ruta que resuelve controlador y acción.

- views/layout.php
    - Plantilla base con cabecera, pie y zona de contenido.

- views/restaurante/index.php
    - Listado de restaurantes con paginación/acciones.

- views/restaurante/show.php
    - Detalle de un restaurante específico.

- views/restaurante/form.php
    - Formulario de alta/edición de restaurante.

- views/reserva/index.php
    - Listado de reservas del sistema o del usuario.

- views/reserva/form.php
    - Formulario para crear/editar reservas.

- views/reserva/confirmacion.php
    - Pantalla de confirmación de reserva.

- views/usuario/login.php
    - Formulario de acceso.

- views/usuario/registro.php
    - Alta de usuario.

- views/usuario/perfil.php
    - Visualización y edición de datos del usuario.

- tests/Unit/
    - Pruebas unitarias de modelos y servicios.

- tests/Integration/
    - Pruebas de controladores, rutas y flujo completo.

- scripts/seed.php
    - Inserta datos de ejemplo (usuarios, restaurantes).

- scripts/migrate.php
    - Crea/modifica tablas (usuarios, restaurantes, reservas y claves foráneas).

- .env
    - Variables de entorno (DB_HOST, DB_NAME, DB_USER, DB_PASS, APP_ENV). No se versiona.

- composer.json
    - Dependencias y autoload PSR-4.

- README.md
    - Documentación del proyecto y guía rápida.