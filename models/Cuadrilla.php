<?php
class Cuadrilla extends Conectar
{

    public function insert_cuadrilla($cua_nombre)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_cuadrilla (cua_nombre) VALUES (?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_nombre);

        if ($stmt->execute()) {
            $lastInsertId = $conectar->lastInsertId();
            error_log("ID insertado: " . $lastInsertId);
            return $lastInsertId;
        } else {
            error_log("Error en la inserción: " . implode(" ", $stmt->errorInfo()));
            return false;
        }
    }



    public function get_cuadrilla()
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_cuadrilla";
            // Preparar y ejecutar la consulta
            $stmt = $conectar->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar una cuadrilla
    public function delete_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM tm_cuadrilla WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $result = $stmt->execute();
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function get_cuadrilla_x_id($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT * FROM tm_cuadrilla WHERE cua_id = ?";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cua_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    //Asigno colaboradores a una cuadrilla 
    public function insert_cuadrilla_asignacion($cua_id, $col_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_cuadrilla_colaborador (cua_id, col_id) 
            VALUES (?, ?)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cua_id, PDO::PARAM_INT);
        $sql->bindValue(2, $col_id, PDO::PARAM_INT);
        if ($sql->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_cuadrilla_equipos($cua_id, $equipo_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_cuadrilla_equipo (cua_id, equipo_id) 
            VALUES (?, ?)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $cua_id, PDO::PARAM_INT);
        $sql->bindValue(2, $equipo_id, PDO::PARAM_INT);
        if ($sql->execute()) {
            return true;
        } else {
            return false;
        }
    }



    //Metodo usado para mostar los colaboradores por cuadrilla en el DataTable
    public function get_colaboradores_por_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consulta para obtener los colaboradores de una cuadrilla
        $sql = "SELECT col.col_nombre
        FROM tm_cuadrilla_colaborador
        INNER JOIN tm_colaborador col ON tm_cuadrilla_colaborador.col_id = col.col_id
        WHERE tm_cuadrilla_colaborador.cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }



    //Metodo usado para mostar los equipos otorgados a las cuadrillas
    public function get_equipos_por_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consulta para obtener los colaboradores de una cuadrilla
        $sql = "SELECT eq.nombre_equipo,eq.marca,eq.serie
        FROM tm_cuadrilla_equipo
        INNER JOIN tm_equipos eq ON tm_cuadrilla_equipo.equipo_id = eq.equipo_id
        WHERE tm_cuadrilla_equipo.cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    //Metodo usado para mostar los equipos otorgados a las cuadrillas
    public function get_empresa_cuadrilla($cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        // Consulta para obtener los colaboradores de una cuadrilla
        $sql = "SELECT cua_empresa,cua_ciudad
         FROM tm_cuadrilla
         WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_id);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function create_word($cua_id)
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT 
    GROUP_CONCAT(DISTINCT c.col_nombre SEPARATOR ',') AS nombres_colaboradores,
    GROUP_CONCAT(DISTINCT c.col_cedula SEPARATOR ',') AS cedulas_colaboradores,
    GROUP_CONCAT(DISTINCT e.serie SEPARATOR ',') AS equipos_asignados,
    cu.cua_nombre AS nombre_cuadrilla
FROM
    tm_cuadrilla_colaborador cc
INNER JOIN
    tm_colaborador c ON cc.col_id = c.col_id
LEFT JOIN
    tm_cuadrilla_equipo ce ON ce.cua_id = cc.cua_id
LEFT JOIN
    tm_equipos e ON ce.equipo_id = e.equipo_id
INNER JOIN
    tm_cuadrilla cu ON cu.cua_id = cc.cua_id
WHERE
    cc.cua_id = ?
";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $cua_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$result) {
                return false;
            }

            return $result;
        } catch (PDOException $e) {
            // Manejo de errores
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }
}
