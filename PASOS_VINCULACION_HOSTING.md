# PASOS PARA VINCULAR GITHUB CON HOSTING Y BASE DE DATOS

## PASO 1: PREPARAR TU HOSTING
### 1.1 Accede a tu panel de hosting (cPanel/Plesk)
- Anota la siguiente información:
  - [ ] URL de tu sitio web
  - [ ] Datos de acceso FTP (host, usuario, contraseña, puerto)
  - [ ] Directorio raíz web (ej: `/public_html`)

### 1.2 Crear Base de Datos en phpMyAdmin
1. Entra a phpMyAdmin desde tu hosting
2. Click en "Nueva" o "New Database"
3. Nombre sugerido: `hai_swimwear`
4. Cotejamiento: `utf8mb4_unicode_ci`
5. Click en "Crear"

**Anota:**
- [ ] Nombre de la base de datos: __________________
- [ ] Host MySQL (normalmente: localhost): __________________
- [ ] Puerto MySQL (normalmente: 3306): __________________
- [ ] Usuario MySQL: __________________
- [ ] Contraseña MySQL: __________________

---

## PASO 2: EJECUTAR SQL EN PHPMYADMIN
1. En phpMyAdmin, selecciona tu base de datos (lado izquierdo)
2. Click en la pestaña "SQL" (arriba)
3. Abre el archivo `SCHEMA_COMPLETO_MYSQL.sql` de este proyecto
4. Copia TODO el contenido
5. Pégalo en el área de texto de phpMyAdmin
6. Click en "Continuar" o "Go"
7. Verifica que aparezca: "✓ Se han ejecutado correctamente X consultas"

---

## PASO 3: CONFIGURAR ARCHIVO DE CONEXIÓN
Cuando me des los datos del PASO 1.2, yo configuraré automáticamente:
- `config.php` con tus credenciales
- `config_mysql.php` actualizado

---

## PASO 4: SUBIR REPOSITORIO A GITHUB
### 4.1 Crear repositorio en GitHub
1. Ve a https://github.com/new
2. Nombre del repositorio: `hai-swimwear`
3. Privado o Público (recomiendo Privado)
4. NO inicialices con README
5. Click "Create repository"

### 4.2 Conectar tu repositorio local
Ejecuta estos comandos (yo te los daré cuando estés listo):
```bash
git remote add origin [URL-DE-TU-REPO]
git push -u origin main
```

---

## PASO 5: CONFIGURAR SECRETOS EN GITHUB
1. Ve a tu repositorio en GitHub
2. Click en "Settings" → "Secrets and variables" → "Actions"
3. Click en "New repository secret"
4. Agrega los siguientes secretos (yo te daré los valores):

**Secretos necesarios:**
- `FTP_SERVER` - Host de tu FTP
- `FTP_USERNAME` - Usuario FTP
- `FTP_PASSWORD` - Contraseña FTP
- `FTP_REMOTE_DIR` - Directorio (ej: /public_html)
- `DB_HOST` - Host MySQL
- `DB_NAME` - Nombre base de datos
- `DB_USER` - Usuario MySQL
- `DB_PASS` - Contraseña MySQL
- `DB_PORT` - Puerto MySQL (normalmente 3306)

---

## PASO 6: CREAR WORKFLOW DE GITHUB ACTIONS
(Yo crearé este archivo automáticamente)

Este workflow:
- Se activará cada vez que hagas `git push`
- Subirá automáticamente los archivos a tu hosting vía FTP
- Mantendrá sincronizado tu código

---

## PASO 7: PRIMER DESPLIEGUE
1. Hacer commit de cambios:
```bash
git add .
git commit -m "Configuración inicial para producción"
git push
```

2. Ver el progreso en GitHub:
   - Ve a tu repositorio → "Actions"
   - Verás el workflow ejecutándose
   - Espera que termine (marca verde ✓)

---

## PASO 8: VERIFICAR FUNCIONAMIENTO
1. Visita tu sitio: `https://tudominio.com`
2. Prueba el login: `https://tudominio.com/login.php`
   - Usuario: `admin@haiswimwear.com`
   - Contraseña: `admin123`

---

## ¿QUÉ NECESITO QUE ME DIGAS AHORA?

**Dame la información del PASO 1.2 (datos de MySQL) para continuar:**
- Host MySQL:
- Puerto MySQL:
- Nombre de la base de datos:
- Usuario MySQL:
- Contraseña MySQL:

**Y del PASO 1.1:**
- URL de tu sitio web:
- Host FTP:
- Usuario FTP:
- Contraseña FTP:
- Directorio raíz (ej: /public_html):

Una vez me des estos datos, yo configuro todo automáticamente.
