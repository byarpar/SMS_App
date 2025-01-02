document.addEventListener('DOMContentLoaded', (event) => {
    // Navigation menu toggle for mobile
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('nav ul');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('show');
        });
    }

    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            let isValid = true;

            // Simple email validation
            const emailInput = form.querySelector('input[type="email"]');
            if (emailInput && !isValidEmail(emailInput.value)) {
                alert('Please enter a valid email address.');
                isValid = false;
            }

            // If form is valid, you can submit it
            if (isValid) {
                form.submit();
            }
        });
    }

    // Email validation helper function
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    // Fetch weather data (example of web service integration)
    fetch('https://api.openweathermap.org/data/2.5/weather?q=London,uk&appid=YOUR_API_KEY')
        .then(response => response.json())
        .then(data => {
            const weatherWidget = document.getElementById('weather-widget');
            if (weatherWidget) {
                weatherWidget.innerHTML = `Current temperature in London: ${Math.round(data.main.temp - 273.15)}°C`;
            }
        })
        .catch(error => console.error('Error:', error));

    // Load social media feed (example of web service integration)
    function loadSocialFeed() {
        const socialFeed = document.getElementById('social-feed');
        if (socialFeed) {
            // This is a mock-up. In a real scenario, you'd fetch this data from a social media API
            socialFeed.innerHTML = `
                <h3>Latest Social Media Updates</h3>
                <ul>
                    <li>New safety features released for Instagram!</li>
                    <li>Join our upcoming webinar on cyberbullying prevention.</li>
                    <li>Check out our latest blog post on privacy settings.</li>
                </ul>
            `;
        }
    }

    loadSocialFeed();
});

// 3D animation example
function animate3DElement() {
    const element = document.querySelector('.animate-3d');
    if (element) {
        let angle = 0;
        setInterval(() => {
            angle += 1;
            element.style.transform = `rotateY(${angle}deg)`;
        }, 50);
    }
}

animate3DElement();

// Custom cursor
const cursor = document.getElementById('custom-cursor');

document.addEventListener('mousemove', (e) => {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = e.clientY + 'px';
});
