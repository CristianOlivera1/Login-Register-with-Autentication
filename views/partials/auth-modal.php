<div id="authModal" class="fixed inset-0 z-50 hidden">
    <div id="modalBackdrop" class="fixed inset-0 bg-black/30 backdrop-blur-xs"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="relative bg-white border border-gray-200 rounded-2xl p-8 w-full max-w-lg overflow-y-auto max-h-[700px]">
            <button id="closeModal" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900 transition-colors">
                <iconify-icon icon="line-md:close-small" width="24"></iconify-icon>
            </button>

            <div class="text-center mb-6">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-900">Iniciar Sesión</h2>
            </div>

            <form id="loginForm" class="space-y-4">
                <div>
                    <label for="loginEmail" class="block text-sm font-medium text-gray-800 mb-2">Correo electrónico</label>
                    <input
                        type="email"
                        id="loginEmail"
                        name="email"
                        required
                        class="w-full px-4 py-3 bg-white/5 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="tu@gmail.com">
                </div>

                <div class="relative">
                    <label for="loginPassword" class="block text-sm font-medium text-gray-800 mb-2">Contraseña</label>
                    <input
                        type="password"
                        id="loginPassword"
                        name="password"
                        required
                        minlength="8"
                        class="w-full px-4 py-3 bg-white/5 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="••••••••">
                    <button type="button" class="toggle-password absolute right-3 top-1/2 text-gray-500 hover:text-gray-700">
                        <iconify-icon icon="line-md:watch-off-loop" width="20"></iconify-icon>
                    </button>
                </div>

                <button id="loginSubmitBtn"
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#0099ff] to-[#00e1ff] text-gray-900 py-3 rounded-lg font-medium hover:opacity-90 transition-opacity">
                    Iniciar Sesión
                </button>
            </form>

            <form id="registerForm" class="space-y-4 hidden">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="registerFirstName" class="block text-sm font-medium text-gray-800 mb-2">Nombre</label>
                        <input
                            type="text"
                            id="registerFirstName"
                            name="firstName"
                            class="w-full px-4 py-3 bg-white/5 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                            placeholder="Raúl">
                    </div>
                    <div>
                        <label for="registerLastName" class="block text-sm font-medium text-gray-800 mb-2">Apellido</label>
                        <input
                            type="text"
                            id="registerLastName"
                            name="lastName"
                            class="w-full px-4 py-3 bg-white/5 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                            placeholder="Ochoa">
                    </div>
                </div>

                <div>
                    <label for="registerEmail" class="block text-sm font-medium text-gray-800 mb-2">Correo electrónico</label>
                    <input
                        type="email"
                        id="registerEmail"
                        name="email"
                        required
                        class="w-full px-4 py-3 bg-white/5 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="tu@gmail.com">
                </div>

                <div class="relative">
                    <label for="registerPassword" class="block text-sm font-medium text-gray-800 mb-2">Contraseña</label>
                    <input
                        type="password"
                        id="registerPassword"
                        name="password"
                        required
                        minlength="8"
                        class="w-full px-4 py-3 bg-white/5 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="••••••••">
                    <button type="button" class="toggle-password absolute right-3 top-1/2 text-gray-500 hover:text-gray-700">
                        <iconify-icon icon="line-md:watch-off-loop" width="20"></iconify-icon>
                    </button>
                </div>

                <div class="relative">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-800 mb-2">Confirmar contraseña</label>
                    <input
                        type="password"
                        id="confirmPassword"
                        name="confirmPassword"
                        required
                        minlength="8"
                        class="w-full px-4 py-3 bg-white/5 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#0099ff] focus:border-transparent"
                        placeholder="••••••••">
                    <button type="button" class="toggle-password absolute right-3 top-1/2 text-gray-500 hover:text-gray-700">
                        <iconify-icon icon="line-md:watch-off-loop" width="20"></iconify-icon>
                    </button>
                </div>

                <button id="registerSubmitBtn"
                    type="submit"
                    class="w-full bg-gradient-to-r from-[#0099ff] to-[#00e1ff] text-gray-900 py-3 rounded-lg font-medium hover:opacity-90 transition-opacity">
                    Crear Cuenta
                </button>
            </form>

            <div id="authMessage" class="hidden mt-4 p-3 rounded-lg text-sm"></div>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-white text-gray-600">O continúa con</span>
                </div>
            </div>

            <div class="space-y-3">
                <button
                    id="googleLogin"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white/5 border border-gray-200 rounded-lg text-gray-900 hover:bg-gray-100 transition-colors">
                    <iconify-icon icon="logos:google-icon" width="20"></iconify-icon>
                    Google
                </button>

                <button
                    id="githubLogin"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white/5 border border-gray-200 rounded-lg text-gray-900 hover:bg-gray-100 transition-colors">
                    <iconify-icon icon="logos:github-icon" width="20"></iconify-icon>
                    GitHub
                </button>

                <button
                    id="facebookLogin"
                    class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white/5 border border-gray-200 rounded-lg text-gray-900 hover:bg-gray-100 transition-colors">
                    <iconify-icon icon="logos:facebook" width="20"></iconify-icon>
                    Facebook
                </button>
            </div>

            <div class="text-center mt-6">
                <button id="toggleForm" class="text-gray-900 hover:underline text-sm">
                    ¿No tienes cuenta? Regístrate
                </button>
            </div>
        </div>
    </div>
</div>