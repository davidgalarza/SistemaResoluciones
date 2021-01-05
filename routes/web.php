<?php

use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('/formatos', 'FormatosController@index')->middleware('auth', 'role:ADMINISTRADOR');
Route::get('/formatos/nuevo', 'FormatosController@create')->middleware('auth', 'role:ADMINISTRADOR');
Route::post('/formatos/crear', 'FormatosController@store')->middleware('auth', 'role:ADMINISTRADOR');
Route::get('/formatos/{formato}/editar', 'FormatosController@editar')->middleware('auth', 'role:ADMINISTRADOR');
Route::put('/formatos/{formato}/', 'FormatosController@update')->middleware('auth', 'role:ADMINISTRADOR');
Route::put('/formatos/{formato}/plantilla', 'FormatosController@actualizarPlantilla')->middleware('auth', 'role:ADMINISTRADOR');
Route::post('/formatos/procesar', 'FormatosController@wordToSchema')->middleware('auth', 'role:ADMINISTRADOR');
Route::get('/consejos', 'ConsejosController@index')->middleware('auth', 'role:ABOGADO');
Route::get('/consejos/nuevo', 'ConsejosController@create')->middleware('auth', 'role:ABOGADO');
Route::post('/consejos/crear', 'ConsejosController@store')->middleware('auth', 'role:ABOGADO');
Route::get('/consejos/{consejo}/editar', 'ConsejosController@editar')->middleware('auth', 'role:ABOGADO');
Route::get('/estudiantes/obtener', 'EstudiantesController@obtener')->middleware('auth', 'role:ABOGADO');
Route::get('/resoluciones/{id_consejo}/{id_formato}/{id_estudiante}/formulario', 'ResolucionesController@formulario')->middleware('auth', 'role:ABOGADO');
Route::post('/resoluciones/anadir', 'ResolucionesController@anadir')->middleware('auth', 'role:ABOGADO');
Route::get('/resoluciones/{id}/descargar', 'ResolucionesController@descargar')->middleware('auth', 'role:ABOGADO');