DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `VerificarUsuario`(IN p_usuario VARCHAR(50), IN p_contrasena VARCHAR(50), OUT p_existe BOOLEAN)
BEGIN
    DECLARE v_count INT;

    -- Verificar si el usuario y la contraseña existen en la tabla usuarios
    SELECT COUNT(*) INTO v_count
    FROM controlacceso.usuarios
    WHERE Usuario = p_usuario AND Password = p_contrasena;

    -- Asignar verdadero si se encuentra al menos un registro que coincida
    IF v_count > 0 THEN
        SET p_existe = TRUE;
    ELSE
        SET p_existe = FALSE;
    END IF;
END$$
DELIMITER ;
