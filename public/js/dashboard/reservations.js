// Reservations page functionality
class Reservations {
    constructor() {
        this.handleStatusUpdates();
    }

    handleStatusUpdates() {
        // Add confirmation for status changes
        document.querySelectorAll('.status-select').forEach(select => {
            const originalValue = select.value;

            select.addEventListener('change', function(e) {
                const newStatus = this.value;
                const statusText = this.options[this.selectedIndex].text;

                if (newStatus !== originalValue) {
                    if (!confirm(`هل أنت متأكد من تغيير حالة الحجز إلى "${statusText}"؟`)) {
                        this.value = originalValue;
                        e.preventDefault();
                        return false;
                    }
                }
            });
        });
    }
}

// Initialize reservations page
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.filters-container')) {
        new Reservations();
    }
});
