// Hero Carousel Functionality
let currentSlide = 0;
let carouselInterval;
let slides = document.querySelectorAll('.carousel-slide');
let indicators = document.querySelectorAll('.indicator');
const prevBtn = document.querySelector('.carousel-btn-prev');
const nextBtn = document.querySelector('.carousel-btn-next');

function showSlide(index) {
    // Actualizar referencias por si el DOM cambió
    slides = document.querySelectorAll('.carousel-slide');
    indicators = document.querySelectorAll('.indicator');

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
    slides = document.querySelectorAll('.carousel-slide');
    if (slides.length === 0) return;
    const next = (currentSlide + 1) % slides.length;
    showSlide(next);
}

function prevSlide() {
    slides = document.querySelectorAll('.carousel-slide');
    if (slides.length === 0) return;
    const prev = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(prev);
}

function startCarousel() {
    stopCarousel(); // Asegurar que no haya múltiples intervalos
    if (document.querySelectorAll('.carousel-slide').length > 1) {
        carouselInterval = setInterval(nextSlide, 5000);
    }
}

function stopCarousel() {
    if (carouselInterval) clearInterval(carouselInterval);
}

function initCarouselLogic() {
    slides = document.querySelectorAll('.carousel-slide');
    indicators = document.querySelectorAll('.indicator');
    
    // Event listeners for carousel controls
    // Removemos listeners antiguos clonando y reemplazando si es necesario, 
    // pero como los botones son estáticos fuera del contenedor dinámico, está bien dejarlos.
    // Solo aseguramos que los botones existan.

    if (nextBtn) {
        // Usamos una propiedad personalizada para evitar listeners duplicados si llamamos init varias veces
        if (!nextBtn.hasAttribute('data-initialized')) {
            nextBtn.addEventListener('click', () => {
                stopCarousel();
                nextSlide();
                startCarousel();
            });
            nextBtn.setAttribute('data-initialized', 'true');
        }
    }

    if (prevBtn) {
        if (!prevBtn.hasAttribute('data-initialized')) {
            prevBtn.addEventListener('click', () => {
                stopCarousel();
                prevSlide();
                startCarousel();
            });
            prevBtn.setAttribute('data-initialized', 'true');
        }
    }

    // Pause carousel on hover
    const carousel = document.querySelector('.hero-carousel');
    if (carousel && !carousel.hasAttribute('data-initialized')) {
        carousel.addEventListener('mouseenter', stopCarousel);
        carousel.addEventListener('mouseleave', startCarousel);
        carousel.setAttribute('data-initialized', 'true');
    }

    // Initialize carousel
    if (slides.length > 0) {
        showSlide(0);
        startCarousel();
    }
}

