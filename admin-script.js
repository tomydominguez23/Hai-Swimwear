/**
 * Script del Panel de Administración - Hai Swimwear
 * Este script maneja toda la funcionalidad del panel de administración
 */

// ==============================================
// CONFIGURACIÓN GLOBAL
// ==============================================
const API_URL = 'api.php';
let currentPage = 'dashboard';
let allProducts = [];
let allCategories = [];
let allStats = {};

// ==============================================
// INICIALIZACIÓN
// ==============================================
document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initModals();
    initForms();
    initSidebar();
    loadDashboardData();
    loadCategories();
    
    // Cargar datos según la página activa
    const hash = window.location.hash.substring(1);
    if (hash) {
        navigateToPage(hash);
    }
});

// ==============================================
// NAVEGACIÓN
// ==============================================
function initNavigation() {
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const pageId = this.getAttribute('data-page');
            navigateToPage(pageId);
        });
    });
}

function navigateToPage(pageId) {
    // Actualizar navegación activa
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    
    const activeNavItem = document.querySelector(`.nav-item[data-page="${pageId}"]`);
    if (activeNavItem) {
        activeNavItem.classList.add('active');
    }
    
    // Mostrar página correspondiente
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });
    
    const activePage = document.getElementById(pageId);
    if (activePage) {
        activePage.classList.add('active');
    }
    
    // Actualizar título
    updatePageTitle(pageId);
    
    // Cargar datos según la página
    loadPageData(pageId);
    
    // Actualizar URL
    window.location.hash = pageId;
    currentPage = pageId;
}

function updatePageTitle(pageId) {
    const titles = {
        'dashboard': 'Panel de Administración',
        'productos': 'Gestión de Productos',
        'pedidos': 'Gestión de Pedidos',
        'clientes': 'Gestión de Clientes',
        'mensajes': 'Centro de Mensajes',
        'cotizaciones': 'Gestión de Cotizaciones',
        'imagenes': 'Gestión de Imágenes Web',
        'categorias': 'Gestión de Categorías',
        'inventario': 'Control de Inventario',
        'ventas': 'Ventas',
        'reportes': 'Análisis y Reportes',
        'configuracion': 'Configuración del Sistema'
    };
    
    const pageTitle = document.getElementById('pageTitle');
    if (pageTitle) {
        pageTitle.textContent = titles[pageId] || 'Panel de Administración';
    }
}

function loadPageData(pageId) {
    switch(pageId) {
        case 'dashboard':
            loadDashboardData();
            break;
        case 'productos':
            loadProducts();
            break;
        case 'pedidos':
            loadOrders();
            break;
        case 'clientes':
            loadClients();
            break;
        case 'mensajes':
            loadMessages();
            break;
        case 'cotizaciones':
            loadQuotations();
            break;
        case 'imagenes':
            loadImages();
            break;
        case 'categorias':
            loadCategoriesPage();
            break;
    }
}

// ==============================================
// SIDEBAR Y MOBILE
// ==============================================
function initSidebar() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });
    }
}

// ==============================================
// MODALES
// ==============================================
function initModals() {
    // Cerrar modales al hacer clic en X o fuera del contenido
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', function() {
            closeModal(this.closest('.modal').id);
        });
    });
    
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
    
    // Botón nuevo producto
    const nuevoProductoBtn = document.getElementById('nuevoProductoBtn');
    if (nuevoProductoBtn) {
        nuevoProductoBtn.addEventListener('click', () => openModal('nuevoProductoModal'));
    }
    
    // Botón subir imágenes
    const subirImagenesBtn = document.getElementById('subirImagenesBtn');
    if (subirImagenesBtn) {
        subirImagenesBtn.addEventListener('click', () => openModal('subirImagenesModal'));
    }
    
    // Acciones rápidas desde dashboard
    document.querySelectorAll('.action-card').forEach(card => {
        card.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            handleQuickAction(action);
        });
    });
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
        
        // Limpiar formulario si existe
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
    }
}

function handleQuickAction(action) {
    switch(action) {
        case 'nuevo-producto':
            openModal('nuevoProductoModal');
            break;
        case 'gestionar-pedidos':
            navigateToPage('pedidos');
            break;
        case 'control-inventario':
            navigateToPage('inventario');
            break;
        case 'ver-reportes':
            navigateToPage('reportes');
            break;
        case 'gestionar-clientes':
            navigateToPage('clientes');
            break;
    }
}

