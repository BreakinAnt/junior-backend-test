<?php

use App\Http\Controllers\CreateContactController;
use Illuminate\Support\Facades\Route;

Route::get('/contacts', [CreateContactController::class, 'index'])->name('contacts.index');
Route::get('/contacts/create', [CreateContactController::class, 'create'])->name('contacts.create');
Route::post('/contacts', [CreateContactController::class, 'store'])->name('contacts.store');
Route::get('/contacts/{id}/edit', [CreateContactController::class, 'edit'])->name('contacts.edit');
Route::put('/contacts/{id}', [CreateContactController::class, 'update'])->name('contacts.update');
Route::delete('/contacts/{id}', [CreateContactController::class, 'destroy'])->name('contacts.destroy');

// Trash routes
Route::get('/contacts/trash', [CreateContactController::class, 'trash'])->name('contacts.trash');
Route::patch('/contacts/{id}/restore', [CreateContactController::class, 'restore'])->name('contacts.restore');
Route::delete('/contacts/{id}/force-delete', [CreateContactController::class, 'forceDelete'])->name('contacts.force-delete');