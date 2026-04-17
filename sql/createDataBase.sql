CREATE DATABASE infografia_mundiales;
USE infografia_mundiales;

CREATE TABLE mundial(
    id int NOT NULL AUTO_INCREMENT,
	nombre varchar(255) NOT NULL UNIQUE,
	fecha DATE NOT NULL,
    sede varchar(255),
	logo BLOB NOT NULL,
    banner BLOB NOT NULL,
	descripcion varchar(800) NOT NULL,
	fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estatus BOOLEAN NOT NULL DEFAULT true,
    PRIMARY KEY (id)
);

CREATE TABLE categoria(
    id int NOT NULL AUTO_INCREMENT,
    categoria varchar(255) NOT NULL UNIQUE,
    estatus BOOLEAN NOT NULL DEFAULT true,
    PRIMARY KEY (id)
);



CREATE TABLE usuario(
    id int NOT NULL AUTO_INCREMENT,
    tipoUsuario int NOT NULL,
	nombre varchar(255) NOT NULL,
	apellido varchar(510) NOT NULL,
	fechaNacimiento DATE NOT NULL,
	foto BLOB,
	genero CHAR(1) NOT NULL,
	paisNacimiento varchar(255) NOT NULL,
	nacionalidad varchar(255) NOT NULL,
	correoElectronico varchar(321) NOT NULL UNIQUE,
	contrasena varchar(255) NOT NULL,
    fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estatus BOOLEAN NOT NULL DEFAULT true,
    PRIMARY KEY (id)
);


CREATE TABLE publicacion(
    id int NOT NULL AUTO_INCREMENT,
	idMundial int NOT NULL,
	idUsuario int NOT NULL,
	idCategoria int NOT NULL,
	pais varchar(255),
	descripcion varchar(800), 
	multimedia LONGBLOB,
	estatus BOOLEAN NOT NULL DEFAULT false,
	vistas int UNSIGNED NOT NULL DEFAULT 0,
    tipoPublicacion tinyint NOT NULL DEFAULT 0,
	fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	fechaAprobacion TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (idMundial) REFERENCES mundial(id),
    FOREIGN KEY (idUsuario) REFERENCES usuario(id),
    FOREIGN KEY (idCategoria) REFERENCES categoria(id),
    PRIMARY KEY (id)
);

CREATE TABLE likePublicacion(
    idUsuario int NOT NULL,
	idPublicacion int NOT NULL,
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (idUsuario, idPublicacion),
    FOREIGN KEY (idUsuario) REFERENCES usuario(id),
    FOREIGN KEY (idPublicacion) REFERENCES publicacion(id)
);

CREATE TABLE comentario(
    id int NOT NULL AUTO_INCREMENT,
    idPublicacion int NOT NULL,
    idUsuario int NOT NULL,
    idComentarioPadre int,
    texto varchar(512),
    estatus BOOLEAN NOT NULL DEFAULT true,
    fechaCreacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idPublicacion) REFERENCES publicacion(id),
    FOREIGN KEY (idUsuario) REFERENCES usuario(id),
    FOREIGN KEY (idComentarioPadre) REFERENCES comentario(id),
    PRIMARY KEY (id)
);
