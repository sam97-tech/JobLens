<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\JobPostController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApplicantProfileController;
use App\Http\Controllers\CompanyJobPostController;
use App\Http\Controllers\PublicJobsController;
use App\Http\Controllers\ApplicantApplicationController;






Route::get('/lang/{locale}', function (Illuminate\Http\Request $request, string $locale) {
    if (in_array($locale, ['en', 'ar'])) {
        $request->session()->put('locale', $locale);
    }

    return redirect()->back();
})->name('lang.switch');

Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
});

Route::get('/jobs', [PublicJobsController::class, 'index'])->name('jobs.index');

Route::get('/contact', function () {
    return view('contact');
});
Route::resource('users',UserController::class)->except(['create','edit']);
Route::resource('companies',CompanyController::class)->except(['create','edit']);
Route::resource('job-posts',JobPostController::class)->except(['create','edit']);
Route::resource('resumes',ResumeController::class)->except(['create','edit']);
Route::resource('answers',AnswerController::class)->except(['create','edit']);
Route::resource('skills',SkillController::class)->except(['create','edit']);
Route::resource('interviews',InterviewController::class)->except(['create','edit']);
Route::middleware('guest')->group(function () {
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')
        ->get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('dashboard.admin');

    Route::middleware('role:company')
        ->get('/company/dashboard', [DashboardController::class, 'company'])
        ->name('dashboard.company');

    Route::middleware('role:company')->group(function () {
        Route::get('/company/job-posts', [CompanyJobPostController::class, 'index'])->name('company.jobposts.index');
        Route::get('/company/job-posts/create', [CompanyJobPostController::class, 'create'])->name('company.jobposts.create');
        Route::post('/company/job-posts', [CompanyJobPostController::class, 'store'])->name('company.jobposts.store');
        Route::get('/company/job-posts/{jobPost}/edit', [CompanyJobPostController::class, 'edit'])->name('company.jobposts.edit');
        Route::put('/company/job-posts/{jobPost}', [CompanyJobPostController::class, 'update'])->name('company.jobposts.update');
        Route::delete('/company/job-posts/{jobPost}', [CompanyJobPostController::class, 'destroy'])->name('company.jobposts.destroy');
        Route::get('/company/job-posts/{jobPost}/applicants', [CompanyJobPostController::class, 'applicants'])->name('company.jobposts.applicants');
        Route::put('/company/applications/{application}/status', [CompanyJobPostController::class, 'updateApplicationStatus'])->name('company.applications.updateStatus');
    });

    Route::middleware('role:applicant')
        ->get('/applicant/dashboard', [DashboardController::class, 'applicant'])
        ->name('dashboard.applicant');

    Route::middleware('role:applicant')->group(function () {
        Route::get('/applicant/profile', [ApplicantProfileController::class, 'edit'])->name('applicant.profile.edit');
        Route::put('/applicant/profile', [ApplicantProfileController::class, 'update'])->name('applicant.profile.update');
        Route::post('/applicant/resume', [ApplicantProfileController::class, 'uploadResume'])->name('applicant.resume.upload');
        Route::put('/applicant/skills', [ApplicantProfileController::class, 'updateSkills'])->name('applicant.skills.update');

        Route::get('/applicant/applications', [ApplicantApplicationController::class, 'index'])->name('applicant.applications.index');
        Route::post('/jobs/{jobPost}/apply', [ApplicantApplicationController::class, 'store'])->name('jobs.apply');
    });
});





