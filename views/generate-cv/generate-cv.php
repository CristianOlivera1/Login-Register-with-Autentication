<header class="mt-14 h-14 md:h-16 border-b border-white/5 bg-[#030305] flex items-center justify-between px-3 md:px-4 lg:px-6 z-20">
    <div class="flex items-center gap-2 md:gap-6">
        <div class="h-6 w-px bg-white/10 hidden sm:block"></div>
        <div class="flex items-center gap-2 text-xs md:text-sm">
            <span class="text-slate-500">Generar CV</span>
            <iconify-icon icon="solar:alt-arrow-right-linear" width="12" class="text-slate-600 hidden sm:block"></iconify-icon>
            <span class="text-white font-medium">Editor</span>
        </div>
    </div>

    <div class="flex items-center gap-2 md:gap-3">
        <div class="hidden xl:flex items-center gap-2 mr-2 auto-save-status">
            <iconify-icon icon="mdi:cloud-check-outline" width="18" class="status-icon text-green-500"></iconify-icon>
            <span class="text-xs text-green-500 font-medium status-text">Auto guardado</span>
        </div>

        <button id="preview-btn" class="h-8 md:h-9 px-3 md:px-4 rounded-lg border border-white/10 bg-white/5 text-white text-xs font-medium hover:bg-white/10 transition-all flex items-center gap-2">
            <iconify-icon icon="solar:eye-linear" width="14" class="md:w-4 md:h-4"></iconify-icon>
            <span class="hidden sm:inline">Previsualizar</span>
        </button>

        <button class="h-8 md:h-9 px-3 md:px-4 rounded-lg bg-[#00e1ff] text-[#030305] text-xs font-semibold hover:bg-[#0099ff] hover:text-white transition-all flex items-center gap-2 shadow-[0_0_15px_-5px_#00e1ff]">
            <iconify-icon icon="solar:download-minimalistic-linear" width="14" class="md:w-4 md:h-4" stroke-width="2"></iconify-icon>
            <span class="hidden sm:inline">Exportar PDF</span>
        </button>
    </div>
</header>

