<?php

use Illuminate\Support\Facades\Route;


Auth::routes(['register' => false]);

Route::get('/', 'HomeController@index')->name('home')->middleware('onlyAdmin', 'banned');
Route::get('formatoPorCarrera/{id}', 'HomeController@formatosXid');
Route::get('graficaBarras/{carrera}/{formato}', 'HomeController@obtenerDatosGrafica');
Route::get('/formatos', 'FormatosController@index')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::get('/formatos/nuevo', 'FormatosController@create')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::post('/formatos/crear', 'FormatosController@store')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::get('/formatos/{formato}/editar', 'FormatosController@editar')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::put('/formatos/{formato}/', 'FormatosController@update')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::put('/formatos/{formato}/plantilla', 'FormatosController@actualizarPlantilla')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::post('/formatos/procesar', 'FormatosController@wordToSchema')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::get('/consejos', 'ConsejosController@index')->middleware('auth', 'role:ABOGADO', 'banned');
Route::get('/consejos/nuevo', 'ConsejosController@create')->middleware('auth', 'role:ABOGADO', 'banned');
Route::post('/consejos/crear', 'ConsejosController@store')->middleware('auth', 'role:ABOGADO', 'banned');
Route::get('/consejos/{consejo}/editar', 'ConsejosController@editar')->middleware('auth', 'role:ABOGADO', 'banned');
Route::get('/estudiantes/obtener', 'EstudiantesController@obtener')->middleware('auth', 'role:ABOGADO', 'banned');
Route::get('/resoluciones/{id_consejo}/{id_formato}/{id_estudiante}/formulario', 'ResolucionesController@formulario')->middleware('auth', 'role:ABOGADO', 'banned');
Route::post('/resoluciones/anadir', 'ResolucionesController@anadir')->middleware('auth', 'role:ABOGADO', 'banned');
Route::get('/resoluciones/{id}/descargar', 'ResolucionesController@descargar')->middleware('auth', 'role:ABOGADO', 'banned');
Route::get('/carreras', 'CarrerasController@index')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::get('/carreras/nuevo', 'CarrerasController@create')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::get('/carreras/{id}/editar', 'CarrerasController@editar')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::resource('/usuarios', 'UsuariosController')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
//Route::get('/usuarios/nuevo', 'UsuariosController@create')->middleware('auth', 'role:ADMINISTRADOR', 'banned');
Route::get('/estudiantes', 'EstudiantesController@index')->middleware('auth', 'role:ADMINISTRADOR', 'banned');