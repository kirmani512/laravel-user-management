<?php



use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\portal\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use OpenSpout\Common\Entity\Row;

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware(['auth'])->group(function () {
    Route::get('home', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('users')->group(function () {
        Route::post('/user-details/update', [UserController::class, 'updateProfile'])->name('user.update.profile')->middleware('permission:update_own_details');
        Route::get('/detail', [UserController::class, 'viewDetail'])->name('user.view')->middleware('permission:view_own_details');
        Route::get('/user-profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit')->middleware('permission:view_own_details');
        Route::get('/', [UserController::class, 'index'])->name('user.index')->middleware('permission:manage_users');
        Route::get('/list', [UserController::class, 'list'])->name('user.list')->middleware('permission:manage_users');
        Route::get('/create', [UserController::class, 'create'])->name('user.create')->middleware('permission:create_user');
        Route::Post('/add', [UserController::class, 'store'])->name('user.store')->middleware('permission:manage_users');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user.edit')->middleware('permission:manage_users');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('user.update')->middleware('permission:manage_users');
        Route::get('/{id}/delete', [UserController::class, 'delete'])->name('user.delete')->middleware('permission:manage_users');
        Route::get('/area-manager',[UserController::class,'areaManagerIndex'])->name('areamanager.index')->middleware('permission:view_users_in_district');
        Route::get('/employee',[UserController::class,'employeeIndex'])->name('employee.index')->middleware('permission:view_own_details');
        Route::get('/employee/details',[UserController::class,'employeeDetails'])->name('employeeDetails.index')->middleware('permission:view_own_details');
        Route::get('/area-manager/details',[UserController::class,'areamanagerDetails'])->name('areamanagerDetails.index')->middleware('permission:view_users_in_district');


    });
    Route::prefix('districts')->group(function () {
        Route::get('/', [DistrictController::class, 'index'])->name('district.index')->middleware('permission:manage_districts');
        Route::get('/list', [DistrictController::class, 'list'])->name('district.list')->middleware('permission:manage_districts');
        Route::get('/create', [DistrictController::class, 'create'])->name('district.create')->middleware('permission:manage_districts');
        Route::Post('/add', [DistrictController::class, 'store'])->name('district.store')->middleware('permission:manage_districts');
        Route::get('/edit/{id}', [DistrictController::class, 'edit'])->name('district.edit')->middleware('permission:manage_districts');
        Route::post('/update/{id}', [DistrictController::class, 'update'])->name('district.update')->middleware('permission:manage_districts');
        Route::get('/{id}/delete', [DistrictController::class, 'delete'])->name('district.delete')->middleware('permission:manage_districts');
    });
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('role.index')->middleware('permission:manage_roles');
        Route::get('/list', [RoleController::class, 'list'])->name('role.list')->middleware('permission:manage_roles');
        Route::get('/create', [RoleController::class, 'create'])->name('role.create')->middleware('permission:manage_roles');
        Route::Post('/add', [RoleController::class, 'store'])->name('role.store')->middleware('permission:manage_roles');
        Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('role.edit')->middleware('permission:manage_roles');
        Route::post('/update/{id}', [RoleController::class, 'update'])->name('role.update')->middleware('permission:manage_roles');
        Route::get('/{id}/delete', [RoleController::class, 'delete'])->name('role.delete')->middleware('permission:manage_roles');
    });
    Route::prefix('permissions')->group(function () {

        Route::get('/', [PermissionController::class, 'index'])->name('permission.index')->middleware('permission:manage_permissions');
        Route::get('/list', [PermissionController::class, 'list'])->name('permission.list')->middleware('permission:manage_permissions');
        Route::get('/create', [PermissionController::class, 'create'])->name('permission.create')->middleware('permission:manage_permissions');
        Route::Post('/add', [PermissionController::class, 'store'])->name('permission.store')->middleware('permission:manage_permissions');
        Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('permission.edit')->middleware('permission:manage_permissions');
        Route::post('/update/{id}', [PermissionController::class, 'update'])->name('permission.update')->middleware('permission:manage_permissions');
        Route::get('/{id}/delete', [PermissionController::class, 'delete'])->name('permission.delete')->middleware('permission:manage_permissions');
    });
});




Auth::routes();


// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
