class CVGenerator {
    constructor() {
        this.currentCVData = null;
        this.currentTemplate = null;
        this.availableTemplates = [];
        this.isAuthenticated = false;
        this.autoSaveTimeout = null; 
        this.autoSaveDelay = 2000;
        this.userCV = null;
        this.lastSavedData = null;
        this.hasUnsavedChanges = false;
        this.isSaving = false;

        this.initializeElements();
        this.checkAuthentication().then(() => {
            this.loadTemplates().then(() => {
                if (this.isAuthenticated) {
                    this.loadUserCV();
                } else {
                    this.loadExampleCV();
                }
            });
        });
        this.initializeEventListeners();
    }

    initializeElements() {
        // Esperar a que el DOM esté completamente cargado
        this.jsonEditor = document.getElementById('json-editor');
        this.previewContainer = document.querySelector('.a4-paper');
        this.uploadBtn = document.getElementById('upload-json-btn');
        this.formatBtn = document.getElementById('format-code-btn');
        this.copyBtn = document.getElementById('copy-btn');
        this.previewBtn = document.getElementById('preview-btn');
        this.exportBtn = document.querySelector('button:has([icon="solar:download-minimalistic-linear"])');
        this.validationStatus = document.querySelector('.h-8.bg-\\[\\#0099ff\\]\\/10');

        this.templateSelect = document.getElementById('custom-select');
        this.templateOptions = document.querySelectorAll('.option-btn');

        this.saveStatusIndicator = document.querySelector('.auto-save-status');

        this.wordWrapBtn = document.getElementById('word-wrap-btn');

        this.jsonTab = document.getElementById('tab-json');
        this.toonTab = document.getElementById('tab-toon');

        if (!this.jsonEditor) {
            console.warn('JSON Editor not found in DOM');
        }
        if (!this.previewContainer) {
            console.warn('Preview Container not found in DOM');
        }

        this.initializeResizer();
    }

    initializeEventListeners() {
        if (this.uploadBtn) {
            this.uploadBtn.addEventListener('click', () => this.uploadJSONFile());
        }

        if (this.formatBtn) {
            this.formatBtn.addEventListener('click', () => this.formatJSON());
        }

        if (this.copyBtn) {
            this.copyBtn.addEventListener('click', () => this.copyJSON());
        }

        if (this.exportBtn) {
            this.exportBtn.addEventListener('click', () => this.exportToPDF());
        }

        if (this.previewBtn) {
            this.previewBtn.addEventListener('click', () => this.handlePreview());
        }

        if (this.templateOptions) {
            this.templateOptions.forEach(option => {
                option.addEventListener('click', (e) => {
                    const templateValue = e.target.getAttribute('data-value');
                    this.changeTemplate(templateValue);
                });
            });
        }

        if (this.wordWrapBtn) {
            this.wordWrapBtn.addEventListener('click', () => this.toggleWordWrap());
        }

        if (this.jsonEditor) {
            this.jsonEditor.addEventListener('input', () => this.handleJSONChange());
            this.jsonEditor.addEventListener('paste', (e) => this.handlePaste(e));
        }

        this.initializeTabs();
    }

    initializeTabs() {
        setTimeout(() => {
            const allSpans = document.querySelectorAll('span');
            allSpans.forEach(span => {
                const text = span.textContent.trim();

                if (text === 'JSON Source') {
                    span.style.cursor = 'pointer';
                    span.addEventListener('click', () => this.switchTab(text));
                }
                // TOON bloqueado hasta nueva funcionalidad
                else if (text === 'TOON Source') {
                    span.style.cursor = 'not-allowed';
                    span.title = "Funcionalidad disponible próximamente";
                    span.classList.add('opacity-50');
                }
            });
        }, 100);
    }

    switchTab(tabName) {
        const allSpans = document.querySelectorAll('span');

        allSpans.forEach(span => {
            const text = span.textContent.trim();
            if (text === 'JSON Source' || text === 'TOON Source') {
                if (text === tabName) {
                    span.className = 'text-xs text-[#00e1ff] border-b border-[#00e1ff] h-10 flex items-center px-1 font-medium';
                } else {
                    span.className = 'text-xs text-slate-500 hover:text-slate-300 h-10 flex items-center px-1 cursor-pointer transition-colors';
                }
            }
        });

        if (tabName === 'JSON Source') {
            this.showJSONEditor();
        } else if (tabName === 'TOON Source') {
            this.showTOONEditor();
        }
    }

    showJSONEditor() {
        const jsonText = this.currentCVData ? JSON.stringify(this.currentCVData, null, 2) : '';
        this.displayJSONInEditor(this.currentCVData || {});
    }

    showTOONEditor() {
        if (!this.jsonEditor) return;

        const toonContent = this.generateTOON(this.currentCVData || {});

        this.jsonEditor.innerHTML = `<pre style="color: #94a3b8; font-family: monospace; font-size: 0.875rem; line-height: 1.5;">${toonContent}</pre>`;
    }

    generateTOON(cvData) {
        const basics = cvData.basics || {};
        const work = cvData.work || [];
        const education = cvData.education || [];
        const skills = cvData.skills || [];
        const projects = cvData.projects || [];

        return `// TOON - Template Object Oriented Notation
// Representación simplificada y legible del CV

@person {
  name: "${basics.name || 'Nombre Completo'}"
  title: "${basics.label || 'Título Profesional'}"
  email: "${basics.email || 'email@ejemplo.com'}"
  phone: "${basics.phone || 'Teléfono'}"
  location: "${typeof basics.location === 'object' ? (basics.location.city + ', ' + basics.location.region) : (basics.location || 'Ciudad, País')}"
  website: "${basics.url || 'https://portafolio.com'}"
  summary: "${basics.summary || 'Resumen profesional'}"
}

@experience {
${work.map((job, index) => `  job_${index + 1} {
    company: "${job.name || 'Empresa'}"
    position: "${job.position || 'Cargo'}"
    location: "${job.location || 'Ubicación'}"
    start_date: "${job.startDate || 'YYYY-MM'}"
    end_date: "${job.endDate || 'Actual'}"
    highlights: [
${(job.highlights || []).map(h => `      "${h}"`).join(',\n')}
    ]
  }`).join('\n\n')}
}

@education {
${education.map((edu, index) => `  degree_${index + 1} {
    institution: "${edu.institution || 'Universidad'}"
    degree: "${edu.studyType || 'Grado'}"
    field: "${edu.area || 'Campo de estudio'}"
    start_date: "${edu.startDate || 'YYYY'}"
    end_date: "${edu.endDate || 'YYYY'}"
  }`).join('\n\n')}
}

@projects {
${projects.map((project, index) => `  project_${index + 1} {
    name: "${project.name || 'Nombre del Proyecto'}"
    role: "${project.role || 'Rol en el Proyecto'}"
    description: "${project.description || 'Descripción'}"
    technologies: [${(project.technologies || []).map(t => `"${t}"`).join(', ')}]
    start_date: "${project.startDate || 'YYYY-MM'}"
    end_date: "${project.endDate || 'YYYY-MM'}"
  }`).join('\n\n')}
}

@skills {
${skills.map((skillGroup, index) => `  category_${index + 1} {
    name: "${skillGroup.name || 'Categoría'}"
    level: "${skillGroup.level || 'Intermedio'}"
    keywords: [${(skillGroup.keywords || []).map(k => `"${k}"`).join(', ')}]
  }`).join('\n\n')}
}

// Generar CV con: toon.compile() → JSON Schema
// Exportar como: toon.export('pdf') | toon.export('html')`;
    }

