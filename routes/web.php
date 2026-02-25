<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CurriculumController;
use App\Http\Controllers\Admin\PromptTemplateController;
use App\Http\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    // Log
    Log::info("Incoming request to home page!");
    return view('welcome');
});

Route::middleware('auth.basic')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Curriculum
    Route::get('/curriculum', [CurriculumController::class, 'index'])->name('admin.curriculum.index');
    Route::post('/curriculum/import', [CurriculumController::class, 'import'])->name('admin.curriculum.import');
    Route::delete('/curriculum/{curriculum}', [CurriculumController::class, 'destroy'])->name('admin.curriculum.destroy');

    // Prompt Templates
    Route::get('/templates', [PromptTemplateController::class, 'index'])->name('admin.templates.index');
    Route::post('/templates', [PromptTemplateController::class, 'store'])->name('admin.templates.store');
    Route::patch('/templates/{template}/toggle', [PromptTemplateController::class, 'toggle'])->name('admin.templates.toggle');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});
