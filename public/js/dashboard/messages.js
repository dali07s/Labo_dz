// Messages page functionality
class Messages {
    constructor() {
        this.handleFormSubmissions();
    }

    handleFormSubmissions() {
        // Add loading states to forms
        document.querySelectorAll('.message-form-card form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';

                    // Reset button if form submission fails
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 5000);
                }
            });
        });
    }
}

// Mark message as read
function markAsRead(messageId) {
    const messageItem = document.querySelector(`[data-message-id="${messageId}"]`);
    if (messageItem) {
        messageItem.classList.remove('unread');
        const badge = messageItem.querySelector('.unread-badge');
        if (badge) badge.remove();
    }
}

// Initialize messages page
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.messages-container')) {
        new Messages();
        window.markAsRead = markAsRead;
    }
});
