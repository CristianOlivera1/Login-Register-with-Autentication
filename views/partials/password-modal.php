<div id="passwordModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
    <div class="bg-[#0a0a0c] border border-white/10 rounded-xl p-6 w-full max-w-md mx-4">
        <h3 class="text-lg font-semibold text-white mb-4">Cambiar Contraseña</h3>
        
        <form id="passwordForm" class="space-y-4">
            <div class="space-y-2">
                <label class="text-xs font-medium text-slate-300 uppercase tracking-wide">Contraseña Actual</label>
                <div class="relative">
                    <input type="password" id="currentPassword" required
                        class="w-full h-10 bg-[#030305] border border-white/10 rounded-lg px-3 pr-10 text-sm text-white focus:outline-none focus:border-[#00e1ff]/50 focus:ring-1 focus:ring-[#00e1ff]/50 transition-all">
                    <button type="button" class="toggle-password absolute right-3 top-2.5 text-slate-500 hover:text-white transition-colors">
                        <iconify-icon icon="line-md:watch-off-loop" width="16"></iconify-icon>
                    </button>
                </div>
                <div class="error-message text-xs text-red-400 hidden"></div>
            </div>
            
            <div class="space-y-2">
                <label class="text-xs font-medium text-slate-300 uppercase tracking-wide">Nueva Contraseña</label>
                <div class="relative">
                    <input type="password" id="newPassword" required minlength="8"
                        class="w-full h-10 bg-[#030305] border border-white/10 rounded-lg px-3 pr-10 text-sm text-white focus:outline-none focus:border-[#00e1ff]/50 focus:ring-1 focus:ring-[#00e1ff]/50 transition-all">
                    <button type="button" class="toggle-password absolute right-3 top-2.5 text-slate-500 hover:text-white transition-colors">
                        <iconify-icon icon="line-md:watch-off-loop" width="16"></iconify-icon>
                    </button>
                </div>
                <div class="error-message text-xs text-red-400 hidden"></div>
            </div>
            
            <div class="space-y-2">
                <label class="text-xs font-medium text-slate-300 uppercase tracking-wide">Confirmar Nueva Contraseña</label>
                <div class="relative">
                    <input type="password" id="confirmPassword" required
                        class="w-full h-10 bg-[#030305] border border-white/10 rounded-lg px-3 pr-10 text-sm text-white focus:outline-none focus:border-[#00e1ff]/50 focus:ring-1 focus:ring-[#00e1ff]/50 transition-all">
                    <button type="button" class="toggle-password absolute right-3 top-2.5 text-slate-500 hover:text-white transition-colors">
                        <iconify-icon icon="line-md:watch-off-loop" width="16"></iconify-icon>
                    </button>
                </div>
                <div class="error-message text-xs text-red-400 hidden"></div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" id="cancelPasswordChange"
                    class="flex-1 px-4 py-2.5 text-xs text-slate-300 bg-[#030305] border border-white/10 rounded-lg hover:border-white/20 transition-colors">
                    Cancelar
                </button>
                <button type="submit" id="savePasswordChange"
                    class="flex-1 px-4 py-2.5 bg-[#00e1ff] text-black text-xs font-bold rounded-lg hover:bg-[#0099ff] hover:text-white transition-all">
                    Cambiar Contraseña
                </button>
            </div>
        </form>
    </div>
</div>