<?php 
namespace App\Services;

use App\Models\Business;

    class BusinessService {
        public static function findById($id){
            return Business::findOrFail($id);
        }
        
        public static function create($data){
            return Business::create($data);
        }

        public static function update($id, $data){
            $business = Business::findOrFail($id);
            $business->update($data);
            return $business;
        }

        public static function delete($id){
            $business = Business::findOrFail($id); // Si falla, lanza una excepción 404 automáticamente 
            $business->delete();
            return true; // Por lo que podemos asumir que si llega aqui es porque se eliminó con exito
        }
    }