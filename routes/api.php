<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmailController;
use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\VolunteerController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\TaiKhoanController;
use App\Http\Controllers\Api\CongDongController;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\SearchController;

 

Route::group(['prefix' => 'email'], function () {
    Route::get('/{userId}', [EmailController::class, 'getEmailsUser']);
    // Route::get('/send', [EmailController::class, 'getEmailsSend'])->name('admin.pages.email.send');
    Route::get('/read/{userId}/{id}', [EmailController::class, 'getEmailById']);
    // Route::get('/compose', [EmailController::class, 'compose'])->name('admin.pages.email.compose');
    Route::post('/create', [EmailController::class, 'createEmail']);
    Route::post('/readed/{id}', [EmailController::class, 'updateEmailStatus']);
});

Route::get('listCampaignIng/{province}', [CampaignController::class, 'getCampaignsIng']);
Route::get('listCampaignWill/{province}', [CampaignController::class, 'getCampaignsWill']);
Route::get('listCampaignEd/{province}', [CampaignController::class, 'getCampaignsEd']);
Route::get('getCampaign/{id}', [CampaignController::class, 'getCampaignDetail']);
Route::get('getCampaignStatistics/{province}', [CampaignController::class, 'getCampaignStatistics']);

Route::post('managerCampaign/{userId}', [CampaignController::class, 'managerCampaign']);
Route::post('getStatusVolunteer', [CampaignController::class, 'getStatusVolunteer']);

Route::get('getJoined/{campaignId}', [VolunteerController::class, 'getJoined']);
Route::get('getPending/{campaignId}', [VolunteerController::class, 'getPending']);






Route::post('/addPost', [UserController::class, 'addPost']);
Route::post('/getPosts', [UserController::class, 'getPosts']);
Route::post('user_information', [UserController::class, 'getUserInformation']);
Route::post('/getInformationNavBar', [UserController::class, 'getInformationNavBar']);
Route::post('/updateFollowStatus', [FollowController::class, 'updateFollowStatus']);
Route::post('/updateUserInfo', [UserController::class, 'updateUserInfo']);
Route::post('/checkLikeStatus', [PostController::class, 'checkLikeStatus']);
Route::post('/getComment', [PostController::class, 'getComment']);
Route::post('/toogleLike', [PostController::class, 'toogleLike']);
Route::post('/deletePost', [PostController::class, 'deletePost']);
Route::post('/getUnFollowedUsers', [FollowController::class, 'getUnFollowedUsers']);
Route::post('/updateFollower', [FollowController::class, 'updateFollower']);
Route::post('/getComments', [PostController::class, 'getComments']);
Route::post('/addComment', [PostController::class, 'addComment']);

Route::post('/addPostGroup', [CongDongController::class, 'addPostGroup']);
Route::post('/getSocialPosts', [CongDongController::class, 'getSocialPosts']);
Route::post('/getCampaignUser', [CongDongController::class, 'getCampaignUser']);
Route::post('/getTopGroup', [CongDongController::class, 'getTopGroup']);
Route::get('/getSocialOutstanding', [CongDongController::class, 'getSocialOutstanding']);
Route::post('/getCampaignPosts', [CongDongController::class, 'getCampaignPosts']);
Route::post('/getInformationCampaign', [CongDongController::class, 'getInformationCampaign']);
Route::get('/checkdatabase', [CongDongController::class, 'checkDatabase']);

Route::post('/login', [TaiKhoanController::class, 'login']);
Route::post('/register', [TaiKhoanController::class, 'register']);
Route::post('/getOnlineUser', [ChatController::class, 'getOnlineUser']);
Route::post('/getListSearch', [SearchController::class, 'getListSearch']);
Route::get('/hashpassword', function () {
    return Hash::make('dinhdeptrai.1610');
});


Route::post('/getUsersChat', [ChatController::class, 'getUsersChat']);
Route::post('/getChats', [ChatController::class, 'getChats']);
Route::post('/sendMessage', [ChatController::class, 'sendMessage']);
Route::post('/getGroupUser', [ChatController::class, 'getGroupUser']);
Route::post('/getGroupChats', [ChatController::class, 'getGroupChats']);
Route::post('/sendMessageGroup', [ChatController::class, 'sendMessageGroup']);

