<?php
namespace App\Http\Controllers;

use App\DTO\Business\UpdateBusinessDTO;
use App\Exceptions\AppException;
use Throwable;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Services\BusinessService;
use Illuminate\Routing\Controller;
use App\DTO\Business\CreateBusinessDTO;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

// TODO: Revisar los trycatch de errores 500
class BusinessController extends Controller
{

    use ApiResponseTrait;

    public function __construct(private readonly BusinessService $businessService)
    {}

    public function findById(int $businessId): JsonResponse
    {
        try {
            $business = $this->businessService->findById($businessId);

            return $this->ok([$business]);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $this->validateBusiness($request);
            $dto = CreateBusinessDTO::createFromArray($request->all());
            $business = $this->businessService->create($dto); // TODO: Hacer el Response

            return $this->created($business);
        } catch (ValidationException $ex) {
            return $this->validationError($ex->validator->errors()->first());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function update(int $businessId, Request $request): JsonResponse
    {
        try {
            $this->validateBusiness($request);
            $dto = UpdateBusinessDTO::createFromArray($request->all(), $businessId);
            $business = $this->businessService->update($dto);

            return $this->ok([$business]); // TODO: Hacer el Response
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (ValidationException $ex) {
            return $this->validationError($ex->validator->errors()->first());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    // Hace un soft delete
    public function delete(int $businessId): JsonResponse
    {
        try {
            $this->businessService->delete($businessId);
            return $this->noContent();
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
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