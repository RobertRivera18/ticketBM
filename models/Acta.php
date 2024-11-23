<?php
class Acta extends Conectar
{

    public function insert_acta($tipo_acta,$col_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO acta (tipo_acta,col_id) VALUES (?,?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $tipo_acta);
        $stmt->bindValue(2, $col_id);

        if ($stmt->execute()) {
            $lastInsertId = $conectar->lastInsertId();
            error_log("ID insertado: " . $lastInsertId);
            return $lastInsertId;
        } else {
            error_log("Error en la inserción: " . implode(" ", $stmt->errorInfo()));
            return false;
        }
    }


}
 
//     //Asigno colaboradores a una cuadrilla 
//     public function insert_cuadrilla_asignacion($cua_id, $col_id)
//     {
//         $conectar = parent::conexion();
//         parent::set_names();
//         $sql = "INSERT INTO tm_cuadrilla_colaborador (cua_id, col_id) 
//             VALUES (?, ?)";
//         $sql = $conectar->prepare($sql);
//         $sql->bindValue(1, $cua_id, PDO::PARAM_INT);
//         $sql->bindValue(2, $col_id, PDO::PARAM_INT);
//         if ($sql->execute()) {
//             return true;
//         } else {
//             return false;
//         }
//     }

//     public function insert_cuadrilla_equipos($cua_id, $equipo_id)
//     {
//         $conectar = parent::conexion();
//         parent::set_names();
//         $sql = "INSERT INTO tm_cuadrilla_equipo (cua_id, equipo_id) 
//             VALUES (?, ?)";
//         $sql = $conectar->prepare($sql);
//         $sql->bindValue(1, $cua_id, PDO::PARAM_INT);
//         $sql->bindValue(2, $equipo_id, PDO::PARAM_INT);
//         if ($sql->execute()) {
//             return true;
//         } else {
//             return false;
//         }
//     }