// ==============================================
// FORMULARIOS
// ==============================================
function initForms() {
    // Formulario de nuevo producto
    const productoForm = document.getElementById('productoForm');
    if (productoForm) {
        productoForm.addEventListener('submit', handleProductSubmit);
    }
    
    // Formulario de subir imágenes
    const uploadImagesForm = document.getElementById('uploadImagesForm');
    if (uploadImagesForm) {
        uploadImagesForm.addEventListener('submit', handleImageUpload);
    }
    
    // Preview de imágenes
    const productImages = document.getElementById('productImages');
    if (productImages) {
        productImages.addEventListener('change', previewProductImages);
    }
    
    const uploadImagesInput = document.getElementById('uploadImagesInput');
    if (uploadImagesInput) {
        uploadImagesInput.addEventListener('change', previewUploadImages);
    }
}

async function handleProductSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const productData = {
        nombre: formData.get('nombre'),
        sku: formData.get('sku'),
        categoria_id: formData.get('categoria_id'),
        precio: parseFloat(formData.get('precio')),
        precio_anterior: formData.get('precio_anterior') ? parseFloat(formData.get('precio_anterior')) : null,
        stock: parseInt(formData.get('stock')) || 0,
        descripcion_corta: formData.get('descripcion_corta'),
        dimensiones: formData.get('dimensiones'),
        peso: formData.get('peso') ? parseFloat(formData.get('peso')) : null,
        producto_destacado: formData.get('producto_destacado') === 'on',
        estado: 'activo'
    };
    
    try {
        showLoading(true);
        
        // 1. Crear el producto primero
        const response = await fetch(`${API_URL}?action=productos`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(productData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            const productId = result.data.id;
            
            // 2. Subir las imágenes si existen
            const imageFiles = document.getElementById('productImages').files;
            if (imageFiles && imageFiles.length > 0) {
                await uploadProductImages(productId, imageFiles);
            }
            
            // 3. Crear página individual del producto
            await createProductPage(productId, productData);
            
            showNotification('Producto creado exitosamente', 'success');
            closeModal('nuevoProductoModal');
            
            // 4. Recargar lista de productos
            loadProducts();
        } else {
            showNotification('Error: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error al crear producto', 'error');
    } finally {
        showLoading(false);
    }
}

async function uploadProductImages(productId, files) {
    try {
        const formData = new FormData();
        formData.append('product_id', productId);
        
        // Agregar todas las imágenes al FormData
        for (let i = 0; i < files.length; i++) {
            formData.append('imagenes[]', files[i]);
            formData.append('alt_text[]', files[i].name);
        }
        
        const response = await fetch(`${API_URL}?action=upload_product_images`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log('Imágenes subidas exitosamente:', result.data);
        } else {
            console.error('Error al subir imágenes:', result.message);
            showNotification('Advertencia: Producto creado pero algunas imágenes no se pudieron subir', 'warning');
        }
    } catch (error) {
        console.error('Error al subir imágenes:', error);
        showNotification('Advertencia: Producto creado pero las imágenes no se pudieron subir', 'warning');
    }
}

async function createProductPage(productId, productData) {
    try {
        // Generar slug para la URL
        const slug = slugify(productData.nombre);
        
        // Crear contenido HTML para la página del producto
        const productPageHTML = generateProductPageHTML(productId, productData, slug);
        
        // Enviar al servidor para crear el archivo
        const response = await fetch('api.php?action=create_product_page', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                slug: slug,
                html_content: productPageHTML
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log('Página del producto creada:', result.data.url);
        }
    } catch (error) {
        console.error('Error al crear página del producto:', error);
    }
}

function generateProductPageHTML(productId, productData, slug) {
    return `<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${productData.nombre} - Hai Swimwear</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .product-detail-container {
            max-width: 1200px;
            margin: 80px auto 40px;
            padding: 40px 20px;
        }
        
        .product-detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            margin-bottom: 60px;
        }
        
        .product-images {
            position: sticky;
            top: 100px;
        }
        
        .main-product-image {
            width: 100%;
            aspect-ratio: 1;
            background: #f8f8f8;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .main-product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 20px 0;
        }
        
        .product-category {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .product-title {
            font-size: 36px;
            font-weight: 600;
            color: #000;
            margin-bottom: 20px;
            font-family: 'Playfair Display', serif;
        }
        
        .product-price-section {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .product-price {
            font-size: 32px;
            font-weight: 700;
            color: #000;
        }
        
        .product-price-original {
            font-size: 24px;
            color: #999;
            text-decoration: line-through;
        }
        
        .product-discount {
            background: #e63946;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .product-description {
            font-size: 16px;
            line-height: 1.8;
            color: #666;
            margin-bottom: 30px;
        }
        
        .product-stock {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .stock-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #10b981;
        }
        
        .stock-indicator.low {
            background: #f59e0b;
        }
        
        .stock-indicator.out {
            background: #ef4444;
        }
        
        .product-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .btn-add-cart {
            flex: 1;
            background: #000;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .btn-add-cart:hover {
            background: #333;
        }
        
        .btn-add-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .btn-whatsapp {
            background: #25D366;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .btn-whatsapp:hover {
            background: #1da851;
        }
        
        .product-specs {
            border-top: 1px solid #eee;
            padding-top: 30px;
        }
        
        .spec-row {
            display: flex;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .spec-label {
            flex: 0 0 150px;
            font-weight: 600;
            color: #333;
        }
        
        .spec-value {
            flex: 1;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .product-images {
                position: relative;
                top: 0;
            }
            
            .product-title {
                font-size: 28px;
            }
            
            .product-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Top Info Bar -->
    <div class="top-info-bar">
        <div class="container">
            <div class="info-left">
                <span>PAGA EN 12 CUOTAS SIN INTERÉS</span>
            </div>
            <div class="info-center">
                <span>Envío RM a $3.490 y REGIONES a $6.390</span>
            </div>
            <div class="info-right">
                <span>RETIRO EN TIENDA GRATIS</span>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="main-nav">
        <div class="container">
            <div class="nav-left">
                <div class="logo">
                    <a href="../index.html">
                        <h1 class="logo-text">Hai Swimwear</h1>
                    </a>
                </div>
            </div>
            <div class="nav-center">
                <ul class="nav-links">
                    <li><a href="../index.html#inicio">Inicio</a></li>
                    <li><a href="../index.html#productos">Productos</a></li>
                    <li><a href="../index.html#colecciones">Colecciones</a></li>
                    <li><a href="../index.html#tallas">Guía de Tallas</a></li>
                    <li><a href="../index.html#contacto">Contacto</a></li>
                </ul>
            </div>
            <div class="nav-right">
                <div class="search-bar">
                    <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input type="text" placeholder="¿Qué buscas?" class="search-input">
                </div>
                <div class="nav-icons">
                    <svg class="nav-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
            </div>
        </div>
    </nav>

    <!-- Product Detail -->
    <div class="product-detail-container">
        <div class="breadcrumb">
            <a href="../index.html">Home</a> / <a href="../index.html#productos">Productos</a> / <span>${productData.nombre}</span>
        </div>
        
        <div class="product-detail-grid">
            <div class="product-images">
                <div class="main-product-image">
                    <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="${productData.nombre}" id="mainProductImage">
                </div>
            </div>
            
            <div class="product-info">
                <div class="product-category">Hai Swimwear</div>
                <h1 class="product-title">${productData.nombre}</h1>
                
                <div class="product-price-section">
                    <span class="product-price">$${formatCurrency(productData.precio)}</span>
                    ${productData.precio_anterior ? `
                    <span class="product-price-original">$${formatCurrency(productData.precio_anterior)}</span>
                    <span class="product-discount">-${Math.round(((productData.precio_anterior - productData.precio) / productData.precio_anterior) * 100)}%</span>
                    ` : ''}
                </div>
                
                ${productData.descripcion_corta ? `
                <div class="product-description">
                    <p>${productData.descripcion_corta}</p>
                </div>
                ` : ''}
                
                <div class="product-stock">
                    <span class="stock-indicator ${productData.stock === 0 ? 'out' : productData.stock < 10 ? 'low' : ''}"></span>
                    <span>${productData.stock === 0 ? 'Agotado' : productData.stock < 10 ? `Solo quedan ${productData.stock} unidades` : 'Disponible'}</span>
                </div>
                
                <div class="product-actions">
                    <button class="btn-add-cart" ${productData.stock === 0 ? 'disabled' : ''}>
                        ${productData.stock === 0 ? 'AGOTADO' : 'AGREGAR AL CARRITO'}
                    </button>
                    <button class="btn-whatsapp" onclick="contactWhatsApp()">
                        <i class="fab fa-whatsapp"></i> CONSULTAR
                    </button>
                </div>
                
                <div class="product-specs">
                    <h3 style="margin-bottom: 20px; font-size: 20px;">Especificaciones</h3>
                    ${productData.sku ? `
                    <div class="spec-row">
                        <span class="spec-label">SKU:</span>
                        <span class="spec-value">${productData.sku}</span>
                    </div>
                    ` : ''}
                    ${productData.dimensiones ? `
                    <div class="spec-row">
                        <span class="spec-label">Dimensiones:</span>
                        <span class="spec-value">${productData.dimensiones}</span>
                    </div>
                    ` : ''}
                    ${productData.peso ? `
                    <div class="spec-row">
                        <span class="spec-label">Peso:</span>
                        <span class="spec-value">${productData.peso} kg</span>
                    </div>
                    ` : ''}
                    <div class="spec-row">
                        <span class="spec-label">Stock:</span>
                        <span class="spec-value">${productData.stock} unidades</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3 class="footer-logo">Hai Swimwear</h3>
                    <p>Trajes de baño diseñados especialmente para mujeres con busto grande. Comodidad, estilo y soporte perfecto.</p>
                </div>
                <div class="footer-section">
                    <h4>Información</h4>
                    <ul>
                        <li><a href="../index.html#tallas">Guía de Tallas</a></li>
                        <li><a href="../index.html#envios">Envíos y Devoluciones</a></li>
                        <li><a href="../index.html#preguntas">Preguntas Frecuentes</a></li>
                        <li><a href="../index.html#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#terminos">Términos y Condiciones</a></li>
                        <li><a href="#privacidad">Política de Privacidad</a></li>
                        <li><a href="#cambios">Política de Cambios</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Síguenos</h4>
                    <div class="social-links">
                        <a href="#" aria-label="Instagram">Instagram</a>
                        <a href="#" aria-label="Facebook">Facebook</a>
                        <a href="#" aria-label="TikTok">TikTok</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Hai Swimwear. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        function contactWhatsApp() {
            const message = encodeURIComponent('Hola! Estoy interesado/a en el producto: ${productData.nombre}');
            window.open('https://wa.me/56912345678?text=' + message, '_blank');
        }
        
        // Cargar imagen del producto desde la API
        fetch('../api.php?action=productos&id=${productId}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.imagen_principal) {
                    document.getElementById('mainProductImage').src = data.data.imagen_principal;
                }
            })
            .catch(error => console.error('Error loading product image:', error));
    </script>
</body>
</html>`;
}

async function handleImageUpload(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=imagenes`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Imagen subida exitosamente', 'success');
            closeModal('subirImagenesModal');
            loadImages();
        } else {
            showNotification('Error: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error al subir imagen', 'error');
    } finally {
        showLoading(false);
    }
}

function previewProductImages(e) {
    const files = e.target.files;
    const previewContainer = document.getElementById('uploadedImages');
    previewContainer.innerHTML = '';
    
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('div');
            img.className = 'preview-image';
            img.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
                <span class="preview-label">${index === 0 ? 'Principal' : `Imagen ${index + 1}`}</span>
            `;
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

function previewUploadImages(e) {
    const files = e.target.files;
    const previewContainer = document.getElementById('imagePreview');
    previewContainer.innerHTML = '';
    
    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('div');
            img.className = 'preview-image';
            img.innerHTML = `
                <img src="${e.target.result}" alt="Preview ${index + 1}">
            `;
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

// ==============================================
// CARGA DE DATOS
// ==============================================
async function loadDashboardData() {
    try {
        const response = await fetch(`${API_URL}?action=stats`);
        const result = await response.json();
        
        if (result.success) {
            allStats = result.data;
            updateDashboardStats(result.data);
        }
    } catch (error) {
        console.error('Error al cargar estadísticas:', error);
    }
}

function updateDashboardStats(stats) {
    // Actualizar estadísticas principales
    document.getElementById('total-productos').textContent = stats.total_productos || 0;
    document.getElementById('pedidos-activos').textContent = stats.pedidos_activos || 0;
    document.getElementById('clientes-registrados').textContent = stats.clientes_registrados || 0;
    document.getElementById('ventas-mes').textContent = '$' + formatCurrency(stats.ventas_mes || 0);
    
    // Actualizar badges en el sidebar
    document.getElementById('productos-badge').textContent = stats.total_productos || 0;
    document.getElementById('pedidos-badge').textContent = stats.pedidos_nuevos || 0;
    document.getElementById('mensajes-badge').textContent = stats.mensajes_nuevos || 0;
    document.getElementById('cotizaciones-badge').textContent = stats.cotizaciones_pendientes || 0;
}

async function loadProducts() {
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=productos`);
        const result = await response.json();
        
        if (result.success) {
            allProducts = result.data;
            displayProducts(result.data);
            updateProductStats(result.data);
        }
    } catch (error) {
        console.error('Error al cargar productos:', error);
        showNotification('Error al cargar productos', 'error');
    } finally {
        showLoading(false);
    }
}

function displayProducts(products) {
    const tbody = document.getElementById('productosTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (products.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" style="text-align: center; padding: 40px;">
                    <p style="color: #999; font-size: 16px;">No hay productos registrados</p>
                    <button class="btn btn-primary" onclick="openModal('nuevoProductoModal')" style="margin-top: 20px;">
                        <i class="fas fa-plus"></i> Agregar Primer Producto
                    </button>
                </td>
            </tr>
        `;
        return;
    }
    
    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="checkbox" class="product-checkbox" data-id="${product.id}"></td>
            <td>
                <div class="product-image-thumb">
                    ${product.imagen_principal ? 
                        `<img src="${product.imagen_principal}" alt="${product.nombre}">` : 
                        '<div class="no-image">Sin imagen</div>'}
                </div>
            </td>
            <td><strong>${product.nombre}</strong></td>
            <td>${product.sku || '-'}</td>
            <td>${product.categoria_nombre || '-'}</td>
            <td>$${formatCurrency(product.precio)}</td>
            <td>
                <span class="stock-badge ${product.stock === 0 ? 'out' : product.stock < 10 ? 'low' : 'ok'}">
                    ${product.stock}
                </span>
            </td>
            <td>
                <span class="status-badge ${product.estado}">${product.estado}</span>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn-icon" onclick="editProduct(${product.id})" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-icon" onclick="viewProduct('${product.slug || product.id}')" title="Ver">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon danger" onclick="deleteProduct(${product.id})" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateProductStats(products) {
    const total = products.length;
    const activos = products.filter(p => p.estado === 'activo').length;
    const agotados = products.filter(p => p.stock === 0).length;
    const bajoStock = products.filter(p => p.stock > 0 && p.stock < 10).length;
    
    const elements = {
        'total-productos-mini': total,
        'productos-activos-mini': activos,
        'productos-agotados-mini': agotados,
        'productos-bajo-stock-mini': bajoStock
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    });
}

async function loadCategories() {
    try {
        const response = await fetch(`${API_URL}?action=categorias`);
        const result = await response.json();
        
        if (result.success) {
            allCategories = result.data;
            updateCategorySelects(result.data);
        }
    } catch (error) {
        console.error('Error al cargar categorías:', error);
    }
}

function updateCategorySelects(categories) {
    const selects = document.querySelectorAll('#modalCategoria, #filterCategoria');
    
    selects.forEach(select => {
        // Guardar la opción por defecto
        const firstOption = select.querySelector('option:first-child');
        select.innerHTML = '';
        if (firstOption) {
            select.appendChild(firstOption);
        }
        
        // Agregar categorías
        categories.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.nombre;
            select.appendChild(option);
        });
    });
}

