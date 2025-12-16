<?php 
namespace App\Http\Controllers;

use App\Services\BusinessService;
use Illuminate\Routing\Controller;

class BusinessController extends Controller {

    // route('business/{id}')
    public function findById($id){
        return BusinessService::findById($id);
    }
}