<?php

use App\Jobs\SendRegisterEmail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['namespace' => "\App\Http\Controllers\Api\V1"], function () {
    Route::get("/", "Controller@home");

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get("protected_test", "Controller@protected_test");
        Route::post("verify_token", "AuthController@verify_token")->middleware(['ability:voter,organizer,participant']);

        // Auth
        Route::post("logout", "AuthController@logout");
        Route::post("verify_email", "AuthController@verify_email")->middleware(['ability:verify-email']);
        Route::post("resend_verification_email", "AuthController@resend_verification_email")->middleware(['ability:verify-email']);

        Route::post("resend_forget_password", "AuthController@resend_forget_password")->middleware(['ability:forget-password']);
        Route::post("verify_forget_password", "AuthController@verify_forget_password")->middleware(['ability:forget-password']);
        Route::post("reset_password", "AuthController@reset_password")->middleware(['ability:reset-password']);


        // Notifications
        Route::get("notifications", "AuthController@notifications");
        Route::post("set_notification_token", "AuthController@set_notification_token");

        // Categories
        Route::post("categories", "CategoryController@request");
        Route::get("categories", "CategoryController@all");
        Route::get("dashboard_categories", "CategoryController@dashboard_categories");
        Route::get("user_categories", "CategoryController@user_all");

        // Competitions
        Route::get("competitions", "CompetitionController@all");
        Route::get("competitions/participation", "CompetitionController@participation");
        Route::get("competitions/explore", "CompetitionController@explore");
        Route::get("competitions/{category}/category", "CompetitionController@category_all");
        Route::post("competitions", "CompetitionController@store");
        Route::get("competitions/single/{competition}", "CompetitionController@get_single");
        Route::post("competitions/calculate_financials", "CompetitionController@calculate_financials");
        Route::post("competitions/{competition}/publish", "CompetitionController@publish");
        Route::post("competitions/{competition}/participate", "CompetitionController@participate");
        Route::put("competitions/{competition}", "CompetitionController@update");
        Route::delete("competitions/{competition}", "CompetitionController@delete");
        Route::post("competitions/{competition}/verify_dates", "CompetitionController@verify_dates");

        // Posts
        Route::get("posts", "PostController@personal");
        Route::get("posts/voted", "PostController@voted");
        Route::get("posts/winner", "PostController@winner");
        Route::get("posts/{competition}", "PostController@all");
        Route::get("posts/single/{post}", "PostController@get_single");
        // Route::post("posts/{competition}", "PostController@store")->middleware("competition_participant");
        Route::post("posts_text/{competition}/draft", "PostController@store_text")->middleware("competition_participant");
        Route::post("posts_image/{competition}/draft/{post?}", "PostController@store_image")->middleware("competition_participant");
        Route::post("posts_video/{competition}/draft/{post?}", "PostController@store_video")->middleware("competition_participant");
        // Post Comments
        Route::get("posts/{post}/comments", "PostController@comments_all");
        Route::get("posts/{post}/comments/{post_comment}", "PostController@comment_replies_all");
        Route::post("posts/{post}/comments", "PostController@comments_store");
        Route::post("posts/{post}/comments/{post_comment}", "PostController@comment_replies");
        Route::patch("posts/{post}/comments/{post_comment}", "PostController@comment_update");

        Route::put("posts/{competition}/update/{post}", "PostController@update")->middleware("competition_participant");
        Route::delete("posts/{competition}/delete_draft/{post}", "PostController@delete")->middleware("competition_participant");
        Route::delete("posts/{competition}/delete_media/{post_media}", "PostController@delete_media")->middleware("competition_participant");
        Route::patch("posts/{competition}/publish/{post}", "PostController@publish")->middleware("competition_participant");

        Route::put("posts/{competition}/approve/{post}", "PostController@approve")->middleware("competition_organizer");
        Route::post("posts/{competition}/object/{post}", "PostController@object")->middleware("competition_organizer");
        Route::post("posts/{competition}/toggle_show/{post}", "PostController@toggle_show")->middleware("competition_organizer");

        Route::post("posts/{competition}/vote/{post}", "PostController@vote")->middleware("post_voter");
        Route::post("posts/{competition}/report/{post}", "PostController@report")->middleware("post_voter");

        // Organizer
        Route::get("organizer/reports", "OrganizerController@reports")->middleware(['ability:organizer']);
        Route::post("organizer/clear_report_toggle/{post_report}", "OrganizerController@clear_report_toggle")->middleware(['ability:organizer']);

        // Payments
        Route::get("payments/all_methods", "PaymentController@all");
        Route::post("payments/competition/card", "PaymentController@card_competition");
        Route::post("payments/competition/card_participation", "PaymentController@card_participation");
        Route::post("payments/competition/easypaisa", "PaymentController@easy_paisa_competition");
        Route::post("payments/competition/jazzcash", "PaymentController@jazz_cash_competition");
    });
    Route::post("test_login", "Controller@test_login");
    Route::get("test", "Controller@test");

    // Auth
    Route::post("register", "AuthController@register");
    Route::post("email_login", "AuthController@email_login");
    Route::post("google_login", "AuthController@google_login");
    Route::post("forget_password", "AuthController@forget_password");

    Route::get("email_template", function () {
        return new \App\Mail\Competition\CompetitionPublished(\App\Models\Competition::find(1));
    });
});

Route::post("aws_test_upload", "\App\Http\Controllers\Controller@aws_test_upload");
Route::post("aws_test_delete", "\App\Http\Controllers\Controller@aws_test_delete");

Route::get("/text_nsfw_text", function () {


    $response = Http::post(env("NSFW_API_URL") . 'filter_text', ["text" => "Fuck you!"]);

    dd($response->json());

});
Route::get("/text_nsfw_image", function () {

    $response = Http::get("https://uniquo-alpha.s3.us-east-1.amazonaws.com/images/posts/667767ac623f0.jpg");

    if ($response->successful()) {
        $imageContents = $response->body();
        $imageName = 'temp_' . uniqid() . '.jpg';
        $response = Http::attach(
            'image',
            $imageContents,
            $imageName

        )->post(env("NSFW_API_URL") . 'filter_image');

        dd($response->json());
    }
});

Route::get('/queue_job', function () {
    try {
        for ($i = 0; $i < 10; $i++) {
            SendRegisterEmail::dispatch();
        }
    } catch (\Throwable $th) {
        echo $th->getMessage();
    }
});
Route::get('/queue_db', function () {
    try {
        echo "Creating Users\n";

        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $username = $faker->userName . rand(11111, 99999);
            $participant = \App\Models\User::create([
                'username' => $username,
                'email' => $username . "@gmail.com",
                'full_name' => 'Participant User',
                'email_verified_at' => date_format($faker->dateTimeBetween("-5 years"), 'Y-m-d H:i:s'),
                'auth_provider' => 'email',
                'password' => Hash::make("secret_pass"),
                'avatar' => 'https://coursebari.com/wp-content/uploads/2021/06/899048ab0cc455154006fdb9676964b3.jpg',
            ]);
        }
        echo "Users Created!\n";
    } catch (\Throwable $th) {
        echo "Send Email: " . $th->getMessage();
    }
});
