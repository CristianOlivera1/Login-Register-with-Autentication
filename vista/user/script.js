const formContainer = document.querySelector(".modal-content");
const formOpenBtn = document.querySelector("#editar-perfil");

if(formOpenBtn){
document.addEventListener("click", (event) => {
    if (!formContainer.contains(event.target) && !formOpenBtn.contains(event.target)) {
        cerrarModal(); 
    }
});
}
 
/*mostrar y ocultar los elementos escondidos en el modal*/
const agregarDescBtn = document.getElementById('agregar-desc');
const desc2 = document.getElementById('perfil-desc-input2');

if (agregarDescBtn && desc2) {
    agregarDescBtn.addEventListener('click', function() {
        if (desc2.style.display === 'none') {
            desc2.style.display = 'block'; 
            agregarDescBtn.disabled = true; 
            agregarDescBtn.style.background = '#d3d3d3'; 
            agregarDescBtn.style.color = '#808080'; 
        }
    });
}

const agregarExpBtn = document.getElementById('agregar-exp');
const exp2 = document.getElementById('experiencia-input2');
const exp3 = document.getElementById('experiencia-input3');

if (agregarExpBtn && exp2 && exp3) {
    agregarExpBtn.addEventListener('click', function() {
        if (exp2.style.display === 'none' || exp2.style.display === '') {
            exp2.style.display = 'block'; 
        } else if (exp3.style.display === 'none' || exp3.style.display === '') {
            exp3.style.display = 'block'; 
            agregarExpBtn.disabled = true; 
            agregarExpBtn.style.background = '#d3d3d3'; 
            agregarExpBtn.style.color = '#808080'; 
        }
    });
}

const agregarHabildBtn = document.getElementById('agregar-habil');
const habilid2 = document.getElementById('habilidades-input2');
const habilid3 = document.getElementById('habilidades-input3');

if (agregarHabildBtn && habilid2 && habilid3) {
    agregarHabildBtn.addEventListener('click', function() {
        if (habilid2.style.display === 'none' || habilid2.style.display === '') {
            habilid2.style.display = 'block'; 
        } else if (habilid3.style.display === 'none' || habilid3.style.display === '') {
            habilid3.style.display = 'block'; 
            agregarHabildBtn.disabled = true; 
            agregarHabildBtn.style.background = '#d3d3d3'; 
            agregarHabildBtn.style.color = '#808080'; 
        }
    });
}

const agregarEduBtn = document.getElementById('agregar-educacion');
const edu2 = document.getElementById('educacion-input2');
const edu3 = document.getElementById('educacion-input3');

if (agregarEduBtn && edu2 && edu3) {
    agregarEduBtn.addEventListener('click', function() {
        if (edu2.style.display === 'none' || edu2.style.display === '') {
            edu2.style.display = 'block'; 
        } else if (edu3.style.display === 'none' || edu3.style.display === '') {
            edu3.style.display = 'block'; 
            agregarEduBtn.disabled = true; 
            agregarEduBtn.style.background = '#d3d3d3'; 
            agregarEduBtn.style.color = '#808080'; 
        }
    });
}


/*mostrar y ocultar los elementos escondidos en el modal*/

