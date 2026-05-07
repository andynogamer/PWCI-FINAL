CREATE OR REPLACE VIEW vw_PublicacionesInfo AS
SELECT 
    p.id AS idPublicacion, 
    p.idMundial,
    m.nombre AS nombreMundial,
    m.fecha AS fechaMundial, 
    m.sede AS sede,
    p.idUsuario,
    u.nombre AS nombreUsuario,
    u.apellido AS apellidoUsuario,
    u.foto AS fotoUsuario,
    p.idCategoria,
    c.categoria AS nombreCategoria,
    p.pais AS paisMencionado,
    p.descripcion,
    p.multimedia,
    p.estatus,
    p.fechaCreacion,
    p.fechaAprobacion,
    p.vistas,
    P.tipoPublicacion
FROM publicacion p 
INNER JOIN usuario u ON p.idUsuario = u.id
INNER JOIN categoria c ON p.idCategoria = c.id
INNER JOIN mundial m ON p.idMundial = m.id;

CREATE OR REPLACE VIEW vw_ComentariosDetalle AS
SELECT 
    c.id AS idComentario,
    c.idPublicacion,
    c.idUsuario,
    u.nombre AS nombreUsuario,
    u.apellido AS apellidoUsuario,
    u.foto AS fotoUsuario,
    c.idComentarioPadre,
    c.texto,
    c.fechaCreacion,
    c.estatus
FROM comentario c
INNER JOIN usuario u ON c.idUsuario = u.id;