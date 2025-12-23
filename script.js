// Hero Carousel Functionality
let currentSlide = 0;
let carouselInterval;
const slides = document.querySelectorAll('.carousel-slide');
const indicators = document.querySelectorAll('.indicator');
const prevBtn = document.querySelector('.carousel-btn-prev');
const nextBtn = document.querySelector('.carousel-btn-next');

function showSlide(index) {
    // Remove active class from all slides and indicators
    slides.forEach(slide => slide.classList.remove('active'));
    indicators.forEach(indicator => indicator.classList.remove('active'));
    
    // Add active class to current slide and indicator
    if (slides[index]) {
        slides[index].classList.add('active');
    }
    if (indicators[index]) {
        indicators[index].classList.add('active');
    }
    
    currentSlide = index;
}

function nextSlide() {
    const next = (currentSlide + 1) % slides.length;
    showSlide(next);
}

function prevSlide() {
    const prev = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(prev);
}

function startCarousel() {
    carouselInterval = setInterval(nextSlide, 5000); // Cambia cada 5 segundos
}

function stopCarousel() {
    clearInterval(carouselInterval);
}

// Event listeners for carousel controls
if (nextBtn) {
    nextBtn.addEventListener('click', () => {
        stopCarousel();
        nextSlide();
        startCarousel();
    });
}

if (prevBtn) {
    prevBtn.addEventListener('click', () => {
        stopCarousel();
        prevSlide();
        startCarousel();
    });
}

// Event listeners for indicators
indicators.forEach((indicator, index) => {
    indicator.addEventListener('click', () => {
        stopCarousel();
        showSlide(index);
        startCarousel();
    });
});

// Pause carousel on hover
const carousel = document.querySelector('.hero-carousel');
if (carousel) {
    carousel.addEventListener('mouseenter', stopCarousel);
    carousel.addEventListener('mouseleave', startCarousel);
}

// Initialize carousel
if (slides.length > 0) {
    showSlide(0);
    startCarousel();
}

// Smooth scroll for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Search functionality
const searchInput = document.querySelector('.search-input');
const searchIcon = document.querySelector('.search-icon');

if (searchInput) {
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.trim();
            if (searchTerm) {
                // Aquí puedes agregar la lógica de búsqueda
                console.log('Buscando:', searchTerm);
                // Ejemplo: window.location.href = `/buscar?q=${encodeURIComponent(searchTerm)}`;
            }
        }
    });
}

// Load Products from API
async function loadProducts() {
    const container = document.getElementById('products-container');
    const loadingElement = container.querySelector('.loading-products');
    
    try {
        const response = await fetch('api.php?action=productos');
        const result = await response.json();
        
        if (result.success && result.data) {
            // Limpiar contenedor
            container.innerHTML = '';
            
            // Actualizar contador
            const countElement = document.querySelector('.product-count');
            if (countElement) {
                countElement.textContent = `(${result.data.length} Productos)`;
            }

            if (result.data.length === 0) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 40px;">No hay productos disponibles.</div>';
                return;
            }

            // Renderizar productos
            result.data.forEach(product => {
                const card = createProductCard(product);
                container.appendChild(card);
            });
            
            // Inicializar eventos de productos después de cargarlos
            initProductEvents();
            
        } else {
            throw new Error(result.message || 'Error al cargar productos');
        }
    } catch (error) {
        console.error('Error:', error);
        if (loadingElement) {
            loadingElement.innerHTML = '<p style="color: red;">Error al cargar los productos. Por favor intenta nuevamente.</p>';
        }
    }
}

function createProductCard(product) {
    const card = document.createElement('div');
    card.className = 'product-card';
    card.dataset.id = product.id;
    card.dataset.category = product.categoria_nombre || '';
    
    // Calcular descuento si existe precio anterior
    let badgeHtml = '';
    if (product.precio_anterior && parseFloat(product.precio_anterior) > parseFloat(product.precio)) {
        const descuento = Math.round((1 - parseFloat(product.precio) / parseFloat(product.precio_anterior)) * 100);
        badgeHtml = `<div class="sale-badge">-${descuento}%</div>`;
    } else if (product.producto_destacado) {
        badgeHtml = `<div class="sale-badge" style="background-color: #000;">HOT</div>`;
    }

    // Imagen
    const imageHtml = product.imagen_principal 
        ? `<img src="${product.imagen_principal}" alt="${product.nombre}" style="width: 100%; height: 100%; object-fit: cover;">`
        : `<div class="product-placeholder">${product.nombre}</div>`;

    // Formatear precio
    const precio = new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(product.precio);
    const precioAnterior = product.precio_anterior 
        ? `<span class="price-original">${new Intl.NumberFormat('es-CL', { style: 'currency', currency: 'CLP' }).format(product.precio_anterior)}</span>`
        : '';

    card.innerHTML = `
        <div class="product-image">
            ${badgeHtml}
            ${imageHtml}
        </div>
        <div class="product-info">
            <h3 class="product-name">${product.nombre}</h3>
            <div class="product-price">
                ${precioAnterior}
                <span class="price-sale">${precio}</span>
            </div>
        </div>
    `;

    return card;
}

