<?php 
namespace App\Services;

use App\Models\Business;

    class BusinessService{
        public static function findById($id){
            return Business::find($id);
        }
    }