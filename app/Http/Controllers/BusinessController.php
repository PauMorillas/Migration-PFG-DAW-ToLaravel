<?php
namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use App\Services\BusinessService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

// TODO: Revisar los trycatch de errores 500
class BusinessController extends Controller
{
    
    public function findById(int $id): JsonResponse
    {
        try {
            $business = BusinessService::findById($id);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Negocio no encontrado'], 404);
        }

        return response()->json([$business], 200);
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $this->validateBusiness($request);
            BusinessService::create($request->all());

        } catch (ValidationException $ex) {
            return response()->json(['error' => $ex->validator->errors()->first()], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }
        return response()->json(['created' => true], 201);
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $this->validateBusiness($request);
            $business = BusinessService::update($id, $request->all());
        } catch (ValidationException $ex) {
            return response()->json(['error' => $ex->validator->errors()->first()], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }

        return response()->json([$business], 200);
    }

    // Hace un soft delete
    public function delete(int $id): JsonResponse
    {
        try {
            BusinessService::delete($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }

        return response()->json(['deleted' => true], 204); // TODO: Preguntar si es correcto devolver un deleted true
    }

    private function validateBusiness(Request $request): array
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'open_hours' => 'required|date_format:H:i',
                'close_hours' => 'required|date_format:H:i|after:open_hours',
                'open_days' => 'required|string|min:1',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El email no tiene un formato válido.',
                'open_hours.required' => 'La hora de apertura es obligatoria.',
                'open_hours.date_format' => 'La hora de apertura debe tener formato HH:MM.',
                'close_hours.required' => 'La hora de cierre es obligatoria.',
                'close_hours.date_format' => 'La hora de cierre debe tener formato HH:MM.',
                'close_hours.after' => 'La hora de cierre debe ser posterior a la de apertura.',
                'open_days.required' => 'Los días de apertura son obligatorios.',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
    /* private function validateData(array $data): array
    {
        $errors = []; // Formato: ['campo' => 'mensaje de error']

        // Errores por nulo
        $errors['name'] = $data['name'] ? null : 'El nombre es obligatorio';
        $errors['email'] = $data['email'] ? null : 'El email es obligatorio';
        $errors['phone'] = $data['phone'] ? null : 'El teléfono es obligatorio';
        $errors['open_hours'] = $data['open_hours'] ? null : 'Las horas de apertura son obligatorias';
        $errors['close_hours'] = $data['close_hours'] ? null : 'Las horas de cierre son obligatorias';
        $errors['open_days'] = $data['open_days'] ? null : 'Los días de apertura son obligatorios';

        if (isset($data['open_hours']) && isset($data['close_hours']) && $data['open_hours'] < $data['close_hours']) {
            $errors['open_hours'] = 'La hora de cierre no puede ser mayor a la hora de apertura';
        }

        // Agregar la lógica de validación según necesidades
        return $errors;
    } */
}