function initProductEvents() {
    // Filter functionality
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox input[type="checkbox"]');
    
    function filterProducts() {
        const productCards = document.querySelectorAll('.product-card');
        const activeFilters = {
            tipo: [],
            talla: [],
            soporte: []
        };

        filterCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const filterGroup = checkbox.closest('.filter-group');
                const groupTitle = filterGroup.querySelector('.filter-group-title').textContent.trim();
                const filterValue = checkbox.nextElementSibling.textContent.trim();

                if (groupTitle === 'Tipo') activeFilters.tipo.push(filterValue);
                // Aquí podrías agregar lógica para Talla y Soporte si esos datos vienen de la BD
            }
        });

        productCards.forEach(card => {
            let shouldShow = true;
            const productName = card.querySelector('.product-name').textContent.toLowerCase();
            const category = card.dataset.category.toLowerCase();

            // Filtrar por tipo (usando nombre o categoría)
            if (activeFilters.tipo.length > 0) {
                const matchesType = activeFilters.tipo.some(tipo => {
                    const tipoLower = tipo.toLowerCase();
                    return productName.includes(tipoLower) || category.includes(tipoLower);
                });
                if (!matchesType) shouldShow = false;
            }

            card.style.display = shouldShow ? 'block' : 'none';
        });
    }

    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', filterProducts);
    });

    // Sort functionality
    const sortSelect = document.querySelector('.sort-select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            const productsContainer = document.getElementById('products-container');
            const products = Array.from(document.querySelectorAll('.product-card'));

            products.sort((a, b) => {
                const priceA = parseFloat(a.querySelector('.price-sale').textContent.replace(/[^0-9]/g, ''));
                const priceB = parseFloat(b.querySelector('.price-sale').textContent.replace(/[^0-9]/g, ''));
                const nameA = a.querySelector('.product-name').textContent;
                const nameB = b.querySelector('.product-name').textContent;

                switch(sortValue) {
                    case 'Precio: Menor a Mayor': return priceA - priceB;
                    case 'Precio: Mayor a Menor': return priceB - priceA;
                    case 'Nombre A-Z': return nameA.localeCompare(nameB);
                    case 'Nombre Z-A': return nameB.localeCompare(nameA);
                    default: return 0;
                }
            });

            products.forEach(product => productsContainer.appendChild(product));
        });
    }

    // Product card click functionality
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function() {
            const productName = this.querySelector('.product-name').textContent;
            console.log('Producto seleccionado:', productName, 'ID:', this.dataset.id);
            // Redirección futura: window.location.href = `producto.php?id=${this.dataset.id}`;
        });
    });
}

// Inicializar carga de productos
document.addEventListener('DOMContentLoaded', loadProducts);

// CTA Button functionality
const ctaButton = document.querySelector('.cta-button');
if (ctaButton) {
    ctaButton.addEventListener('click', function() {
        const productsSection = document.getElementById('productos');
        if (productsSection) {
            productsSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
}

// Navbar scroll effect
let lastScroll = 0;
const navbar = document.querySelector('.main-nav');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.2)';
    } else {
        navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
    }
    
    lastScroll = currentScroll;
});

// Mobile menu toggle (para futuras mejoras)
function initMobileMenu() {
    const navLinks = document.querySelector('.nav-links');
    const navRight = document.querySelector('.nav-right');
    
    // Crear botón de menú móvil si no existe
    if (window.innerWidth <= 768 && !document.querySelector('.mobile-menu-toggle')) {
        const menuToggle = document.createElement('button');
        menuToggle.className = 'mobile-menu-toggle';
        menuToggle.innerHTML = '☰';
        menuToggle.style.cssText = 'background: none; border: none; color: white; font-size: 24px; cursor: pointer;';
        
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('mobile-open');
            navRight.classList.toggle('mobile-open');
        });
        
        const navLeft = document.querySelector('.nav-left');
        navLeft.appendChild(menuToggle);
    }
}

// Initialize on load
window.addEventListener('load', () => {
    initMobileMenu();
});

// Reinitialize on resize
window.addEventListener('resize', () => {
    initMobileMenu();
});

// Add loading animation
window.addEventListener('load', () => {
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s';
        document.body.style.opacity = '1';
    }, 100);
});

