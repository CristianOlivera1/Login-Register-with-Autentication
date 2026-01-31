<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$isAuthenticated = isset($_SESSION['user_id']);
?>

<section class="relative pb-16 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 text-center">
        <img src="/assets/img/logo-json2cv.png" alt="logo-json2cv" class="mx-auto w-xl mt-10 sm:-mt-4" />
        <p class="text-xl md:text-2xl text-slate-400 mb-8 -mt-2 sm:-mt-6 max-w-3xl mx-auto">
            Escribe tu historial profesional en
            <span class="relative inline-block">
                <span class="font-bold bg-clip-text text-transparent bg-gradient-to-r from-[#FA736C] to-[#D93C47]">
                    JSON
                </span>
                <span class="absolute -bottom-1 left-0 w-full">
                    <img src="/assets/img/line-draw.svg" alt="line draw" class="w-full h-auto" />
                </span>
                <span class="absolute -bottom-1 left-0 w-full opacity-20">
                    <img src="/assets/img/line-static.svg" alt="line draw" class="w-full h-auto" />
                </span>
            </span>
            y deja que nuestro motor genere un currículum minimalista y perfecto al instante.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-8 sm:mb-14">
            <a href="/generate-cv" data-reload class="h-12 px-8 rounded-full bg-[#00e1ff] text-[#030305] font-medium hover:bg-[#0099ff] hover:text-white transition-all flex items-center gap-2 shadow-[0_0_20px_-5px_#00e1ff]">
                <iconify-icon icon="solar:rocket-2-linear" stroke-width="1.5" width="20"></iconify-icon>
                Generar CV
            </a>
            <a href="https://github.com/CristianOlivera1/Login-Register-with-Autentication" target="_blank" class="h-12 px-8 rounded-full border border-white/10 hover:border-white/20 hover:bg-white/5 text-white transition-all flex items-center gap-2">
                <iconify-icon icon="line-md:github-loop" width="24" height="24"></iconify-icon>
                Ver Código
            </a>
        </div>

        <img src="/assets/img/background-video.jpg"
            alt="background-video"
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 max-w-7xl h-full object-cover opacity-90 -z-10 rounded-4xl"
            style="border-radius: 80px; corner-shape: squircle;">

        <div class="relative max-w-7xl -mx-6 sm:mx-auto">
            <div class="absolute -inset-1 bg-linear-to-r from-[#0099ff] to-[#00e1ff] rounded-2xl opacity-30 blur-lg hidden sm:block"></div>

            <div class="relative bg-[#0a0a0c] border-y sm:border border-white/10 rounded-none sm:rounded-xl overflow-hidden shadow-2xl flex flex-col h-auto">

                <div class="h-10 border-b border-white/5 bg-[#030305] z-20 flex items-center px-4 justify-between shrink-0">
                    <div class="flex gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-500/20 border border-red-500/50"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/20 border border-yellow-500/50"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-green-500/20 border border-green-500/50"></div>
                    </div>
                    <span class="text-[10px] text-slate-500 font-mono uppercase tracking-[0.2em]">Editor Preview</span>
                    <div class="w-12"></div>
                </div>

                <div class="relative bg-black flex items-center justify-center">
                    <img
                        src="assets/img/video-poster.avif"
                        class="absolute inset-0 w-full h-full object-cover blur-[100px] opacity-50 scale-110 pointer-events-none"
                        aria-hidden="true">

                    <video
                        class="relative z-10 w-full h-auto shadow-2xl block scale-125"
                        autoplay
                        muted
                        loop
                        playsinline
                        poster="assets/img/video-poster.avif">
                        <source src="https://github.com/CristianOlivera1/Resources-dev/raw/main/any/demo-codeoner.mp4" type="video/mp4">
                    </video>

                    <div class="absolute inset-0 z-20 pointer-events-none bg-radial-gradient from-transparent to-[#0a0a0c]/20"></div>
                </div>
            </div>
        </div>

    </div>
</section>