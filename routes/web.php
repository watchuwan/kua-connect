<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::home')->name('home');

Route::livewire('/tracking', 'pages::pelacakan.index')->name('tracking.index');
Route::livewire('/tracking/{pendaftaran:nomor_antrean}', 'pages::pelacakan.show')->name('tracking.show');

Route::livewire('/layanan', 'pages::pelayanan.index')->name('pelayanan.index');
Route::livewire('/layanan/{pelayanan}/daftar', 'pages::pelayanan.daftar')->name('pelayanan.daftar');
Route::redirect('/layanan/{pelayanan}', '/layanan/{pelayanan}/daftar')->name('pelayanan.show');

Route::livewire('/test-counter', 'pages::test-counter')->name('test.counter');


