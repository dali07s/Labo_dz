// Login Page JavaScript
class LoginPage {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.autoFocusUsername();
        this.setupInputEffects();
    }

    // Toggle password visibility
    togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.className = 'fas fa-eye-slash';
        } else {
            passwordInput.type = 'password';
            toggleIcon.className = 'fas fa-eye';
        }
    }

    // Setup all event listeners
    setupEventListeners() {
        // Form submission
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Password toggle
        const toggleBtn = document.querySelector('.toggle-password');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => this.togglePassword());
        }

        // Enter key submission
        document.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.submitForm();
            }
        });
    }

    // Handle form submission
    handleFormSubmit(e) {
        const loginBtn = document.getElementById('loginBtn');
        this.showLoadingState(loginBtn);
        
        // Form will submit normally, this just handles the UI
    }

    // Show loading state on button
    showLoadingState(button) {
        button.classList.add('btn-loading');
        button.disabled = true;

        // Re-enable after 5 seconds in case of error
        setTimeout(() => {
            this.hideLoadingState(button);
        }, 5000);
    }

    // Hide loading state
    hideLoadingState(button) {
        button.classList.remove('btn-loading');
        button.disabled = false;
    }

    // Programmatic form submission
    submitForm() {
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.dispatchEvent(new Event('submit'));
        }
    }

    // Auto-focus on username field
    autoFocusUsername() {
        const usernameInput = document.getElementById('username');
        if (usernameInput) {
            usernameInput.focus();
        }
    }

    // Add interactive effects to inputs
    setupInputEffects() {
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });

            // Check initial state
            if (input.value) {
                input.parentElement.classList.add('focused');
            }
        });
    }

    // Show error message (can be called from other scripts)
    showError(message) {
        this.showMessage(message, 'error');
    }

    // Show success message
    showSuccess(message) {
        this.showMessage(message, 'success');
    }

    // Generic message display
    showMessage(message, type) {
        // Remove existing messages
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());

        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        
        const icon = type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle';
        alertDiv.innerHTML = `<i class="fas ${icon}"></i> ${message}`;

        // Insert after lab info or at top of form
        const labInfo = document.querySelector('.lab-info');
        if (labInfo) {
            labInfo.parentNode.insertBefore(alertDiv, labInfo.nextSibling);
        } else {
            const loginContainer = document.querySelector('.login-container');
            loginContainer.insertBefore(alertDiv, loginContainer.firstChild);
        }

        // Auto-remove after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Validate form before submission
    validateForm() {
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;

        if (!username) {
            this.showError('يرجى إدخال اسم المستخدم');
            return false;
        }

        if (!password) {
            this.showError('يرجى إدخال كلمة المرور');
            return false;
        }

        return true;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.loginPage = new LoginPage();
});

// Make functions available globally for onclick attributes
function togglePassword() {
    if (window.loginPage) {
        window.loginPage.togglePassword();
    }
}