<?php

use App\Http\Controllers\CreateContactController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/contacts/create', [CreateContactController::class, 'create'])->name('contacts.create');
Route::post('/contacts', [CreateContactController::class, 'store'])->name('contacts.store');
