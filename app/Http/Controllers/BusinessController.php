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

class BusinessController extends Controller
{

    use ApiResponseTrait;

    public function __construct(private readonly BusinessService $businessService)
    {}

    public function findById(int $businessId): JsonResponse
    {
        try {
            $businessResp = $this->businessService->findById($businessId);

            return $this->ok([$businessResp]);
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
            $businessResp = $this->businessService->create($dto);

            return $this->created($businessResp);
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
            $businessResp = $this->businessService->update($dto);

            return $this->ok([$businessResp]);
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
                'email' => 'required|email|max:255|unique:businesses,email',
                'phone' => 'required|string|max:20',
                'open_hours' => 'required|date_format:H:i',
                'close_hours' => 'required|date_format:H:i|after:open_hours',
                'open_days' => 'required|string|min:1',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'El email no tiene un formato válido.',
                'email.unique' => 'El email ya esta registrado.',
                'open_hours.required' => 'La hora de apertura es obligatoria.',
                'open_hours.date_format' => 'La hora de apertura debe tener formato HH:MM.',
                'close_hours.required' => 'La hora de cierre es obligatoria.',
                'close_hours.date_format' => 'La hora de cierre debe tener formato HH:MM.',
                'close_hours.after' => 'La hora de cierre debe ser posterior a la de apertura.',
                'open_days.required' => 'Los días de apertura son obligatorios.',
            ],
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