// Abrir el modal y cargar los datos actuales
function abrirModal() {
    document.getElementById('modal-editar').style.display = 'block';
    
    // Cargar los datos actuales del perfil al modal
    document.getElementById('nombre').value = document.getElementById('nombre-completo').innerText;
    document.getElementById('profesion-input').value = document.getElementById('profesion').innerText;

    // Verificar si los enlaces existen antes de intentar obtenerlos
    const ubicacionElement = document.getElementById('ubicacion');
    document.getElementById('ubicacion-input').value = ubicacionElement ? ubicacionElement.getAttribute('href') : '';
    const portafolioElement = document.getElementById('portafolio');
    document.getElementById('portafolio-input').value = portafolioElement ? portafolioElement.getAttribute('href') : '';
    const linkedinElement = document.getElementById('linkedin');
    document.getElementById('linkedin-input').value = linkedinElement ? linkedinElement.getAttribute('href') : '';
    const githubElement = document.getElementById('github');
    document.getElementById('github-input').value = githubElement ? githubElement.getAttribute('href') : '';

    const perfilDescElement = document.getElementById('perfil-desc');
    document.getElementById('perfil-desc-input').value = perfilDescElement ? perfilDescElement.innerText : '';
    
    const experienciaElement = document.getElementById('experiencia');
    document.getElementById('experiencia-input').value = experienciaElement ? experienciaElement.innerText : '';

    const habilidadesElement = document.getElementById('habilidades');
    document.getElementById('habilidades-input').value = habilidadesElement ? habilidadesElement.innerText : '';
    
    document.getElementById('proyectos-input').value = document.getElementById('proyectos').innerText;
    
    const educacionElement = document.getElementById('educacion');
    document.getElementById('educacion-input').value = educacionElement ? educacionElement.innerText : '';
        
    const perfilDesc2 = document.getElementById('perfil-desc2');
    if (perfilDesc2 && perfilDesc2.innerText.trim() !== '') {
        document.getElementById('perfil-desc-input2').value = perfilDesc2.innerText;
        desc2.style.display = 'block';
        agregarDescBtn.disabled = true; 
        agregarDescBtn.style.background = '#d3d3d3';
        agregarDescBtn.style.color = '#808080'; 
    }else{
        desc2.style.display = 'none';
        agregarDescBtn.disabled = false;  
        agregarDescBtn.style.background = ''; 
        agregarDescBtn.style.color = ''; 
    }

    const expe2 = document.getElementById('experiencia2');
    const expe3 = document.getElementById('experiencia3');
    if (expe2 && expe2.innerText.trim() !== '') {
        document.getElementById('experiencia-input2').value = expe2.innerText;
        exp2.style.display = 'block'; 
    } else {
        exp2.style.display = 'none';
    }
    if (expe3 && expe3.innerText.trim() !== '') {
        document.getElementById('experiencia-input3').value = expe3.innerText;
        exp3.style.display = 'block'; 
        agregarExpBtn.disabled = true; 
        agregarExpBtn.style.background = '#d3d3d3';
        agregarExpBtn.style.color = '#808080'; 
    } else {
        exp3.style.display = 'none';
        agregarExpBtn.disabled = false;  
        agregarExpBtn.style.background = ''; 
        agregarExpBtn.style.color = ''; 
    }

    const habilvp2 = document.getElementById('habilidades2');
    const habilvp3 = document.getElementById('habilidades3');
    if (habilvp2 && habilvp2.innerText.trim() !== '') {
        document.getElementById('habilidades-input2').value = habilvp2.innerText;
        habilid2.style.display = 'block'; 
    } else {
        habilid2.style.display = 'none';
    }
    if (habilvp3 && habilvp3.innerText.trim() !== '') {
        document.getElementById('habilidades-input3').value = habilvp3.innerText; // vp--> vista previa
        habilid3.style.display = 'block'; 
        agregarHabildBtn.disabled = true; 
        agregarHabildBtn.style.background = '#d3d3d3';
        agregarHabildBtn.style.color = '#808080'; 
    } else {
        habilid3.style.display = 'none';
        agregarHabildBtn.disabled = false;  
        agregarHabildBtn.style.background = ''; 
        agregarHabildBtn.style.color = ''; 
    }

    const eduvp2 = document.getElementById('educacion2');
    const eduvp3 = document.getElementById('educacion3');
    if (eduvp2 && eduvp2.innerText.trim() !== '') {
        document.getElementById('educacion-input2').value = eduvp2.innerText;
        edu2.style.display = 'block'; 
    } else {
        edu2.style.display = 'none';
    }
    if (eduvp3 && eduvp3.innerText.trim() !== '') {
        document.getElementById('educacion-input3').value = eduvp3.innerText;
        edu3.style.display = 'block'; 
        agregarEduBtn.disabled = true; 
        agregarEduBtn.style.background = '#d3d3d3';
        agregarEduBtn.style.color = '#808080'; 
    } else {
        edu3.style.display = 'none';
        agregarEduBtn.disabled = false;  
        agregarEduBtn.style.background = ''; 
        agregarEduBtn.style.color = ''; 
    }

}

// Cerrar el modal
function cerrarModal() {
    document.getElementById('modal-editar').style.display = 'none';
}

// Evento para mostrar la vista previa de la imagen seleccionada del modal
const profileImageInput = document.getElementById('profileImage');
if (profileImageInput) {
    profileImageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-icon-edit').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
}

