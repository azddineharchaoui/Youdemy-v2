-- Créer la base de données
CREATE DATABASE youdemy;

-- Créer les types ENUM nécessaires
CREATE TYPE role_nom AS ENUM ('etudiant', 'enseignant', 'admin');
CREATE TYPE statut_type AS ENUM ('active', 'inactive', 'suspendu');
CREATE TYPE course_type AS ENUM ('video', 'text');

-- Table: roles
CREATE TABLE roles (
    id_role INT SERIAL PRIMARY KEY,
    nom role_nom NOT NULL
);

-- Table: utilisateurs
CREATE TABLE utilisateurs (
    id_utilisateur INT SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    statut statut_type DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id_role) ON DELETE CASCADE
);

-- Table: categories
CREATE TABLE categories (
    id_categorie INT SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: tags
CREATE TABLE tags (
    id_tag INT SERIAL PRIMARY KEY,
    nom VARCHAR(255) NOT NULL UNIQUE
);

-- Table: courses
CREATE TABLE courses (
    id_course INT SERIAL PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(100),
    type course_type,
    contenu TEXT NOT NULL,
    categorie_id INT,
    enseignant_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id_categorie) ON DELETE SET NULL,
    FOREIGN KEY (enseignant_id) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE
);

-- Table: course_tags
CREATE TABLE course_tags (
    course_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES courses(id_course) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id_tag) ON DELETE CASCADE
);

-- Table: inscriptions
CREATE TABLE inscriptions (
    course_id INT NOT NULL,
    etudiant_id INT NOT NULL,
    inscrit_a TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (course_id, etudiant_id),
    FOREIGN KEY (course_id) REFERENCES courses(id_course) ON DELETE CASCADE,
    FOREIGN KEY (etudiant_id) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE
);