document.addEventListener('DOMContentLoaded', function() {
    const selectButton = document.getElementById('select-button');
    const selectMenu = document.getElementById('select-menu');
    const selectedValueText = document.getElementById('selected-value');
    const options = document.querySelectorAll('.option-btn');

    if (!selectButton || !selectMenu || !selectedValueText) {
        return;
    }

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
            if (template.name.toLowerCase().includes('moderno')) {
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
            selectedValueText.textContent = templates[0].name;
            
            if (window.cvGenerator) {
                window.cvGenerator.currentTemplate = templates[0].id;
            }
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