// Obtiene los elementos por ID
let habilidades = document.getElementById('habilidades');
let habilidades2 = document.getElementById('habilidades2');
let habilidades3 = document.getElementById('habilidades3');

// Función para mostrar u ocultar las habilidades según su contenido
function checkAndDisplayHabilidad(element, text) {
    if (text.trim() !== '') { // Si el texto no está vacío ni contiene solo espacios
        element.style.display = 'list-item'; // Mostrar la viñeta
    } else {
        element.style.display = 'none'; // Ocultar la viñeta si está vacío o con solo espacios
    }
}

// Verificar y ajustar la visualización para cada habilidad
if (habilidades) {
    checkAndDisplayHabilidad(habilidades, habilidades.innerText);
}

if (habilidades2) {
    checkAndDisplayHabilidad(habilidades2, habilidades2.innerText);
}

if (habilidades3) {
    checkAndDisplayHabilidad(habilidades3, habilidades3.innerText);
}

// Añadir evento click al botón de guardar cambios
const saveChangesButton = document.getElementById('saveChangesButton');
if (saveChangesButton) {
    saveChangesButton.addEventListener('click', guardarCambios);
}

// Guardar los cambios realizados y mostrarlos en la vista previa
function guardarCambios() {
    // Obtener los nuevos valores
    let nuevoNombre = document.getElementById('nombre').value;
    let nuevaProfesion = document.getElementById('profesion-input').value;
    let nuevaUbicacion = document.getElementById('ubicacion-input').value;
    let nuevoPortafolio = document.getElementById('portafolio-input').value;
    let nuevoLinkedin = document.getElementById('linkedin-input').value;
    let nuevoGithub = document.getElementById('github-input').value;
    let nuevaDescripcion = document.getElementById('perfil-desc-input').value;
    let nuevaExperiencia = document.getElementById('experiencia-input').value;
    let nuevasHabilidades = document.getElementById('habilidades-input').value;
    let nuevosProyectos = document.getElementById('proyectos-input').value;
    let nuevaEducacion = document.getElementById('educacion-input').value;
    let nuevaDescripcion2 = document.getElementById('perfil-desc-input2').value;
    let nuevaExperiencia2 = document.getElementById('experiencia-input2').value;
    let nuevaExperiencia3 = document.getElementById('experiencia-input3').value;
    let nuevasHabilidades2 = document.getElementById('habilidades-input2').value;
    let nuevasHabilidades3 = document.getElementById('habilidades-input3').value;
    let nuevaEducacion2 = document.getElementById('educacion-input2').value;
    let nuevaEducacion3 = document.getElementById('educacion-input3').value;

    // Obtener la imagen de perfil seleccionada
    let nuevaFotoPerfil = document.getElementById('profileImage').files[0];

    // Crear un objeto FormData para enviar al servidor
    let formData = new FormData();
    formData.append('nombre', nuevoNombre);
    formData.append('profesion', nuevaProfesion);
    formData.append('ubicacion', nuevaUbicacion);
    formData.append('portafolio', nuevoPortafolio);
    formData.append('linkedin', nuevoLinkedin);
    formData.append('github', nuevoGithub);
    formData.append('perfil_desc', nuevaDescripcion);
    formData.append('experiencia', nuevaExperiencia);
    formData.append('habilidades', nuevasHabilidades);
    formData.append('proyectos', nuevosProyectos); 
    formData.append('educacion', nuevaEducacion);
    formData.append('perfil_desc2', nuevaDescripcion2);
    formData.append('experiencia2', nuevaExperiencia2);
    formData.append('experiencia3', nuevaExperiencia3);
    formData.append('habilidades2', nuevasHabilidades2);
    formData.append('habilidades3', nuevasHabilidades3);
    formData.append('educacion2', nuevaEducacion2);
    formData.append('educacion3', nuevaEducacion3);

    // Si hay una nueva imagen de perfil, adjuntarla
    if (nuevaFotoPerfil) {
        formData.append('foto_perfil', nuevaFotoPerfil);
    }

    // Enviar los datos al servidor usando fetch
    fetch('../helpers/updatePerfil.php', {
        method: 'POST',
        body: formData // Usamos formData en lugar de JSON
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
     
   // Si es JSON, puedes parsearlo manualmente
   try {
    let parsedResult = JSON.parse(result);

    if (parsedResult.success) {
            // Actualizar los elementos del perfil en la interfaz de la vista previa
            document.getElementById('nombre-completo').innerText = nuevoNombre;
            document.getElementById('profesion').innerText = nuevaProfesion;
      
               // Verificar si el elemento de ubicación existe y tiene valor
               let ubicacionElement = document.getElementById('ubicacion');
               if (nuevaUbicacion) {
                   if (ubicacionElement) {
                       ubicacionElement.setAttribute('href', nuevaUbicacion);
                   } else {
                       // Si no existe, puedes agregarlo dinámicamente
                       const nuevoElemento = document.createElement('p');
                       nuevoElemento.innerHTML = `<a href="${nuevaUbicacion}" id="ubicacion" target="_blank"><i class="fas fa-map-marker-alt"></i> Ubicación</a>`;
                       document.querySelector('.icon').appendChild(nuevoElemento);
                   }
               } else if (ubicacionElement) {
                   // Si está vacío y el elemento existe, eliminarlo
                   ubicacionElement.parentElement.remove();
               }

               // Repetir el mismo proceso para portafolio, linkedin, y github
               let portafolioElement = document.getElementById('portafolio');
               if (nuevoPortafolio) {
                   if (portafolioElement) {
                       portafolioElement.setAttribute('href', nuevoPortafolio);
                   } else {
                       const nuevoElemento = document.createElement('p');
                       nuevoElemento.innerHTML = `<a href="${nuevoPortafolio}" id="portafolio" target="_blank"><i class="fas fa-link"></i> Portafolio</a>`;
                       document.querySelector('.icon').appendChild(nuevoElemento);
                   }
               } else if (portafolioElement) {
                   portafolioElement.parentElement.remove();
               }

               let linkedinElement = document.getElementById('linkedin');
               if (nuevoLinkedin) {
                   if (linkedinElement) {
                       linkedinElement.setAttribute('href', nuevoLinkedin);
                   } else {
                       const nuevoElemento = document.createElement('p');
                       nuevoElemento.innerHTML = `<a href="${nuevoLinkedin}" id="linkedin" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn</a>`;
                       document.querySelector('.icon').appendChild(nuevoElemento);
                   }
               } else if (linkedinElement) {
                   linkedinElement.parentElement.remove();
               }

               let githubElement = document.getElementById('github');
               if (nuevoGithub) {
                   if (githubElement) {
                       githubElement.setAttribute('href', nuevoGithub);
                   } else {
                       const nuevoElemento = document.createElement('p');
                       nuevoElemento.innerHTML = `<a href="${nuevoGithub}" id="github" target="_blank"><i class="fab fa-github"></i> GitHub</a>`;
                       document.querySelector('.icon').appendChild(nuevoElemento);
                   }
               } else if (githubElement) {
                   githubElement.parentElement.remove();
               }
               
            let perfilDesc = document.getElementById('perfil-desc');
            if (perfilDesc) {
                perfilDesc.innerText = nuevaDescripcion;
                perfilDesc.style.display = nuevaDescripcion.trim() !== '' ? 'block' : 'none';
            }

            let perfilDesc2 = document.getElementById('perfil-desc2');
            if (perfilDesc2) {
                perfilDesc2.innerText = nuevaDescripcion2;
                perfilDesc2.style.display = nuevaDescripcion2.trim() !== '' ? 'block' : 'none';
            }

            let experiencia = document.getElementById('experiencia');
            if (experiencia) {
                experiencia.innerText = nuevaExperiencia;
                experiencia.style.display = nuevaExperiencia ? 'block' : 'none';
            }

                let experiencia2 = document.getElementById('experiencia2');
                if (experiencia2) {
                    experiencia2.innerText = nuevaExperiencia2;
                    experiencia2.style.display = nuevaExperiencia2 ? 'block' : 'none';
                }

                let experiencia3 = document.getElementById('experiencia3');
                if (experiencia3) {
                    experiencia3.innerText = nuevaExperiencia3;
                    experiencia3.style.display = nuevaExperiencia3 ? 'block' : 'none';
                }

            let habilidades = document.getElementById('habilidades');
            if (habilidades) {
                habilidades.innerText = nuevasHabilidades.trim();
                habilidades.style.display = nuevasHabilidades.trim() !== "" ? 'list-item' : 'none';
                habilidades.style.listStyle = nuevasHabilidades.trim() !== "" ? 'disc' : 'none';
            }
            

            let habilidades2 = document.getElementById('habilidades2');
            if (habilidades2) {
                habilidades2.innerText = nuevasHabilidades2.trim();
                habilidades2.style.display = nuevasHabilidades2.trim() !== "" ? 'list-item' : 'none';
                habilidades2.style.listStyle = nuevasHabilidades2.trim() !== "" ? 'disc' : 'none';
            }
            
            let habilidades3 = document.getElementById('habilidades3');
            if (habilidades3) {
                habilidades3.innerText = nuevasHabilidades3.trim();
                habilidades3.style.display = nuevasHabilidades3.trim() !== "" ? 'list-item' : 'none';
                habilidades3.style.listStyle = nuevasHabilidades3.trim() !== "" ? 'disc' : 'none';
            }

            document.getElementById('proyectos').innerText = nuevosProyectos;
            
            let educacion = document.getElementById('educacion');
            if (educacion) {
                educacion.innerText = nuevaEducacion;
                educacion.style.display = nuevaEducacion ? 'block' : 'none';
            }

            let educacion2 = document.getElementById('educacion2');
            if (educacion2) {
                educacion2.innerText = nuevaEducacion2;
                educacion2.style.display = nuevaEducacion2 ? 'block' : 'none';
            }

            let educacion3 = document.getElementById('educacion3');
            if (educacion3) {
                educacion3.innerText = nuevaEducacion3;
                educacion3.style.display = nuevaEducacion3 ? 'block' : 'none';
            }

            // Actualizar la imagen de perfil en la interfaz
            if (nuevaFotoPerfil) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('foto-perfil').src = e.target.result;
                }
                reader.readAsDataURL(nuevaFotoPerfil); 
            }

            // Cerrar el modal
            cerrarModal();
        } else {
            alert('Hubo un error al actualizar el perfil. Inténtalo de nuevo.');
        }
    } catch (error) {
        console.error('No se pudo parsear el JSON:', error);
    }
})
.catch(error => {
    console.error('Error:', error);
});
}