<main class="flex-1 flex flex-col lg:flex-row overflow-hidden relative">

    <!-- Columna izquierda - JSON Editor -->
    <div id="left-panel" class="relative flex flex-col border-r-0 lg:border-r border-white/5 bg-[#0a0a0c] h-[40vh] lg:h-auto" style="width: 100%; --lg-width: 50%;">

        <div class="h-10 border-b border-white/5 flex items-center justify-between px-3 md:px-4 bg-[#0a0a0c]">
            <div class="flex items-center gap-2 md:gap-4">
                <span id="tab-json" class="text-xs text-[#00e1ff] border-b border-[#00e1ff] h-10 flex items-center px-1 font-medium">JSON Source</span>
                <span id="tab-toon" class="text-xs text-slate-500 hover:text-slate-300 h-10 flex items-center px-1 cursor-pointer transition-colors">TOON Source</span>
            </div>
            <div class="flex items-center gap-2 md:gap-3">
                <button id="word-wrap-btn" class="text-slate-500 hover:text-white transition-colors" title="Alternar ajuste de líneas">
                    <iconify-icon icon="fluent:text-first-line-20-filled" width="14" class="md:w-4 md:h-4"></iconify-icon>
                </button>
                <button id="format-code-btn" class="text-slate-500 hover:text-white transition-colors" title="Formatear JSON">
                    <iconify-icon icon="tabler:code-dots" width="14" class="md:w-4 md:h-4"></iconify-icon>
                </button>
                <button id="upload-json-btn" class="text-slate-500 hover:text-white transition-colors" title="Subir JSON">
                    <iconify-icon icon="solar:upload-minimalistic-linear" width="14" class="md:w-4 md:h-4"></iconify-icon>
                </button>
                <button id="copy-btn" class="text-slate-500 hover:text-white transition-colors" title="Copiar al portapapeles">
                    <iconify-icon icon="solar:copy-linear" width="14" class="md:w-4 md:h-4"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- JSON Editor -->
        <div class="flex-1 overflow-auto relative flex font-mono text-xs md:text-sm leading-6 max-h-[calc(40vh-60px)] lg:max-h-[calc(100dvh-100px)] overflow-y-auto custom-scroll">
            <div class="w-8 md:w-12 flex-shrink-0 bg-[#0a0a0c] border-r border-white/5 text-slate-600 text-right pr-2 md:pr-3 pt-4 select-none text-xs">
                1<br>2<br>3<br>4<br>5<br>6<br>7<br>8<br>9<br>10<br>11<br>12<br>13<br>14<br>15<br>16<br>17<br>18<br>19<br>20
            </div>

            <div id="json-editor" class="flex-1 p-2 md:p-4 outline-none text-slate-300" contenteditable="true" spellcheck="false" style="white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word;">
                <span class="text-slate-500">// Cargando datos del CV...</span>
            </div>
        </div>

        <div class="h-8 bg-[#0099ff]/10 border-t border-[#0099ff]/20 flex items-center justify-between px-3 md:px-4 text-xs">
            <div class="flex items-center gap-2 text-[#00e1ff]">
                <iconify-icon icon="solar:check-circle-linear" width="14"></iconify-icon>
                <span class="hidden sm:inline">Json válido</span>
                <span class="sm:hidden">Válido</span>
            </div>
            <div class="text-slate-500 font-mono hidden sm:block">
                Ln 20, Col 1
            </div>
        </div>
    </div>

    <div id="resizer" class="hidden lg:block w-0.5 ms-1 bg-white/50 hover:bg-[#00e1ff]/50 cursor-col-resize transition-colors relative group">
        <div class="absolute inset-y-0 group-hover:bg-[#00e1ff]/20"></div>
    </div>

    <div id="right-panel" class="relative bg-[#0e0e11] flex flex-col h-[60vh] lg:h-full border-t lg:border-t-0 border-white/5" style="width: 100%; --lg-width: 50%;">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>

        <div class="h-10 border-b border-white/5 bg-[#0e0e11]/80 backdrop-blur z-10 flex items-center justify-between px-3 my-4 sm:my-0">

            <div class="flex items-center gap-3">
                <div class="relative group" id="custom-select">
                    <button id="select-button" class="flex items-center gap-2 text-xs text-white bg-white/5 border border-white/10 px-2 md:px-3 py-1.5 rounded-lg hover:bg-white/10 transition-colors">
                        <iconify-icon icon="solar:palette-linear" class="text-slate-400" width="14"></iconify-icon>
                        <span id="selected-value">Formatos</span>
                        <iconify-icon icon="solar:alt-arrow-down-linear" class="text-slate-500" width="12"></iconify-icon>
                    </button>

                    <div id="select-menu" class="absolute top-full left-0 mt-2 w-48 bg-[#16161a] border border-white/10 rounded-xl shadow-2xl opacity-0 invisible translate-y-2 transition-all duration-200 z-50">
                        <div class="p-1">
                            <div class="text-center py-4 text-xs text-slate-500">
                                Cargando plantillas...
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-4 w-px bg-white/10 hidden sm:block"></div>
            </div>
        </div>

        <div class="flex-1 py-1 md:py-2 lg:p-3 flex justify-center items-start relative z-0">

            <div class="a4-paper w-full sm:w-[210mm] p-[5mm] md:p-[10mm] lg:p-[15mm] max-h-[calc(60vh-60px)] lg:max-h-[calc(100dvh-80px)] overflow-y-auto custom-scroll text-slate-800 relative origin-top transform-none sm:scale-[0.7] md:scale-[0.8] lg:scale-[0.9] xl:scale-100 transition-transform duration-300">

                <div id="loader" class="flex flex-col items-center justify-center min-h-[200px] text-slate-400">
                    <iconify-icon icon="solar:document-linear" width="32" class="md:w-12 md:h-12 mb-4 opacity-50"></iconify-icon>
                    <p class="text-xs md:text-sm">Cargando vista previa del CV...</p>
                </div>

                <div id="cv-content">
                </div>

            </div>
        </div>
    </div>
</main>

<style>
    @media (min-width: 1024px) {
        #left-panel {
            width: var(--lg-width) !important;
            height: auto !important;
        }
        #right-panel {
            width: var(--lg-width) !important;
            height: auto !important;
        }
        main {
            flex-direction: row !important;
        }
    }
    
    @media (max-width: 1023px) {
        #json-editor {
            font-size: 12px;
            line-height: 1.4;
        }
        
        .custom-scroll {
            -webkit-overflow-scrolling: touch;
        }
    }
    
    @media (max-width: 640px) {
        #json-editor {
            padding: 8px;
            font-size: 11px;
        }
        
        .a4-paper {
            padding: 4mm !important;
        }
    }
</style>