async function loadCategoriesPage() {
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=categorias`);
        const result = await response.json();
        
        if (result.success) {
            displayCategoriesGrid(result.data);
        }
    } catch (error) {
        console.error('Error al cargar categorías:', error);
    } finally {
        showLoading(false);
    }
}

function displayCategoriesGrid(categories) {
    const grid = document.getElementById('categoriesGrid');
    if (!grid) return;
    
    grid.innerHTML = '';
    
    categories.forEach(cat => {
        const card = document.createElement('div');
        card.className = 'category-card';
        card.innerHTML = `
            <h3>${cat.nombre}</h3>
            <p>${cat.descripcion || 'Sin descripción'}</p>
            <div class="category-stats">
                <span>Orden: ${cat.orden}</span>
            </div>
            <div class="category-actions">
                <button class="btn btn-secondary btn-sm">Editar</button>
                <button class="btn btn-danger btn-sm">Eliminar</button>
            </div>
        `;
        grid.appendChild(card);
    });
}

async function loadOrders() {
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=pedidos`);
        const result = await response.json();
        
        if (result.success) {
            displayOrders(result.data);
            updateOrderStats(result.data);
        }
    } catch (error) {
        console.error('Error al cargar pedidos:', error);
    } finally {
        showLoading(false);
    }
}