//manejar los tabs(pestañas) de configuracion
// Seleccionar todos los elementos de tab
const tabLinks = document.querySelectorAll('.tab-link');
const tabContents = document.querySelectorAll('.tab-content');

// Añadir evento click a cada tab
tabLinks.forEach(link => {
    link.addEventListener('click', function() {
        // Eliminar la clase active de todos los links y contenidos
        tabLinks.forEach(link => link.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));
        
        // Añadir la clase active al link y contenido correspondiente
        this.classList.add('active');
        const tabId = this.getAttribute('data-tab');
        document.getElementById(tabId).classList.add('active');
    });
});
//manejar los tabs(pestañas) de configuracion

const perfilBackgroundEdit = document.querySelector(".perfil-background-edit");
if (perfilBackgroundEdit) {
    generateGradient(perfilBackgroundEdit);
  }

  function generateGradient(element) {
    let gradient = "", mode = "";

    const blendModes = [
      "normal",
      "multiply",
      "screen",
      "overlay",
      "lighten",
      "hard-light",
      "soft-light",
      "hue",
      "color"
    ];
   
    const notableMinimalistColors = [
      "#000000",   
      "#82126c",  
      "#19ac8a",  
      "#49b7b4",   
      "#9100ff",   
      "#1030e9",   
      "#0238CE",  
      "#FF4000"    
    ];

    for (let i = 0; i < 3; i++) {
      const deg = Math.floor(Math.random() * 180);
      const firstColor = notableMinimalistColors[Math.floor(Math.random() * notableMinimalistColors.length)];
      const lastColor = notableMinimalistColors[Math.floor(Math.random() * notableMinimalistColors.length)];
      const firstSpread = Math.floor(Math.random() * 40) + 30; // Between 30% to 70%
      const lastSpread = firstSpread + Math.floor(Math.random() * 20) + 20; // Between firstSpread + 20% to firstSpread + 40%

      mode += `${blendModes[Math.floor(Math.random() * blendModes.length)]},`;
      gradient += `linear-gradient(${deg}deg, ${firstColor} ${firstSpread}%, ${lastColor} ${lastSpread}%),`;
    }

    const style = `background-image: ${gradient.slice(0, -1)}; background-blend-mode: ${mode.slice(0, -1)};`;
    element.setAttribute("style", style);
  }
