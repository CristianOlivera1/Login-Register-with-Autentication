# Inicio de sesión y registro con métodos de autenticación 🚀

## 📝 Descripción
Esta aplicación web permite a los usuarios registrarse o iniciar sesión utilizando tanto métodos de autenticación social (como Google, Facebook, etc.) como métodos tradicionales (usuario y contraseña). Los usuarios autenticados tienen acceso a su perfil personal donde pueden gestionar su información.

## 🌟 Características
- 📥 Registro de usuarios.
- 🔐 Inicio de sesión con autenticación social.
- 🔑 Inicio de sesión con usuario y contraseña.
- 🖥️ Perfil de usuario personalizable.
- 🌐 Soporte para múltiples proveedores de autenticación.

## 🎥 Demostración
![demostracion](https://github.com/user-attachments/assets/e8e4b482-e7dc-44f3-b55e-0986447a7c53)

## 🖼️ Ejemplo de perfil de usuario visualizar perfil de usuario [![Ver](https://img.shields.io/badge/VER-%23facc15?logo=eye)](https://codeoner.rf.gd/vista/user/userlink.php?email=oliverachavezcristian%40gmail.com)


![demostracion](https://github.com/CristianOlivera1/Resources-dev/raw/main/login-register/img/Frame%201618873116.svg)


## 💻 Tecnologías Utilizadas
[![PHP](https://img.shields.io/badge/PHP-56.8%25-blue?logo=php)](https://www.php.net/)
[![CSS](https://img.shields.io/badge/CSS-24.2%25-blue?logo=css3)](https://developer.mozilla.org/es/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-19.0%25-yellow?logo=javascript)](https://developer.mozilla.org/es/docs/Web/JavaScript)

## 🛠️ Instalación
Para ejecutar este proyecto localmente, sigue estos pasos:

1. Clona el repositorio:
    ```bash
    git clone https://github.com/CristianOlivera1/Login-Register-with-Autentication.git
    ```
2. Navega al directorio del proyecto:
    ```bash
    cd Login-Register-with-Autentication
    ```
3. Instala las dependencias:
    - Descarga el instalador de Composer desde la página oficial de [Composer](https://getcomposer.org/download/).
    - Instala `vlucas/phpdotenv` para configurar las variables de entorno:
    ```bash
    composer require vlucas/phpdotenv
    ```

## 📘 Uso
1. Configuración de Clientes de Autenticación:

    ### Google Developer Console
    1. Ve a [Google Developer Console](https://console.cloud.google.com/cloud-resource-manager).
    2. Crea un nuevo proyecto.
    3. Habilita las APIs necesarias (por ejemplo, Google+ API).
    4. En la sección "Credenciales", crea un ID de cliente OAuth 2.0.
    5. Configura la pantalla de consentimiento OAuth.
    6. Agrega la URL de redirección autorizada: `http://localhost:3000/loginRegister/callback/google.php`.
    7. Guarda el ID de cliente y el secreto del cliente, y agréguelos al archivo `.env` en **paso 2**.

    ### GitHub
    1. Ve a [GitHub Developer Settings](https://github.com/settings/developers).
    2. Crea una nueva aplicación OAuth.
    3. Completa los campos necesarios (nombre de la aplicación, URL de la página principal, URL de devolución de llamada de autorización).
    4. La URL de devolución de llamada debe ser `http://localhost:3000/loginRegister/callback/github.php`.
    5. Guarda el ID de cliente y el secreto del cliente, y agréguelos al archivo `.env` en **paso 2**.

    ### Facebook
    1. Ve a [Facebook Developers](https://developers.facebook.com/).
    2. Crea una nueva aplicación.
    3. Configura los detalles de la aplicación.
    4. En la sección "Productos", agrega Facebook Login.
    5. Configura la URL de redirección: `http://localhost:3000/loginRegister/callback/facebook.php`.
    6. Guarda el ID de cliente y el secreto del cliente, y agréguelos al archivo `.env` en **paso 2**.

2. Configura las variables de entorno: Crea un archivo `.env` en la raíz de tu proyecto y define tus variables de entorno. Ejemplo:
    ```dotenv
    GOOGLE_CLIENT_ID=your-google-client-id
    GOOGLE_CLIENT_SECRET=your-google-client-secret
    GITHUB_CLIENT_ID=your-github-client-id
    GITHUB_CLIENT_SECRET=your-github-client-secret
    FACEBOOK_CLIENT_ID=your-facebook-client-id
    FACEBOOK_CLIENT_SECRET=your-facebook-client-secret
    ```
3. Configura tu servidor web preferido (por ejemplo, XAMPP, WAMP) para servir el proyecto. El usado en el proyecto es XAMPP 8.2.
4. Configura la base de datos.
5. Abre la aplicación en tu navegador.

## 📬 Resultado
**Página del Proyecto**: [Login-Register-with-Autentication](https://codeoner.rf.gd/)