function displayOrders(orders) {
    const tbody = document.getElementById('pedidosTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (orders.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <p style="color: #999; font-size: 16px;">No hay pedidos registrados</p>
                </td>
            </tr>
        `;
        return;
    }
    
    orders.forEach(order => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>#${order.id}</strong></td>
            <td>${order.cliente_nombre || 'Cliente'}</td>
            <td>${order.numero_items || 1} items</td>
            <td>${formatDate(order.fecha_pedido)}</td>
            <td>$${formatCurrency(order.total)}</td>
            <td><span class="status-badge ${order.estado_pedido}">${order.estado_pedido}</span></td>
            <td><span class="status-badge ${order.estado_pago}">${order.estado_pago}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn-icon" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateOrderStats(orders) {
    const total = orders.length;
    const nuevos = orders.filter(o => o.estado_pedido === 'nuevo').length;
    const proceso = orders.filter(o => o.estado_pedido === 'en_proceso').length;
    const pendientes = orders.filter(o => o.estado_pedido === 'pendiente').length;
    
    const elements = {
        'total-pedidos': total,
        'pedidos-nuevos': nuevos,
        'pedidos-proceso': proceso,
        'pedidos-pendientes': pendientes
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    });
}

async function loadClients() {
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=clientes`);
        const result = await response.json();
        
        if (result.success) {
            displayClients(result.data);
        }
    } catch (error) {
        console.error('Error al cargar clientes:', error);
    } finally {
        showLoading(false);
    }
}

