# 🚀 Guía para Subir el Proyecto a GitHub

## ⚠️ IMPORTANTE: NO subas archivos en ZIP

GitHub está diseñado para trabajar con Git, no con archivos ZIP. Subir archivo por archivo usando Git es la forma correcta y profesional.

---

## 📋 PASOS PARA SUBIR A GITHUB

### PASO 1: Instalar Git (si no lo tienes)

1. Descarga Git desde: https://git-scm.com/download/win
2. Instálalo con las opciones por defecto
3. Abre PowerShell o Git Bash

### PASO 2: Crear un Repositorio en GitHub

1. Ve a https://github.com
2. Inicia sesión (o crea una cuenta)
3. Haz clic en el botón **"+"** (arriba a la derecha) → **"New repository"**
4. Completa:
   - **Repository name:** `hai-swimwear` (o el nombre que prefieras)
   - **Description:** "E-commerce de trajes de baño Hai Swimwear"
   - **Visibility:** Elige Público o Privado
   - **NO marques** "Add a README file" (ya tenemos uno)
   - **NO marques** "Add .gitignore" (ya tenemos uno)
5. Haz clic en **"Create repository"**

### PASO 3: Inicializar Git en tu Proyecto

Abre PowerShell en la carpeta del proyecto y ejecuta:

```powershell
# Navega a tu carpeta del proyecto
cd "D:\Pagina Hai definitiva"

# Inicializa Git
git init

# Agrega todos los archivos (excepto los que están en .gitignore)
git add .

# Crea el primer commit
git commit -m "Initial commit: Hai Swimwear e-commerce completo"
```

### PASO 4: Conectar con GitHub

```powershell
# Reemplaza TU-USUARIO con tu usuario de GitHub
# Reemplaza hai-swimwear con el nombre de tu repositorio
git remote add origin https://github.com/TU-USUARIO/hai-swimwear.git

# Verifica que se agregó correctamente
git remote -v
```

### PASO 5: Subir los Archivos

```powershell
# Sube todo al repositorio
git branch -M main
git push -u origin main
```

**Nota:** Si es la primera vez que usas Git, te pedirá autenticarte:
- Puede pedirte usuario y contraseña
- O puedes usar un **Personal Access Token** (más seguro)

---

## 🔐 Autenticación en GitHub

### Opción 1: Personal Access Token (Recomendado)

1. Ve a GitHub → Settings → Developer settings → Personal access tokens → Tokens (classic)
2. Haz clic en **"Generate new token"**
3. Dale un nombre (ej: "Hai Swimwear")
4. Selecciona el scope **"repo"** (todos los permisos de repositorio)
5. Haz clic en **"Generate token"**
6. **Copia el token** (solo se muestra una vez)
7. Cuando Git te pida contraseña, usa el token en lugar de tu contraseña

### Opción 2: GitHub CLI (Más fácil)

```powershell
# Instala GitHub CLI
winget install --id GitHub.cli

# Autentica
gh auth login

# Luego puedes hacer push normalmente
git push -u origin main
```

---

## ✅ VERIFICAR QUE TODO SE SUBIÓ

1. Ve a tu repositorio en GitHub: `https://github.com/TU-USUARIO/hai-swimwear`
2. Deberías ver todos los archivos y carpetas
3. **IMPORTANTE:** Verifica que NO aparezcan estos archivos (están en .gitignore):
   - ❌ `database/config_mysql.php`
   - ❌ `database/config_supabase.php`
   - ❌ `Hai-Swimwear-Complete.zip`
   - ❌ Archivos de log

---

## 📝 COMANDOS ÚTILES PARA EL FUTURO

### Ver el estado de los archivos
```powershell
git status
```

### Agregar cambios específicos
```powershell
git add nombre-archivo.php
```

### Hacer commit de cambios
```powershell
git commit -m "Descripción de los cambios"
```

### Subir cambios
```powershell
git push
```

### Ver el historial
```powershell
git log
```

### Descargar cambios del servidor
```powershell
git pull
```

---

## 🐛 SOLUCIÓN DE PROBLEMAS

### Error: "fatal: not a git repository"
**Solución:** Ejecuta `git init` primero

### Error: "remote origin already exists"
**Solución:** 
```powershell
git remote remove origin
git remote add origin https://github.com/TU-USUARIO/hai-swimwear.git
```

### Error: "Authentication failed"
**Solución:** Usa un Personal Access Token en lugar de tu contraseña

### Error: "Permission denied"
**Solución:** Verifica que el nombre del repositorio y usuario sean correctos

---

## 📚 RECURSOS ADICIONALES

- [Documentación oficial de Git](https://git-scm.com/doc)
- [Guía de GitHub](https://guides.github.com/)
- [GitHub Desktop](https://desktop.github.com/) - Interfaz gráfica (opcional)

---

## ✅ CHECKLIST FINAL

- [ ] Git instalado
- [ ] Repositorio creado en GitHub
- [ ] Git inicializado en el proyecto
- [ ] `.gitignore` creado (ya está hecho)
- [ ] `README.md` creado (ya está hecho)
- [ ] Archivos agregados con `git add .`
- [ ] Primer commit creado
- [ ] Repositorio remoto conectado
- [ ] Archivos subidos con `git push`
- [ ] Verificado en GitHub que todo está correcto

---

¡Listo! Tu proyecto está en GitHub. 🎉

