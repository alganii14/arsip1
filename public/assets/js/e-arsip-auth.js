// E-Arsip Authentication Enhancements

document.addEventListener('DOMContentLoaded', function() {

    // Form validation enhancements
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(input => {
            // Real-time validation feedback
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });

        // Form submission with loading state
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;

                // Re-enable button after 3 seconds (in case of validation errors)
                setTimeout(() => {
                    submitBtn.classList.remove('loading');
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    });

    // Field validation function
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'Field ini wajib diisi';
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                isValid = false;
                errorMessage = 'Format email tidak valid';
            }
        }

        // Password validation
        if (field.type === 'password' && field.name === 'password' && value) {
            if (value.length < 6) {
                isValid = false;
                errorMessage = 'Password minimal 6 karakter';
            }
        }

        // Password confirmation validation
        if (field.name === 'password_confirmation' && value) {
            const passwordField = document.querySelector('input[name="password"]');
            if (passwordField && value !== passwordField.value) {
                isValid = false;
                errorMessage = 'Konfirmasi password tidak cocok';
            }
        }

        // Phone validation
        if (field.name === 'phone' && value) {
            const phoneRegex = /^[0-9+\-\s()]{8,15}$/;
            if (!phoneRegex.test(value)) {
                isValid = false;
                errorMessage = 'Format nomor telepon tidak valid';
            }
        }

        // Apply validation classes
        if (isValid) {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
            removeErrorMessage(field);
        } else {
            field.classList.remove('is-valid');
            field.classList.add('is-invalid');
            showErrorMessage(field, errorMessage);
        }

        return isValid;
    }

    // Show error message
    function showErrorMessage(field, message) {
        removeErrorMessage(field);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    // Remove error message
    function removeErrorMessage(field) {
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    // Password strength indicator
    const passwordInput = document.querySelector('input[name="password"]');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            showPasswordStrength(this);
        });
    }

    function showPasswordStrength(passwordField) {
        const password = passwordField.value;
        const strengthBar = getOrCreateStrengthBar(passwordField);

        let strength = 0;
        let strengthText = '';
        let strengthClass = '';

        if (password.length >= 6) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;

        switch (strength) {
            case 0:
            case 1:
                strengthText = 'Sangat Lemah';
                strengthClass = 'bg-danger';
                break;
            case 2:
                strengthText = 'Lemah';
                strengthClass = 'bg-warning';
                break;
            case 3:
                strengthText = 'Sedang';
                strengthClass = 'bg-info';
                break;
            case 4:
                strengthText = 'Kuat';
                strengthClass = 'bg-success';
                break;
            case 5:
                strengthText = 'Sangat Kuat';
                strengthClass = 'bg-success';
                break;
        }

        const percentage = (strength / 5) * 100;
        strengthBar.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Kekuatan Password:</small>
                <small class="text-muted">${strengthText}</small>
            </div>
            <div class="progress mt-1" style="height: 4px;">
                <div class="progress-bar ${strengthClass}" style="width: ${percentage}%"></div>
            </div>
        `;
    }

    function getOrCreateStrengthBar(passwordField) {
        let strengthBar = passwordField.parentNode.querySelector('.password-strength');
        if (!strengthBar) {
            strengthBar = document.createElement('div');
            strengthBar.className = 'password-strength mt-2';
            passwordField.parentNode.appendChild(strengthBar);
        }
        return strengthBar;
    }

    // Show/hide password functionality
    const passwordFields = document.querySelectorAll('input[type="password"]');
    passwordFields.forEach(field => {
        const toggleButton = document.createElement('button');
        toggleButton.type = 'button';
        toggleButton.className = 'btn btn-sm btn-outline-secondary position-absolute end-0 top-50 translate-middle-y me-2';
        toggleButton.style.border = 'none';
        toggleButton.style.background = 'transparent';
        toggleButton.style.zIndex = '10';
        toggleButton.innerHTML = '<i class="fas fa-eye"></i>';

        field.parentNode.style.position = 'relative';
        field.parentNode.appendChild(toggleButton);

        toggleButton.addEventListener('click', function() {
            const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
            field.setAttribute('type', type);

            const icon = this.querySelector('i');
            if (type === 'password') {
                icon.className = 'fas fa-eye';
            } else {
                icon.className = 'fas fa-eye-slash';
            }
        });
    });

    // Smooth scrolling for form errors
    const errorElements = document.querySelectorAll('.text-danger, .alert-danger');
    if (errorElements.length > 0) {
        errorElements[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // Terms and conditions modal (if needed)
    const termsLinks = document.querySelectorAll('a[href="javascript:;"]');
    termsLinks.forEach(link => {
        if (link.textContent.includes('Syarat dan Ketentuan')) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                showTermsModal();
            });
        }
    });

    function showTermsModal() {
        // Create modal for terms and conditions
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-file-contract me-2 text-primary"></i>
                            Syarat dan Ketentuan E-Arsip
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-primary">1. Penggunaan Sistem</h6>
                        <p>Sistem E-Arsip digunakan untuk mengelola dokumen arsip secara digital dengan keamanan tinggi.</p>

                        <h6 class="text-primary">2. Keamanan Data</h6>
                        <p>Pengguna bertanggung jawab menjaga kerahasiaan akun dan password.</p>

                        <h6 class="text-primary">3. Akses Dokumen</h6>
                        <p>Akses dokumen sesuai dengan tingkat otoritas yang diberikan.</p>

                        <h6 class="text-primary">4. Kebijakan Privasi</h6>
                        <p>Data pribadi akan dijaga kerahasiaannya sesuai dengan kebijakan privasi yang berlaku.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Setuju</button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        const bootstrapModal = new bootstrap.Modal(modal);
        bootstrapModal.show();

        modal.addEventListener('hidden.bs.modal', function() {
            document.body.removeChild(modal);
        });
    }

    console.log('E-Arsip Authentication enhancements loaded successfully');
});