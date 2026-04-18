#USE DATABASE infografia_mundiales;

----------------------------------USUARIOS--------------------------------------

--ALTA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_RegistrarUsuario //
CREATE PROCEDURE sp_RegistrarUsuario(
    IN p_tipoUsuario int,
    IN p_nombre varchar(255),
    IN p_apellido varchar(510),
    IN p_fechaNacimiento DATE,
    IN p_foto mediumblob,
    IN p_genero CHAR(1),
    IN p_paisNacimiento varchar(255),
    IN p_nacionalidad varchar(255),
    IN p_correoElectronico varchar(321),
    IN p_contrasena varchar(255)

)
BEGIN
    INSERT INTO usuario(
        tipoUsuario,
        nombre,
        apellido,
        fechaNacimiento,
        foto,
        genero,
        paisNacimiento,
        nacionalidad,
        correoElectronico,
        contrasena
    )
    VALUES(
        p_tipoUsuario,
        p_nombre,
        p_apellido,
        p_fechaNacimiento,
        p_foto,
        p_genero,
        p_paisNacimiento,
        p_nacionalidad,
        p_correoElectronico,
        p_contrasena
    );
END //


--CAMBIOS--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_CambioContrasena // #CONTRASENA
CREATE PROCEDURE sp_CambioContrasena(
    IN p_id int,
    IN p_contrasena varchar(50)
)
BEGIN
    UPDATE usuario
    SET contrasena = p_contrasena
    WHERE id = p_id;
END //

DELIMITER //
DROP PROCEDURE IF EXISTS sp_CambioFoto // #FOTO
CREATE PROCEDURE sp_CambioFoto(
    IN p_id int,
    IN p_foto mediumblob
)
BEGIN
    UPDATE usuario
    SET foto = p_foto
    WHERE id = p_id;
END //


DELIMITER // 
DROP PROCEDURE IF EXISTS sp_CambiosGeneralesUsuario // #GENERALES
CREATE PROCEDURE sp_CambiosGeneralesUsuario(
    IN p_id int,
    IN p_nombre varchar(255),
    IN p_apellido varchar(510),
    IN p_fechaNacimiento DATE,
    IN p_genero CHAR(1),
    IN p_paisNacimiento varchar(255),
    IN p_nacionalidad varchar(255)
)
BEGIN
    UPDATE usuario
    SET nombre = p_nombre, apellido = p_apellido, fechaNacimiento = p_fechaNacimiento, genero = p_genero, paisNacimiento = p_paisNacimiento, nacionalidad = p_nacionalidad
    WHERE id = p_id;
END //

--CONSULTA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultaUsuario //
CREATE PROCEDURE sp_ConsultaUsuario( #Usuario consulta su propia información
    IN p_id int
)
BEGIN 
    SELECT id, tipoUsuario, nombre, apellido, fechaNacimiento, foto, genero, paisNacimiento, nacionalidad, correoElectronico
    FROM usuario 
    WHERE id = p_id;
END //

DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultaUsuarioPorCorreo //
CREATE PROCEDURE sp_ConsultaUsuarioPorCorreo( 
    IN p_correoElectronico varchar(321)
)
BEGIN 
    SELECT id, tipoUsuario, nombre, apellido, fechaNacimiento, foto, genero, paisNacimiento, nacionalidad, correoElectronico, contrasena
    FROM usuario 
    WHERE correoElectronico = p_correoElectronico;
END //


---------------------------------------------------------------------------------

-----------------------------------MUNDIAL---------------------------------------
--ALTA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_RegistrarMundial //
CREATE PROCEDURE sp_RegistrarMundial(
    IN p_nombre varchar(255),
    IN p_fecha DATE,
    IN p_sede varchar(255),
    IN p_logo mediumblob,
    IN p_banner mediumblob,
    IN p_descripcion varchar(800)
)
BEGIN
    INSERT INTO mundial (
        nombre, 
        fecha, 
        sede, 
        logo, 
        banner, 
        descripcion
    )
    VALUES (
        p_nombre, 
        p_fecha, 
        p_sede, 
        p_logo, 
        p_banner, 
        p_descripcion
    );
END //

--CAMBIOS--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_ModificarMundial //
CREATE PROCEDURE sp_ModificarMundial(
    IN p_id int,
    IN p_nombre varchar(255),
    IN p_fecha DATE,
    IN p_sede varchar(255),
    IN p_logo mediumblob,
    IN p_banner mediumblob,
    IN p_descripcion varchar(800)
)
BEGIN
    UPDATE mundial
    SET nombre = p_nombre,
        fecha = p_fecha,
        sede = p_sede,
        logo = p_logo,
        banner = p_banner,
        descripcion = p_descripcion
    WHERE id = p_id;
END //

