// Form handling and validation
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a plan parameter in URL and pre-select it
    const urlParams = new URLSearchParams(window.location.search);
    const plan = urlParams.get('plan');
    const packageSelect = document.getElementById('package');
    
    if (plan && packageSelect) {
        packageSelect.value = plan;
        // Highlight the form to draw attention
        const formContainer = document.querySelector('.form-container');
        if (formContainer) {
            formContainer.scrollIntoView({ behavior: 'smooth' });
        }
    }
    
    // Handle form submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous error messages
            clearErrorMessages();
            
            const submitBtn = this.querySelector('.submit-btn');
            const originalText = submitBtn.textContent;
            
            // Get form data
            const originalFormData = new FormData(this);
            const name = originalFormData.get('name').trim();
            const email = originalFormData.get('email').trim();
            const car = originalFormData.get('car').trim();
            const packageSelected = originalFormData.get('package');
            
            // Client-side validation
            let hasErrors = false;
            
            if (!name) {
                showFieldError('name', 'Name is required');
                hasErrors = true;
            }
            
            if (!email) {
                showFieldError('email', 'Email address is required');
                hasErrors = true;
            } else if (!isValidEmail(email)) {
                showFieldError('email', 'Please enter a valid email address');
                hasErrors = true;
            }
            
            if (!car) {
                showFieldError('car', 'Car information is required');
                hasErrors = true;
            }
            
            if (!packageSelected) {
                showFieldError('package', 'Please select a service package');
                hasErrors = true;
            }
            
            // CAPTCHA validation
            const captchaResponse = grecaptcha.getResponse();
            if (!captchaResponse) {
                showCaptchaError('Please complete the CAPTCHA verification');
                hasErrors = true;
            }
            
            if (hasErrors) {
                showMessage('Please fill in all required fields and complete the CAPTCHA', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.textContent = 'Sending...';
            submitBtn.disabled = true;
            
            // Prepare data for submission using FormData (more compatible)
            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('package', packageSelected);
            formData.append('car', car);
            formData.append('g-recaptcha-response', captchaResponse);
            
            // Submit form via AJAX
            console.log('Submitting form with data:', {name, email, packageSelected, car});
            
            fetch('submit-form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response headers:', response.headers);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show success message
                    showThankYouMessage(name);
                    // Reset form and CAPTCHA
                    this.reset();
                    grecaptcha.reset();
                } else {
                    // Show error message
                    showMessage(data.message || 'An error occurred. Please try again.', 'error');
                    // Reset CAPTCHA on error
                    grecaptcha.reset();
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showMessage('Network error. Please check your connection and try again.', 'error');
            })
            .finally(() => {
                // Reset button
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    // Email validation function
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Show field-specific error
    function showFieldError(fieldName, message) {
        const field = document.getElementById(fieldName);
        if (field) {
            field.style.borderColor = '#e74c3c';
            field.style.boxShadow = '0 0 0 2px rgba(231, 76, 60, 0.2)';
            
            // Remove existing error message
            const existingError = field.parentElement.querySelector('.field-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'field-error';
            errorDiv.textContent = message;
            errorDiv.style.cssText = `
                color: #e74c3c;
                font-size: 0.8rem;
                margin-top: 0.25rem;
                animation: fadeIn 0.3s ease;
            `;
            field.parentElement.appendChild(errorDiv);
        }
    }
    
    // Show CAPTCHA error
    function showCaptchaError(message) {
        const captchaDiv = document.querySelector('.g-recaptcha');
        if (captchaDiv) {
            captchaDiv.classList.add('captcha-error');
            
            // Remove existing error message
            const existingError = captchaDiv.parentElement.querySelector('.captcha-field-error');
            if (existingError) {
                existingError.remove();
            }
            
            // Add error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'captcha-field-error';
            errorDiv.textContent = message;
            errorDiv.style.cssText = `
                color: #e74c3c;
                font-size: 0.8rem;
                margin-top: 0.5rem;
                text-align: center;
                animation: fadeIn 0.3s ease;
            `;
            captchaDiv.parentElement.appendChild(errorDiv);
            
            // Remove error class after 3 seconds
            setTimeout(() => {
                captchaDiv.classList.remove('captcha-error');
            }, 3000);
        }
    }
    
    // Clear all error messages
    function clearErrorMessages() {
        const errorMessages = document.querySelectorAll('.field-error, .captcha-field-error');
        errorMessages.forEach(error => error.remove());
        
        const messageContainer = document.querySelector('.message-container');
        if (messageContainer) {
            messageContainer.remove();
        }
        
        // Reset field styles
        const fields = document.querySelectorAll('.form-group input');
        fields.forEach(field => {
            field.style.borderColor = '#ddd';
            field.style.boxShadow = 'none';
        });
        
        // Reset CAPTCHA error styles
        const captchaDiv = document.querySelector('.g-recaptcha');
        if (captchaDiv) {
            captchaDiv.classList.remove('captcha-error');
        }
    }
    
    // Show general message
    function showMessage(message, type) {
        const form = document.getElementById('contactForm');
        const existingMessage = document.querySelector('.message-container');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message-container';
        messageDiv.innerHTML = `
            <div class="message ${type}">
                ${message}
            </div>
        `;
        
        form.parentElement.insertBefore(messageDiv, form);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentElement) {
                messageDiv.remove();
            }
        }, 5000);
    }
    
    // Show thank you message
    function showThankYouMessage(name) {
        const formContainer = document.querySelector('.form-container');
        
        // Create thank you content
        const thankYouHTML = `
            <div class="thank-you-container">
                <div class="thank-you-icon">✅</div>
                <h2 class="thank-you-title">Thank You, ${name}!</h2>
                <p class="thank-you-message">
                    Your information has been submitted successfully. 
                    Our team will review your request and contact you soon.
                </p>
                <button class="new-submission-btn" onclick="showForm()">
                    Submit Another Request
                </button>
            </div>
        `;
        
        // Hide form and show thank you message
        formContainer.innerHTML = thankYouHTML;
    }
    
    // Global function to show form again
    window.showForm = function() {
        location.reload();
    };

    // Add loading class removal after page load
    window.addEventListener('load', function() {
        document.body.classList.add('loaded');
    });

    // Handle image loading error
    const heroImage = document.querySelector('.hero-image');
    if (heroImage) {
        heroImage.addEventListener('error', function() {
            console.log('Image failed to load, using fallback');
            this.style.background = 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)';
            this.style.display = 'flex';
            this.style.alignItems = 'center';
            this.style.justifyContent = 'center';
            this.innerHTML = '<div style="color: white; text-align: center; font-size: 2rem; font-weight: bold;">Mechanic Africa<br><span style="font-size: 1rem; opacity: 0.8;">Professional Auto Services</span></div>';
        });
    }

    // Form field focus effects
    const formInputs = document.querySelectorAll('.form-group input');
    formInputs.forEach(input => {
        input.addEventListener('focus', function() {
            // Clear error state on focus
            this.style.borderColor = '#e74c3c';
            this.style.boxShadow = '0 0 0 2px rgba(231, 76, 60, 0.1)';
            
            const errorMsg = this.parentElement.querySelector('.field-error');
            if (errorMsg) {
                errorMsg.remove();
            }
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.style.borderColor = '#ddd';
                this.style.boxShadow = 'none';
            }
        });
    });
});

// Add CSS for enhanced animations
const style = document.createElement('style');
style.textContent = `
    .loaded .hero-image {
        animation: slideInLeft 1s ease-out;
    }

    .loaded .form-container {
        animation: slideInRight 1s ease-out;
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .form-group {
        position: relative;
        overflow: hidden;
    }

    .form-group::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: #e74c3c;
        transition: width 0.3s ease;
    }

    .form-group:focus-within::after {
        width: 100%;
    }

    .submit-btn {
        position: relative;
        overflow: hidden;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .submit-btn:active::before {
        width: 300px;
        height: 300px;
    }
`;
document.head.appendChild(style);