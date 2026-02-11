<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;

Route::post('/leads', [LeadController::class, 'store']);