function displayClients(clients) {
    const tbody = document.getElementById('clientesTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (clients.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <p style="color: #999; font-size: 16px;">No hay clientes registrados</p>
                </td>
            </tr>
        `;
        return;
    }
    
    clients.forEach(client => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>${client.nombre || client.email}</strong></td>
            <td>${client.email}</td>
            <td>${client.telefono || '-'}</td>
            <td>${client.total_pedidos || 0}</td>
            <td>$${formatCurrency(client.total_gastado || 0)}</td>
            <td><span class="status-badge ${client.estado || 'activo'}">${client.estado || 'activo'}</span></td>
            <td>
                <div class="action-buttons">
                    <button class="btn-icon" title="Ver perfil">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

async function loadMessages() {
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=mensajes`);
        const result = await response.json();
        
        if (result.success) {
            displayMessages(result.data);
            updateMessageStats(result.data);
        }
    } catch (error) {
        console.error('Error al cargar mensajes:', error);
    } finally {
        showLoading(false);
    }
}

function displayMessages(messages) {
    const container = document.getElementById('messagesContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    if (messages.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <p style="color: #999; font-size: 16px;">No hay mensajes</p>
            </div>
        `;
        return;
    }
    
    messages.forEach(msg => {
        const card = document.createElement('div');
        card.className = 'message-card';
        card.innerHTML = `
            <div class="message-header">
                <div>
                    <strong>${msg.nombre || msg.email}</strong>
                    <span class="message-date">${formatDate(msg.fecha_creacion)}</span>
                </div>
                <span class="status-badge ${msg.estado}">${msg.estado}</span>
            </div>
            <div class="message-body">
                <p><strong>Asunto:</strong> ${msg.asunto || 'Sin asunto'}</p>
                <p>${msg.mensaje}</p>
            </div>
            <div class="message-footer">
                <button class="btn btn-secondary btn-sm">Marcar como leído</button>
                <button class="btn btn-primary btn-sm">Responder</button>
            </div>
        `;
        container.appendChild(card);
    });
}

function updateMessageStats(messages) {
    const total = messages.length;
    const nuevos = messages.filter(m => !m.leido).length;
    const pendientes = messages.filter(m => m.estado === 'pendiente').length;
    const respondidos = messages.filter(m => m.estado === 'respondido').length;
    
    const elements = {
        'total-mensajes': total,
        'mensajes-nuevos': nuevos,
        'mensajes-pendientes': pendientes,
        'mensajes-respondidos': respondidos
    };
    
    Object.entries(elements).forEach(([id, value]) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    });
}

async function loadQuotations() {
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=cotizaciones`);
        const result = await response.json();
        
        if (result.success) {
            displayQuotations(result.data);
        }
    } catch (error) {
        console.error('Error al cargar cotizaciones:', error);
    } finally {
        showLoading(false);
    }
}

