<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use Inertia\Inertia;

Route::apiResource("/tickets", TicketController::class);
