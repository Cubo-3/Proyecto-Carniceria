 Proyecto Web de Calidad de Software: Gestión de Carnicería
 Descripción del Proyecto

Pagina web funcional para la administración de una carnicería, implementando un crud completo que permite la gestión del inventario.

 Tecnologías Utilizadas
Lenguaje: PHP 

Base de Datos: MySQL / MariaDB

Librería de BD: PDO 

Frontend: HTML5, CSS3, Bootstrap 5 (CDN).

Control de Versiones: Git.


Análisis de Calidad: Codacy.

Estructura del Proyecto

1. Módulos de Acceso y Vistas (public/)
Contiene los archivos principales a los que el usuario accede para ver la interfaz y el contenido.

/login.php: Formulario de inicio de sesión.

/dashboard.php: Panel de administración principal y listado de productos (READ).

/index.php: (Opcional) Redirección a login.php.

/css/: Carpeta para archivos de estilos (CSS, Bootstrap).

2. Módulos de Autenticación y Sesión
Contiene los scripts que controlan el acceso al sistema.

/actions/auth.php: Procesa el login y crea la sesión, verificando la contraseña.

/actions/logout.php: Cierra la sesión de forma segura.

3. Módulos de Gestión CRUD y Lógica (actions/)
Contiene la lógica pesada del sistema, implementando las operaciones requeridas y aplicando las correcciones de Seguridad:

/actions/save_product.php: Lógica de CREATE. Recibe datos, valida el Token CSRF e inserta el nuevo producto.

/actions/edit_product.php: Lógica de UPDATE. Recibe datos, valida el Token CSRF y actualiza el producto.

/actions/delete_product.php: Lógica de DELETE. Recibe el ID, valida el Token CSRF y elimina el producto.

/actions/tipos.php: CRUD o manejo de la tabla auxiliar tipos_productos.

4. Archivos de Configuración y Base de Datos
Contiene archivos que se incluyen o que son vitales para la configuración global.

/includes/db.php: Archivo de conexión segura a MySQL utilizando PDO.

/includes/config.php: (Recomendado) Para constantes globales y variables de configuración.

/carniceria.sql: Script para la creación inicial de las tablas de la base de datos.

Credenciales de Acceso de Prueba:

Usuario: admin@carniceria.com

Contraseña: 1234 
