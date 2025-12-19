# 🏖️ Hai Swimwear - E-commerce de Trajes de Baño

Sitio web completo de e-commerce para Hai Swimwear, incluyendo frontend, panel de administración y base de datos.

## 📋 Características

### Frontend
- ✨ Diseño moderno y responsive
- 🛍️ Catálogo de productos con filtros
- 🛒 Carrito de compras
- 📱 Compatible con móviles y tablets

### Panel de Administración
- 📊 Dashboard con estadísticas
- 📦 Gestión de productos (CRUD completo)
- 📝 Gestión de pedidos
- 👥 Gestión de clientes
- 💬 Centro de mensajes
- 💰 Gestión de cotizaciones
- 🖼️ Gestión de imágenes web
- ⚙️ Configuración del sitio
- 📈 Reportes y análisis
- 📦 Control de inventario

## 🚀 Instalación

### Requisitos
- PHP 7.4 o superior
- MySQL 5.7+ o MariaDB 10.3+
- Servidor web (Apache/Nginx) o XAMPP/WAMP
- phpMyAdmin (recomendado)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/hai-swimwear.git
   cd hai-swimwear
   ```

2. **Configurar la base de datos**
   - Crea una base de datos MySQL llamada `hai_swimwear`
   - Ejecuta el script SQL: `database/SCHEMA_COMPLETO_MYSQL.sql` en phpMyAdmin
   - Ver instrucciones detalladas en: `database/INSTRUCCIONES_PHPMYADMIN.md`

3. **Configurar la conexión**
   - Copia `database/config_mysql.php.example` a `database/config_mysql.php`
   - Edita `database/config_mysql.php` con tus credenciales:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'hai_swimwear');
     define('DB_USER', 'root');
     define('DB_PASS', 'tu_contraseña');
     define('DB_PORT', '3306');
     ```

4. **Configurar permisos**
   - Asegúrate de que la carpeta `uploads/` tenga permisos de escritura

5. **Acceder al sitio**
   - Frontend: `http://localhost/hai-swimwear/`
   - Admin: `http://localhost/hai-swimwear/admin/login.php`
   - Credenciales por defecto:
     - Email: `admin@haiswimwear.com`
     - Password: `admin123`

## 📁 Estructura del Proyecto

```
hai-swimwear/
├── admin/              # Panel de administración
│   ├── api.php        # API REST para el panel
│   ├── index.php      # Página principal del admin
│   ├── login.php      # Login de administradores
│   └── ...
├── api/               # API pública
│   ├── productos.php
│   └── categorias.php
├── database/          # Scripts y configuración de BD
│   ├── SCHEMA_COMPLETO_MYSQL.sql
│   ├── config_mysql.php.example
│   └── ...
├── uploads/           # Imágenes subidas
├── index.html         # Página principal del frontend
├── styles.css         # Estilos del frontend
└── script.js          # JavaScript del frontend
```

## 🔧 Configuración

### Base de Datos

El proyecto soporta:
- **MySQL/MariaDB** (recomendado para desarrollo local)
- **PostgreSQL/Supabase** (para producción en la nube)

Archivos SQL disponibles:
- `database/SCHEMA_COMPLETO_MYSQL.sql` - Para MySQL/MariaDB
- `database/SCHEMA_COMPLETO.sql` - Para PostgreSQL/Supabase

### Variables de Configuración

Edita `database/config_mysql.php` para configurar:
- Host de la base de datos
- Nombre de la base de datos
- Usuario y contraseña
- Puerto

## 🔐 Seguridad

⚠️ **IMPORTANTE**: 
- Nunca subas archivos de configuración con credenciales a Git
- Cambia la contraseña del administrador por defecto en producción
- Usa HTTPS en producción
- Configura permisos adecuados en la carpeta `uploads/`

## 📚 Documentación

- `INSTALACION.md` - Guía de instalación completa
- `VINCULACION_BASE_DATOS.md` - Guía de conexión a base de datos
- `database/INSTRUCCIONES_PHPMYADMIN.md` - Instrucciones para phpMyAdmin
- `database/LEEME_MYSQL.txt` - Guía rápida MySQL

## 🛠️ Tecnologías Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Backend**: PHP 7.4+
- **Base de Datos**: MySQL/MariaDB o PostgreSQL
- **Servidor**: Apache/Nginx o XAMPP/WAMP

## 📝 Licencia

Este proyecto es privado y propiedad de Hai Swimwear.

## 👥 Soporte

Para problemas o preguntas, consulta la documentación en la carpeta `database/` o crea un issue en el repositorio.

---

**Desarrollado para Hai Swimwear** 🏖️

