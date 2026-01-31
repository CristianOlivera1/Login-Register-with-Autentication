class ProfileManager {
    constructor() {
        this.currentUser = null;
        this.profileData = null;
        this.isOAuthUser = false;
        
        this.initializeElements();
        this.loadProfile();
        this.initializeEventListeners();
    }

    initializeElements() {
        this.avatarImg = document.getElementById('avatarImage');
        this.uploadAvatarBtn = document.getElementById('avatarContainer');
        this.uploadNewBtn = document.getElementById('uploadNewBtn');
        this.removeAvatarBtn = document.getElementById('removeAvatarBtn');
        
        this.firstNameInput = document.getElementById('firstName');
        this.lastNameInput = document.getElementById('lastName');
        this.emailInput = document.getElementById('userEmail');
        this.saveChangesBtn = document.getElementById('saveChangesBtn');
        this.resetPasswordBtn = document.getElementById('resetPasswordBtn');
        
        this.visibilityToggle = document.getElementById('cvVisibilityToggle');
        this.visibilityStatus = document.getElementById('visibilityStatus');
        
        this.topCVContainer = document.getElementById('topCVCard');
        this.noCVCard = document.getElementById('noCVCard');
        
        this.createAvatarUploadInput();
        
        this.createPasswordModal();
    }

    async loadProfile() {
        try {
            const response = await fetch('/profile/get');
            const data = await response.json();
            
            if (data.success) {
                this.profileData = data.profile;
                this.isOAuthUser = data.profile.auth_provider !== 'manual';
                this.populateForm();
                this.updateTopCV(data.topCV);
            } else {
                this.showNotification('Error al cargar perfil', 'error');
            }
        } catch (error) {
            console.error('Error loading profile:', error);
            this.showNotification('Error de conexión', 'error');
        }
    }

    populateForm() {
        if (!this.profileData) return;
        
        if (this.firstNameInput) this.firstNameInput.value = this.profileData.firstName || '';
        if (this.lastNameInput) this.lastNameInput.value = this.profileData.lastName || '';
        if (this.emailInput) this.emailInput.value = this.profileData.email || '';
        if (this.avatarImg) this.avatarImg.src = this.profileData.avatar || '';
        
        // Configure email field based on auth provider
        const lockIcon = document.getElementById('lockIcon');
        const emailWarning = document.getElementById('emailWarning');
        const passwordSection = document.getElementById('passwordSection');
        
        if (this.isOAuthUser) {
            this.emailInput.readOnly = true;
            this.emailInput.classList.add('cursor-not-allowed', 'text-slate-400');
            if (lockIcon) lockIcon.classList.remove('hidden');
            if (emailWarning) emailWarning.classList.remove('hidden');
            if (passwordSection) passwordSection.style.display = 'none';
        } else {
            this.emailInput.readOnly = false;
            this.emailInput.classList.remove('cursor-not-allowed', 'text-slate-400');
            if (lockIcon) lockIcon.classList.add('hidden');
            if (emailWarning) emailWarning.classList.add('hidden');
            if (passwordSection) passwordSection.style.display = 'flex';
        }
        
        if (this.visibilityToggle && this.visibilityStatus) {
            const isPublic = Boolean(this.profileData.has_public_cv);
            this.visibilityToggle.checked = isPublic;
            this.visibilityStatus.textContent = isPublic ? 'Público' : 'Privado';
            
            const visibilityDescription = document.getElementById('visibilityDescription');
            if (visibilityDescription) {
                visibilityDescription.textContent = isPublic ? 'Cualquier persona con el enlace puede ver' : 'Solo tú puedes ver';
            }
        }
    }

    updateTopCV(cvData) {
        if (!cvData) {
            if (this.topCVContainer) this.topCVContainer.classList.add('hidden');
            if (this.noCVCard) this.noCVCard.classList.remove('hidden');
            return;
        }
        
        if (this.noCVCard) this.noCVCard.classList.add('hidden');
        if (this.topCVContainer) {
            this.topCVContainer.classList.remove('hidden');
            
            const titleElement = document.getElementById('topCVTitle');
            const slugElement = document.getElementById('topCVSlug');
            const viewsElement = document.getElementById('topCVViews');
            
            if (titleElement) titleElement.textContent = cvData.title || 'Mi CV';
            if (slugElement) {
                slugElement.innerHTML = `<iconify-icon icon="line-md:link" width="10"></iconify-icon> /${cvData.slug}`;
                slugElement.href = `/cv/${cvData.slug}`;
            }
            if (viewsElement) viewsElement.textContent = cvData.view_count || 0;
        }
    }

    initializeEventListeners() {
        if (this.uploadAvatarBtn) {
            this.uploadAvatarBtn.addEventListener('click', () => {
                this.avatarInput.click();
            });
        }
        
        if (this.uploadNewBtn) {
            this.uploadNewBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.avatarInput.click();
            });
        }
        
        if (this.removeAvatarBtn) {
            this.removeAvatarBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.removeAvatar();
            });
        }
        
        if (this.saveChangesBtn) {
            this.saveChangesBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.saveProfile();
            });
        }
        
        [this.firstNameInput, this.lastNameInput, this.emailInput].forEach(input => {
            if (input) {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => this.clearFieldError(input));
            }
        });
        
        if (this.visibilityToggle) {
            this.visibilityToggle.addEventListener('change', () => this.toggleCVVisibility());
        }
        
        if (this.resetPasswordBtn) {
            this.resetPasswordBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.showPasswordModal();
            });
        }
    }

    createAvatarUploadInput() {
        this.avatarInput = document.createElement('input');
        this.avatarInput.type = 'file';
        this.avatarInput.accept = '.jpg,.jpeg,.png,.webp,.svg';
        this.avatarInput.style.display = 'none';
        document.body.appendChild(this.avatarInput);
        
        this.avatarInput.addEventListener('change', (e) => {
            if (e.target.files[0]) {
                this.uploadAvatar(e.target.files[0]);
            }
        });
    }

    createPasswordModal() {
        this.passwordModal = document.getElementById('passwordModal');
        this.passwordForm = document.getElementById('passwordForm');
        
        document.getElementById('cancelPasswordChange').addEventListener('click', () => this.hidePasswordModal());
        this.passwordForm.addEventListener('submit', (e) => this.changePassword(e));
        
        this.initializePasswordToggles();
    }

    initializePasswordToggles() {
        document.querySelectorAll('#passwordModal .toggle-password').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const input = btn.parentElement.querySelector('input');
                const icon = btn.querySelector('iconify-icon');
                
                if (!input || !icon) {
                    return;
                }
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.setAttribute('icon', 'line-md:watch-loop');
                } else {
                    input.type = 'password';
                    icon.setAttribute('icon', 'line-md:watch-off-loop');
                }
            });
        });
    }

    async uploadAvatar(file) {
        const formData = new FormData();
        formData.append('avatar', file);
        
        try {
            this.showNotification('Subiendo avatar...', 'info');
            
            const response = await fetch('/profile/upload-avatar', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.avatarImg.src = data.avatar;
                this.showNotification('Avatar actualizado exitosamente', 'success');
            } else {
                console.error('Error uploading avatar:', data);
                this.showNotification(data.message || 'Error al subir avatar', 'error');
            }
        } catch (error) {
            console.error('Network error uploading avatar:', error);
            this.showNotification('Error de conexión al subir avatar', 'error');
        }
    }

    async removeAvatar() {
        try {
            const response = await fetch('/profile/remove-avatar', {
                method: 'POST'
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.avatarImg.src = data.avatar;
                this.showNotification('Avatar removido exitosamente', 'success');
            } else {
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            this.showNotification('Error al remover avatar', 'error');
        }
    }

    async saveProfile() {
        const formData = {
            firstName: this.firstNameInput.value.trim(),
            lastName: this.lastNameInput.value.trim()
        };
        
        // Solo incluir email si no es usuario OAuth
        if (!this.isOAuthUser) {
            formData.email = this.emailInput.value.trim();
        }
        
        // Validate fields
        const isValid = this.validateAllFields();
        if (!isValid) {
            return;
        }
        
        try {
            this.setLoadingState(this.saveChangesBtn, true);
            
            const response = await fetch('/profile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showNotification('Perfil actualizado exitosamente', 'success');
            } else {
                if (data.errors) {
                    this.showFieldErrors(data.errors);
                } else {
                    this.showNotification(data.message, 'error');
                }
            }
        } catch (error) {
            this.showNotification('Error al actualizar perfil', 'error');
        } finally {
            this.setLoadingState(this.saveChangesBtn, false);
        }
    }

    async toggleCVVisibility() {
        const isPublic = this.visibilityToggle.checked;
        
        try {
            const response = await fetch('/profile/toggle-cv-visibility', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ isPublic })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.visibilityStatus.textContent = isPublic ? 'Público' : 'Privado';
                this.showNotification(
                    `CV ahora es ${isPublic ? 'público' : 'privado'}`, 
                    'success'
                );
            } else {
                this.visibilityToggle.checked = !isPublic;
                this.showNotification(data.message, 'error');
            }
        } catch (error) {
            this.visibilityToggle.checked = !isPublic;
            this.showNotification('Error al cambiar visibilidad', 'error');
        }
    }

    showPasswordModal() {
        this.passwordModal.classList.remove('hidden');
        this.passwordModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    hidePasswordModal() {
        this.passwordModal.classList.add('hidden');
        this.passwordModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        this.passwordForm.reset();
        this.clearFormErrors();
    }

    async changePassword(e) {
        e.preventDefault();
        
        const formData = {
            currentPassword: document.getElementById('currentPassword').value,
            newPassword: document.getElementById('newPassword').value,
            confirmPassword: document.getElementById('confirmPassword').value
        };
        
        try {
            const saveBtn = document.getElementById('savePasswordChange');
            this.setLoadingState(saveBtn, true);
            
            const response = await fetch('/profile/change-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.hidePasswordModal();
                this.showNotification('Contraseña cambiada exitosamente', 'success');
            } else {
                if (data.errors) {
                    this.showPasswordErrors(data.errors);
                } else {
                    this.showNotification(data.message, 'error');
                }
            }
        } catch (error) {
            this.showNotification('Error al cambiar contraseña', 'error');
        } finally {
            const saveBtn = document.getElementById('savePasswordChange');
            this.setLoadingState(saveBtn, false);
        }
    }

    validateField(input) {
        const value = input.value.trim();
        let isValid = true;
        let message = '';
        
        if (input === this.firstNameInput) {
            if (!value || value.length > 150) {
                isValid = false;
                message = 'El nombre debe tener entre 1 y 150 caracteres';
            }
        } else if (input === this.lastNameInput) {
            if (!value || value.length > 250) {
                isValid = false;
                message = 'El apellido debe tener entre 1 y 250 caracteres';
            }
        } else if (input === this.emailInput && !this.isOAuthUser) {
            if (!value || !this.isValidEmail(value)) {
                isValid = false;
                message = 'Formato de email inválido';
            }
        }
        
        this.showFieldError(input, isValid ? '' : message);
        return isValid;
    }

    validateAllFields() {
        const fields = [this.firstNameInput, this.lastNameInput];
        if (!this.isOAuthUser) fields.push(this.emailInput);
        
        let allValid = true;
        fields.forEach(field => {
            if (field && !this.validateField(field)) {
                allValid = false;
            }
        });
        
        return allValid;
    }

    showFieldError(input, message) {
        const container = input.parentElement;
        let errorDiv = container.querySelector('.field-error');
        
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'field-error text-xs text-red-400 mt-1';
            container.appendChild(errorDiv);
        }
        
        errorDiv.textContent = message;
        errorDiv.style.display = message ? 'block' : 'none';
        
        if (message) {
            input.classList.add('border-red-500');
        } else {
            input.classList.remove('border-red-500');
        }
    }

    clearFieldError(input) {
        const container = input.parentElement;
        const errorDiv = container.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }
        input.classList.remove('border-red-500');
    }

    showFieldErrors(errors) {
        Object.keys(errors).forEach(field => {
            let input;
            if (field === 'firstName') input = this.firstNameInput;
            else if (field === 'lastName') input = this.lastNameInput;
            else if (field === 'email') input = this.emailInput;
            
            if (input) {
                this.showFieldError(input, errors[field]);
            }
        });
    }

    showPasswordErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                const container = input.parentElement.parentElement;
                let errorDiv = container.querySelector('.error-message');
                if (errorDiv) {
                    errorDiv.textContent = errors[field];
                    errorDiv.classList.remove('hidden');
                    input.classList.add('border-red-500');
                }
            }
        });
    }

    clearFormErrors() {
        document.querySelectorAll('.error-message').forEach(error => {
            error.classList.add('hidden');
        });
        document.querySelectorAll('.border-red-500').forEach(input => {
            input.classList.remove('border-red-500');
        });
    }

    setLoadingState(button, isLoading) {
        if (!button) return;
        
        if (isLoading) {
            button.originalText = button.textContent;
            button.disabled = true;
            button.innerHTML = `
                <span class="flex items-center justify-center gap-2">
                    <iconify-icon icon="line-md:loading-twotone-loop" width="16"></iconify-icon>
                    Guardando...
                </span>
            `;
            button.classList.add('opacity-70', 'cursor-not-allowed');
        } else {
            button.disabled = false;
            button.textContent = button.originalText || 'Guardar';
            button.classList.remove('opacity-70', 'cursor-not-allowed');
        }
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            type === 'warning' ? 'bg-yellow-500' :
            'bg-blue-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname === '/profile') {
        window.profileManager = new ProfileManager();
    }
});