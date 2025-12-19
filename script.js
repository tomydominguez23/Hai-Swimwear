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