--CONSULTAS--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultarMundialesActivos //
CREATE PROCEDURE sp_ConsultarMundialesActivos()
BEGIN
    SELECT id, nombre, fecha, sede, logo, banner, descripcion, fechaCreacion
    FROM mundial
    WHERE estatus = true
    ORDER BY fecha ASC; 
END //

DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultarMundialPorId //
CREATE PROCEDURE sp_ConsultarMundialPorId(
    IN p_id int
    )
BEGIN
    SELECT id, nombre, fecha, sede, logo, banner, descripcion, fechaCreacion, estatus
    FROM mundial
    WHERE id = p_id;
END //

DROP PROCEDURE IF EXISTS sp_ConsultarMundialPorSede //
CREATE PROCEDURE sp_ConsultarMundialPorSede(
    IN p_sede varchar(255)
    )
BEGIN
    SELECT id, nombre, fecha, sede, logo, banner, descripcion
    FROM mundial
    WHERE sede LIKE CONCAT('%', p_sede, '%') AND estatus = true;
END //

--BAJAS--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_BajaMundial //
CREATE PROCEDURE sp_BajaMundial(
    IN p_id int
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;
    START TRANSACTION;

        UPDATE mundial
        SET estatus = false
        WHERE id = p_id;

        UPDATE publicacion
        SET estatus = 2
        WHERE idMundial = p_id;

        UPDATE comentario
        SET estatus = false
        WHERE idPublicacion IN (
            SELECT id 
            FROM publicacion
            WHERE idMundial = p_id
        );

    COMMIT;
END //

---------------------------------------------------------------------------------

-------------------------------CATEGORIA-----------------------------------------
--ALTA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_RegistrarCategoria //
CREATE PROCEDURE sp_RegistrarCategoria(
    IN p_categoria varchar(255)
)
BEGIN
    INSERT INTO categoria(
        categoria
    )
    VALUES(
        p_categoria
    );
END //

--CAMBIOS--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_CambioCategoria //
CREATE PROCEDURE sp_CambioCategoria(
    IN p_id int,
    IN p_categoria varchar(255)
)
BEGIN
    UPDATE categoria
    SET categoria = p_categoria
    WHERE id = p_id;
END //

--CONSULTAS--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultaCategorias //
CREATE PROCEDURE sp_ConsultaCategorias()
BEGIN   
    SELECT id, categoria
    FROM categoria
    WHERE estatus = true; 
END //

--BAJAS--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_BajaCategoria //
CREATE PROCEDURE sp_BajaCategoria(
    IN p_id int
)
BEGIN
    UPDATE categoria
    SET estatus = false
    WHERE id = p_id;
END //
DELIMITER ;
---------------------------------------------------------------------------------

-------------------------------PUBLICACION---------------------------------------
--ALTA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_RegistrarPublicacion //
CREATE PROCEDURE sp_RegistrarPublicacion(
    IN p_idMundial int,
    IN p_idUsuario int,
    IN p_idCategoria int,
    IN p_pais varchar(255),
    IN p_descripcion varchar(800),
    IN p_multimedia LONGBLOB,
    IN p_tipoPublicacion tinyint
)
BEGIN
    INSERT INTO publicacion (
        idMundial, idUsuario, idCategoria, pais, descripcion, multimedia, tipoPublicacion
    )
    VALUES (
        p_idMundial, p_idUsuario, p_idCategoria, p_pais, p_descripcion, p_multimedia, p_tipoPublicacion
    );
END //
--CAMBIOS--
DELIMITER //

DROP PROCEDURE IF EXISTS sp_AprobarPublicacion //
CREATE PROCEDURE sp_AprobarPublicacion(
    IN p_idPublicacion int
)
BEGIN
    UPDATE publicacion 
    SET estatus = true, 
        fechaAprobacion = NOW() 
    WHERE id = p_idPublicacion;
END //


--BAJA--


--CONSULTA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultarPublicacionPorId //
CREATE PROCEDURE sp_ConsultarPublicacionPorId(IN p_id int)
BEGIN
    SELECT 
        idPublicacion, idMundial, nombreMundial, fechaMundial,
        idUsuario, nombreUsuario, apellidoUsuario, fotoUsuario,
        idCategoria, nombreCategoria, paisMencionado, descripcion,
        multimedia, estatus, fechaCreacion, fechaAprobacion, vistas, tipoPublicacion
    FROM vw_PublicacionesInfo
    WHERE idPublicacion = p_id;
END //

DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultarPublicacionesPorUsuario //
CREATE PROCEDURE sp_ConsultarPublicacionesPorUsuario(IN p_idUsuario int)
BEGIN
    SELECT 
        idPublicacion, nombreMundial, nombreCategoria, 
        descripcion, multimedia, estatus, vistas, fechaCreacion
    FROM vw_PublicacionesInfo
    WHERE idUsuario = p_idUsuario
    ORDER BY fechaCreacion DESC;
END //

DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultarPublicacionesPendientes //
CREATE PROCEDURE sp_ConsultarPublicacionesPendientes()
BEGIN
    SELECT 
        idPublicacion, nombreUsuario, nombreMundial, 
        nombreCategoria, descripcion, fechaCreacion
    FROM vw_PublicacionesInfo
    WHERE estatus = false
    ORDER BY fechaCreacion ASC;
END //

--QUEDA PENDIENTE PUBLICACIONES POR BUSQUEDA


---------------------------------------------------------------------------------

-----------------------------------LIKE------------------------------------------

--ALTA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_RegistrarLike //
CREATE PROCEDURE sp_RegistrarLike(
    IN p_idUsuario int,
    IN p_idPublicacion int
)
BEGIN
    INSERT IGNORE INTO likePublicacion(
        idUsuario,
        idPublicacion
    )
    VALUES(
        p_idUsuario,
        p_idPublicacion
    );
END //

DELIMITER //
DROP PROCEDURE IF EXISTS sp_ToggleLike //
CREATE PROCEDURE sp_ToggleLike(
    IN p_idUsuario int,
    IN p_idPublicacion int
)
BEGIN
    DECLARE v_isThereInteraction INT;
    SELECT COUNT(idUsuario) INTO v_isThereInteraction
    FROM likePublicacion
    WHERE idUsuario = p_idUsuario AND idPublicacion = p_idPublicacion;

    IF v_isThereInteraction > 0 THEN 
        DELETE FROM likePublicacion
        WHERE idUsuario = p_idUsuario AND idPublicacion = p_idPublicacion;
    ELSE
        INSERT INTO likePublicacion(
        idUsuario,
        idPublicacion
        )
        VALUES(
            p_idUsuario,
            p_idPublicacion
        );
    END IF;
END//


--CONSULTAS
DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultaLikePorPublicacion //
CREATE PROCEDURE sp_ConsultaLikePorPublicacion(
    IN p_idPublicacion int
)
BEGIN
    SELECT COUNT(idUsuario) AS totalLikes
    FROM likePublicacion
    WHERE idPublicacion = p_idPublicacion;    
END //

DELIMITER //
DROP PROCEDURE IF EXISTS sp_VerificarLikeUsuario //
CREATE PROCEDURE sp_VerificarLikeUsuario(
    IN p_idUsuario int,
    IN p_idPublicacion int
)
BEGIN
    
    SELECT EXISTS(
        SELECT 1 FROM likePublicacion 
        WHERE idUsuario = p_idUsuario AND idPublicacion = p_idPublicacion
    ) AS yaDioLike;
END //
DELIMITER ;

--BAJA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_EliminarLike //
CREATE PROCEDURE sp_EliminarLike(
    IN p_idUsuario int,
    IN p_idPublicacion int
)
BEGIN
    DELETE FROM likePublicacion
    WHERE idUsuario = p_idUsuario AND idPublicacion = p_idPublicacion;
END //
---------------------------------------------------------------------------------

--------------------------------COMENTARIO---------------------------------------
--ALTA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_RegistrarComentario //
CREATE PROCEDURE sp_RegistrarComentario(
    IN p_idPublicacion int,
    IN p_idUsuario int,
    IN p_idComentarioPadre int,
    IN p_texto varchar(512)
)
BEGIN
    INSERT INTO comentario(
        idPublicacion,
        idUsuario,
        idComentarioPadre,
        texto
    )
    VALUES(
        p_idPublicacion,
        p_idUsuario,
        p_idComentarioPadre,
        p_texto
    );
END //

--CONSULTAS--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_ConsultarComentariosPorPublicacion //
CREATE PROCEDURE sp_ConsultarComentariosPorPublicacion(
    IN p_idPublicacion int
)
BEGIN
    SELECT 
        idComentario, 
        nombreUsuario, 
        apellidoUsuario, 
        fotoUsuario, 
        idComentarioPadre, 
        texto, 
        fechaCreacion
    FROM vw_ComentariosDetalle
    WHERE idPublicacion = p_idPublicacion 
      AND estatus = true
    ORDER BY fechaCreacion ASC;
END //

DELIMITER //
DROP PROCEDURE IF EXISTS sp_ContarComentariosPorPublicacion //
CREATE PROCEDURE sp_ContarComentariosPorPublicacion(
    IN p_idPublicacion int
)
BEGIN
    SELECT COUNT(id) AS totalComentarios
    FROM comentario
    WHERE idPublicacion = p_idPublicacion 
      AND estatus = true;
END //
DELIMITER ;

--BAJA--
DELIMITER //
DROP PROCEDURE IF EXISTS sp_BajaComentario //
CREATE PROCEDURE sp_BajaComentario(
    IN p_id int
)
BEGIN
    UPDATE comentario
    SET estatus = false
    WHERE id = p_id;
END //
