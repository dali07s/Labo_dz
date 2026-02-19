// Common dashboard functionality
class DashboardBase {
    constructor() {
        this.init();
    }

    init() {
        this.handleNotifications();
        this.handleForms();
    }

    // Auto-hide notifications
    handleNotifications() {
        const notifications = document.querySelectorAll('.notification.show');
        notifications.forEach(notification => {
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        });
    }

    // Common form handling
    handleForms() {
        // Confirm delete actions
        document.querySelectorAll('form[method="POST"]').forEach(form => {
            const method = form.querySelector('input[name="_method"]');
            if (method && method.value === 'DELETE') {
                form.addEventListener('submit', (e) => {
                    if (!confirm('هل أنت متأكد من هذا الإجراء؟')) {
                        e.preventDefault();
                    }
                });
            }
        });
    }

    // Show notification
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type} show`;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.dashboardBase = new DashboardBase();
});
