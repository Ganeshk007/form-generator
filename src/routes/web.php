<?php

use Ganesh\FormGenerator\Controllers\FormTemplateController;
use Ganesh\FormGenerator\Controllers\FormGeneratorController;
use Illuminate\Support\Facades\Route;


Route::resource('form-template', FormTemplateController::class)
    ->names([
        'index' => 'form-generator.template',
        'create' => 'form-generator.template.create',
        'store' => 'form-generator.template.store',
        'edit' => 'form-generator.template.edit',
        'update' => 'form-generator.template.update',
        'destroy' => 'form-generator.template.delete'
    ])->except(['show']);

Route::get('/form-builder', [FormGeneratorController::class, 'index'])->name('form-builder');
Route::get('/form-fields/{templateId}', [FormGeneratorController::class, 'getFields'])->name('form-fields');