    async loadTemplates() {
        try {
            const response = await fetch('/cv/templates');
            const data = await response.json();

            if (data.success && data.templates) {
                this.availableTemplates = data.templates;

                // Buscar Harvard Professional como plantilla por defecto
                const harvardTemplate = data.templates.find(t =>
                    t.name === 'Harvard Profesional' || t.name.toLowerCase().includes('harvard')
                );

                if (harvardTemplate) {
                    this.currentTemplate = harvardTemplate.id;
                } else {
                    this.currentTemplate = data.templates[0].id;
                    console.log('Harvard no encontrado, usando:', data.templates[0].name);
                }

                return true;
            }
            return false;
        } catch (error) {
            console.error('Error loading templates:', error);
            return false;
        }
    }

    async checkAuthentication() {
        try {
            const response = await fetch('/auth/check');
            const data = await response.json();
            this.isAuthenticated = data.authenticated;
        } catch (error) {
            console.error('Error checking authentication:', error);
            this.isAuthenticated = false;
        }
    }

    async loadUserCV() {
        try {
            const response = await fetch('/cv/user-cv');
            const data = await response.json();

            if (data.success && data.has_cv && data.cv && data.cv.cv_data) {
                // Usuario autenticado tiene CV guardado en la BD
                this.userCV = {
                    id: data.cv.id,
                    slug: data.cv.slug,
                    title: data.cv.title,
                    template_id: data.cv.template_id
                };

                try {
                    this.currentCVData = JSON.parse(data.cv.cv_data);

                    // Establecer la plantilla correcta si existe template_id en la BD
                    if (data.cv.template_id) {
                        this.currentTemplate = data.cv.template_id;

                        this.updateCustomSelect();
                    }

                    this.displayJSONInEditor(this.currentCVData);

                    // Cargar el título en el editor de nombre
                    if (this.filenameEditor && this.currentCVData.basics?.name) {
                        this.filenameEditor.textContent = this.currentCVData.basics.name;
                    }

                    this.updatePreview();
                    this.markAsSaved();
                    this.updateSaveStatus('Auto guardado', 'success');
                } catch (parseError) {
                    console.error('Error parsing user CV data:', parseError);
                    await this.loadExampleCV();
                }
            } else if (data.success && !data.has_cv) {
                this.userCV = null;
                await this.loadExampleCV();
            } else {
                console.log('Loading example CV (not authenticated)');
                await this.loadExampleCV();
            }
        } catch (error) {
            console.error('Error loading user CV:', error);
            await this.loadExampleCV();
        }
    }

    async loadExampleCV() {
        try {
            // Para usuarios no autenticados, primero intentar cargar desde localStorage
            if (!this.isAuthenticated) {
                const savedData = localStorage.getItem('codeoner_cv_data');
                if (savedData) {
                    this.loadFromLocalStorage();
                    return;
                }
            }

            // Si no hay datos en localStorage o es usuario autenticado, cargar ejemplo del servidor
            const response = await fetch('/cv/example');
            const data = await response.json();

            if (data.success && data.cv_data) {
                this.currentCVData = data.cv_data;

                // Para usuarios no autenticados, usar Harvard por defecto si no hay plantilla seleccionada
                if (!this.isAuthenticated && !this.currentTemplate) {
                    const harvardTemplate = this.availableTemplates.find(t =>
                        t.name === 'Harvard Profesional' || t.name.toLowerCase().includes('harvard')
                    );
                    if (harvardTemplate) {
                        this.currentTemplate = harvardTemplate.id;
                    }
                }

                this.displayJSONInEditor(this.currentCVData);

                // Cargar título en el editor de nombre
                if (this.filenameEditor && this.currentCVData.basics?.name) {
                    this.filenameEditor.textContent = this.currentCVData.basics.name;
                }

                this.updatePreview();
                this.markAsSaved();
                this.updateSaveStatus('Auto guardado', 'success');
            } else {
                console.error('Error loading example CV from server');
            }
        } catch (error) {
            console.error('Error loading example CV:', error);
        }
    }

    uploadJSONFile() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.json,.txt';

