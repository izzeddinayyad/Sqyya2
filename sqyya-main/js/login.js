document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const loginForm = document.getElementById('formValidation');
    const emailInput = document.getElementById('Email1');
    const passwordInput = document.getElementById('Password1');
    const statusElement = document.getElementById('Status');
    
    // Check if elements exist
    if (!loginForm || !emailInput || !passwordInput || !statusElement) {
        console.error('Required login form elements not found');
        return;
    }

    // Initialize error messages
    const emailError = document.getElementById('emailError') || createErrorMessage(emailInput);
    const passwordError = document.getElementById('passwordError') || createErrorMessage(passwordInput);

    // Form submission handler
    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();
        
        // Get values
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        let isValid = true;

        // Email validation
        if (!email) {
            showError(emailInput, emailError, 'البريد الإلكتروني مطلوب');
            isValid = false;
        } else if (!validateEmail(email)) {
            showError(emailInput, emailError, 'البريد الإلكتروني غير صحيح');
            isValid = false;
        }

        // Password validation
        if (!password) {
            showError(passwordInput, passwordError, 'كلمة المرور مطلوبة');
            isValid = false;
        } else if (password.length < 6) {
            showError(passwordInput, passwordError, 'كلمة المرور يجب أن تكون 6 أحرف على الأقل');
            isValid = false;
        }

        // If valid, attempt login
        if (isValid) {
            attemptLogin(email, password);
        }
    });

    // Helper functions
    function createErrorMessage(inputElement) {
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.style.color = '#D63031';
        errorElement.style.fontSize = '14px';
        errorElement.style.marginTop = '5px';
        errorElement.style.display = 'none';
        inputElement.parentNode.insertBefore(errorElement, inputElement.nextSibling);
        return errorElement;
    }

    function clearErrors() {
        // Clear error messages
        [emailError, passwordError].forEach(el => {
            el.style.display = 'none';
        });
        
        // Remove error/success classes
        [emailInput, passwordInput].forEach(el => {
            el.classList.remove('error', 'success');
        });
        
        // Clear status message
        statusElement.textContent = '';
    }

    function showError(inputElement, errorElement, message) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        inputElement.classList.add('error');
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function attemptLogin(email, password) {
        // Show loading state
        statusElement.textContent = 'جاري تسجيل الدخول...';
        statusElement.style.color = '#00B894';
        
        // Get users from localStorage
        const users = JSON.parse(localStorage.getItem('registeredUsers')) || [];
        
        // Check credentials (with small delay for UX)
        setTimeout(() => {
            const user = users.find(u => u.email === email && u.password === password);
            
            if (user) {
                // Successful login
                statusElement.textContent = 'تم تسجيل الدخول بنجاح!';
                
                // Store current user session
                sessionStorage.setItem('currentUser', JSON.stringify({
                    email: user.email,
                    name: user.fullName,
                    loggedInAt: new Date().toISOString()
                }));
                
                // Redirect to home page
                setTimeout(() => {
                    window.location.href = 'index.html';
                }, 1000);
            } else {
                // Failed login
                statusElement.textContent = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
                statusElement.style.color = '#D63031';
                passwordInput.value = '';
            }
        }, 500); // Small delay for better UX
    }

    // Real-time validation
    emailInput.addEventListener('input', function() {
        if (emailInput.value.trim()) {
            emailInput.classList.remove('error');
            emailError.style.display = 'none';
        }
    });
    
    passwordInput.addEventListener('input', function() {
        if (passwordInput.value.trim()) {
            passwordInput.classList.remove('error');
            passwordError.style.display = 'none';
        }
    });
});