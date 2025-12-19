# 🗺️ Cómo Ir a las Credenciales desde Donde Estás

## 📍 Estás en: Database > Tables > usuarios

Perfecto, las tablas ya están creadas. Ahora necesitas ir a las credenciales.

## 🎯 Pasos para Llegar a las Credenciales

### Paso 1: Ir a Settings
1. En el **menú lateral izquierdo**, busca la sección **"CONFIGURATION"**
2. Haz clic en **"Settings"** (está en la sección CONFIGURATION)

### Paso 2: Ir a Database
1. En la página de Settings, verás varias opciones
2. Haz clic en **"Database"** en el submenú o en la lista de opciones

### Paso 3: Encontrar las Credenciales
En la página de Database Settings, busca:

#### Opción A: Connection String
Busca una sección que diga **"Connection string"** o **"Connection info"**

Verás algo como:
```
postgresql://postgres:[PASSWORD]@db.xxxxx.supabase.co:5432/postgres
```

O una tabla con:
- **Host**: `db.xxxxx.supabase.co`
- **Database**: `postgres`
- **User**: `postgres`
- **Port**: `5432`

#### Opción B: Connection Pooling
Busca la sección **"Connection pooling"** - ahí puede estar el string de conexión

#### Opción C: Database Password
Ya deberías ver la sección **"Database password"** con un botón **"Reset database password"**

---

## 🔑 Lo que Necesito

Una vez que estés en Settings > Database, dime:

1. ¿Ves una sección "Connection string"?
2. ¿Ves una tabla con Host, Database, User, Port?
3. ¿Qué secciones ves en esa página?

---

## ✅ Si Ya Tienes las Credenciales

Si ya tienes:
- Host: `db.rvedynuxwfdbqwgkdgjg.supabase.co`
- Database: `postgres`
- User: `postgres`
- Password: `M7s5bjxY5F99arcu`
- Port: `5432`

Entonces solo necesitas:
1. Verificar que la contraseña sea correcta
2. Ejecutar el script de permisos
3. Verificar la conexión

