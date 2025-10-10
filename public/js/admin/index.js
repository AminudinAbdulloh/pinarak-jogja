document.addEventListener('DOMContentLoaded', function () {
    const notifications = document.querySelectorAll('.notification');

    notifications.forEach(function (notification) {
        // Auto hide after 5 seconds
        setTimeout(function () {
            notification.classList.add('fade-out');

            // Remove from DOM after animation completes
            setTimeout(function () {
                notification.remove();
            }, 500);
        }, 5000);
    });
});

// Function to manually close notification
function closeNotification(element) {
    const notification = element.closest('.notification');
    notification.classList.add('fade-out');

    setTimeout(function () {
        notification.remove();
    }, 500);
}

function setupSearchForm(options) {
    const {
        formId,
        inputId,
        baseUrl,
        searchPath = '/search/',
        defaultPath = ''
    } = options;

    const form = document.getElementById(formId);
    const input = document.getElementById(inputId);

    if (!form || !input) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const searchValue = input.value.trim();

        if (searchValue) {
            // Ganti spasi dengan tanda plus
            const safeSearch = searchValue.replace(/\s+/g, '+');
            window.location.href = `${baseUrl}${searchPath}${encodeURIComponent(safeSearch)}`;
        } else {
            // Redirect ke path default
            window.location.href = `${baseUrl}${defaultPath}`;
        }
    });
}

/**
 * Inisialisasi search form handler
 * Gunakan data attributes pada form untuk konfigurasi
 */
function initSearchForm() {
    const searchForms = document.querySelectorAll('[data-search-form]');
    
    searchForms.forEach(form => {
        const searchInput = form.querySelector('[data-search-input]');
        const baseUrl = form.getAttribute('data-base-url') || '';
        const searchPath = form.getAttribute('data-search-path') || '/search/';
        const indexPath = form.getAttribute('data-index-path') || '';
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!searchInput) {
                console.error('Search input not found');
                return;
            }
            
            const searchValue = searchInput.value.trim();
            
            if (searchValue) {
                // Ganti spasi dengan tanda plus
                const safeSearch = searchValue.replace(/\s+/g, '+');
                
                // Redirect ke route dengan search parameter
                window.location.href = baseUrl + searchPath + encodeURIComponent(safeSearch);
            } else {
                // Jika kosong, redirect ke index
                window.location.href = baseUrl + indexPath;
            }
        });
    });
}

// Auto-initialize saat DOM ready
document.addEventListener('DOMContentLoaded', initSearchForm);