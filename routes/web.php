<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CurriculumController;
use App\Http\Controllers\Admin\PromptTemplateController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ConversationController;
use App\Http\Controllers\Admin\UsageController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\CommunityModerationController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WebChatController;
use App\Http\Controllers\CommunityController;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    $communityPosts = \App\Models\CommunityPost::where('is_approved', true)
        ->whereHas('thread', function($query) {
            $query->where('slug', 'general-advice');
        })
        ->with('user')
        ->latest()
        ->take(6)
        ->get();
    return view('welcome', compact('communityPosts'));
})->name('welcome');

// Web Chatbot Routes
Route::get('/chat', [WebChatController::class, 'index'])->name('chat.index');
Route::post('/chat/login', [WebChatController::class, 'login'])->name('chat.login');
Route::get('/chat/messages', [WebChatController::class, 'getMessages'])->name('chat.messages');
Route::post('/chat/send', [WebChatController::class, 'sendMessage'])->name('chat.send');
Route::post('/chat/logout', [WebChatController::class, 'logout'])->name('chat.logout');

// Legal Pages
Route::view('/terms-and-conditions', 'legal.terms')->name('legal.terms');
Route::view('/privacy-policy', 'legal.privacy')->name('legal.privacy');

// Community Routes
Route::prefix('community')->name('community.')->group(function () {
    Route::get('/', [CommunityController::class, 'index'])->name('index');
    Route::post('/threads', [CommunityController::class, 'storeThread'])->name('threads.store');
    Route::get('/threads/{thread:slug}', [CommunityController::class, 'show'])->name('show');
    Route::post('/threads/{thread:slug}/posts', [CommunityController::class, 'storePost'])->name('posts.store');
    Route::post('/threads/{thread:slug}/join', [CommunityController::class, 'join'])->name('join');
    Route::post('/threads/{thread:slug}/leave', [CommunityController::class, 'leave'])->name('leave');
});

// Landing page contact / subscribe / partner forms
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

Route::middleware('auth.basic')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}/ban', [UserController::class, 'toggleBan'])->name('users.ban');
    Route::patch('/users/{user}/reset-strikes', [UserController::class, 'resetStrikes'])->name('users.reset');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Conversations / message log
    Route::get('/conversations', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/export', [ConversationController::class, 'export'])->name('conversations.export');
    Route::get('/conversations/{message}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::delete('/conversations/{message}', [ConversationController::class, 'destroy'])->name('conversations.destroy');

    // Usage & insights
    Route::get('/usage', [UsageController::class, 'index'])->name('usage.index');

    // Curriculum (legal database)
    Route::get('/curriculum', [CurriculumController::class, 'index'])->name('curriculum.index');
    Route::get('/curriculum/export', [CurriculumController::class, 'export'])->name('curriculum.export');
    Route::post('/curriculum', [CurriculumController::class, 'store'])->name('curriculum.store');
    Route::post('/curriculum/import', [CurriculumController::class, 'import'])->name('curriculum.import');
    Route::post('/curriculum/bulk', [CurriculumController::class, 'bulk'])->name('curriculum.bulk');
    Route::get('/curriculum/{curriculum}/edit', [CurriculumController::class, 'edit'])->name('curriculum.edit');
    Route::put('/curriculum/{curriculum}', [CurriculumController::class, 'update'])->name('curriculum.update');
    Route::patch('/curriculum/{curriculum}/toggle', [CurriculumController::class, 'toggle'])->name('curriculum.toggle');
    Route::delete('/curriculum/{curriculum}', [CurriculumController::class, 'destroy'])->name('curriculum.destroy');

    // Legal aid providers
    Route::get('/providers', [ProviderController::class, 'index'])->name('providers.index');
    Route::get('/providers/export', [ProviderController::class, 'export'])->name('providers.export');
    Route::post('/providers', [ProviderController::class, 'store'])->name('providers.store');
    Route::post('/providers/import', [ProviderController::class, 'import'])->name('providers.import');
    Route::post('/providers/sync', [ProviderController::class, 'sync'])->name('providers.sync');
    Route::get('/providers/{provider}/edit', [ProviderController::class, 'edit'])->name('providers.edit');
    Route::put('/providers/{provider}', [ProviderController::class, 'update'])->name('providers.update');
    Route::delete('/providers/{provider}', [ProviderController::class, 'destroy'])->name('providers.destroy');

    // Prompt Templates
    Route::get('/templates', [PromptTemplateController::class, 'index'])->name('templates.index');
    Route::post('/templates', [PromptTemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/preview', [PromptTemplateController::class, 'preview'])->name('templates.preview');
    Route::post('/templates/preview', [PromptTemplateController::class, 'runPreview'])->name('templates.preview.run');
    Route::get('/templates/{template}/edit', [PromptTemplateController::class, 'edit'])->name('templates.edit');
    Route::put('/templates/{template}', [PromptTemplateController::class, 'update'])->name('templates.update');
    Route::delete('/templates/{template}', [PromptTemplateController::class, 'destroy'])->name('templates.destroy');
    Route::patch('/templates/{template}/toggle', [PromptTemplateController::class, 'toggle'])->name('templates.toggle');

    // Community moderation
    Route::get('/community', [CommunityModerationController::class, 'index'])->name('community.index');
    Route::patch('/community/posts/{post}/approve', [CommunityModerationController::class, 'toggleApproval'])->name('community.posts.approve');
    Route::patch('/community/posts/{post}/pin', [CommunityModerationController::class, 'togglePin'])->name('community.posts.pin');
    Route::delete('/community/posts/{post}', [CommunityModerationController::class, 'destroyPost'])->name('community.posts.destroy');
    Route::delete('/community/threads/{thread}', [CommunityModerationController::class, 'destroyThread'])->name('community.threads.destroy');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // System health, tools and audit trail
    Route::get('/system', [SystemController::class, 'index'])->name('system.index');
    Route::get('/system/activity', [SystemController::class, 'activity'])->name('system.activity');
    Route::post('/system/tools/{tool}', [SystemController::class, 'runTool'])->name('system.tool');
});
