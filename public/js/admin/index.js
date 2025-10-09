document.addEventListener('DOMContentLoaded', function() {
    const notifications = document.querySelectorAll('.notification');
    
    notifications.forEach(function(notification) {
        // Auto hide after 5 seconds
        setTimeout(function() {
            notification.classList.add('fade-out');
            
            // Remove from DOM after animation completes
            setTimeout(function() {
                notification.remove();
            }, 500);
        }, 5000);
    });
});

// Function to manually close notification
function closeNotification(element) {
    const notification = element.closest('.notification');
    notification.classList.add('fade-out');
    
    setTimeout(function() {
        notification.remove();
    }, 500);
}