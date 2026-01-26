<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isAuthenticated = isset($_SESSION['user_id']);
?>

<section class="relative pt-24 pb-16">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <p class="text-xl md:text-2xl text-slate-400 mb-12 max-w-3xl mx-auto">
            Escribe tu historial profesional en JSON simple y deja que nuestro motor genere un currículum minimalista y perfecto al instante.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-20">
            <button class="h-12 px-8 rounded-full bg-[#00e1ff] text-[#030305] font-medium hover:bg-[#0099ff] hover:text-white transition-all flex items-center gap-2 shadow-[0_0_20px_-5px_#00e1ff]">
                <iconify-icon icon="solar:rocket-2-linear" stroke-width="1.5" width="20"></iconify-icon>
                Generar CV
            </button>
            <button class="h-12 px-8 rounded-full border border-white/10 hover:border-white/20 hover:bg-white/5 text-white transition-all flex items-center gap-2">
                <iconify-icon icon="line-md:github-loop" width="24" height="24"></iconify-icon>
                Ver Código
            </button>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <div class="absolute -inset-1 bg-gradient-to-r from-[#0099ff] to-[#00e1ff] rounded-2xl opacity-20 blur-xl"></div>

            <div class="relative bg-[#0a0a0c] border border-white/10 rounded-xl overflow-hidden shadow-2xl grid md:grid-cols-2 h-[500px] md:h-[600px]">

                <div class="border-b md:border-b-0 md:border-r border-white/5 flex flex-col text-left">
                    <div class="h-10 border-b border-white/5 bg-[#030305] flex items-center px-4 gap-2">
                        <div class="flex gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-red-500/20 border border-red-500/50"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/20 border border-yellow-500/50"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-green-500/20 border border-green-500/50"></div>
                        </div>
                        <span class="text-xs text-slate-500 ml-2 font-mono">profile.json</span>
                    </div>
                    <div class="p-6 font-mono text-xs md:text-sm leading-relaxed overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-8 h-full border-r border-white/5 bg-white/[0.02] text-slate-700 pt-6 text-center select-none">
                            1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13
                        </div>
                        <div class="pl-6">
                            <span class="text-slate-500">{</span><br>
                            &nbsp;&nbsp;<span class="syntax-key">"basics"</span>: <span class="text-slate-500">{</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="syntax-key">"name"</span>: <span class="syntax-string">"Alex Rivera"</span>,<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="syntax-key">"label"</span>: <span class="syntax-string">"Software Engineer"</span>,<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="syntax-key">"email"</span>: <span class="syntax-string">"alex@dev.com"</span>,<br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="syntax-key">"location"</span>: <span class="text-slate-500">{</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="syntax-key">"city"</span>: <span class="syntax-string">"San Francisco"</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-slate-500">}</span><br>
                            &nbsp;&nbsp;<span class="text-slate-500">}</span>,<br>
                            &nbsp;&nbsp;<span class="syntax-key">"skills"</span>: <span class="text-slate-500">[</span><br>
                            &nbsp;&nbsp;&nbsp;&nbsp;<span class="syntax-string">"React"</span>, <span class="syntax-string">"Tailwind"</span>, <span class="syntax-string cursor-blink">"Node.js"</span><br>
                            &nbsp;&nbsp;<span class="text-slate-500">]</span><br>
                            <span class="text-slate-500">}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white flex flex-col relative group">
                    <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="bg-[#030305] text-white p-2 rounded shadow-lg hover:bg-[#00e1ff] hover:text-black transition-colors">
                            <iconify-icon icon="solar:download-linear" width="16"></iconify-icon>
                        </button>
                    </div>

                    <div class="flex-1 p-8 md:p-12 text-left scale-[0.8] md:scale-100 origin-top-left w-[125%] md:w-full h-full">
                        <h2 class="text-3xl font-semibold text-slate-900 tracking-tight mb-1">Alex Rivera</h2>
                        <p class="text-[#0099ff] font-medium text-sm mb-4">Software Engineer</p>

                        <div class="flex items-center gap-4 text-xs text-slate-500 mb-8 border-b border-slate-100 pb-6">
                            <span class="flex items-center gap-1">
                                <iconify-icon icon="solar:letter-linear"></iconify-icon> alex@dev.com
                            </span>
                            <span class="flex items-center gap-1">
                                <iconify-icon icon="solar:map-point-linear"></iconify-icon> San Francisco
                            </span>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-xs uppercase tracking-widest font-semibold text-slate-400 mb-3">Skills</h3>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs rounded border border-slate-200">React</span>
                                <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs rounded border border-slate-200">Tailwind</span>
                                <span class="px-2 py-1 bg-[#00e1ff]/10 text-[#0099ff] text-xs rounded border border-[#00e1ff]/20">Node.js</span>
                            </div>
                        </div>

                        <div class="space-y-4 opacity-50 blur-[1px]">
                            <h3 class="text-xs uppercase tracking-widest font-semibold text-slate-400">Experience</h3>
                            <div class="h-2 bg-slate-200 rounded w-3/4"></div>
                            <div class="h-2 bg-slate-200 rounded w-full"></div>
                            <div class="h-2 bg-slate-200 rounded w-5/6"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>