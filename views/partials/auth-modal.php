<div id="authModal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative bg-[#0a0b0f] border border-white/10 rounded-2xl p-8 w-full max-w-md overflow-y-auto max-h-[700px]">
            <button id="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors">
                <iconify-icon icon="solar:close-circle-linear" width="24"></iconify-icon>
            </button>

            <div class="text-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-white">Iniciar Sesión</h2>
                <p id="modalSubtitle" class="text-gray-400 mt-1">Accede a tu cuenta en Codeoner</p>
            </div>

            <form id="loginForm" class="space-y-4">
                <div>
                    <label for="loginEmail" class="block text-sm font-medium text-gray-300 mb-2">Correo electrónico</label>
                    <input 
                        type="email" 
                        id="loginEmail" 
                        name="email" 
                        required 
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="tu@email.com"
                    >
                </div>
                
                <div>
                    <label for="loginPassword" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                    <input 
                        type="password" 
                        id="loginPassword" 
                        name="password" 
                        required 
                        minlength="8"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="••••••••"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-[#0099ff] to-[#00e1ff] text-white py-3 rounded-lg font-medium hover:opacity-90 transition-opacity"
                >
                    Iniciar Sesión
                </button>
            </form>

            <form id="registerForm" class="space-y-4 hidden">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="registerFirstName" class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                        <input 
                            type="text" 
                            id="registerFirstName" 
                            name="firstName" 
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                            placeholder="Juan"
                        >
                    </div>
                    <div>
                        <label for="registerLastName" class="block text-sm font-medium text-gray-300 mb-2">Apellido</label>
                        <input 
                            type="text" 
                            id="registerLastName" 
                            name="lastName" 
                            class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                            placeholder="Pérez"
                        >
                    </div>
                </div>

                <div>
                    <label for="registerEmail" class="block text-sm font-medium text-gray-300 mb-2">Correo electrónico</label>
                    <input 
                        type="email" 
                        id="registerEmail" 
                        name="email" 
                        required 
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="tu@email.com"
                    >
                </div>
                
                <div>
                    <label for="registerPassword" class="block text-sm font-medium text-gray-300 mb-2">Contraseña</label>
                    <input 
                        type="password" 
                        id="registerPassword" 
                        name="password" 
                        required 
                        minlength="8"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="••••••••"
                    >
                </div>

                <div>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-300 mb-2">Confirmar contraseña</label>
                    <input 
                        type="password" 
                        id="confirmPassword" 
                        name="confirmPassword" 
                        required 
                        minlength="8"
                        class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="••••••••"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-[#0099ff] to-[#00e1ff] text-white py-3 rounded-lg font-medium hover:opacity-90 transition-opacity"
                >
                    Crear Cuenta
                </button>
            </form>

            <div id="authMessage" class="hidden mt-4 p-3 rounded-lg text-sm"></div>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-[#0a0b0f] text-gray-400">O continúa con</span>
                </div>
            </div>

            <div class="space-y-3">
                <button 
                    id="googleLogin"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white/5 border border-white/10 rounded-lg text-white hover:bg-white/10 transition-colors"
                >
                    <iconify-icon icon="logos:google-icon" width="20"></iconify-icon>
                    Google
                </button>

                <button 
                    id="githubLogin"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white/5 border border-white/10 rounded-lg text-white hover:bg-white/10 transition-colors"
                >
                    <iconify-icon icon="logos:github-icon" width="20"></iconify-icon>
                    GitHub
                </button>

                <button 
                    id="facebookLogin"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white/5 border border-white/10 rounded-lg text-white hover:bg-white/10 transition-colors"
                >
                    <iconify-icon icon="logos:facebook" width="20"></iconify-icon>
                    Facebook
                </button>
            </div>

            <div class="text-center mt-6">
                <button id="toggleForm" class="text-[#00e1ff] hover:underline text-sm">
                    ¿No tienes cuenta? Regístrate
                </button>
            </div>
        </div>
    </div>
</div>