<?php

namespace App\DDD\Backoffice\Booking\Domain\Entity;

use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingDate;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingId;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingToken;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use App\DDD\Backoffice\Shared\ValueObject\Password;
use App\DDD\Backoffice\Shared\ValueObject\SpanishPhoneNumber;
use App\DDD\Backoffice\Shared\ValueObject\Text;
use App\DDD\Backoffice\Shared\ValueObject\Uuid;
use App\DDD\Backoffice\User\Domain\ValueObject\AuthUserId;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;

final readonly class PreBooking implements Arrayable, JsonSerializable
{

    protected function __construct(
        private ServiceId          $serviceId,
        private AuthUserId         $authUserId,

        private BookingDate        $startDate,
        private BookingDate        $endDate,

        private Text               $userName,
        private Text               $userEmail,
        private Password           $userPass,
        private BookingToken       $bookingToken,
        private BookingDate        $expirationDate,
        private ?BookingId         $id = null,
        private ?Uuid              $uuid = null,
        private ?SpanishPhoneNumber $userPhone = null,
    )
    {
    }

    public static function create(
        ServiceId          $serviceId,
        AuthUserId         $authUserId,
        BookingDate        $startDate,
        BookingDate        $endDate,
        Text               $userName,
        Text               $userEmail,
        Password           $userPass,
        BookingToken       $bookingToken,
        BookingDate        $expirationDate,
        ?BookingId         $id = null,
        ?Uuid              $uuid = null,
        ?SpanishPhoneNumber $userPhone = null,
    ): self
    {
        return new self(
            serviceId: $serviceId,
            authUserId: $authUserId,
            startDate: $startDate,
            endDate: $endDate,
            userName: $userName,
            userEmail: $userEmail,
            userPass: $userPass,
            bookingToken: $bookingToken,
            expirationDate: $expirationDate,
            id: $id,
            uuid: $uuid,
            userPhone: $userPhone
        );
    }

    public static function fromEloquentModel(Model $model): self
    {
        return new self(
            serviceId: ServiceId::createFromInt($model->service_id),
            authUserId: AuthUserId::createFromInt($model->user_id),
            startDate: BookingDate::createFromString($model->start_date),
            endDate: BookingDate::createFromString($model->end_date),
            userName: Text::createFromString($model->user_name),
            userEmail: Text::createFromString($model->user_email),
            userPass: Password::createFromString($model->user_pass),
            bookingToken: BookingToken::createFromString($model->token),
            expirationDate: BookingDate::createFromString($model->expiration_date),
            id: $model->id ? BookingId::createFromInt($model->id) : null,
            uuid: $model->uuid ? Uuid::createFromString($model->uuid) : null,
            userPhone: $model->user_phone ? SpanishPhoneNumber::createFromString($model->user_phone) : null,
        );
    }

    public static function fromEloquentModelWithUser(Model $model): self
    {
        return new self(
            serviceId: ServiceId::createFromInt($model->service_id),
            authUserId: AuthUserId::createFromInt($model->user->id),
            startDate: BookingDate::createFromString($model->start_date),
            endDate: BookingDate::createFromString($model->end_date),
            userName: Text::createFromString($model->user->name),
            userEmail: Text::createFromString($model->user->email),
            userPass: Password::createFromString($model->user->password),
            bookingToken: BookingToken::createFromString($model->token),
            expirationDate: BookingDate::createFromString($model->expiration_date),
            id: $model->id ? BookingId::createFromInt($model->id) : null,
            uuid: $model->uuid ? Uuid::createFromString($model->uuid) : null,
            userPhone: $model->user_phone ? SpanishPhoneNumber::createFromString($model->user->telephone) : null,
        );
    }

    public static function getEloquentModel(): Model
    {
        return new class () extends Model {
            protected $table = 'pre_bookings';
            protected $primaryKey = 'id';
            public $timestamps = true;
            public $incrementing = true;

            protected $fillable = [
                'uuid',
                'id',
                'service_id',
                'user_id',
                'start_date',
                'end_date',
                'token',
                'expiration_date',
                'user_name',
                'user_email',
                'user_phone',
                'user_pass',
            ];

            public function user() {
                return $this->belongsTo(User::class);
            }
        };
    }

    public function toEloquentModel(bool $exists = false): Model
    {
        $model = self::getEloquentModel();

        $model->exists = $exists;

        $model->id = $exists ? $this->id?->value() : null;
        $model->uuid = $this->uuid?->value();
        $model->service_id = $this->serviceId->value();
        $model->user_id = $this->authUserId->value();
        $model->start_date = $this->startDate->value();
        $model->end_date = $this->endDate->value();
        $model->token = $this->bookingToken->value();
        $model->expiration_date = $this->expirationDate->value();
        $model->user_name = $this->userName->value();
        $model->user_email = $this->userEmail->value();
        $model->user_phone = $this->userPhone?->value();
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

    public function getId(): ?BookingId
    {
        return $this->id;
    }

    public function getServiceId(): ServiceId
    {
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

    public function toArray(): array
    {
        return [
            'uuid' => $this->uuid ? $this->uuid->value() : null,
            'id' => $this->id ? $this->id->value() : null,
            'service_id' => $this->serviceId->value(),
            'user_id' => $this->authUserId->value(),
            'start_date' => $this->startDate->value(),
            'end_date' => $this->endDate->value(),
            'token' => $this->bookingToken->value(),
            'expiration_date' => $this->expirationDate->value(),
            'user_name' => $this->userName->value(),
            'user_email' => $this->userEmail->value(),
            'user_phone' => $this->userPhone ? $this->userPhone->value() : null,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