        input.onchange = (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                try {
                    const jsonData = JSON.parse(e.target.result);
                    this.currentCVData = jsonData;
                    this.displayJSONInEditor(jsonData);
                    this.updatePreview();
                    this.validateJSON(true);
                    this.saveToLocalStorage();

                    this.showNotification('JSON cargado exitosamente', 'success');
                } catch (error) {
                    this.showNotification('Error: Archivo JSON inválido', 'error');
                    this.validateJSON(false);
                }
            };
            reader.readAsText(file);
        };

        input.click();
    }

    handlePaste(e) {
        e.preventDefault();

        let pastedText = (e.clipboardData || window.clipboardData).getData('text');

        try {
            const jsonData = JSON.parse(pastedText);
            this.currentCVData = jsonData;
            this.displayJSONInEditor(jsonData);
            this.updatePreview();
            this.validateJSON(true);
            this.saveToLocalStorage();

            this.showNotification('JSON pegado exitosamente', 'success');
        } catch (error) {
            // Si no es JSON válido, insertar como texto plano
            document.execCommand('insertText', false, pastedText);
        }
    }

    handleJSONChange() {
        const jsonText = this.getPlainTextFromEditor();

        try {
            const newData = JSON.parse(jsonText);
            this.currentCVData = newData;

            // Detectar cambios
            const currentDataString = JSON.stringify(newData);
            const lastDataString = this.lastSavedData ? JSON.stringify(this.lastSavedData) : null;

            if (currentDataString !== lastDataString) {
                this.hasUnsavedChanges = true;
                this.updateSaveStatus('Guardando...', 'saving');

                // Siempre auto-guardar cuando hay cambios
                this.scheduleAutoSave();
            }

            this.updatePreview();
            this.validateJSON(true);
        } catch (error) {
            this.validateJSON(false);
        }
    }

    initializeResizer() {
        const resizer = document.getElementById('resizer');
        const leftPanel = document.getElementById('left-panel');
        const rightPanel = document.getElementById('right-panel');

        if (!resizer || !leftPanel || !rightPanel) return;

        // Solo inicializar resizer en desktop
        const initializeDesktopResizer = () => {
            if (window.innerWidth < 1024) return; // lg breakpoint

            let isResizing = false;

            const handleMouseDown = (e) => {
                isResizing = true;
                document.body.style.cursor = 'col-resize';
                document.body.style.userSelect = 'none';

                const startX = e.clientX;
                const startLeftWidth = leftPanel.offsetWidth;
                const startRightWidth = rightPanel.offsetWidth;
                const totalWidth = startLeftWidth + startRightWidth;

                const handleMouseMove = (e) => {
                    if (!isResizing) return;

                    const currentX = e.clientX;
                    const diffX = currentX - startX;

                    let newLeftWidth = startLeftWidth + diffX;
                    let newRightWidth = startRightWidth - diffX;

                    const minWidth = totalWidth * 0.2;

                    if (newLeftWidth < minWidth) {
                        newLeftWidth = minWidth;
                        newRightWidth = totalWidth - minWidth;
                    } else if (newRightWidth < minWidth) {
                        newRightWidth = minWidth;
                        newLeftWidth = totalWidth - minWidth;
                    }

                    const leftPercent = (newLeftWidth / totalWidth) * 100;
                    const rightPercent = (newRightWidth / totalWidth) * 100;

                    leftPanel.style.setProperty('--lg-width', leftPercent + '%');
                    rightPanel.style.setProperty('--lg-width', rightPercent + '%');
                    leftPanel.style.width = leftPercent + '%';
                    rightPanel.style.width = rightPercent + '%';
                };

                const handleMouseUp = () => {
                    isResizing = false;
                    document.body.style.cursor = '';
                    document.body.style.userSelect = '';
                    document.removeEventListener('mousemove', handleMouseMove);
                    document.removeEventListener('mouseup', handleMouseUp);
                };

                document.addEventListener('mousemove', handleMouseMove);
                document.addEventListener('mouseup', handleMouseUp);
            };

            resizer.addEventListener('mousedown', handleMouseDown);
        };

        // Inicializar en desktop y re-inicializar en resize
        initializeDesktopResizer();

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                initializeDesktopResizer();
            }
        });
    }

    toggleWordWrap() {
        if (!this.jsonEditor) return;

        const currentStyle = this.jsonEditor.style.whiteSpace;
        const isWrapped = currentStyle === 'pre-wrap';

        if (isWrapped) {
            this.jsonEditor.style.whiteSpace = 'pre';
            this.jsonEditor.style.wordWrap = 'normal';
            this.jsonEditor.style.overflowWrap = 'normal';
            this.wordWrapBtn.style.color = '#64748b';
        } else {
            this.jsonEditor.style.whiteSpace = 'pre-wrap';
            this.jsonEditor.style.wordWrap = 'break-word';
            this.jsonEditor.style.overflowWrap = 'break-word';
            this.wordWrapBtn.style.color = '#00e1ff';
        }
    }

    scheduleAutoSave() {
        if (this.autoSaveTimeout) {
            clearTimeout(this.autoSaveTimeout);
        }

        this.autoSaveTimeout = setTimeout(() => {
            this.performAutoSave();
        }, this.autoSaveDelay);
    }

    async performAutoSave() {
        if (!this.currentCVData || !this.hasUnsavedChanges || this.isSaving) return;

        this.isSaving = true;

        try {
            if (this.isAuthenticated) {
                await this.saveToDatabase();
            } else {
                this.saveToLocalStorage();
                this.markAsSaved();
                this.updateSaveStatus('Auto guardado', 'success');
            }
        } catch (error) {
            console.error('Auto-save failed:', error);
            this.updateSaveStatus('Error al guardar', 'error');
        } finally {
            this.isSaving = false;
        }
    }

    displayJSONInEditor(jsonData) {
        if (!this.jsonEditor) {
            console.warn('JSON Editor element not found, cannot display JSON');
            return;
        }

        let isTOONMode = false;
        const allSpans = document.querySelectorAll('span');
        allSpans.forEach(span => {
            const text = span.textContent.trim();
            if (text === 'TOON Source' && span.className.includes('text-[#00e1ff]')) {
                isTOONMode = true;
            }
        });

        if (isTOONMode) {
            this.showTOONEditor();
        } else {
            const formattedJSON = this.syntaxHighlight(JSON.stringify(jsonData, null, 2));
            this.jsonEditor.innerHTML = formattedJSON;
            this.updateLineNumbers();
        }
    }

    syntaxHighlight(json) {
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            let cls = 'text-white';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'token-key';
                } else {
                    cls = 'token-string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'text-green-400';
            } else if (/null/.test(match)) {
                cls = 'text-red-400';
            } else if (/\d/.test(match)) {
                cls = 'text-blue-400';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
    }

    getPlainTextFromEditor() {
        return this.jsonEditor.textContent || this.jsonEditor.innerText || '';
    }

    updateLineNumbers() {
        const lines = this.getPlainTextFromEditor().split('\n');
        const lineNumberContainer = document.querySelector('.w-12.flex-shrink-0');

        if (lineNumberContainer) {
            let lineNumberHTML = '';
            for (let i = 1; i <= lines.length; i++) {
                lineNumberHTML += i + '<br>';
            }
            lineNumberContainer.innerHTML = lineNumberHTML;
        }
    }

    formatJSON() {
        if (this.currentCVData) {
            this.displayJSONInEditor(this.currentCVData);
            this.showNotification('JSON formateado', 'success');
        }
    }

    copyJSON() {
        const jsonText = JSON.stringify(this.currentCVData, null, 2);

        if (navigator.clipboard) {
            navigator.clipboard.writeText(jsonText).then(() => {
                this.showNotification('JSON copiado al portapapeles', 'success');
            });
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = jsonText;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            this.showNotification('JSON copiado al portapapeles', 'success');
        }
    }

    async changeTemplate(templateId) {
        this.currentTemplate = templateId;

        // Mostrar estado "Guardando" inmediatamente
        if (this.isAuthenticated && this.currentCVData) {
            this.hasUnsavedChanges = true;
            this.updateSaveStatus('Guardando...', 'saving');
            this.scheduleAutoSave();
        }

        await this.updatePreview();

        if (!this.currentCVData) return;

        if (!this.previewContainer) {
            console.warn('Preview container not found, cannot update preview');
            return;
        }

        const cvHTML = this.generateCVHTML(this.currentCVData, this.currentTemplate);
        this.previewContainer.innerHTML = cvHTML;
    }

    generateCVHTML(cvData, template) {
        const basics = cvData.basics || {};
        const work = cvData.work || [];
        const projects = cvData.projects || [];
        const education = cvData.education || [];
        const skills = cvData.skills || [];
        const languages = cvData.languages || [];

        // Verificar si es la plantilla Harvard
        const isHarvardTemplate = this.availableTemplates.find(t =>
            t.id === template &&
            (t.name === 'Harvard Profesional' || t.name.toLowerCase().includes('harvard'))
        );

        // Verificar si es la plantilla Creativo Moderno
        const isModernChronological = this.availableTemplates.find(t =>
            t.id === template &&
            (t.name === 'Modern Chronological' || t.name.toLowerCase().includes('modern chronological') ||
                t.name.toLowerCase().includes('chronological') || t.name.toLowerCase().includes('platinum'))
        );

        if (isHarvardTemplate) {
            return this.generateHarvardTemplate(cvData);
        }

        if (isModernChronological) {
            return this.generateModernChronologicalTemplate(cvData);
        }

        let html = `
            <!-- CV Header -->
            <header class="mb-8 border-b border-slate-200 pb-6 flex flex-col sm:flex-row items-center sm:items-start gap-6">
    ${basics.image ? `
        <div class="flex-shrink-0">
            <img 
                src="${basics.image}" 
                alt="${basics.name}" 
                class="w-32 h-32 object-cover rounded-xl border border-slate-100 shadow-sm"
            />
        </div>
    ` : ''}

    <div class="flex-1 text-center sm:text-left">
        <h1 class="text-4xl font-bold text-slate-900 tracking-tight mb-2">
            ${basics.name || ''}
        </h1>
        
        ${basics.label ? `
            <p class="text-lg text-slate-600 font-medium mb-4">
                ${basics.label}
            </p>
        ` : ''}
        
        <div class="flex flex-wrap justify-center sm:justify-start gap-4 text-xs text-slate-500 font-medium">
            ${basics.location ? `
                <div class="flex items-center gap-1">
                    <iconify-icon icon="solar:map-point-linear" width="14"></iconify-icon>
                    ${typeof basics.location === 'object' ? `${basics.location.city || ''}, ${basics.location.region || ''}` : basics.location}
                </div>` : ''}

            ${basics.url ? `
                <a href="${basics.url}" target="_blank" class="flex items-center gap-1 text-blue-600">
                    <iconify-icon icon="line-md:link" width="14"></iconify-icon>
                    ${basics.url.replace(/^https?:\/\//, '')}
                </a>` : ''}

            ${basics.phone ? `
                <div class="flex items-center gap-1">
                    <iconify-icon icon="solar:phone-linear" width="14"></iconify-icon>
                    ${basics.phone}
                </div>` : ''}

            ${basics.email ? `
                <div class="flex items-center gap-1">
                    <iconify-icon icon="solar:letter-linear" width="14"></iconify-icon>
                    ${basics.email}
                </div>` : ''}
        </div>
    </div>
</header>

            ${basics.summary ? `
            <section class="mb-8">
                <p class="text-sm text-slate-600 leading-relaxed">${basics.summary}</p>
            </section>
            ` : ''}

            ${work.length > 0 ? `
            <section class="mb-8">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Experiencia Profesional</h2>
                ${work.map(job => `
                    <div class="mb-6">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-slate-800">${job.name || ''}</h3>
                            <span class="text-xs text-slate-500 font-mono">${this.formatDateRange(job.startDate, job.endDate)}</span>
                        </div>
                        <div class="text-sm text-slate-600 italic mb-2">${job.position || ''}</div>
                        ${job.location ? `<div class="text-xs text-slate-500 mb-2">${job.location}</div>` : ''}
                        ${job.highlights && job.highlights.length > 0 ? `
                            <ul class="list-disc list-outside ml-4 space-y-1 text-sm text-slate-600 leading-relaxed">
                                ${job.highlights.map(highlight => `<li>${highlight}</li>`).join('')}
                            </ul>
                        ` : ''}
                    </div>
                `).join('')}
            </section>
            ` : ''}

            ${projects.length > 0 ? `
            <section class="mb-8">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Proyectos</h2>
                ${projects.map(project => `
                    <div class="mb-6">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-slate-800">${project.name || ''}</h3>
                            <span class="text-xs text-slate-500 font-mono">${this.formatDateRange(project.startDate, project.endDate)}</span>
                        </div>
                        ${project.role ? `<div class="text-sm text-slate-600 italic mb-1">${project.role}</div>` : ''}
                        ${project.type ? `<div class="text-xs text-slate-500 mb-2">${project.type}</div>` : ''}
                        ${project.description ? `<p class="text-sm text-slate-600 mb-2">${project.description}</p>` : ''}
                        ${project.highlights && project.highlights.length > 0 ? `
                            <ul class="list-disc list-outside ml-4 space-y-1 text-sm text-slate-600 leading-relaxed">
                                ${project.highlights.map(highlight => `<li>${highlight}</li>`).join('')}
                            </ul>
                        ` : ''}
                        ${project.technologies && project.technologies.length > 0 ? `
                            <div class="mt-2">
                                <span class="text-xs font-medium text-slate-700">Tecnologías: </span>
                                <span class="text-xs text-slate-600">${project.technologies.join(', ')}</span>
                            </div>
                        ` : ''}
                    </div>
                `).join('')}
            </section>
            ` : ''}

            ${education.length > 0 ? `
            <section class="mb-8">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Educación</h2>
                ${education.map(edu => `
                    <div class="mb-4">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-bold text-slate-800">${edu.institution || ''}</h3>
                            <span class="text-xs text-slate-500 font-mono">${this.formatDateRange(edu.startDate, edu.endDate)}</span>
                        </div>
                        ${edu.studyType ? `<div class="text-sm text-slate-600">${edu.studyType}</div>` : ''}
                        ${edu.area ? `<div class="text-sm text-slate-600">${edu.area}</div>` : ''}
                        ${edu.location ? `<div class="text-xs text-slate-500">${edu.location}</div>` : ''}
                    </div>
                `).join('')}
            </section>
            ` : ''}

            ${skills.length > 0 ? `
            <section class="mb-8">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Habilidades</h2>
                ${skills.map(skillGroup => `
                    <div class="mb-3">
                        <h4 class="text-sm font-semibold text-slate-800 mb-2">${skillGroup.name || ''}</h4>
                        <div class="flex flex-wrap gap-2">
                            ${(skillGroup.keywords || []).map(skill =>
            `<span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-medium rounded">${skill}</span>`
        ).join('')}
                        </div>
                    </div>
                `).join('')}
            </section>
            ` : ''}

            ${languages.length > 0 ? `
            <section>
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Idiomas</h2>
                <div class="grid grid-cols-3 gap-2">
                    ${languages.map(lang => `
                        <div class="text-sm">
                            <span class="font-medium text-slate-800">${lang.language || ''}</span>
                            <span class="text-slate-600"> - ${lang.fluency || ''}</span>
                        </div>
                    `).join('')}
                </div>
            </section>
            ` : ''}
        `;

        return html;
    }

    generateHarvardTemplate(cvData) {
        const basics = cvData.basics || {};
        const work = cvData.work || [];
        const projects = cvData.projects || [];
        const education = cvData.education || [];
        const skills = cvData.skills || [];
        const languages = cvData.languages || [];

        return `
            <style>
                .font-stix {
                    font-family: 'STIX Two Text', serif;
                }
            </style>
            
            <div class="font-stix text-slate-900">
                <!-- Header -->
                <header class="text-center mb-3">
                    <h1 class="text-3xl font-bold tracking-tight mb-2">${basics.name || 'Nombre Completo'}</h1>
                    <div class="flex justify-center flex-wrap gap-2 text-[11pt] text-slate-800">
                        ${basics.location ? `<span>${typeof basics.location === 'object' ? (basics.location.city + ', ' + basics.location.region) : basics.location}</span>` : ''}
                        ${basics.location && basics.url ? '<span class="text-slate-500">•</span>' : ''}
                        ${basics.url ? `<a href="${basics.url}" target="_blank" class="text-blue-600">${basics.url.replace(/^https?:\/\//, '')}</a>` : ''}
                        ${(basics.location || basics.url) && basics.phone ? '<span class="text-slate-500">•</span>' : ''}
                        ${basics.phone ? `<span>${basics.phone}</span>` : ''}
                        ${((basics.location || basics.url || basics.phone) && basics.email) ? '<span class="text-slate-500">•</span>' : ''}
                        ${basics.email ? `<span>${basics.email}</span>` : ''}
                    </div>
                    <hr class="mt-1 border-slate-300 border-b">
                </header>
    
                <!-- Summary -->
                ${basics.summary ? `
                <section class="mb-3">
                    <p class="text-[11pt] text-slate-800 italic leading-snug">
                        ${basics.summary}
                    </p>
                </section>
                ` : ''}
            <!-- Experiencia Laboral -->
                ${work.length > 0 ? `
                <section class="mb-3">
                    <h2 class="text-md font-bold uppercase tracking-wide border-b-2 border-slate-300 mb-3">Experiencia Profesional</h2>
                    ${work.map((job, index) => `
                        <div class="${index < work.length - 1 ? 'mb-5' : ''}">
                            <div class="flex justify-between items-baseline">
                                <h3 class="font-bold text-[12pt]">${job.name || 'Empresa'}</h3>
                                <span class="font-bold text-[11pt]">${job.location || ''}</span>
                            </div>
                            <div class="flex justify-between items-baseline mb-2">
                                <span class="text-[11pt]">${job.position || 'Cargo'}</span>
                                <span class="italic text-[10pt]">${this.formatDateRange(job.startDate, job.endDate)}</span>
                            </div>
                            ${job.highlights && job.highlights.length > 0 ? `
                                <ul class="list-disc list-outside ml-5 space-y-0.5 text-[10.5pt] text-slate-800">
                                    ${job.highlights.map(highlight => `<li>${highlight}</li>`).join('')}
                                </ul>
                            ` : ''}
                        </div>
                    `).join('')}
                </section>
                ` : ''}
                <!-- Proyectos -->
                ${projects.length > 0 ? `
                <section class="mb-3">
                    <h2 class="text-md font-bold uppercase tracking-wide border-b-2 border-slate-300 mb-3">Proyectos</h2>
                    ${projects.map((project, index) => `
                        <div class="${index < projects.length - 1 ? 'mb-5' : ''}">
                            <div class="flex justify-between items-baseline">
                                <h3 class="font-bold text-[12pt]">${project.name || 'Proyecto'}</h3>
                                <span class="font-bold text-[11pt]">${project.type || 'Proyecto Personal'}</span>
                            </div>
                            <div class="flex justify-between items-baseline mb-2">
                                <span class="text-[11pt]">${project.role || 'Desarrollador'}</span>
                                <span class="italic text-[10pt]">${this.formatDateRange(project.startDate, project.endDate)}</span>
                            </div>
                            ${project.highlights && project.highlights.length > 0 ? `
                                <ul class="list-disc list-outside ml-5 space-y-0.5 text-[10.5pt] text-slate-800">
                                    ${project.highlights.map(highlight => `<li>${highlight}</li>`).join('')}
                                </ul>
                            ` : ''}
                            ${project.technologies && project.technologies.length > 0 ? `
                                <p class="text-[10pt] mt-1">
                                    <span class="italic">Tecnologías:</span> ${project.technologies.join(', ')}
                                </p>
                            ` : ''}
                        </div>
                    `).join('')}
                </section>
                ` : ''}
    
                <!-- Educación -->
                ${education.length > 0 ? `
                <section class="mb-3">
                    <h2 class="text-md font-bold uppercase tracking-wide border-b-2 border-slate-300 mb-3">Educación</h2>
                    ${education.map(edu => `
                        <div class="flex justify-between items-baseline">
                            <h3 class="font-bold text-[12pt]">${edu.institution || 'Universidad'}</h3>
                            <span class="font-bold text-[11pt]">${edu.location || ''}</span>
                        </div>
                        <div class="flex justify-between items-baseline">
                            <span class="text-[11pt]">${edu.studyType || ''} ${edu.area ? 'en ' + edu.area : ''}</span>
                            <span class="italic text-[10pt]">${this.formatDateRange(edu.startDate, edu.endDate)}</span>
                        </div>
                    `).join('')}
                </section>
                ` : ''}
    
                <!-- Skills -->
                ${skills.length > 0 ? `
                <section>
                    <h2 class="text-md font-bold uppercase tracking-wide border-b-2 border-slate-300 mb-3">Skills Adicionales</h2>
                    <ul class="list-disc list-outside ml-5 space-y-1 text-[11pt] text-slate-800">
                        ${skills.map(skillGroup => {
            const skillsText = skillGroup.keywords ? skillGroup.keywords.join(', ') : skillGroup.name;
            return `<li>${skillsText}</li>`;
        }).join('')}
                        ${languages.length > 0 ? `<li>Idiomas: ${languages.map(lang => `${lang.language} (${lang.fluency || 'Intermedio'})`).join(', ')}</li>` : ''}
                    </ul>
                </section>
                ` : ''}
            </div>
        `;
    }

    generateModernChronologicalTemplate(cvData) {
        const basics = cvData.basics || {};
        const work = cvData.work || [];
        const projects = cvData.projects || [];
        const education = cvData.education || [];
        const skills = cvData.skills || [];
        const languages = cvData.languages || [];

        return `
            <style>
                .cv-body { font-family: 'Inter', sans-serif; line-height: 1.5; color: #1a202c; }
                .section-header { 
                    display: flex; 
                    align-items: center; 
                    text-transform: uppercase; 
                    letter-spacing: 0.05em; 
                    font-weight: 700; 
                    font-size: 10pt; 
                    color: #2d3748;
                    border-bottom: 1px solid #e2e8f0;
                    margin-bottom: 12px;
                    margin-top: 20px;
                    padding-bottom: 4px;
                }
            </style>

            <div class="cv-body p-2">
                <!-- Header Minimalista -->
                <header class="text-center mb-8">
                    <h1 class="text-4xl font-extrabold tracking-tighter text-slate-900 mb-3">
                        ${basics.name || 'Nombre Completo'}
                    </h1>
                    <div class="flex justify-center flex-wrap gap-x-4 text-[9.5pt] text-slate-600 font-medium">
                        ${basics.phone ? `<span>${basics.phone}</span>` : ''}
                        ${basics.phone && basics.email ? '<span class="text-slate-300">|</span>' : ''}
                        ${basics.email ? `<span>${basics.email}</span>` : ''}
                        ${(basics.phone || basics.email) && basics.location ? '<span class="text-slate-300">|</span>' : ''}
                        ${basics.location ? `<span>${typeof basics.location === 'object' ? (basics.location.city || basics.location) : basics.location}</span>` : ''}
                        ${(basics.phone || basics.email || basics.location) && basics.url ? '<span class="text-slate-300">|</span>' : ''}
                        ${basics.url ? `<a href="${basics.url}" class="text-slate-900 underline decoration-slate-300">Portafolio</a>` : ''}
                    </div>
                </header>

                <!-- Resumen Ejecutivo -->
                ${basics.summary ? `
                <section>
                    <h2 class="section-header">Perfil Profesional</h2>
                    <p class="text-[10pt] text-slate-700 text-justify">
                        ${basics.summary}
                    </p>
                </section>
                ` : ''}

                <!-- Experiencia: El núcleo del CV -->
                ${work.length > 0 ? `
                <section>
                    <h2 class="section-header">Experiencia Profesional</h2>
                    ${work.map(job => `
                        <div class="mb-5">
                            <div class="flex justify-between items-baseline mb-1">
                                <h3 class="text-[11pt] font-bold text-slate-900">${job.name || 'Empresa'}</h3>
                                <span class="text-[9pt] font-semibold text-slate-500">${this.formatDateRange(job.startDate, job.endDate)}</span>
                            </div>
                            <div class="flex justify-between items-baseline mb-2">
                                <span class="text-[10pt] font-medium text-slate-700 italic">${job.position || 'Cargo'}</span>
                                <span class="text-[9pt] text-slate-400">${job.location || ''}</span>
                            </div>
                            ${job.highlights && job.highlights.length > 0 ? `
                                <ul class="list-disc ml-4 space-y-1">
                                    ${job.highlights.map(h => `<li class="text-[9.5pt] text-slate-700 pl-1">${h}</li>`).join('')}
                                </ul>
                            ` : ''}
                        </div>
                    `).join('')}
                </section>
                ` : ''}

                <!-- Habilidades: Agrupadas para lectura rápida -->
                ${skills.length > 0 ? `
                <section>
                    <h2 class="section-header">Habilidades y Competencias</h2>
                    <div class="grid grid-cols-1 gap-2">
                        ${skills.map(skill => `
                            <p class="text-[9.5pt]">
                                <span class="font-bold text-slate-800">${skill.name || 'Categoría'}:</span>
                                <span class="text-slate-600">${skill.keywords ? skill.keywords.join(', ') : ''}</span>
                            </p>
                        `).join('')}
                    </div>
                </section>
                ` : ''}

                <!-- Educación -->
                ${education.length > 0 ? `
                <section>
                    <h2 class="section-header">Formación Académica</h2>
                    ${education.map(edu => `
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="text-[10pt] font-bold text-slate-900">${edu.institution || 'Universidad'}</h3>
                            <span class="text-[9pt] text-slate-500">${this.formatDateRange(edu.startDate, edu.endDate)}</span>
                        </div>
                        <p class="text-[9.5pt] text-slate-700">
                            ${(edu.studyType || '') + (edu.area ? " en " + edu.area : (edu.field ? " en " + edu.field : ""))}
                        </p>
                    `).join('')}
                </section>
                ` : ''}
            </div>
        `;
    }

    formatDateRange(startDate, endDate) {
        const formatDate = (dateStr) => {
            if (!dateStr) return '';
            try {
                const date = new Date(dateStr);
                return date.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'short'
                });
            } catch (e) {
                return dateStr;
            }
        };

        const start = formatDate(startDate);
        const end = endDate ? formatDate(endDate) : 'Actual';

        return start && end ? `${start} - ${end}` : (start || end || '');
    }

    validateJSON(isValid) {
        const statusContainer = this.validationStatus;
        if (!statusContainer) return;

        const statusIcon = statusContainer.querySelector('iconify-icon');
        const statusText = statusContainer.querySelector('span');

        if (isValid) {
            statusContainer.className = 'h-8 bg-[#0099ff]/10 border-t border-[#0099ff]/20 flex items-center justify-between px-4 text-xs';
            statusContainer.querySelector('.flex').className = 'flex items-center gap-2 text-[#00e1ff]';
            statusIcon.setAttribute('icon', 'solar:check-circle-linear');
            statusText.textContent = 'JSON válido';
        } else {
            statusContainer.className = 'h-8 bg-red-500/10 border-t border-red-500/20 flex items-center justify-between px-4 text-xs';
            statusContainer.querySelector('.flex').className = 'flex items-center gap-2 text-red-400';
            statusIcon.setAttribute('icon', 'line-md:close-small');
            statusText.textContent = 'JSON inválido';
        }
    }

    async saveToDatabase() {
        try {
            const response = await fetch('/cv/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    cv_data: this.currentCVData,
                    template_id: this.currentTemplate,
                    title: this.currentCVData.basics?.name || 'Mi CV'
                })
            });

            if (response.ok) {
                const result = await response.json();

                // Actualizar userCV con la respuesta del servidor
                this.userCV = {
                    id: result.cv_id,
                    slug: result.slug,
                    title: this.currentCVData.basics?.name || 'Mi CV'
                };

                this.markAsSaved();
                this.updateSaveStatus('Guardado', 'success');
            } else {
                const error = await response.json();
                this.updateSaveStatus('Error al guardar', 'error');
                console.error('Save error:', error.message);
            }
        } catch (error) {
            console.error('Save to database failed:', error);
            this.updateSaveStatus('Error al guardar', 'error');
        }
    }

    async exportToPDF() {
        if (this.isMobileDevice()) {
            this.showNotification(
                'Para descargar PDF, por favor accede desde una laptop o PC. Los dispositivos móviles no soportan esta función.',
                'warning'
            );
            return;
        }

        try {
            let isHarvardTemplate = false;
            let isModernChronological = false;
            const templateObj = this.availableTemplates.find(t => t.id === this.currentTemplate);

            if (templateObj) {
                isHarvardTemplate = templateObj.name === 'Harvard Profesional' ||
                    templateObj.name.toLowerCase().includes('harvard');
                isModernChronological = templateObj.name === 'Modern Chronological' ||
                    templateObj.name.toLowerCase().includes('modern chronological') ||
                    templateObj.name.toLowerCase().includes('chronological') ||
                    templateObj.name.toLowerCase().includes('platinum');
            }

            const cvContent = this.previewContainer.innerHTML;
            const cvTitle = this.userCV?.title || this.filenameEditor?.textContent?.trim() || 'Mi CV';
            const filename = this.generateSlug(cvTitle) + '.pdf';

            const printWindow = window.open('', '_blank');

            let fontImport, bodyStyles;

            if (isHarvardTemplate) {
                fontImport = "@import url('https://fonts.googleapis.com/css2?family=STIX+Two+Text:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap');";
                bodyStyles = `
                    body {
                        font-family: 'STIX Two Text', serif;
                        line-height: 1.4;
                        color: #0f172a;
                    }
                    .font-stix {
                        font-family: 'STIX Two Text', serif;
                    }
                    .text-[11pt] {
                        font-size: 10pt;
                    }
                    .text-\[12pt\] {
                        font-size: 10pt;
                    }
                    .text-\[10pt\] {
                        font-size: 10pt;
                    }
                    .text-\[10\.5pt\] {
                        font-size: 10.5pt;
                    }
                    .border-\[1\.5px\] {
                        border-width: 0.5px;
                    }
                    .border-slate-300 {
                        border-color: #cbd5e1;
                    }
                    .border-b-2 {
                        border-bottom-width: 1px;
                    }
                    .text-center{
                        text-align: center;
                    }
                    .mb-4{
                        margin-bottom: 0.5rem;
                    }
                    .flex{
                        display: flex;
                    }
                    .justify-center{
                        justify-content: center;
                    }
                    .flex-wrap{
                        flex-wrap: wrap;
                    }
                    .text-[11pt]{
                        font-size: 10pt;
                    }
                    .gap-2{
                        gap: 0.5rem;
                    }
                    .list-disc{
                        list-style-type: disc;
                    }
                    .list-outside{
                        list-style-position: outside;
                    }
                    .tracking-wide{
                        letter-spacing: 0.05em;
                    }
                    .ml-5{
                        margin-left: 1.25rem;
                    }
                    .mb-3 { margin-bottom: 0.1rem; }
                    .space-y-0.5{
                        row-gap: 0.125rem;}
                    
                    .text-[10.5pt]{
                        font-size: 10.5pt;
                    }
                    h2{
                        margin:0;
                        padding:0;
                    }
                    h1, h2, h3, p, ul {
                        font-size: inherit; 
                        font-weight: inherit; 
                    }

                    a {
                        color: inherit;
                        text-decoration: inherit; 
                    }

                    ul {
                        list-style: none; 
                    }
                    .text-slate-900 {
                        color: #0f172a;
                    }
                    .text-slate-800 {
                        color: #1e293b;
                    }
                    .text-slate-700 {
                        color: #334155;
                    }
                    .text-slate-500 {
                        color: #64748b;
                    }
                    .text-blue-600 {
                        color: #2563eb;
                    }
                `;
            } else if (isModernChronological) {
                // Estilos para plantilla Modern Chronological / Platinum Standard
                fontImport = "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');";
                bodyStyles = `
                    body {
                        font-family: 'Inter', sans-serif;
                        line-height: 1.5;
                        color: #1a202c;
                        padding: 10mm;
                    }
                    .cv-body {
                        font-family: 'Inter', sans-serif;
                        line-height: 1.5;
                        color: #1a202c;
                    }
                    .section-header {
                        display: flex;
                        align-items: center;
                        text-transform: uppercase;
                        letter-spacing: 0.05em;
                        font-weight: 700;
                        font-size: 10pt;
                        color: #2d3748;
                        border-bottom: 1px solid #e2e8f0;
                        margin-bottom: 12px;
                        margin-top: 20px;
                        padding-bottom: 4px;
                    }
                    .text-4xl {
                        font-size: 24pt;
                    }
                    .font-extrabold {
                        font-weight: 800;
                    }
                    .tracking-tighter {
                        letter-spacing: -0.025em;
                    }
                    .text-slate-900 {
                        color: #0f172a;
                    }
                    .text-slate-800 {
                        color: #1e293b;
                    }
                    .text-slate-700 {
                        color: #334155;
                    }
                    .text-slate-600 {
                        color: #475569;
                    }
                    .text-slate-500 {
                        color: #64748b;
                    }
                    .text-slate-400 {
                        color: #94a3b8;
                    }
                    .text-slate-300 {
                        color: #cbd5e1;
                    }
                    .text-[11pt] {
                        font-size: 11pt;
                    }
                    .text-[10pt] {
                        font-size: 10pt;
                    }
                    .text-[9.5pt] {
                        font-size: 9.5pt;
                    }
                    .text-[9pt] {
                        font-size: 9pt;
                    }
                    .font-bold {
                        font-weight: 700;
                    }
                    .font-semibold {
                        font-weight: 600;
                    }
                    .font-medium {
                        font-weight: 500;
                    }
                    .italic {
                        font-style: italic;
                    }
                    .text-center {
                        text-align: center;
                    }
                    .text-justify {
                        text-align: justify;
                    }
                    .flex {
                        display: flex;
                    }
                    .justify-center {
                        justify-content: center;
                    }
                    .justify-between {
                        justify-content: space-between;
                    }
                    .items-center {
                        align-items: center;
                    }
                    .items-baseline {
                        align-items: baseline;
                    }
                    .flex-wrap {
                        flex-wrap: wrap;
                    }
                    .gap-x-4 {
                        column-gap: 1rem;
                    }
                    .mb-8 {
                        margin-bottom: 2rem;
                    }
                    .mb-5 {
                        margin-bottom: 1.25rem;
                    }
                    .mb-3 {
                        margin-bottom: 0.75rem;
                    }
                    .mb-2 {
                        margin-bottom: 0.5rem;
                    }
                    .mb-1 {
                        margin-bottom: 0.25rem;
                    }
                    .ml-4 {
                        margin-left: 1rem;
                    }
                    .p-2 {
                        padding: 0.5rem;
                    }
                    .pl-1 {
                        padding-left: 0.25rem;
                    }
                    .list-disc {
                        list-style-type: disc;
                    }
                    .space-y-1 > * + * {
                        margin-top: 0.25rem;
                    }
                    .grid-cols-1 {
                        grid-template-columns: repeat(1, minmax(0, 1fr));
                    }
                    .gap-2 {
                        gap: 0.5rem;
                    }
                    .grid {
                        display: grid;
                    }
                    .underline {
                        text-decoration: underline;
                    }
                    .decoration-slate-300 {
                        text-decoration-color: #cbd5e1;
                    }
                    a {
                        color: inherit;
                    }
                `;
            } else {
                // Estilos para plantilla estándar
                fontImport = "@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');";
                bodyStyles = `
                    body {
                        font-family: 'Inter', sans-serif;
                        line-height: 1.5;
                        color: #1f2937;
                        padding: 15mm;
                    }
                `;
            }

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>${cvTitle}</title>
                    <style>
                        ${fontImport}
                        
                        * { margin: 0; padding: 0; box-sizing: border-box; }
                        @media print {
                        @page {
                            margin: 0;
                        }
                        body {
                            padding: 15mm;
                        }
                        h1, h2, h3, .section-title {
                            page-break-after: avoid;
                        }
                        }
                        ${bodyStyles}
                            line-height: 1.6;
                            color: #1e293b;
                            background: white;
                        }
                      
                        .a4-paper {
                            width: 216mm;
                            min-height: 297mm;
                            margin: 0 auto;
                            background: white;
                            box-shadow: none;
                            padding: 15mm;
                        }
                        
                        @media print {
                            body { margin: 0; }
                            .a4-paper { 
                                margin: 0; 
                                box-shadow: none;
                                width: 100%;
                                min-height: auto;
                            }
                        }
                        .grid { 
                            display: grid !important; 
                        }
                        .grid-cols-3 { 
                            grid-template-columns: repeat(3, 1fr) !important; 
                        }
                        .flex-shrink-0 { flex-shrink: 0; }
                        .flex-1 { flex: 1 1 0%; }
                        .gap-6 { gap: 1.5rem; }
                        .items-start { align-items: flex-start; }
                        img {
                            max-width: 100%;
                            display: block;
                        }
                        .w-32 { width: 8rem; } 
                        .h-32 { height: 8rem; }
                        .w-28 { width: 7rem; } 
                        .h-28 { height: 7rem; }
                        .object-cover { object-fit: cover; }
                        .rounded-xl { border-radius: 0.75rem; }
                        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
                        
                        h1 { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
                        h2 { font-size: 0.875rem; font-weight: 700; text-transform: uppercase; 
                             letter-spacing: 0.05em; margin: 2rem 0 1rem 0; 
                             border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; }
                        h3 { font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; }
                        h4 { font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; }
                        p { margin-bottom: 0.5rem; }
                        
                        .text-4xl { font-size: 2rem; }
                        .text-lg { font-size: 1.125rem; }
                        .text-sm { font-size: 0.875rem; }
                        .text-xs { font-size: 0.75rem; }
                        
                        .font-bold { font-weight: 700; }
                        .font-semibold { font-weight: 600; }
                        .font-medium { font-weight: 500; }
                        
                        .mb-8 { margin-bottom: 2rem; }
                        .mb-6 { margin-bottom: 1.5rem; }
                        .mb-4 { margin-bottom: 0.5rem; }
                        .mb-3 { margin-bottom: 0.75rem; }
                        .mb-2 { margin-bottom: 0.5rem; }
                        .mb-1 { margin-bottom: 0.25rem; }
                        
                        .pb-6 { padding-bottom: 1.5rem; }
                        .pb-2 { padding-bottom: 0.5rem; }
                        
                        .border-b { border-bottom: 1px solid #e2e8f0; }
                        
                        .flex { display: flex; }
                        .flex-wrap { flex-wrap: wrap; }
                        .gap-4 { gap: 1rem; }
                        .gap-2 { gap: 0.5rem; }
                        .gap-1 { gap: 0.25rem; }
                        .items-center { align-items: center; }
                        .justify-between { justify-content: space-between; }
                        
                        .grid-cols-2 { display: grid; grid-template-columns: repeat(2, 1fr); }
                        
                        .list-disc { list-style-type: disc; }
                        .list-outside { list-style-position: outside; }
                        .ml-4 { margin-left: 1rem; }
                        
                        .space-y-1 > * + * { margin-top: 0.25rem; }
                        
                        .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
                        .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
                        
                        .bg-slate-100 { background-color: #f1f5f9; }
                        .rounded { border-radius: 0.25rem; }
                        
                        .text-slate-900 { color: #0f172a; }
                        .text-slate-800 { color: #1e293b; }
                        .text-slate-700 { color: #334155; }
                        .text-slate-600 { color: #475569; }
                        .text-slate-500 { color: #64748b; }
                        
                        .text-blue-600 { color: #2563eb; }
                        
                        .tracking-tight { letter-spacing: -0.025em; }
                        .tracking-wider { letter-spacing: 0.05em; }
                        
                        .leading-relaxed { line-height: 1.625; }
                        
                        .uppercase { text-transform: uppercase; }
                        .italic { font-style: italic; }
                        
                        .font-mono { font-family: 'Courier New', monospace; }
                        
                    </style>
                </head>
                <body>
                    <div class="a4-paper">
                        ${cvContent}
                    </div>
                    <script>
                        window.onload = function() {
                            setTimeout(() => {
                                window.print();
                                window.close();
                            }, 500);
                        };
                    </script>
                </body>
                </html>
            `);
            printWindow.document.close();

            this.showNotification('PDF generado exitosamente', 'success');
        } catch (error) {
            console.error('Error al generar PDF:', error);
            this.showNotification('Error al generar PDF', 'error');
        }
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transition-all duration-300 ${type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
                type === 'warning' ? 'bg-yellow-500' :
                    'bg-blue-500'
            }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    markAsSaved() {
        this.lastSavedData = JSON.parse(JSON.stringify(this.currentCVData));
        this.hasUnsavedChanges = false;
    }

    handlePreview() {
        if (!this.isAuthenticated) {
            this.showNotification('Debes iniciar sesión para ver la vista previa pública de tu CV', 'warning');
            return;
        }

        if (!this.userCV || !this.userCV.slug) {
            this.showNotification('Primero debes guardar tu CV para poder previsualizarlo', 'warning');
            return;
        }

        window.open(`/cv/${this.userCV.slug}`, '_blank');
    }

    generateSlug(name) {
        return name.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
            window.innerWidth <= 768 ||
            ('ontouchstart' in window);
    }

    isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
            window.innerWidth <= 768 ||
            ('ontouchstart' in window);
    }

    updateSaveStatus(message, type = 'info') {
        const saveStatusIndicator = document.querySelector('.auto-save-status');
        if (!saveStatusIndicator) return;

        const icon = saveStatusIndicator.querySelector('.status-icon');
        const text = saveStatusIndicator.querySelector('.status-text');

        if (text) text.textContent = message;

        if (icon) {
            const config = {
                'saving': {
                    icon: 'line-md:cloud-upload-outline-loop',
                    color: 'text-blue-400'
                },
                'success': {
                    icon: 'mdi:cloud-check-outline',
                    color: 'text-green-500'
                },
                'error': {
                    icon: 'mdi:cloud-alert-outline',
                    color: 'text-red-500'
                },
                'warning': {
                    icon: 'mdi:cloud-outline',
                    color: 'text-yellow-500'
                },
                'info': {
                    icon: 'mdi:cloud-outline',
                    color: 'text-slate-400'
                }
            }[type] || { icon: 'mdi:cloud-outline', color: 'text-slate-400' };

            icon.setAttribute('icon', config.icon);
            icon.className = `status-icon ${config.color}`;

            if (text) {
                text.className = `text-xs font-medium status-text ${config.color}`;
            }
        }
    }

    async updatePreview() {
        if (!this.currentCVData) return;

        if (!this.previewContainer) {
            console.warn('Preview container not found, cannot update preview');
            return;
        }

        // Mostrar loader mientras se genera la vista previa
        const loader = this.previewContainer.querySelector('#loader');
        const content = this.previewContainer.querySelector('#cv-content');

        if (loader) loader.style.display = 'flex';
        if (content) content.style.display = 'none';

        try {
            const cvHTML = this.generateCVHTML(this.currentCVData, this.currentTemplate);

            if (content) {
                content.innerHTML = cvHTML;
                content.style.display = 'block';
            } else {
                // Si no existe el contenedor de contenido, crear uno
                this.previewContainer.innerHTML = `<div id="cv-content">${cvHTML}</div>`;
            }

            if (loader) loader.style.display = 'none';
        } catch (error) {
            console.error('Error updating preview:', error);
            if (loader) loader.style.display = 'none';
        }
    }

    saveToLocalStorage() {
        try {
            localStorage.setItem('codeoner_cv_data', JSON.stringify(this.currentCVData));
            localStorage.setItem('codeoner_cv_template', this.currentTemplate);
            if (this.currentCVData.basics?.name) {
                localStorage.setItem('codeoner_cv_title', this.currentCVData.basics.name);
            }
        } catch (error) {
            console.error('Error saving to localStorage:', error);
        }
    }

    loadFromLocalStorage() {
        if (!localStorage) return false;

        try {
            const savedData = localStorage.getItem('codeoner_cv_data');
            const savedTemplate = localStorage.getItem('codeoner_cv_template');
            const savedTitle = localStorage.getItem('codeoner_cv_title');

            if (savedData) {
                this.currentCVData = JSON.parse(savedData);
                this.displayJSONInEditor(this.currentCVData);

                // Cargar título en el editor de nombre
                if (this.filenameEditor && savedTitle) {
                    this.filenameEditor.textContent = savedTitle;
                } else if (this.filenameEditor && this.currentCVData.basics?.name) {
                    this.filenameEditor.textContent = this.currentCVData.basics.name;
                }

                this.updatePreview();
                this.markAsSaved();
                this.updateSaveStatus('Auto guardado', 'success');
                return true;
            }

            if (savedTemplate && this.availableTemplates.some(t => t.id === savedTemplate)) {
                this.currentTemplate = savedTemplate;
            }

            return false;
        } catch (error) {
            console.error('Error loading from localStorage:', error);
            return false;
        }
    }

    updateCustomSelect() {
        // Notificar al custom-select que se actualice con la plantilla actual
        if (window.customSelectInstance && window.customSelectInstance.updateSelectedTemplate) {
            window.customSelectInstance.updateSelectedTemplate(this.currentTemplate);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    if (window.location.pathname === '/generate-cv' || document.querySelector('#cv-filename')) {
        window.cvGenerator = new CVGenerator();
    }
});