// Dashboard page functionality
class Dashboard {
    constructor() {
        this.animateStats();
    }

    animateStats() {
        const statNumbers = document.querySelectorAll('.stat-number');

        statNumbers.forEach(stat => {
            const target = parseInt(stat.textContent);
            if (isNaN(target)) return;

            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = target;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(current);
                }
            }, 30);
        });
    }
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.stats-grid')) {
        new Dashboard();
    }
});
