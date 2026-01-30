
//manejar los componentes que no se mostraran una ves que el usuario haya iniciado sesion
document.addEventListener("DOMContentLoaded", () => {
  const formContainer = document.querySelector(".form_container");
  const formCloseBtn = document.querySelector(".form_close");
  const formOpenBtn = document.querySelector("#form-open");
  const home = document.querySelector(".home");
  const signupBtn = document.querySelector("#signup");
  const btnRegister = document.querySelector("#button-register");
  const buttonRegisterHeader = document.querySelector("#button-register-header");
  const loginBtn = document.querySelector("#login");
  const pwShowHide = document.querySelectorAll(".pw_hide");

  /*Menu amburguesa*/
  const hamburgerMenu = document.getElementById("hamburger-menu");
  const menuIcon = document.getElementById("menu-icon");
  const closeIcon = document.getElementById("close-icon");
  const navItems = document.getElementById("nav-items");

  if (hamburgerMenu) {
    hamburgerMenu.addEventListener("click", function() {
      navItems.classList.toggle("open");

      // Alternar visibilidad entre los íconos
      if (menuIcon.style.display === "none") {
        menuIcon.style.display = "block";
        closeIcon.style.display = "none";
      } else {
        menuIcon.style.display = "none";
        closeIcon.style.display = "block";
      }
    });
  }
  
  /*Menu amburguesa*/

  if (formOpenBtn) {
    formOpenBtn.addEventListener("click", () => {
          home.classList.add("show");
      });
  }
  if (btnRegister) { 
    btnRegister.addEventListener("click", () => {
          home.classList.add("show");
          formContainer.classList.add("active");
      });
  }
  if (buttonRegisterHeader) { 
    buttonRegisterHeader.addEventListener("click", () => {
          home.classList.add("show");
          formContainer.classList.add("active");
      });
  }
    // Manejar el cierre del formulario
    if (formCloseBtn) {
      formCloseBtn.addEventListener("click", () => {
        home.classList.remove("show");
      });
    }

    if(formCloseBtn){
    // Cerrar el formulario al hacer clic fuera de él
    document.addEventListener("click", (event) => {
      if (!formContainer.contains(event.target) && !formOpenBtn.contains(event.target) && !btnRegister.contains(event.target) && !buttonRegisterHeader.contains(event.target)) {
        home.classList.remove("show");
      }
    });
  }

  pwShowHide.forEach((icon) => {
    icon.addEventListener("click", () => {
      let getPwInput = icon.parentElement.querySelector("input");
      if (getPwInput.type === "password") {
        getPwInput.type = "text";
        icon.classList.replace("uil-eye-slash", "uil-eye");
      } else {
        getPwInput.type = "password";
        icon.classList.replace("uil-eye", "uil-eye-slash");
      }
    });
  });
  
   // Manejar el registro y el inicio de sesión
   if (signupBtn) { 
    signupBtn.addEventListener("click", (e) => {
      e.preventDefault();
      formContainer.classList.add("active");
    });
  }

  if (loginBtn) { 
    loginBtn.addEventListener("click", (e) => {
      e.preventDefault();
      formContainer.classList.remove("active");
    });
  }
  // Manejar el envío del formulario de Inicio de sesión
  const loginForm = document.getElementById("login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", function(event) {
      event.preventDefault(); 

      const formData = new FormData(this);

      fetch("loginRegister/login.php", {
        method: "POST",
        body: formData,
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Redirigir al index si la autenticación es exitosa
          window.location.href = "index.php"; 
        } else {
          // Mostrar el mensaje de error en el formulario
          const errorMessage = document.getElementById("error-message");
          const errorText = document.getElementById("error-text");
          errorText.textContent = data.message;
          errorMessage.style.display = "block"; 
        }
      })
      .catch(error => {
        console.error("Error:", error);
      });
    });
  }

  // Manejar el envío del formulario de registro
  const signupForm = document.getElementById("signup-form");
  if (signupForm) {
    signupForm.addEventListener("submit", function(event) {
      event.preventDefault(); // Evita el envío del formulario y la recarga de la página.

      const formData = new FormData(this);

      fetch("loginRegister/register.php", {
        method: "POST",
        body: formData,
      })
      .then(response => response.json())
      .then(data => {
        const errorMessage = document.getElementById("signup-error-message");
        const errorText = document.getElementById("signup-error-text");

        if (data.success) {
          window.location.href = "loginRegister/registerSuccessful.php";
          
        } else {
          errorText.textContent = data.message; 
          errorMessage.style.display = "block";
        }
      })
      .catch(error => {
        console.error("Error:", error);
      });
    });
  }
  // Manejar el envío del formulario de registro

    //manejar el menu desplegalbe del perfil
    const profileIcon = document.getElementById('profile-icon');
    const dropdownMenu = document.getElementById('dropdown-menu');
    const perfilDropdown = document.querySelector(".perfil-dropdown");
    
      if (profileIcon && dropdownMenu) { 
        profileIcon.addEventListener('click', function () {
            dropdownMenu.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (!profileIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('open');
            }
        });

        if (perfilDropdown) {
          generateGradient(perfilDropdown);
        }
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
      const firstSpread = Math.floor(Math.random() * 40) + 30; 
      const lastSpread = firstSpread + Math.floor(Math.random() * 20) + 20;

      mode += `${blendModes[Math.floor(Math.random() * blendModes.length)]},`;
      gradient += `linear-gradient(${deg}deg, ${firstColor} ${firstSpread}%, ${lastColor} ${lastSpread}%),`;
    }

    const style = `background-image: ${gradient.slice(0, -1)}; background-blend-mode: ${mode.slice(0, -1)};`;
    element.setAttribute("style", style);
  }

    const logoutButton = document.getElementById('logoutButton');
    const logoutConfirmationModal = document.getElementById('logoutConfirmationModal');
    const confirmLogoutButton = document.getElementById('confirmLogoutButton');
    const cancelLogoutButton = document.getElementById('cancelLogoutButton');
    const closeModalButton = document.getElementById('closeModalLogout');

    // Solo agrega los eventos si los elementos existen
    if (logoutButton && logoutConfirmationModal && confirmLogoutButton && cancelLogoutButton &&closeModalButton) {
      logoutButton.addEventListener('click', function() {
        logoutConfirmationModal.style.display = 'block';
      });
      
      closeModalButton.addEventListener('click', function() {
        logoutConfirmationModal.style.display = 'none';
    });

      // Confirmar el cierre de sesión cuando se hace clic en "Sí"
      confirmLogoutButton.addEventListener('click', function() {
        // Obtener la ruta actual
        const currentPath = window.location.pathname; 
        let logoutPath;

        // Verifica la ruta actual y ajusta la redirección
        if (currentPath.includes('/vista/user/')) {
          logoutPath = '../../loginRegister/logout.php'; 
        } else {
          logoutPath = 'loginRegister/logout.php'; // Por defecto
        }

        // Redirigir a la URL de cierre de sesión
        window.location.href = logoutPath;
      });

      // Cancelar y cerrar el modal cuando se hace clic en "No"
      cancelLogoutButton.addEventListener('click', function() {
        logoutConfirmationModal.style.display = 'none';
      });

      // Cerrar el modal si se hace clic fuera del contenido del modal
      window.addEventListener('click', function(event) {
        if (event.target === logoutConfirmationModal) {
          logoutConfirmationModal.style.display = 'none';
        }
      });
    }

});
//manejar los componentes que no se mostraran una ves que el usuario haya iniciado sesion

