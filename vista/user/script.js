const formContainer = document.querySelector(".modal-content");
const formOpenBtn = document.querySelector("#editar-perfil");

if(formOpenBtn){
document.addEventListener("click", (event) => {
    if (!formContainer.contains(event.target) && !formOpenBtn.contains(event.target)) {
        cerrarModal(); // Cerrar el modal
    }
});
}
 
/*mostrar y ocultar los elementos escondidos en el modal*/
const agregarDescBtn = document.getElementById('agregar-desc');
const desc2 = document.getElementById('perfil-desc-input2');

if (agregarDescBtn && desc2) {
    agregarDescBtn.addEventListener('click', function() {
        if (desc2.style.display === 'none') {
            desc2.style.display = 'block'; // Mostrar el campo
            agregarDescBtn.disabled = true; 
            agregarDescBtn.style.background = '#d3d3d3'; // Cambiar el color de fondo a un color apagado
            agregarDescBtn.style.color = '#808080'; // Cambiar el color del texto a un color apagado
        }
    });
}

const agregarExpBtn = document.getElementById('agregar-exp');
const exp2 = document.getElementById('experiencia-input2');
const exp3 = document.getElementById('experiencia-input3');

if (agregarExpBtn && exp2 && exp3) {
    agregarExpBtn.addEventListener('click', function() {
        if (exp2.style.display === 'none' || exp2.style.display === '') {
            exp2.style.display = 'block'; // Mostrar el campo
        } else if (exp3.style.display === 'none' || exp3.style.display === '') {
            exp3.style.display = 'block'; // Mostrar el campo
            agregarExpBtn.disabled = true; 
            agregarExpBtn.style.background = '#d3d3d3'; // Cambiar el color de fondo a un color apagado
            agregarExpBtn.style.color = '#808080'; // Cambiar el color del texto a un color apagado
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

    document.getElementById('perfil-desc-input').value = document.getElementById('perfil-desc').innerText;
    document.getElementById('experiencia-input').value = document.getElementById('experiencia').innerText; 
    document.getElementById('habilidades-input').value = document.getElementById('habilidades').innerText;
    document.getElementById('proyectos-input').value = document.getElementById('proyectos').innerText;
    document.getElementById('educacion-input').value = document.getElementById('educacion').innerText;
    
    const perfilDesc2 = document.getElementById('perfil-desc2');
    if (perfilDesc2 && perfilDesc2.innerText.trim() !== '') {
        document.getElementById('perfil-desc-input2').value = perfilDesc2.innerText;
        desc2.style.display = 'block'; // Mostrar el imput de la segunda descripcion siesque tiene texto
        agregarDescBtn.disabled = true; 
        agregarDescBtn.style.background = '#d3d3d3'; // Cambiar el color de fondo a un color apagado
        agregarDescBtn.style.color = '#808080'; // Cambiar el color del texto a un color apagado
    }else{
        desc2.style.display = 'none';
        agregarDescBtn.disabled = false;  
        agregarDescBtn.style.background = ''; // Restaurar el color de fondo original
        agregarDescBtn.style.color = ''; // Restaurar el color del texto original
    }

    const expe2 = document.getElementById('experiencia2');
    const expe3 = document.getElementById('experiencia3');
    if (expe2 && expe2.innerText.trim() !== '') {
        document.getElementById('experiencia-input2').value = expe2.innerText;
        exp2.style.display = 'block'; // Mostrar el input de la segunda experiencia si es que tiene texto
    } else {
        exp2.style.display = 'none';
    }
    if (expe3 && expe3.innerText.trim() !== '') {
        document.getElementById('experiencia-input3').value = expe3.innerText;
        exp3.style.display = 'block'; // Mostrar el input de la tercera experiencia si es que tiene texto
        agregarExpBtn.disabled = true; 
        agregarExpBtn.style.background = '#d3d3d3'; // Cambiar el color de fondo a un color apagado
        agregarExpBtn.style.color = '#808080'; // Cambiar el color del texto a un color apagado
    } else {
        exp3.style.display = 'none';
        agregarExpBtn.disabled = false;  
        agregarExpBtn.style.background = ''; // Restaurar el color de fondo original
        agregarExpBtn.style.color = ''; // Restaurar el color del texto original
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
                // Cambiar la vista previa en el modal
                document.getElementById('profile-icon-edit').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
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
               
            document.getElementById('perfil-desc').innerText = nuevaDescripcion;

            let perfilDesc2 = document.getElementById('perfil-desc2');
            if (perfilDesc2) {
                perfilDesc2.innerText = nuevaDescripcion2;
                perfilDesc2.style.display = nuevaDescripcion2 ? 'block' : 'none';
                // Forzar el redibujado del elemento para asegurar que se actualice
                perfilDesc2.style.visibility = 'hidden';
                perfilDesc2.offsetHeight; // Forzar un reflow
                perfilDesc2.style.visibility = 'visible';
            }

            document.getElementById('experiencia').innerText = nuevaExperiencia;

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

            document.getElementById('habilidades').innerText = nuevasHabilidades;
            document.getElementById('proyectos').innerText = nuevosProyectos;
            document.getElementById('educacion').innerText = nuevaEducacion;

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