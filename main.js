document.addEventListener('DOMContentLoaded', () => {
    // Mobile Navigation Toggle
    const mobileNavToggle = document.querySelector('.mobile-nav-toggle');
    const nav = document.querySelector('nav ul');

    if (mobileNavToggle && nav) {
        mobileNavToggle.addEventListener('click', () => {
            nav.classList.toggle('show');
            mobileNavToggle.setAttribute('aria-expanded', 
                mobileNavToggle.getAttribute('aria-expanded') === 'false' ? 'true' : 'false'
            );
        });
    }

    // Form Validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });

    function validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input, textarea');
        
        inputs.forEach(input => {
            if (input.hasAttribute('required') && !input.value.trim()) {
                isValid = false;
                showError(input, 'This field is required');
            } else if (input.type === 'email' && !isValidEmail(input.value)) {
                isValid = false;
                showError(input, 'Please enter a valid email address');
            } else {
                clearError(input);
            }
        });

        return isValid;
    }

    function showError(input, message) {
        clearError(input);
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        input.parentNode.insertBefore(errorElement, input.nextSibling);
        input.classList.add('error');
    }

    function clearError(input) {
        const errorElement = input.nextSibling;
        if (errorElement && errorElement.className === 'error-message') {
            errorElement.remove();
        }
        input.classList.remove('error');
    }

    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Smooth Scrolling for Anchor Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Dynamic Copyright Year
    const copyrightYear = document.querySelector('.copyright-year');
    if (copyrightYear) {
        copyrightYear.textContent = new Date().getFullYear();
    }

    // Weather Widget (Mock data - replace with actual API call in production)
    const weatherWidget = document.getElementById('weather-widget');
    if (weatherWidget) {
        const mockWeatherData = {
            temperature: 22,
            condition: 'Sunny'
        };
        weatherWidget.textContent = `Current Weather: ${mockWeatherData.temperature}°C, ${mockWeatherData.condition}`;
    }

    // Social Media Feed (Mock data - replace with actual API call in production)
    const socialFeed = document.getElementById('social-feed');
    if (socialFeed) {
        const mockPosts = [
            { user: 'SafetyFirst', content: 'Remember to update your privacy settings regularly!' },
            { user: 'TechTips', content: 'New feature alert: Instagram now allows you to limit sensitive content.' },
            { user: 'CyberExpert', content: 'Join our webinar on cyberbullying prevention next week.' }
        ];
        
        const feedHtml = mockPosts.map(post => `
            <div class="social-post">
                <strong>${post.user}</strong>: ${post.content}
            </div>
        `).join('');
        
        socialFeed.innerHTML = `<h3>Latest Updates</h3>${feedHtml}`;
    }

    // Accessibility: Add aria-current to the current page in navigation
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentPage) {
            link.setAttribute('aria-current', 'page');
        }
    });
});

