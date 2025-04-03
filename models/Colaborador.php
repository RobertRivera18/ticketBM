<?php
class Colaborador extends Conectar
{

    public function insert_colaborador($col_nombre, $col_cedula, $empresa_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "INSERT INTO tm_colaborador (col_nombre,col_cedula,empresa_id) VALUES (?,?,?)";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $col_nombre);
        $stmt->bindValue(2, $col_cedula);
        $stmt->bindValue(3, $empresa_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $conectar->lastInsertId();
        } else {
            return false;
        }
    }
    public function update_colaborador($col_id, $col_nombre, $col_cedula, $empresa_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "UPDATE tm_colaborador set 
             col_nombre = ?,
              col_cedula = ?, 
              empresa_id = ? WHERE 
              col_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $col_nombre);
        $stmt->bindValue(2, $col_cedula);
        $stmt->bindValue(3, $empresa_id);
        $stmt->bindValue(4, $col_id);
        $stmt->execute();
        return $resultado=$stmt->fetchAll();
    }

    public function get_colaborador(){
        try {
            $conectar = parent::conexion();
            parent::set_names();
            $sql = "SELECT * FROM tm_colaborador";
            // Preparar y ejecutar la consulta
            $stmt = $conectar->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar un colaborador
    public function delete_colaborador($col_id){
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "DELETE FROM tm_colaborador WHERE col_id = ?";
        $stmt = $conectar->prepare($sql);
        $stmt->bindValue(1, $col_id);
        $result = $stmt->execute();
       
        if ($result) {
            return true; 
        } else {
            return false; 
        }
    }

    public function get_colaborador_x_id($col_id)
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "call sp_l_colaborador_01(?)";
        $sql = $conectar->prepare($sql);
        $sql->bindValue(1, $col_id);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }


    //Muestra los colaboradores que no tienen asignado una cuadrilla 
    public function get_colaboradores()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT c.*
             FROM tm_colaborador c
             LEFT JOIN tm_cuadrilla_colaborador cc ON c.col_id = cc.col_id
             WHERE cc.col_id IS NULL";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }

    public function get_all_colaboradores()
    {
        $conectar = parent::conexion();
        parent::set_names();
        $sql = "SELECT c.*
             FROM tm_colaborador c
             LEFT JOIN tm_cuadrilla_colaborador cc ON c.col_id = cc.col_id";
        $sql = $conectar->prepare($sql);
        $sql->execute();
        return $resultado = $sql->fetchAll();
    }


      //Muestra los colaboradores que pertenecen a claro
      public function get_colaboradoresClaro(){
          $conectar = parent::conexion();
          parent::set_names();
          $sql = "SELECT *
               FROM tm_colaborador c
               WHERE empresa_id =2";
          $sql = $conectar->prepare($sql);
          $sql->execute();
          return $resultado = $sql->fetchAll();
      }
}
