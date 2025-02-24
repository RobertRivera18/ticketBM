<?php
class Usuario_Equipo extends Conectar
{
    public function insert_usuario_equipos($usu_id, $equipo_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "INSERT INTO tm_usuario_equipo (usu_id, equipo_id) VALUES (?, ?)";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $equipo_id, PDO::PARAM_INT);
            return $stmt->execute(); // Retorna true o false directamente
        } catch (PDOException $e) {
            error_log("Error en insert_usuario_equipos: " . $e->getMessage());
            return false;
        }
    }


    public function get_equipos_por_usuario($usu_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT 
    eq.equipo_id, 
    eq.nombre_equipo, 
    eq.marca, 
    eq.modelo, 
    eq.serie, 
    u.usu_nom, 
    u.usu_ape,
    u.ip,
    u.mac
FROM tm_usuario_equipo
INNER JOIN tm_equipos eq ON tm_usuario_equipo.equipo_id = eq.equipo_id
INNER JOIN tm_usuario u ON tm_usuario_equipo.usu_id = u.usu_id
WHERE tm_usuario_equipo.usu_id = ?
ORDER BY eq.equipo_id DESC;
";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error en get_equipos_por_usuario: " . $e->getMessage());
            return [];
        }
    }

    public function get_user_address($usu_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT ip, mac FROM tm_usuario WHERE usu_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        } catch (PDOException $e) {
            error_log("Error en get_user_address: " . $e->getMessage());
            return []; 
        }
    }



    public function delete_usuario_equipo($usu_id, $equipo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM tm_usuario_equipo WHERE usu_id = ? AND equipo_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $equipo_id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function create_word($usu_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT 
                        u.usu_nom AS nombre_usuario,
                        u.usu_ape AS apellido_usuario,
                        u.usu_cedula AS cedula,
                        eq.equipo_id, 
                        eq.nombre_equipo AS descripcion, 
                        eq.marca, 
                        eq.modelo, 
                        eq.serie AS serie
                    FROM 
                        tm_usuario_equipo ue
                    INNER JOIN 
                        tm_equipos eq ON ue.equipo_id = eq.equipo_id
                    INNER JOIN 
                        tm_usuario u ON ue.usu_id = u.usu_id
                    WHERE 
                        ue.usu_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $usu_id, PDO::PARAM_INT);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($results)) {
                return false;
            }

            return $results; // Retorna todos los registros
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }
}
