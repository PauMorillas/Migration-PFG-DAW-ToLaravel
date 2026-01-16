<?php

namespace App\DDD\Backoffice\Shared\Domain\Bus;

// La intención de la interfaz es inyectar explícitamente el tipo de bus
interface SyncCommandBusInterface extends CommandBusInterface {}
