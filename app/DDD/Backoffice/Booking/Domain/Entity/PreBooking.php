<?php

namespace App\DDD\Backoffice\Booking\Domain\Entity;

use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingDate;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use App\DDD\Backoffice\Shared\ValueObject\Password;
use App\DDD\Backoffice\Shared\ValueObject\SpanishPhoneNumber;
use App\DDD\Backoffice\Shared\ValueObject\Text;
use App\DDD\Backoffice\Shared\ValueObject\Uuid;
use App\DDD\Backoffice\User\Domain\ValueObject\AuthUserId;
use App\Models\Booking;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

final readonly class PreBooking implements Arrayable, JsonSerializable
{

    protected function __construct(
        private BookingId          $id,
        private ?Uuid              $uuid,
        private ServiceId          $serviceId,
        private AuthUserId         $authUserId,

        private BookingDate        $startDate,
        private BookingDate        $endDate,

        private Text               $userName,
        private Text               $userEmail,
        private SpanishPhoneNumber $userPhone,
        private Password           $userPass,
    )
    {
    }

    public static function create(
        BookingId          $id,
        ServiceId          $serviceId,
        AuthUserId         $authUserId,
        BookingDate        $startDate,
        BookingDate        $endDate,
        Text               $userName,
        Text               $userEmail,
        SpanishPhoneNumber $userPhone,
        Password           $userPass,
        ?Uuid              $uuid = null,
    ): self
    {
        return new self(
            id: $id,
            uuid: $uuid,
            serviceId: $serviceId,
            authUserId: $authUserId,
            startDate: $startDate,
            endDate: $endDate,
            userName: $userName,
            userEmail: $userEmail,
            userPhone: $userPhone,
            userPass: $userPass
        );
    }

    public static function fromEloquentModel(Model $model): self
    {
        $entity = new self(
            id: BookingId::createFromInt($model->id),
            uuid: $model->uuid ? Uuid::crateFromString($model->uuid) : null,
            serviceId: ServiceId::createFromInt($model->service_id),
            authUserId: AuthUserId::createFromInt($model->auth_user_id),
            startDate: BookingDate::createFromString($model->start_date),
            endDate: BookingDate::createFromString($model->end_date),
            userName: Text::createFromString($model->user_name),
            userEmail: Text::createFromString($model->user_email),
            userPhone: SpanishPhoneNumber::createFromString($model->user_phone),
            userPass: Password::createFromString($model->user_pass)
        );

        return $entity;
    }

    public static function getEloquentModel(): Model
    {
        return new class () extends Model {
            protected $table = 'pre_bookings';
            protected $primaryKey = 'id';
            public $timestamps = true;
            public $incrementing = true;

            protected $fillable = [
                /*'uuid',*/
                'id',
                'service_id',
                'user_id',
                'start_date',
                'end_date',
                'user_name',
                'user_email',
                'user_phone',
                'user_pass',
            ];
        };
    }

    public function toEloquentModel(bool $exists = false): Model
    {
        $model = self::getEloquentModel();

        $model->exists = $exists;

        $model->id = $exists ? $this->id?->value() : null;
        $model->uuid = $this->uuid?->value();
        $model->service_id = $this->serviceId->value();
        $model->auth_user_id = $this->authUserId->value();
        $model->start_date = $this->startDate->value();
        $model->end_date = $this->endDate->value();

        $model->user_name = $this->userName->value();
        $model->user_email = $this->userEmail->value();
        $model->user_phone = $this->userPhone->value();
        $model->user_pass = $this->userPass->value();

        return $model;
    }

    /* =======================
       Helpers y getters
       ======================= */
    public function hasId(): bool
    {
        return $this->id !== null;
    }

    public function hasUuid(): bool
    {
        return $this->uuid !== null;
    }

    public function getId(): BookingId {
        return $this->id;
    }

    public function getServiceId(): ServiceId {
        return $this->serviceId;
    }

    public function getStartDate(): BookingDate
    {
        return $this->startDate;
    }

    public function getEndDate(): BookingDate
    {
        return $this->endDate;
    }

    public function getUserName(): Text
    {
        return $this->userName;
    }

    public function getUserEmail(): Text
    {
        return $this->userEmail;
    }

    public function getUserPhone(): SpanishPhoneNumber
    {
        return $this->userPhone;
    }

    public function toArray()
    {
        return [
            'uuid' => $this->uuid?->value(),
            'id' => $this->id->value(),
            'service_id' => $this->serviceId->value(),
            'auth_user_id' => $this->authUserId->value(),
            'start_date' => $this->startDate->value(),
            'end_date' => $this->endDate->value(),
            'user_name' => $this->userName->value(),
            'user_email' => $this->userEmail->value(),
            'user_phone' => $this->userPhone->value(),
            'user_pass' => $this->userPass->value(),
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
