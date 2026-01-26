<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PendidikanTemplateController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/pendidikan/template', [PendidikanTemplateController::class, 'download'])->name('pendidikan.template');
