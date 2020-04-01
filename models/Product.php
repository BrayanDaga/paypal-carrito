<?php

include_once 'lib/db.php';

class Product extends DB{

    function __construct(){
        parent::__construct();
    }

    public function getAll(){
        $query = $this->connect()->prepare('SELECT * FROM products');
        $query->execute();
        $items = $query->fetchAll(PDO::FETCH_ASSOC);
        
            
        return $items;
    }

    public function delete($codigo){
        $query = $this->connect()->prepare('DELETE FROM empleados WHERE codigo = :codigo');
        try{
            $query->execute([
                'codigo' => $codigo
            ]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }


    public function insert($datos){
        $query = $this->connect()->prepare('INSERT INTO empleados VALUES(:codigo, :nombres, :lugar_nacimiento, :fecha_nacimiento, :direccion, :telefono, :puesto, :estado)');
        try{
            $query->execute([
                'codigo'            => $datos['codigo'],
                'nombres'           => $datos['nombres'],
                'lugar_nacimiento'  => $datos['lugar_nacimiento'],
                'fecha_nacimiento'  => $datos['fecha_nacimiento'],
                'direccion'         => $datos['direccion'], 
                'telefono'          => $datos['telefono'],
                'puesto'            => $datos['puesto'],
                'estado'            => $datos['estado']
            ]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }

    public function getById($codigo){
        $query = $this->connect()->prepare('SELECT * FROM empleados WHERE codigo= :codigo');
        $query->execute(['codigo'=>$codigo]);

        $row=$query->fetch();
        return [
            'codigo'            => $row['codigo'],
            'nombres'           => $row['nombres'],
            'lugar_nacimiento'  => $row['lugar_nacimiento'],
            'fecha_nacimiento'  => $row['fecha_nacimiento'],
            'direccion'         => $row['direccion'],
            'telefono'          => $row['telefono'],
            'puesto'            => $row['puesto'],
            'estado'            => $row['estado']   
        ];
    }

    public function update($item){
        $query = $this->connect()->prepare('UPDATE empleados SET nombres = :nombres, lugar_nacimiento = :lugar_nacimiento, fecha_nacimiento = :fecha_nacimiento, direccion = :direccion, telefono = :telefono, puesto = :puesto, estado = :estado WHERE codigo = :codigo');
        try{
            $query->execute([
                'codigo'            => $item['codigo'],
                'nombres'           => $item['nombres'],
                'lugar_nacimiento'  => $item['lugar_nacimiento'],
                'fecha_nacimiento'  => $item['fecha_nacimiento'],
                'direccion'         => $item['direccion'],
                'telefono'          => $item['telefono'],
                'puesto'            => $item['puesto'],
                'estado'            => $item['estado']   
            ]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }

    
}

?>