// Carga dinámica de imágenes
async function loadDynamicImages() {
    try {
        // Cargar Logo
        const logoResponse = await fetch('api.php?action=imagenes&tipo=logos');
        const logoData = await logoResponse.json();
        
        if (logoData.success && logoData.data && logoData.data.length > 0) {
            const logoContainer = document.getElementById('main-logo-container');
            if (logoContainer) {
                // Usamos el logo más reciente
                const logo = logoData.data[0];
                // Aseguramos que la URL sea correcta (asumiendo que 'uploads/' es accesible desde raíz)
                const logoUrl = logo.url; 
                logoContainer.innerHTML = `<img src="${logoUrl}" alt="Hai Swimwear" class="logo-image" style="max-height: 60px; width: auto;">`;
            }
        }

        // Cargar Banners
        const bannersResponse = await fetch('api.php?action=imagenes&tipo=banners');
        const bannersData = await bannersResponse.json();

        if (bannersData.success && bannersData.data && bannersData.data.length > 0) {
            const carouselContainer = document.getElementById('hero-carousel-container');
            const indicatorsContainer = document.querySelector('.carousel-indicators');
            
            if (carouselContainer) {
                let slidesHtml = '';
                let indicatorsHtml = '';
                
                bannersData.data.forEach((banner, index) => {
                    const activeClass = index === 0 ? 'active' : '';
                    const bannerUrl = banner.url;
                    
                    // Construir slide
                    slidesHtml += `
                        <div class="carousel-slide ${activeClass}" style="background-image: url('${bannerUrl}');">
                            <div class="hero-content">
                                <h2 class="hero-title">${banner.titulo || 'Hai Swimwear'}</h2>
                                <p class="hero-subtitle">${banner.descripcion || 'Colección Exclusiva'}</p>
                                <p class="hero-cta">Descubre más</p>
                                <button class="cta-button" onclick="window.location.href='productos.html'">VER COLECCIÓN</button>
                            </div>
                            <div class="hero-overlay"></div>
                        </div>
                    `;
                    
                    // Construir indicador
                    indicatorsHtml += `
                        <button class="indicator ${activeClass}" data-slide="${index}" aria-label="Slide ${index + 1}"></button>
                    `;
                });

                carouselContainer.innerHTML = slidesHtml;
                
                if (indicatorsContainer) {
                    indicatorsContainer.innerHTML = indicatorsHtml;
                    // Re-adjuntar eventos a los nuevos indicadores
                    const newIndicators = indicatorsContainer.querySelectorAll('.indicator');
                    newIndicators.forEach((indicator, index) => {
                        indicator.addEventListener('click', () => {
                            stopCarousel();
                            showSlide(index);
                            startCarousel();
                        });
                    });
                }
            }
        }

        // Reinicializar lógica del carrusel con los nuevos elementos
        initCarouselLogic();

    } catch (error) {
        console.error('Error cargando imágenes dinámicas:', error);
        // Si falla, inicializamos con lo que haya (contenido estático original)
        initCarouselLogic();
    }
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

// Filter functionality
const filterCheckboxes = document.querySelectorAll('.filter-checkbox input[type="checkbox"]');
const productCards = document.querySelectorAll('.product-card');

function filterProducts() {
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

            if (groupTitle === 'Tipo') {
                activeFilters.tipo.push(filterValue);
            } else if (groupTitle === 'Talla') {
                activeFilters.talla.push(filterValue);
            } else if (groupTitle === 'Soporte') {
                activeFilters.soporte.push(filterValue);
            }
        }
    });

    // Filtrar productos (ejemplo básico - puedes expandir esta lógica)
    productCards.forEach(card => {
        let shouldShow = true;
        const productName = card.querySelector('.product-name').textContent.toLowerCase();

        // Filtrar por tipo
        if (activeFilters.tipo.length > 0) {
            const matchesType = activeFilters.tipo.some(tipo => {
                const tipoLower = tipo.toLowerCase();
                return productName.includes(tipoLower) || 
                       (tipoLower.includes('bikini') && productName.includes('bikini')) ||
                       (tipoLower.includes('traje') && productName.includes('traje'));
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
        const productsContainer = document.querySelector('.products-grid');
        const products = Array.from(productCards);

        products.sort((a, b) => {
            const priceA = parseFloat(a.querySelector('.price-sale').textContent.replace(/[^0-9]/g, ''));
            const priceB = parseFloat(b.querySelector('.price-sale').textContent.replace(/[^0-9]/g, ''));
            const nameA = a.querySelector('.product-name').textContent;
            const nameB = b.querySelector('.product-name').textContent;

            switch(sortValue) {
                case 'Precio: Menor a Mayor':
                    return priceA - priceB;
                case 'Precio: Mayor a Menor':
                    return priceB - priceA;
                case 'Nombre A-Z':
                    return nameA.localeCompare(nameB);
                case 'Nombre Z-A':
                    return nameB.localeCompare(nameA);
                default:
                    return 0;
            }
        });

        products.forEach(product => productsContainer.appendChild(product));
    });
}

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

// Product card click functionality
productCards.forEach(card => {
    card.addEventListener('click', function() {
        const productName = this.querySelector('.product-name').textContent;
        console.log('Producto seleccionado:', productName);
        // Aquí puedes agregar la lógica para abrir el detalle del producto
        // Ejemplo: window.location.href = `/producto/${encodeURIComponent(productName)}`;
    });
});

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
    loadDynamicImages(); // Inicia la carga dinámica de imágenes y luego el carrusel
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
