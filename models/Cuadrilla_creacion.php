<?php
class Cuadrilla_creacion extends Conectar
{

    public function insert_cuadrilla($cua_nombre, $cua_empresa, $cua_ciudad)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_cuadrilla (cua_nombre,cua_empresa,cua_ciudad) VALUES (?,?,?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_nombre);
        $stmt->bindValue(2, $cua_empresa);
        $stmt->bindValue(3, $cua_ciudad);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $conectar->lastInsertId();
        } else {
            return false;
        }
    }
    public function update_cuadrilla($cua_nombre, $cua_empresa, $cua_ciudad, $cua_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_cuadrilla SET cua_nombre = ?, cua_empresa = ?, cua_ciudad = ? WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $cua_nombre);
        $stmt->bindValue(2, $cua_empresa);
        $stmt->bindValue(3, $cua_ciudad);
        $stmt->bindValue(4, $cua_id);
        $stmt->execute();
        return $stmt->rowCount();
    }


    public function get_cuadrillas()
    {
        try {
            // Conexión a la base de datos
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_cuadrilla";
            $stmt = $conectar->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar un colaborador
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

    public function marcarRecarga($cua_id, $recargas)
    {
        $conectar = parent::conexion();
        parent::set_names();
        // Preparar la consulta para actualizar el campo recargas
        $sql = "UPDATE tm_cuadrilla SET recargas = ? WHERE cua_id = ?";
        $stmt = $conectar->prepare($sql);

        // Enlazar los valores
        $stmt->bindValue(1, $recargas, PDO::PARAM_BOOL);  // True o False
        $stmt->bindValue(2, $cua_id, PDO::PARAM_INT);     // ID de la cuadrilla

        // Ejecutar la consulta
        $stmt->execute();

        // Retornar si la actualización fue exitosa
        return $stmt->rowCount();
    }


    //Todos los registros de recargas los establezco en false
    public function desmarcarTodas()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_cuadrilla SET recargas = false";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();
        return $stmt->rowCount();
    }


    //Obtener cuadrillas que se le han hecho las recargas 
    public function obtenerRecargasTrue()
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "SELECT 
    cua.cua_id, 
    cua.cua_nombre, 
    CASE cua.cua_ciudad
        WHEN 1 THEN 'Guayaquil'
        WHEN 2 THEN 'Quito'
        ELSE 'Desconocida'  -- Para manejar valores que no sean 1 o 2
    END AS ciudad_nombre, 
    IF(cua.recargas, 'Recarga realizada', 'No realizada') AS recargas, -- Utilizamos IF para simplificar
    eq.serie
FROM tm_cuadrilla cua
LEFT JOIN tm_cuadrilla_equipo cqe ON cua.cua_id = cqe.cua_id
LEFT JOIN tm_equipos eq ON cqe.equipo_id = eq.equipo_id;
 
";
        $stmt = $conectar->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
