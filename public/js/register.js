document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const passwordInput = document.getElementById('Password');
    const confirmPasswordInput = document.getElementById('re-pass');
    const emailInput = document.getElementById('Email1');
    const phoneInput = document.getElementById('phone');
    const userTypeSelect = document.getElementById('user_type');
    const citySelect = document.getElementById('city');

    // Initialize localStorage with empty array if it doesn't exist
    if (!localStorage.getItem('registeredUsers')) {
        localStorage.setItem('registeredUsers', JSON.stringify([]));
    }

    // Error message elements
    const errorMessages = {
        fullName: createErrorMessageElement(),
        email: createErrorMessageElement(),
        phone: createErrorMessageElement(),
        userType: createErrorMessageElement(),
        address: createErrorMessageElement(),
        city: createErrorMessageElement(),
        password: createErrorMessageElement(),
        confirmPassword: createErrorMessageElement()
    };

    // Insert error message elements
    insertAfter(errorMessages.fullName, document.getElementById('full-name'));
    insertAfter(errorMessages.email, emailInput);
    insertAfter(errorMessages.phone, phoneInput);
    insertAfter(errorMessages.userType, userTypeSelect);
    insertAfter(errorMessages.address, document.getElementById('adress'));
    insertAfter(errorMessages.city, citySelect);
    insertAfter(errorMessages.password, passwordInput);
    insertAfter(errorMessages.confirmPassword, confirmPasswordInput);

    // Form submission handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrorMessages();

        let isValid = true;

        // Validate full name
        const fullName = document.getElementById('full-name').value.trim();
        if (!fullName || fullName.length < 5) {
            showError('fullName', 'الاسم الكامل مطلوب ويجب أن يكون أكثر من 5 أحرف');
            isValid = false;
        }

        // Validate email
        const email = emailInput.value.trim();
        if (!validateEmail(email)) {
            showError('email', 'البريد الإلكتروني غير صحيح');
            isValid = false;
        } else {
            try {
                if (isEmailRegistered(email)) {
                    showError('email', 'هذا البريد الإلكتروني مسجل بالفعل');
                    isValid = false;
                }
            } catch (error) {
                console.error('Error checking email:', error);
                showError('email', 'حدث خطأ أثناء التحقق من البريد الإلكتروني');
                isValid = false;
            }
        }

        // Validate phone number
        const phone = phoneInput.value.trim();
        const phoneRegex = /^05\d{8}$/;
        if (!phoneRegex.test(phone)) {
            showError('phone', 'رقم الهاتف يجب أن يبدأ بـ 05 ويتكون من 10 أرقام');
            isValid = false;
        }

        // Validate user type
        if (userTypeSelect.selectedIndex === 0) {
            showError('userType', 'يجب اختيار نوع المستخدم');
            isValid = false;
        }

        // Validate address
        const address = document.getElementById('adress').value.trim();
        if (!address || address.length < 10) {
            showError('address', 'العنوان مطلوب ويجب أن يكون أكثر من 10 أحرف');
            isValid = false;
        }

        // Validate city
        if (citySelect.selectedIndex === 0) {
            showError('city', 'يجب اختيار المحافظة');
            isValid = false;
        }

        // Validate password
        const password = passwordInput.value;
        if (!validatePassword(password)) {
            showError('password', 'كلمة المرور يجب أن تحتوي على الأقل على 8 أحرف، حرف كبير، حرف صغير، رقم ورمز خاص');
            isValid = false;
        }

        // Validate password confirmation
        if (password !== confirmPasswordInput.value) {
            showError('confirmPassword', 'كلمة المرور غير متطابقة');
            isValid = false;
        }

        if (isValid) {
            saveUserToLocalStorage();
        }
    });

    // Helper functions
    function createErrorMessageElement() {
        const element = document.createElement('div');
        element.className = 'error-message';
        element.style.color = '#D63031';
        element.style.fontSize = '14px';
        element.style.marginTop = '5px';
        element.style.display = 'none';
        return element;
    }

    function insertAfter(newNode, referenceNode) {
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    function showError(field, message) {
        errorMessages[field].textContent = message;
        errorMessages[field].style.display = 'block';
        const inputField = document.getElementById(field === 'fullName' ? 'full-name' : 
                                                field === 'address' ? 'adress' : field);
        inputField.style.borderColor = '#D63031';
    }

    function clearErrorMessages() {
        Object.values(errorMessages).forEach(element => {
            element.style.display = 'none';
        });
        
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.style.borderColor = '';
        });
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function validatePassword(password) {
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(password);
    }

    function isEmailRegistered(email) {
        try {
            const usersJson = localStorage.getItem('registeredUsers');
            if (!usersJson) return false;
            
            const users = JSON.parse(usersJson);
            if (!Array.isArray(users)) return false;
            
            return users.some(user => user.email === email);
        } catch (error) {
            console.error('Error parsing user data:', error);
            return false;
        }
    }

    function saveUserToLocalStorage() {
        try {
            const userData = {
                fullName: document.getElementById('full-name').value.trim(),
                email: emailInput.value.trim(),
                phone: phoneInput.value.trim(),
                userType: userTypeSelect.options[userTypeSelect.selectedIndex].text,
                address: document.getElementById('adress').value.trim(),
                city: citySelect.options[citySelect.selectedIndex].text,
                password: passwordInput.value,
                registrationDate: new Date().toISOString()
            };

            const usersJson = localStorage.getItem('registeredUsers');
            const users = usersJson ? JSON.parse(usersJson) : [];
            
            if (!Array.isArray(users)) {
                throw new Error('Invalid user data format');
            }

            users.push(userData);
            localStorage.setItem('registeredUsers', JSON.stringify(users));
            
            alert('تم تسجيل الحساب بنجاح! سيتم توجيهك إلى صفحة تسجيل الدخول');
            form.reset();
            
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 2000);
        } catch (error) {
            console.error('Error saving user data:', error);
            alert('حدث خطأ أثناء حفظ البيانات. يرجى المحاولة مرة أخرى.');
        }
    }

    // Real-time validation
    passwordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        if (password.length > 0 && !validatePassword(password)) {
            passwordInput.style.borderColor = '#D63031';
        } else {
            passwordInput.style.borderColor = '';
        }
    });

    emailInput.addEventListener('input', function() {
        if (emailInput.value.length > 0 && !validateEmail(emailInput.value)) {
            emailInput.style.borderColor = '#D63031';
        } else {
            emailInput.style.borderColor = '';
        }
    });
});