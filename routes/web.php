<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PertanyaanController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PenggunaLulusanController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TCFormController;
use App\Http\Controllers\InstansiController;
use App\Http\Controllers\ForgotPwAdminController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'postlogin'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Routes yang sudah disinkronkan dengan sistem yang ada
Route::post('/admin/forgot-password', [ForgotPwAdminController::class, 'sendResetLinkEmail'])->name('admin.password.email');
Route::get('/admin/reset-password/{token}', [ForgotPwAdminController::class, 'showResetForm'])->name('admin.password.reset');
Route::post('/admin/reset-password', [ForgotPwAdminController::class, 'reset'])->name('admin.password.update');

// Route::get('register', [AuthController::class, 'register'])->name('register');
// Route::post('register', [AuthController::class, 'postRegister']);

Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard-export_excel', [AdminDashboardController::class, 'export_excel']);
Route::get('/dashboard-lingkup_kerja', [AdminDashboardController::class, 'exportLingkupKerja']);
Route::get('/dashboard-masa_tunggu', [AdminDashboardController::class, 'exportMasaTunggu']);


Route::get('/pertanyaan', [PertanyaanController::class, 'index'])->name('pertanyaan.index');
Route::get('/pertanyaan/create', [PertanyaanController::class, 'create'])->name('pertanyaan.create');
Route::post('/pertanyaan', [PertanyaanController::class, 'store'])->name('pertanyaan.store');
Route::get('/pertanyaan/{id}/edit', [PertanyaanController::class, 'edit'])->name('pertanyaan.edit');
Route::put('/pertanyaan/{id}', [PertanyaanController::class, 'update'])->name('pertanyaan.update');
Route::delete('/pertanyaan/{id}', [PertanyaanController::class, 'destroy'])->name('pertanyaan.destroy');

// Route::get('/import', [ImportController::class, 'index'])->name('import.index');
// Route::get('/list', [ImportController::class, 'list']);
// Route::get('/import-form', [ImportController::class, 'import']); // Halaman form upload
// Route::post('/import_ajax', [ImportController::class, 'import_ajax'])->name('import_ajax');
// Route::post('/import_excel', [ImportController::class, 'import_excel']);
// Route::get('/export_excel', [ImportController::class, 'export_excel']);
// Route::get('/import/{nim}/edit_ajax', [ImportController::class, 'edit_ajax']);
// Route::put('/import/{nim}/update_ajax', [ImportController::class, 'update_ajax']);
// Route::delete('/import/{nim}/delete_ajax', [ImportController::class, 'delete_ajax']);

// Route::resource('alumni', AlumniController::class);// Routes untuk tabel Alumni
Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
Route::get('/alumni/create', [AlumniController::class, 'create'])->name('alumni.create');
Route::post('/alumni', [AlumniController::class, 'store'])->name('alumni.store');
Route::get('/alumni/{nim}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
Route::put('/alumni/{nim}', [AlumniController::class, 'update'])->name('alumni.update');
Route::delete('/alumni/{nim}', [AlumniController::class, 'destroy'])->name('alumni.destroy');
Route::post('alumni/import', [AlumniController::class, 'import'])->name('alumni.import');
Route::get('alumni/template/download', [AlumniController::class, 'downloadTemplate'])->name('alumni.template');
Route::get('alumni/export', [AlumniController::class, 'export'])->name('alumni.export');
Route::get('alumni/prodi-by-jurusan', [AlumniController::class, 'getProdiByJurusan'])->name('alumni.prodi-by-jurusan');

Route::resource('instansi', InstansiController::class);

Route::resource('penggunaLulusan', PenggunaLulusanController::class);
Route::get('penggunaLulusan/{id}/alumni', [PenggunaLulusanController::class, 'showAlumni'])->name('penggunaLulusan.showAlumni');
Route::get('/pengguna-lulusan/export', [PenggunaLulusanController::class, 'export'])->name('penggunaLulusan.export');
Route::get('/pengguna-lulusan/export-sudah-survey', [PenggunaLulusanController::class, 'exportSudahIsiSurvey'])->name('penggunaLulusan.exportSudahIsiSurvey');

// Routes untuk tabel Admin
Route::middleware(['auth', 'super_admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin', [AdminController::class, 'store'])->name('admin.store');
    Route::get('/admin/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});

Route::prefix('tracerstudy')->group(function () {
    //landing page
    Route::get('/', [TCFormController::class, 'index'])->name("landingpage");
    //opsi form
    Route::get('/formopsi', [TCFormController::class, 'opsi'])->name("form.opsi");

    // form alumni 
    Route::get('/formopsi/formalumni', [TCFormController::class, 'kusionerA'])->name("form.alumni");
    Route::get('/formulir', [TCFormController::class, 'nim'])->name('formulir.create'); // menampilkan nim importan 
    Route::get('/get-alumni-data/{keyword}', [TCFormController::class, 'getAlumniData']); // mengisi data otomatis 
    Route::post('/formulir/{nim}', [TCFormController::class, 'create_form'])->name('formulir.store'); // menyimpan data form tracer study 

    //otp validation
    Route::get('/formopsi/otpcheck', [TCFormController::class, 'otpcheck'])->name('otp.check');
    Route::post('/formopsi/otpvalidation', [TCFormController::class, 'otpvalidation'])->name('otp.validation');

    //form penggunalulusan 
    Route::get('/formopsi/formpenggunalulusan', [TCFormController::class, 'surveiPL'])->name("form.penggunalulusan");
    Route::get('/get-pl-data/{nama}', [TCFormController::class, 'getPL']); // mengisi data otomatis 
    Route::post('/tracerstudy/store', [TCFormController::class, 'create_PL'])->name('survey.store');
});
