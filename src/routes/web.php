<?php

use hafid\project_installer\http\Controller\InstallController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'installer', 'namespace' => 'hafid\project_installer\http\Controller'], function () {
    Route::get('install', [InstallController::class, 'index'])->name("install.check");
    Route::post('install', [InstallController::class, 'install_db'])->name("install.db");
    Route::get('install/administrator', [InstallController::class, 'install_administrator'])->name("install.administrator");
    Route::post('install/administrator', [InstallController::class, 'install_finish'])->name('install.finish');
});
