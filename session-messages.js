document.addEventListener('DOMContentLoaded', function() {
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    function deleteCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/;';
    }

    const messageContainer = document.getElementById('message-container');
    const errorMessage = getCookie('error_message');
    const successMessage = getCookie('success_message');

    if (errorMessage) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = decodeURIComponent(errorMessage);
        messageContainer.appendChild(errorDiv);
        deleteCookie('error_message');

        // If the error message contains "Account is locked", start a countdown
        if (errorMessage.includes("Account is locked")) {
            const seconds = parseInt(errorMessage.match(/\d+/)[0]);
            let remainingTime = seconds;
            const countdownElement = document.createElement('div');
            countdownElement.className = 'countdown';
            errorDiv.appendChild(countdownElement);

            const countdownInterval = setInterval(() => {
                remainingTime--;
                countdownElement.textContent = `You can try again in ${remainingTime} seconds.`;
                if (remainingTime <= 0) {
                    clearInterval(countdownInterval);
                    errorDiv.remove();
                }
            }, 1000);
        }
    }

    if (successMessage) {
        const successDiv = document.createElement('div');
        successDiv.className = 'success-message';
        successDiv.textContent = decodeURIComponent(successMessage);
        messageContainer.appendChild(successDiv);
        deleteCookie('success_message');
    }
});

