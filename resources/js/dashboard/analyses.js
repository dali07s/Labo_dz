// Analyses page functionality
class Analyses {
    constructor() {
        this.handleImagePreview();
    }

    handleImagePreview() {
        const imageInput = document.querySelector('input[name="image"]');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        let preview = document.querySelector('.image-preview');
                        if (!preview) {
                            preview = document.createElement('div');
                            preview.className = 'image-preview';
                            preview.style.marginTop = '10px';
                            preview.innerHTML = '<p style="margin-bottom: 5px;">معاينة الصورة:</p><img src="" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">';
                            imageInput.parentElement.appendChild(preview);
                        }
                        preview.querySelector('img').src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    }
}

// Toggle description function
function toggleDescription(button) {
    const cell = button.closest('.description-cell');
    const text = cell.querySelector('.description-text');
    const fullText = text.getAttribute('data-full-text');
    const shortText = text.getAttribute('data-short-text');

    if (!fullText) {
        const currentText = text.textContent;
        text.setAttribute('data-full-text', currentText);
        text.setAttribute('data-short-text', currentText.substring(0, 50) + '...');
    }

    if (button.textContent.includes('عرض المزيد')) {
        text.textContent = text.getAttribute('data-full-text');
        button.textContent = 'عرض أقل';
    } else {
        text.textContent = text.getAttribute('data-short-text');
        button.textContent = 'عرض المزيد';
    }
}

// Initialize analyses page
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.stats-cards')) {
        new Analyses();
        window.toggleDescription = toggleDescription;
    }
});
