CREATE TABLE users (
    id CHAR(36) PRIMARY KEY NOT NULL,
    email VARCHAR(150) NOT NULL,
    password VARCHAR(150) NOT NULL,
    avatar VARCHAR(200),
    firstName VARCHAR(150),
    lastName VARCHAR(250),
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    auth_provider ENUM('manual', 'google', 'github', 'facebook') DEFAULT 'manual',
    last_password_change TIMESTAMP NULL,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_users_auth_provider ON users(auth_provider);

-- 1. PLANTILLAS DE CV (Templates)
CREATE TABLE cv_templates (
    id CHAR(36) PRIMARY KEY NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    preview_image VARCHAR(255),
    template_data JSON NOT NULL, -- Estructura del template
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- 2. CV GENERADOS (Para usuarios autenticados)
CREATE TABLE user_cvs (
    id CHAR(36) PRIMARY KEY NOT NULL,
    user_id CHAR(36) NOT NULL,
    title VARCHAR(150) NOT NULL DEFAULT 'Mi CV',
    slug VARCHAR(100) UNIQUE NOT NULL, -- Para URL pública (ej: olivera-chavez-cristian)
    template_id CHAR(36) NOT NULL,
    cv_data JSON NOT NULL, -- Datos completos del CV en JSON
    original_json_input TEXT, -- JSON original subido por el usuario
    is_public BOOLEAN DEFAULT TRUE,
    view_count INT DEFAULT 0,
    last_viewed_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (template_id) REFERENCES cv_templates(id),
    
    INDEX idx_user_cvs_slug (slug),
    INDEX idx_user_cvs_user_id (user_id),
    INDEX idx_user_cvs_public (is_public)
);

-- 3. INFORMACIÓN PERSONAL BASE
CREATE TABLE cv_personal_info (
    id CHAR(36) PRIMARY KEY NOT NULL,
    cv_id CHAR(36) NOT NULL,
    full_name VARCHAR(200) NOT NULL,
    professional_title VARCHAR(150),
    email VARCHAR(150),
    phone VARCHAR(50),
    location VARCHAR(200),
    website_url VARCHAR(255),
    github_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    portfolio_url VARCHAR(255),
    summary TEXT,
    profile_image VARCHAR(255),
    
    FOREIGN KEY (cv_id) REFERENCES user_cvs(id) ON DELETE CASCADE
);

-- 4. EXPERIENCIA PROFESIONAL
CREATE TABLE cv_work_experience (
    id CHAR(36) PRIMARY KEY NOT NULL,
    cv_id CHAR(36) NOT NULL,
    company_name VARCHAR(150) NOT NULL,
    position VARCHAR(150) NOT NULL,
    location VARCHAR(150),
    start_date DATE NOT NULL,
    end_date DATE NULL, -- NULL = trabajo actual
    is_current BOOLEAN DEFAULT FALSE,
    description TEXT,
    achievements JSON, -- Array de logros/responsabilidades
    technologies JSON, -- Array de tecnologías usadas
    display_order INT DEFAULT 0,
    
    FOREIGN KEY (cv_id) REFERENCES user_cvs(id) ON DELETE CASCADE,
    INDEX idx_work_experience_cv_order (cv_id, display_order)
);

-- 5. PROYECTOS
CREATE TABLE cv_projects (
    id CHAR(36) PRIMARY KEY NOT NULL,
    cv_id CHAR(36) NOT NULL,
    project_name VARCHAR(150) NOT NULL,
    role VARCHAR(100),
    project_type VARCHAR(100) DEFAULT 'personal',
    start_date DATE,
    end_date DATE,
    description TEXT,
    achievements JSON, -- Array de logros específicos del proyecto
    technologies JSON, -- Array de tecnologías usadas
    project_url VARCHAR(255),
    github_url VARCHAR(255),
    demo_url VARCHAR(255),
    display_order INT DEFAULT 0,
    
    FOREIGN KEY (cv_id) REFERENCES user_cvs(id) ON DELETE CASCADE,
    INDEX idx_projects_cv_order (cv_id, display_order)
);

-- 6. EDUCACIÓN
CREATE TABLE cv_education (
    id CHAR(36) PRIMARY KEY NOT NULL,
    cv_id CHAR(36) NOT NULL,
    institution VARCHAR(200) NOT NULL,
    degree VARCHAR(150) NOT NULL,
    field_of_study VARCHAR(150),
    location VARCHAR(150),
    start_date DATE,
    end_date DATE,
    gpa VARCHAR(20),
    honors VARCHAR(255), -- Ej: "Matrícula de Honor en Programación"
    description TEXT,
    display_order INT DEFAULT 0,
    
    FOREIGN KEY (cv_id) REFERENCES user_cvs(id) ON DELETE CASCADE,
    INDEX idx_education_cv_order (cv_id, display_order)
);

-- 7. HABILIDADES Y COMPETENCIAS

CREATE TABLE cv_skills (
    id CHAR(36) PRIMARY KEY NOT NULL,
    cv_id CHAR(36) NOT NULL,
    skill_category VARCHAR(100) NOT NULL, -- Ej: "Lenguajes", "Frameworks", "Tools"
    skills JSON NOT NULL, -- Array de habilidades
    proficiency_level VARCHAR(50) DEFAULT 'intermediate',
    display_order INT DEFAULT 0,
    
    FOREIGN KEY (cv_id) REFERENCES user_cvs(id) ON DELETE CASCADE,
    INDEX idx_skills_cv_order (cv_id, display_order)
);

-- INSERTAR PLANTILLAS BÁSICAS

-- Plantilla Harvard
INSERT INTO cv_templates (id, name, description, template_data) VALUES 
(
    UUID(),
    'Harvard Profesional',
    'Plantilla limpia y profesional ideal para desarrolladores y profesionales tech',
    JSON_OBJECT(
        'layout', 'single-column',
        'sections', JSON_ARRAY(
            'personal_info',
            'summary',
            'work_experience', 
            'projects',
            'education',
            'skills'
        ),
        'colors', JSON_OBJECT(
            'primary', '#000000',
            'secondary', '#333333',
            'accent', '#0099ff'
        ),
        'fonts', JSON_OBJECT(
            'primary', 'Inter',
            'secondary', 'Arial'
        )
    )
);

-- Plantilla Creativa
INSERT INTO cv_templates (id, name, description, template_data) VALUES 
(
    UUID(),
    'Creativo Moderno',
    'Plantilla creativa con diseño innovador y elementos visuales atractivos',
    JSON_OBJECT(
        'layout', 'creative',
        'sections', JSON_ARRAY(
            'personal_info',
            'work_experience',
            'skills',
            'projects', 
            'education'
        ),
        'colors', JSON_OBJECT(
            'primary', '#7c3aed',
            'secondary', '#a855f7',
            'accent', '#ec4899'
        ),
        'fonts', JSON_OBJECT(
            'primary', 'Nunito',
            'secondary', 'Open Sans'
        )
    )
);

-- ÍNDICES PARA PERFORMANCE
-- Para búsquedas rápidas de CV públicos
CREATE INDEX idx_public_cvs ON user_cvs (is_public, updated_at DESC);

-- VISTA PARA CV PÚBLICOS
-- Vista para CV públicos con información del usuario
CREATE VIEW public_cvs AS
SELECT 
    cv.id,
    cv.title,
    cv.slug,
    cv.view_count,
    cv.updated_at,
    u.firstName,
    u.lastName,
    u.avatar,
    t.name as template_name
FROM user_cvs cv
JOIN users u ON cv.user_id = u.id
JOIN cv_templates t ON cv.template_id = t.id
WHERE cv.is_public = TRUE;