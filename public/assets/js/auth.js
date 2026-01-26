class AuthModal {
    constructor() {
        this.modal = document.getElementById('authModal');
        this.loginForm = document.getElementById('loginForm');
        this.registerForm = document.getElementById('registerForm');
        this.toggleBtn = document.getElementById('toggleForm');
        this.closeBtn = document.getElementById('closeModal');
        this.modalTitle = document.getElementById('modalTitle');
        this.modalSubtitle = document.getElementById('modalSubtitle');
        this.authMessage = document.getElementById('authMessage');
        
        this.isLoginMode = true;
        this.config = null;
        
        this.initEventListeners();
        this.loadConfig().then(() => {
            this.checkAuthStatus();
        });
    }

    async loadConfig() {
        try {
            const response = await fetch('/auth/config');
            this.config = await response.json();
        } catch (error) {
            console.error('Failed to load OAuth config:', error);
        }
    }

    initEventListeners() {
        this.closeBtn.addEventListener('click', () => this.closeModal());
        this.toggleBtn.addEventListener('click', () => this.toggleMode());
        
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) this.closeModal();
        });

        this.loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        this.registerForm.addEventListener('submit', (e) => this.handleRegister(e));

        document.getElementById('googleLogin').addEventListener('click', () => this.handleOAuth('google'));
        document.getElementById('githubLogin').addEventListener('click', () => this.handleOAuth('github'));
        document.getElementById('facebookLogin').addEventListener('click', () => this.handleOAuth('facebook'));

        document.querySelectorAll('a[href="#auth"]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const isRegister = btn.textContent.includes('Registrarse');
                this.openModal(isRegister);
            });
        });
    }

    openModal(isRegister = false) {
        this.isLoginMode = !isRegister;
        this.updateModalMode();
        this.modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        this.clearMessage();
    }

    closeModal() {
        this.modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        this.clearForms();
        this.clearMessage();
    }

    toggleMode() {
        this.isLoginMode = !this.isLoginMode;
        this.updateModalMode();
        this.clearMessage();
    }

    updateModalMode() {
        if (this.isLoginMode) {
            this.modalTitle.textContent = 'Iniciar Sesión';
            this.modalSubtitle.textContent = 'Accede a tu cuenta en Codeoner';
            this.loginForm.classList.remove('hidden');
            this.registerForm.classList.add('hidden');
            this.toggleBtn.textContent = '¿No tienes cuenta? Regístrate';
        } else {
            this.modalTitle.textContent = 'Crear Cuenta';
            this.modalSubtitle.textContent = 'Únete a la comunidad de Codeoner';
            this.loginForm.classList.add('hidden');
            this.registerForm.classList.remove('hidden');
            this.toggleBtn.textContent = '¿Ya tienes cuenta? Inicia sesión';
        }
    }

    async handleLogin(e) {
        e.preventDefault();
        
        const formData = new FormData(this.loginForm);
        const data = {
            email: formData.get('email'),
            password: formData.get('password')
        };

        try {
            this.showMessage('Iniciando sesión...', 'info');
            
            const response = await fetch('/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showMessage('¡Inicio de sesión exitoso!', 'success');
                setTimeout(() => {
                    this.closeModal();
                    this.updateAuthState(result.user);
                    window.location.reload();
                }, 1000);
            } else {
                this.showMessage(result.message, 'error');
            }
        } catch (error) {
            this.showMessage('Error de conexión. Inténtalo de nuevo.', 'error');
        }
    }

    async handleRegister(e) {
        e.preventDefault();
        
        const formData = new FormData(this.registerForm);
        const data = {
            firstName: formData.get('firstName'),
            lastName: formData.get('lastName'),
            email: formData.get('email'),
            password: formData.get('password'),
            confirmPassword: formData.get('confirmPassword')
        };

        if (data.password !== data.confirmPassword) {
            this.showMessage('Las contraseñas no coinciden', 'error');
            return;
        }

        try {
            this.showMessage('Creando cuenta...', 'info');
            
            const response = await fetch('/auth/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                this.showMessage('¡Cuenta creada exitosamente!', 'success');
                setTimeout(() => {
                    this.closeModal();
                    this.updateAuthState(result.user);
                    window.location.reload();
                }, 1000);
            } else {
                this.showMessage(result.message, 'error');
            }
        } catch (error) {
            this.showMessage('Error de conexión. Inténtalo de nuevo.', 'error');
        }
    }

    handleOAuth(provider) {
        if (!this.config) {
            this.showMessage('Configuración OAuth no disponible', 'error');
            return;
        }

        const baseUrl = window.location.origin;
        const oauthUrls = {
            google: `https://accounts.google.com/o/oauth2/auth?client_id=${this.config.google_client_id}&redirect_uri=${baseUrl}/auth/callback/google&scope=openid%20profile%20email&response_type=code`,
            github: `https://github.com/login/oauth/authorize?client_id=${this.config.github_client_id}&redirect_uri=${baseUrl}/auth/callback/github&scope=user:email`,
            facebook: `https://www.facebook.com/v14.0/dialog/oauth?client_id=${this.config.facebook_client_id}&redirect_uri=${baseUrl}/auth/callback/facebook&scope=email&response_type=code`
        };

        if (oauthUrls[provider]) {
            window.location.href = oauthUrls[provider];
        }
    }

    async checkAuthStatus() {
        try {
            const response = await fetch('/auth/check');
            const result = await response.json();
            
            if (result.authenticated) {
                this.updateAuthState(result.user);
            }
        } catch (error) {
            console.error('Failed to check auth status:', error);
        }
    }

    updateAuthState(user) {
        
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('oauth_success')) {
            window.history.replaceState({}, document.title, window.location.pathname);
            window.location.reload();
        }
    }

    async logout() {
        try {
            const response = await fetch('/auth/logout');
            const result = await response.json();
            
            if (result.success) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Logout failed:', error);
        }
    }

    showMessage(message, type) {
        this.authMessage.textContent = message;
        this.authMessage.className = `mt-4 p-3 rounded-lg text-sm ${this.getMessageClasses(type)}`;
        this.authMessage.classList.remove('hidden');
    }

    clearMessage() {
        this.authMessage.classList.add('hidden');
    }

    getMessageClasses(type) {
        switch (type) {
            case 'success':
                return 'bg-green-500/20 text-green-400 border border-green-500/30';
            case 'error':
                return 'bg-red-500/20 text-red-400 border border-red-500/30';
            case 'info':
                return 'bg-blue-500/20 text-blue-400 border border-blue-500/30';
            default:
                return 'bg-gray-500/20 text-gray-400 border border-gray-500/30';
        }
    }

    clearForms() {
        this.loginForm.reset();
        this.registerForm.reset();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.authModal = new AuthModal();
});