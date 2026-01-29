<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isAuthenticated = isset($_SESSION['user_id']);
$user = $isAuthenticated ? [
    'firstName' => $_SESSION['firstName'] ?? '',
    'lastName' => $_SESSION['lastName'] ?? '',
    'email' => $_SESSION['email'] ?? '',
    'avatar' => $_SESSION['avatar'] ?? '',
    'username' => $_SESSION['username'] ?? ''
] : null;
?>

<header>
    <nav class="fixed top-0 w-full z-50 border-b border-white/5 bg-[#030305]/80 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-6 h-14 flex items-center justify-between">
            <a href="/" class="text-white text-xl font-medium tracking-tight flex items-center gap-2 group">
                <div class="w-8 h-8 rounded flex items-center justify-center text-black">
                    <img src="/assets/img/logo-co.svg" alt="logo-codeoner" class="w-8 h-8 group-hover:rotate-12 transition-transform">
                </div>
                <span class="tracking-tighter">Codeoner</span>
            </a>

            <div class="hidden md:flex items-center gap-8 text-sm font-light">
                <a href="/" class="hover:text-[#00e1ff] transition-colors">Inicio</a>
                <?php if ($isAuthenticated): ?>
                    <a href="/profile" class="hover:text-[#00e1ff] transition-colors">Perfil</a>
                <?php endif; ?>
                <a href="/generate-cv" data-reload class="hover:text-[#00e1ff] transition-colors">Generar CV</a>
            </div>

            <?php if (!$isAuthenticated): ?>
                <div class="flex items-center gap-4">
                    <a href="#auth" class="hidden sm:block text-sm hover:text-white transition-colors">Iniciar sesión</a>
                    <a href="#auth" class="text-sm bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-2 rounded-full transition-all flex items-center gap-2">
                        <span>Registrarse</span>
                        <iconify-icon icon="solar:arrow-right-linear" width="16" stroke-width="1.5"></iconify-icon>
                    </a>
                </div>
            <?php else: ?>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-3 text-sm">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?php echo htmlspecialchars($user['avatar']); ?>"
                                alt="Avatar" class="w-8 h-8 rounded-full object-cover"
                                onerror="this.src='https://api.dicebear.com/9.x/pixel-art/svg?seed=<?php echo urlencode($user['username']); ?>';">
                        <?php else: ?>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#0099ff] to-[#00e1ff] flex items-center justify-center text-black font-semibold text-sm">
                                <?php echo strtoupper(substr($user['firstName'] ?: $user['username'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <span class="text-white"><?php echo htmlspecialchars($user['firstName'] ?: $user['username']); ?></span>
                    </div>

                    <div class="relative group">
                        <button class="text-sm bg-white/5 hover:bg-white/10 text-white border border-white/10 px-4 py-2 rounded-full transition-all flex items-center gap-2">
                            <iconify-icon icon="solar:settings-linear" width="16"></iconify-icon>
                            <iconify-icon icon="solar:alt-arrow-down-linear" width="12"></iconify-icon>
                        </button>

                        <div class="absolute right-0 mt-2 w-48 bg-[#0a0b0f] border border-white/10 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-10">
                            <div class="p-2">
                                <a href="/profile" data-reload class="block px-3 py-2 text-sm text-white hover:bg-white/10 rounded-md transition-colors">
                                    <iconify-icon icon="solar:user-linear" width="16" class="mr-2"></iconify-icon>
                                    Mi Perfil
                                </a>
                                <a href="/generate-cv" data-reload class="block px-3 py-2 text-sm text-white hover:bg-white/10 rounded-md transition-colors">
                                    <iconify-icon icon="solar:document-linear" width="16" class="mr-2"></iconify-icon>
                                    Generar CV
                                </a>
                                <hr class="my-2 border-white/10">
                                <button onclick="window.authModal && window.authModal.logout()" class="w-full text-left px-3 py-2 text-sm text-red-400 hover:bg-red-500/10 rounded-md transition-colors">
                                    <iconify-icon icon="solar:logout-linear" width="16" class="mr-2"></iconify-icon>
                                    Cerrar Sesión
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </nav>
</header>