<?php 
namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use App\Services\BusinessService;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Js;
use Nette\Utils\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class BusinessController extends Controller {

    public function findById(int $id): JsonResponse {
        $business = BusinessService::findById($id);

        return response()->json([$business], 200);
    }

    public function create(Request $request): JsonResponse{
        $data = $request->all();

        BusinessService::create($data);

        return response()->json(['created' => true], 201);
    }

    public function update(int $id, Request $request): JsonResponse {
        $data = $request->all();
        $business = BusinessService::update($id, $data);

        return response()->json([$business], 200);
    }

    public function delete(int $id): JsonResponse {
        BusinessService::delete($id);

        return response()->json(['deleted' => true], 204);
    }
}