<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: /?auth=required');
    exit;
}
?>

<main class="max-w-6xl mx-auto flex-1 flex flex-col min-w-0 bg-[#030305]">
    <div
        class="h-16 border-b border-white/5 bg-[#030305] flex items-center justify-between px-0 sm:px-6 z-10 shrink-0 mt-16">
        <div class="flex items-center gap-2 text-sm">
            <span class="text-slate-500">Cuenta</span>
            <iconify-icon icon="solar:alt-arrow-right-linear" width="12" class="text-slate-600"></iconify-icon>
            <span class="text-white font-medium">Perfil</span>
        </div>
    </div>

    <div class="flex-1 p-0 md:p-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <h1 class="text-2xl font-semibold text-white tracking-tight mb-2">Configuración General</h1>
                    <p class="text-sm text-slate-500">Administra la información y preferencias de tu cuenta.</p>
                </div>

                <!-- Avatar Section -->
                <div class="p-6 border border-white/5 rounded-xl bg-[#0a0a0c]">
                    <div class="flex items-center gap-6">
                        <div class="relative group cursor-pointer" id="avatarContainer">
                            <div
                                class="w-20 h-20 rounded-full bg-slate-800 border-2 border-[#0a0a0c] outline outline-2 outline-white/10 overflow-hidden relative">
                                <img src="https://api.dicebear.com/9.x/pixel-art/svg?seed=co" alt="Avatar" id="avatarImage" onerror="this.src='https://api.dicebear.com/9.x/pixel-art/svg?seed=co';" class="w-full h-full object-cover">
                                <!-- Upload Overlay -->
                                <div
                                    class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    <iconify-icon icon="solar:camera-linear" class="text-white"
                                        width="24"></iconify-icon>
                                </div>
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-6 h-6 bg-[#00e1ff] rounded-full border-4 border-[#0a0a0c]">
                            </div>
                        </div>
                        <div>
                            <h3 class="text-white font-medium mb-1">Foto de Perfil</h3>
                            <p class="text-xs text-slate-500 mb-3">Soporta JPG, PNG, SVG o WEBP. máx 1MB.</p>
                            <div class="flex gap-3">
                                <button id="uploadNewBtn"
                                    class="px-3 py-1.5 text-xs text-white bg-white/10 rounded-md border border-white/5 hover:bg-white/15 transition-colors font-medium">Subir Nueva</button>
                                <button id="removeAvatarBtn"
                                    class="px-3 py-1.5 text-xs text-red-400 hover:text-red-300 transition-colors font-medium">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information Form -->
                <form id="profileForm" class="p-6 border border-white/5 rounded-xl bg-[#0a0a0c] space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First Name -->
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-300 uppercase tracking-wide">Nombre</label>
                            <div class="relative">
                                <input type="text" id="firstName" value=""
                                    class="w-full h-10 bg-[#030305] border border-white/10 rounded-lg px-3 pl-9 text-sm text-white focus:outline-none focus:border-[#00e1ff]/50 focus:ring-1 focus:ring-[#00e1ff]/50 transition-all placeholder:text-slate-600"
                                    placeholder="Ingrese su nombre">
                                <iconify-icon icon="solar:user-linear"
                                    class="absolute left-3 top-2.5 text-slate-500" width="16"></iconify-icon>
                            </div>
                        </div>

                        <!-- Last Name -->
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-300 uppercase tracking-wide">Apellido</label>
                            <div class="relative">
                                <input type="text" id="lastName" value=""
                                    class="w-full h-10 bg-[#030305] border border-white/10 rounded-lg px-3 pl-9 text-sm text-white focus:outline-none focus:border-[#00e1ff]/50 focus:ring-1 focus:ring-[#00e1ff]/50 transition-all placeholder:text-slate-600"
                                    placeholder="Ingrese su apellido">
                                <iconify-icon icon="solar:user-linear"
                                    class="absolute left-3 top-2.5 text-slate-500" width="16"></iconify-icon>
                            </div>
                        </div>
                    </div>

                    <!-- Email (Read Only for OAuth / Editable for manual) -->
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-slate-300 uppercase tracking-wide">Correo Electrónico</label>
                        <div class="relative">
                            <input type="email" id="userEmail" value=""
                                class="w-full h-10 bg-[#030305] border border-white/10 rounded-lg px-3 pl-9 text-sm text-white focus:outline-none focus:border-[#00e1ff]/50 focus:ring-1 focus:ring-[#00e1ff]/50 transition-all placeholder:text-slate-600">
                            <iconify-icon icon="solar:letter-linear" class="absolute left-3 top-2.5 text-slate-500"
                                width="16"></iconify-icon>
                            <div class="absolute right-3 top-2.5 hidden" id="lockIcon">
                                <iconify-icon icon="solar:lock-keyhole-minimalistic-linear" class="text-slate-600"
                                    width="16"></iconify-icon>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-600 hidden" id="emailWarning">El correo no se puede cambiar.</p>
                    </div>

                    <hr class="border-white/5">

                    <!-- Password Section -->
                    <div id="passwordSection" class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-slate-200">Contraseña</h4>
                            <p class="text-xs text-slate-500 mt-1" id="passwordLastChanged">Configura tu contraseña</p>
                        </div>
                        <button type="button" id="resetPasswordBtn"
                            class="px-4 py-2 text-xs text-white bg-[#030305] border border-white/10 rounded-lg hover:border-white/20 transition-colors">
                            Cambiar Contraseña
                        </button>
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="button" id="saveChangesBtn"
                            class="px-6 py-2.5 bg-[#00e1ff] text-black text-xs font-bold rounded-lg hover:bg-[#0099ff] hover:text-white transition-all shadow-[0_0_15px_-5px_#00e1ff]">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
            <!-- RIGHT COLUMN: Visualization & Stats (Table: user_cvs) -->
            <div class="space-y-6">
                <div
                    class="p-4 rounded-xl border border-white/5 bg-[#0a0a0c] flex flex-col justify-between h-32 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform duration-500">
                        <iconify-icon icon="solar:share-circle-bold" width="60"></iconify-icon>
                    </div>

                    <span class="text-xs text-slate-500 font-medium uppercase tracking-wider">Visibilidad del CV </span>

                    <div class="flex items-center justify-between relative z-10">
                        <div class="flex flex-col">
                            <span id="visibilityStatus" class="text-xl font-semibold text-white">Privado</span>
                            <span class="text-[10px] text-slate-500" id="visibilityDescription">Solo tú puedes ver</span>
                        </div>

                        <!-- Toggle Switch -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="cvVisibilityToggle" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#00e1ff]">
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Most Popular CV Card -->
                <div id="topCVCard"
                    class="p-5 border border-white/5 rounded-xl bg-gradient-to-b from-[#0a0a0c] to-[#050507] relative hidden">
                    <div
                        class="absolute top-0 right-0 p-3 opacity-10 group-hover:scale-110 transition-transform duration-500">
                        <iconify-icon icon="streamline-flex:user-king-crown-solid" width="60"></iconify-icon>
                    </div>
                    <h3
                        class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <iconify-icon icon="solar:star-linear" class="text-yellow-500"></iconify-icon>
                        CV Más Popular
                    </h3>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-16 bg-white rounded border border-slate-200 shadow-sm flex-shrink-0 relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black/10"></div>
                            <div class="p-1 space-y-1 opacity-40">
                                <div class="h-1 w-2/3 bg-black rounded-full"></div>
                                <div class="h-0.5 w-full bg-black rounded-full"></div>
                                <div class="h-0.5 w-full bg-black rounded-full"></div>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 id="topCVTitle" class="text-sm font-medium text-white truncate">Mi CV</h4>
                            <a href="#" target="_blank" id="topCVSlug"
                                class="text-xs text-[#00e1ff] hover:underline flex items-center gap-1 mt-0.5 font-mono">
                                /mi-cv
                            </a>
                            <div class="mt-3 flex items-center gap-4 text-xs text-slate-500">
                                <span class="flex items-center gap-1">
                                    <iconify-icon icon="solar:eye-linear"></iconify-icon> 
                                    <span id="topCVViews">0</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No CV Card (shown when no CVs exist) -->
                <div id="noCVCard"
                    class="p-5 border border-white/5 rounded-xl bg-gradient-to-b from-[#0a0a0c] to-[#050507] relative">
                    <div
                        class="absolute top-0 right-0 p-3 opacity-10">
                        <iconify-icon icon="solar:document-add-linear" width="60"></iconify-icon>
                    </div>
                    <h3
                        class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                        <iconify-icon icon="solar:document-linear" class="text-blue-500"></iconify-icon>
                        Crea Tu Primer CV
                    </h3>

                    <div class="text-center py-4">
                        <p class="text-sm text-slate-400 mb-4">No has creado ningún CV aún</p>
                        <a href="/generate-cv" data-reload
                            class="inline-flex items-center gap-2 px-4 py-2 bg-[#00e1ff] text-black text-xs font-bold rounded-lg hover:bg-[#0099ff] hover:text-white transition-all">
                            <iconify-icon icon="solar:add-circle-linear" width="16"></iconify-icon>
                            Crear CV
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../partials/password-modal.php'; ?>

<!-- Include Profile JavaScript -->
<script src="/assets/js/profile.js"></script>