function displayQuotations(quotations) {
    const tbody = document.getElementById('cotizacionesTableBody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    if (quotations.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    <p style="color: #999; font-size: 16px;">No hay cotizaciones registradas</p>
                </td>
            </tr>
        `;
        return;
    }
    
    quotations.forEach(quote => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><strong>#${quote.id}</strong></td>
            <td>${quote.cliente_nombre || 'Cliente'}</td>
            <td>${formatDate(quote.fecha_creacion)}</td>
            <td>${quote.numero_items || 0} items</td>
            <td>$${formatCurrency(quote.total)}</td>
            <td><span class="status-badge ${quote.estado}">${quote.estado}</span></td>
            <td>${formatDate(quote.fecha_vencimiento)}</td>
            <td>
                <div class="action-buttons">
                    <button class="btn-icon" title="Ver">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-icon" title="Descargar PDF">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

async function loadImages() {
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=imagenes`);
        const result = await response.json();
        
        if (result.success) {
            displayImages(result.data);
        }
    } catch (error) {
        console.error('Error al cargar imágenes:', error);
    } finally {
        showLoading(false);
    }
}

function displayImages(images) {
    const grid = document.getElementById('imagesGrid');
    if (!grid) return;
    
    grid.innerHTML = '';
    
    if (images.length === 0) {
        grid.innerHTML = `
            <div style="text-align: center; padding: 40px; grid-column: 1 / -1;">
                <p style="color: #999; font-size: 16px;">No hay imágenes subidas</p>
                <button class="btn btn-primary" onclick="openModal('subirImagenesModal')" style="margin-top: 20px;">
                    <i class="fas fa-upload"></i> Subir Primera Imagen
                </button>
            </div>
        `;
        return;
    }
    
    images.forEach(img => {
        const card = document.createElement('div');
        card.className = 'image-card';
        card.innerHTML = `
            <div class="image-preview">
                <img src="${img.url}" alt="${img.titulo || 'Imagen'}">
            </div>
            <div class="image-info">
                <h4>${img.titulo || 'Sin título'}</h4>
                <p><span class="badge">${img.tipo}</span></p>
                <div class="image-actions">
                    <button class="btn-icon" title="Copiar URL" onclick="copyToClipboard('${img.url}')">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button class="btn-icon danger" title="Eliminar">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

// ==============================================
// ACCIONES DE PRODUCTOS
// ==============================================
function editProduct(id) {
    // TODO: Implementar edición de producto
    showNotification('Función de edición en desarrollo', 'info');
}

function viewProduct(slug) {
    window.open(`productos/${slug}.html`, '_blank');
}

async function deleteProduct(id) {
    if (!confirm('¿Estás seguro de eliminar este producto?')) {
        return;
    }
    
    try {
        showLoading(true);
        const response = await fetch(`${API_URL}?action=productos&id=${id}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Producto eliminado', 'success');
            loadProducts();
        } else {
            showNotification('Error al eliminar: ' + result.message, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error al eliminar producto', 'error');
    } finally {
        showLoading(false);
    }
}

// ==============================================
// UTILIDADES
// ==============================================
function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('es-CL', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(amount);
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-CL', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('URL copiada al portapapeles', 'success');
    }).catch(() => {
        showNotification('Error al copiar URL', 'error');
    });
}

function showLoading(show) {
    let loader = document.getElementById('globalLoader');
    
    if (!loader) {
        loader = document.createElement('div');
        loader.id = 'globalLoader';
        loader.className = 'global-loader';
        loader.innerHTML = '<div class="loader-spinner"></div>';
        document.body.appendChild(loader);
    }
    
    loader.style.display = show ? 'flex' : 'none';
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
