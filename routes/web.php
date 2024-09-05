<?php

use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;
// use UserController
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\FunController;



Route::get('/login', [AdminController::class, 'login'])->name('admin.auth.login');
Route::post('/login-post', [AdminController::class, 'loginPost'])->name('admin.auth.login-post');
Route::get('/register', [AdminController::class, 'register'])->name('admin.auth.register');
Route::post('register-post', [AdminController::class, 'registerPost'])->name('admin.auth.register-post');


Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth', 'admin-middleware']], function () {
// Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/user', [UserController::class, 'userList'])->name('admin.pages.user.index');

    Route::get('/fun', [FunController::class, 'funManager'])->name('admin.pages.fun.index');

    Route::group(['prefix' => 'campaign'], function () {
        Route::get('/list', [CampaignController::class, 'campaignList'])->name('admin.pages.campaign.list');
        Route::get('/pending', [CampaignController::class, 'campaignPendingList'])->name('admin.pages.campaign.pending');
        Route::post('/update-status', [CampaignController::class, 'updateStatus'])->name('admin.pages.campaign.updateStatus');
        Route::get('/detail/{campaignId}', [CampaignController::class, 'getCampaignDetail'])->name('admin.pages.campaign.detail');
        Route::get('/searchByProvince', [CampaignController::class, 'searchByProvince'])->name('admin.pages.campaign.list.searchByProvince');
        Route::get('/searchPendingByProvince', [CampaignController::class, 'searchByProvincePending'])->name('admin.pages.campaign.pending.searchByProvincePending');
        Route::get('/searchById', [CampaignController::class, 'searchById'])->name('admin.pages.campaign.list.searchById');
        Route::get('/searchPedingById', [CampaignController::class, 'searchPendingById'])->name('admin.pages.campaign.pending.searchById');
    });

    Route::group(['prefix' => 'community'], function () {
        Route::get('/list', [PostController::class, 'getPosts'])->name('admin.pages.community.list');
        Route::get('/pending', [PostController::class, 'getPendingPosts'])->name('admin.pages.community.pending');
        Route::delete('/delete/{post}', [PostController::class, 'deletePost'])->name('admin.pages.community.delete');
        Route::post('/duyet/{post}', [PostController::class, 'approvePost'])->name('admin.pages.community.duyet');
    });

    Route::group(['prefix' => 'email'], function () {
        Route::get('/', [EmailController::class, 'getEmailsAdmin'])->name('admin.pages.email.index');
        Route::get('/send', [EmailController::class, 'getEmailsSend'])->name('admin.pages.email.send');
        Route::get('/read/{id}', [EmailController::class, 'getEmailById'])->name('admin.pages.email.read');
        Route::post('/readed/{id}', [EmailController::class, 'updateEmailStatus'])->name('admin.pages.email.readed');
        Route::get('/compose', [EmailController::class, 'compose'])->name('admin.pages.email.compose');
        Route::post('/email/create', [EmailController::class, 'createEmail'])->name('admin.pages.email.create');
    });
});
