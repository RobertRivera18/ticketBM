<?php
class Inspeccion extends Conectar
{

    public function insert_inspeccion(
        $trabajo,
        $ubicacion,
        $numero_orden,
        $solicitante_id,
        $zona_resbaladiza,
        $zona_con_desnivel,
        $hueco_piso_danado,
        $instalacion_mal_estado,
        $desconectados_expuestos,
        $escalera_buen_estado,
        $senaletica_instalada,

    ) {
        $conectar = parent::conexion();
        parent::set_names();

        try {
            // Iniciar transacción
            $conectar->beginTransaction();

            // Insertar la inspección principal
            $sql = "INSERT INTO tm_inspeccion (trabajo, ubicacion, numero_orden, fecha, solicitante_id, 
                zona_resbaladiza, zona_con_desnivel, hueco_piso_danado, instalacion_mal_estado, 
                cables_desconectados_expuestos, escalera_buen_estado, senaletica_instalada) 
                VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $trabajo);
            $stmt->bindValue(2, $ubicacion);
            $stmt->bindValue(3, $numero_orden);
            $stmt->bindValue(4, $solicitante_id);
            $stmt->bindValue(5, $zona_resbaladiza);
            $stmt->bindValue(6, $zona_con_desnivel);
            $stmt->bindValue(7, $hueco_piso_danado);
            $stmt->bindValue(8, $instalacion_mal_estado);
            $stmt->bindValue(9, $desconectados_expuestos);
            $stmt->bindValue(10, $escalera_buen_estado);
            $stmt->bindValue(11, $senaletica_instalada);

            $stmt->execute();
            $inspeccion_id = $conectar->lastInsertId();

            // Insertar los equipos de seguridad
            $sql_equipos = "INSERT INTO tm_equipos_seguridad (
                inspeccion_id, 
                botas,
                chaleco,
                proteccion_auditiva,
                proteccion_visual,
                linea_vida,
                arnes,
                otros_equipos
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_equipos = $conectar->prepare($sql_equipos);
            $stmt_equipos->bindValue(1, $inspeccion_id);
            $stmt_equipos->bindValue(2, $_POST['botas'] ?? 'N/A');
            $stmt_equipos->bindValue(3, $_POST['chaleco'] ?? 'N/A');
            $stmt_equipos->bindValue(4, $_POST['proteccion_auditiva'] ?? 'N/A');
            $stmt_equipos->bindValue(5, $_POST['proteccion_visual'] ?? 'N/A');
            $stmt_equipos->bindValue(6, $_POST['linea_vida'] ?? 'N/A');
            $stmt_equipos->bindValue(7, $_POST['arnes'] ?? 'N/A');
            $stmt_equipos->bindValue(8, $_POST['otros_equipos'] ?? null);

            $stmt_equipos->execute();

            // Confirmar transacción
            $conectar->commit();

            return $inspeccion_id;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conectar->rollBack();
            return false;
        }
    }


    public function guardar_imagen_inspeccion($inspeccion_id, $ruta_imagen)
    {
        $conectar = parent::conexion();
        $sql = "UPDATE tm_inspeccion SET imagen = ? WHERE inspeccion_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $ruta_imagen);
        $stmt->bindValue(2, $inspeccion_id);
        $stmt->execute();
    }

    public function get_inspecciones()
    {
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "
                SELECT 
                i.inspeccion_id,
                 i.trabajo,
                 i.ubicacion,
                 i.numero_orden,
                 i.fecha,
                 c.col_nombre
           FROM 
               tm_inspeccion AS i
           INNER JOIN 
               tm_colaborador AS c
           ON 
               i.solicitante_id = c.col_id
            ";

            $stmt = $conectar->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Manejo de errores si la consulta falla
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }

    public function insert_equipos_seguridad($data)
    {
        $conectar = parent::conexion();
        parent::set_names();

        $sql = "INSERT INTO tm_equipos_seguridad (
            inspeccion_id,
            botas,
            chaleco,
            proteccion_auditiva,
            proteccion_visual,
            linea_vida,
            arnes,
            otros_equipos
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $data['inspeccion_id']);
        $stmt->bindValue(2, $data['botas']);
        $stmt->bindValue(3, $data['chaleco']);
        $stmt->bindValue(4, $data['proteccion_auditiva']);
        $stmt->bindValue(5, $data['proteccion_visual']);
        $stmt->bindValue(6, $data['linea_vida']);
        $stmt->bindValue(7, $data['arnes']);
        $stmt->bindValue(8, $data['otros_equipos']);

        return $stmt->execute();
    }

    //Método para eliminar una inspeccion
    public function delete_inspeccion($inspeccion_id)
    {
        $conectar = parent::conexion();
        parent::set_names();

        try {
            // Iniciar transacción
            $conectar->beginTransaction();

            // Eliminar equipos de seguridad relacionados con la inspección
            $sql_equipos = "DELETE FROM tm_equipos_seguridad WHERE inspeccion_id = ?";
            $stmt_equipos = $conectar->prepare($sql_equipos);
            $stmt_equipos->bindValue(1, $inspeccion_id);
            $stmt_equipos->execute();

            // Eliminar la inspección
            $sql = "DELETE FROM tm_inspeccion WHERE inspeccion_id = ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $inspeccion_id);
            $result = $stmt->execute();

            // Confirmar transacción
            $conectar->commit();

            return $result ? true : false;
        } catch (Exception $e) {
            // Revertir transacción en caso de error
            $conectar->rollBack();
            return false;
        }
    }




    //Funcion para obtener todos los datos de la inspeccion
    public function get_inspeccion_x_id($inspeccion_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT 
        tm_inspeccion.inspeccion_id,
        tm_inspeccion.trabajo,
        tm_inspeccion.ubicacion,
        tm_inspeccion.numero_orden,
        tm_inspeccion.fecha,
        tm_colaborador.col_nombre,
        tm_inspeccion.zona_resbaladiza,
        tm_inspeccion.zona_con_desnivel,
        tm_inspeccion.hueco_piso_danado,
        tm_inspeccion.instalacion_mal_estado,
        tm_inspeccion.cables_desconectados_expuestos,
        tm_inspeccion.escalera_buen_estado,
        tm_inspeccion.senaletica_instalada,
        tm_inspeccion.imagen,
        tm_equipos_seguridad.botas, 
        tm_equipos_seguridad.chaleco,
        tm_equipos_seguridad.proteccion_auditiva,
        tm_equipos_seguridad.proteccion_visual,
        tm_equipos_seguridad.linea_vida,
        tm_equipos_seguridad.arnes,
        tm_equipos_seguridad.otros_equipos
    FROM 
        tm_inspeccion
    INNER JOIN 
        tm_colaborador 
        ON tm_inspeccion.solicitante_id = tm_colaborador.col_id
    LEFT JOIN 
        tm_equipos_seguridad 
        ON tm_inspeccion.inspeccion_id = tm_equipos_seguridad.inspeccion_id
    WHERE 
        tm_inspeccion.inspeccion_id = ?
    GROUP BY 
        tm_inspeccion.inspeccion_id";

        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $inspeccion_id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }
}
