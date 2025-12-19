# 🔑 Cómo Obtener las Credenciales de Supabase

## 📍 Ubicación Exacta en Supabase

### Paso 1: Ir al Dashboard
1. Abre: https://app.supabase.com
2. Inicia sesión
3. Selecciona tu proyecto: **Hai Swimwear** (o el nombre que le diste)

### Paso 2: Ir a Settings
1. En el menú lateral izquierdo, busca el icono de **⚙️ Settings**
2. Haz clic en **Settings**

### Paso 3: Ir a Database
1. En el submenú de Settings, haz clic en **Database**

### Paso 4: Encontrar las Credenciales

En la página de Database Settings verás varias secciones. Busca:

#### Opción A: "Connection string" (String de Conexión)
```
postgresql://postgres:[PASSWORD]@db.xxxxx.supabase.co:5432/postgres
```

De aquí puedes extraer:
- **Host**: `db.xxxxx.supabase.co` (después de `@` y antes de `:`)
- **Port**: `5432` (después del segundo `:`)
- **Database**: `postgres` (después del último `/`)
- **User**: `postgres` (después de `://` y antes de `:`)

#### Opción B: "Connection info" (Información de Conexión)
Aquí verás una tabla o lista con:
- **Host**: `db.xxxxx.supabase.co`
- **Database name**: `postgres`
- **Port**: `5432`
- **User**: `postgres`
- **Password**: [Click to reveal] o [Reset password]

### Paso 5: Obtener la Contraseña

**Si ya la tienes:**
- Haz clic en "Show" o "Reveal" para verla
- Cópiala

**Si no la recuerdas:**
1. Busca el botón **"Reset database password"** o **"Change database password"**
2. Haz clic en resetear
3. **IMPORTANTE**: Copia la nueva contraseña inmediatamente (solo se muestra una vez)
4. Guárdala en un lugar seguro

---

## 📋 Checklist de Credenciales

Marca cada una cuando la encuentres:

- [ ] **Host**: `db.________________.supabase.co`
- [ ] **Database**: `postgres` (generalmente)
- [ ] **User**: `postgres` (generalmente)
- [ ] **Password**: `________________` (la contraseña de la BD)
- [ ] **Port**: `5432` (generalmente)

---

## 🔧 Actualizar Configuración

Una vez que tengas todos los valores:

1. Abre: `database/config_supabase.php`
2. Busca estas líneas (alrededor de la línea 9-15):

```php
define('SUPABASE_HOST', 'db.rvedynuxwfdbqwgkdgjg.supabase.co');
define('SUPABASE_DB', 'postgres');
define('SUPABASE_USER', 'postgres');
define('SUPABASE_PASS', 'M7s5bjxY5F99arcu'); // ← ACTUALIZA ESTA
define('SUPABASE_PORT', '5432');
```

3. Actualiza `SUPABASE_PASS` con tu contraseña real
4. Si tu Host es diferente, actualiza `SUPABASE_HOST` también

---

## ✅ Verificar

Después de actualizar, abre:
```
http://localhost/Pagina%20Hai%20definitiva/admin/verificar_que_falta.php
```

Debe mostrar que la conexión funciona.

---

## 🆘 Ayuda

Si no encuentras algo, comparte:
1. ¿Qué secciones ves en Settings > Database?
2. ¿Ves "Connection string" o "Connection info"?
3. ¿Hay algún botón de "Reset password"?

