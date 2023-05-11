<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OTPHandleController;
use App\Http\Controllers\DocumentProcessController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ForgotPasswordController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/home', function () {
    return view('home');
})->name('/home');

Route::get('/booking', function () {
    return view('components.booking');
});

Route::get('/payment', function () {
    return view('components.payment');
});

Route::get('/category', function () {
    return view('components.category');
});

Route::get('/rawMaterial', function () {
    return view('components.raw-material');
});

Route::get('/totalPayment', function () {
    return view('components.total-payment');
});


// Route::get('/login', function () {
//     return view('auth.login');
// });
Route::get('/signup', function () {
    return view('auth.signup');
})->name('signUp');

Route::get('signup', function () {
    return view('auth.signUp');
})->name('signUp');
Route::get('login', function () {
    return view('auth.login');
})->name('login');
Route::get('userDocument', function () {
    return view('components.user-document');
})->name('userDocument');

// Route::get('userDocument/{id}', 'DocumentProcessController@index');

Route::get('updatedDocument', function () {
    return view('components.updated-documents');
})->name('updatedDocument');


Route::get('documentProcess', function () {
    return view('components.document-process');
})->name('documentProcess');


// Route::get("/redirect",function()
// {
//     if (!Auth::check()) {
//         return redirect()->route('register');
//     }
//     return redirect("/");
// });


Route::get("/resetPassword",function()
{
  return view('auth.resetPassword');
});


Route::post('ajaxRequest', [OTPHandleController::class, 'ajaxRequestPost'])->name('ajaxRequest.post');
Route::post('store', [OTPHandleController::class, 'store'])->name('store');
Route::post('ajaxRequestPostDocument', [DocumentProcessController::class, 'ajaxRequestPostDocument'])->name('ajaxRequest.postdocument');
Route::post('fileUploadPost', 'App\Http\Controllers\FileUploadController@fileUploadPost')->name('file.upload.post');


Route::get('signUpSubmit',function()
{
  return view('auth.signUpSubmit');
})->name('signUpSubmit');

Route::post('post-login', [LoginController::class, 'postLogin'])->name('login.post'); 


Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post'); 
// Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
// Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');


//new roue add

Route::post('documentOutput',[DocumentProcessController::class,'documentOutput'])->name('documentOutput');

Route::get("/forgot-password",function()
{
 return view('auth.forgot-password');
})->name('forgot-password');

// Route::get('post-logout', [LoginController::class, 'postLogout'])->name('postLogout'); 
Route::get('/logout', function () {
    Auth::logout();
    Session::flush();
    return redirect('/login');
})->name('logout');


Route::get("/redirect",function(){

    //dd(Auth::user()->state);
    if(Auth::check())
{
   // dd(Auth::user()->state);
if(Auth::user()->state==1)
{
  redirect()->route('signUpSubmit');
 
}
else if(Auth::user()->state==2)
{
  //session(['state' => '2']);  
  //dd(Session::get('state'));
  
  redirect()->route('userDocument');
  
}
else if(Auth::user()->state==3  )
{
    redirect()->route('documentProcess');
}
else 
{
    redirect()->route('home');
}
}

return redirect()->route('/home');

});


Route::get("/test",function(){
  return view("components.test");
});

//new route add