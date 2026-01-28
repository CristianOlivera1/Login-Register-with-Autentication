document.addEventListener('DOMContentLoaded', function() {
    const selectButton = document.getElementById('select-button');
    const selectMenu = document.getElementById('select-menu');
    const selectedValueText = document.getElementById('selected-value');

    if (!selectButton || !selectMenu || !selectedValueText) {
        return;
    }

    let availableTemplates = [];

    // Crear instancia global para sincronización con cv-generator
    window.customSelectInstance = {
        updateSelectedTemplate: function(templateId) {
            const template = availableTemplates.find(t => t.id === templateId);
            if (template) {
                selectedValueText.textContent = template.name;
                console.log('Custom-select sincronizado con plantilla:', template.name);
            }
        }
    };

    loadTemplates();

    selectButton.addEventListener('click', (e) => {
        e.stopPropagation();
        selectMenu.classList.toggle('active');
    });

    updateOptionListeners();

    document.addEventListener('click', () => {
        selectMenu.classList.remove('active');
    });

    async function loadTemplates() {
        try {
            const response = await fetch('/cv/templates');
            const data = await response.json();
            
            if (data.success && data.templates) {
                availableTemplates = data.templates;
                updateTemplateOptions(data.templates);
            }
        } catch (error) {
            console.error('Error loading templates:', error);
        }
    }

    function updateTemplateOptions(templates) {
        if (!selectMenu) return;
        
        const menuContainer = selectMenu.querySelector('.p-1') || selectMenu;
        menuContainer.innerHTML = '';
        
        templates.forEach(template => {
            const option = document.createElement('button');
            option.className = 'option-btn w-full text-left px-3 py-2 text-xs text-slate-300 hover:bg-white/5 hover:text-white rounded-lg transition-colors flex items-center gap-2';
            option.setAttribute('data-value', template.id);
            option.setAttribute('data-name', template.name);
            
            let icon = 'solar:document-text-linear';
            if (template.name.toLowerCase().includes('harvard')) {
                icon = 'simple-line-icons:graduation';
            } else if (template.name.toLowerCase().includes('moderno')) {
                icon = 'solar:magic-stick-3-linear';
            } else if (template.name.toLowerCase().includes('creativ')) {
                icon = 'solar:star-rainbow-linear';
            }
            
            option.innerHTML = `
                <iconify-icon icon="${icon}"></iconify-icon> 
                ${template.name}
            `;
            
            menuContainer.appendChild(option);
        });
        
        updateOptionListeners();
        
        if (templates.length > 0) {
            // Dar tiempo para que cvGenerator se inicialice completamente
            setTimeout(() => {
                let defaultTemplate;
                
                // Si cvGenerator ya tiene una plantilla seleccionada, usar esa
                if (window.cvGenerator && window.cvGenerator.currentTemplate) {
                    defaultTemplate = templates.find(t => t.id === window.cvGenerator.currentTemplate);
                    console.log('Usando plantilla desde cvGenerator:', defaultTemplate?.name);
                }
                
                // Si no hay plantilla en cvGenerator, buscar Harvard como default
                if (!defaultTemplate) {
                    defaultTemplate = templates.find(t => t.name === 'Harvard Profesional');
                    console.log('Forzando plantilla Harvard por defecto:', defaultTemplate?.name);
                    
                    // Asignar Harvard a cvGenerator si no tiene plantilla
                    if (window.cvGenerator && defaultTemplate) {
                        window.cvGenerator.currentTemplate = defaultTemplate.id;
                        window.cvGenerator.updatePreview(); // Actualizar vista previa
                        console.log('Template Harvard asignado a cvGenerator:', defaultTemplate.id);
                    }
                }
                
                // Fallback al primer template
                if (!defaultTemplate) {
                    defaultTemplate = templates[0];
                    console.log('Usando primer template como fallback:', defaultTemplate?.name);
                    
                    if (window.cvGenerator) {
                        window.cvGenerator.currentTemplate = defaultTemplate.id;
                        window.cvGenerator.updatePreview();
                    }
                }
                
                selectedValueText.textContent = defaultTemplate.name;
            }, 100); // Reducir el delay para mejor sincronización
        }
    }

    function updateOptionListeners() {
        const currentOptions = document.querySelectorAll('.option-btn');
        
        currentOptions.forEach(option => {
            const newOption = option.cloneNode(true);
            option.parentNode.replaceChild(newOption, option);
            
            newOption.addEventListener('click', () => {
                const value = newOption.getAttribute('data-value');
                const text = newOption.getAttribute('data-name') || newOption.innerText.trim();
                
                selectedValueText.textContent = text;
                selectMenu.classList.remove('active');
                
                if (window.cvGenerator) {
                    window.cvGenerator.changeTemplate(value);
                }
                
                console.log("Plantilla seleccionada:", { id: value, name: text });
            });
        });
    }
});
