<?php


use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use RobbieP\CloudConvertLaravel\Facades\CloudConvert;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::pattern('id', '[0-9]+');

Route::get('/', function () {
    \Auth::loginUsingId(2);
    // \Auth::logout();
      return view('welcome');
});


Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'role:admin|member'], function() {
    get('/', 'DashBoardsController@index');
});


Route::group(['namespace' => 'Frontend'], function() {
    get('/template/{id}', ['uses' => 'TemplatesController@detail']);
    get('/template/convert', ['uses' => 'TemplatesController@convert']);
});

Route::group(['prefix' => 'api', 'namespace' => 'API'], function() {
    /**
     * Authenticate Route
     */
    get('auth/login', ['as' => 'auth.login', 'uses' => 'AuthenticateController@getLogin']);
    get('auth/register', ['as' => 'auth.register', 'uses' => 'AuthenticateController@getRegister']);

    post('auth/register', ['as' => 'auth.register', 'uses' => 'AuthenticateController@postRegister']);
    post('auth/login', ['as' => 'auth.login', 'uses' => 'AuthenticateController@postLogin']);
    post('auth/reset-password', ['uses' => 'AuthenticateController@postResetPassword']);
    Route::any('auth/login-with-linkedin', ['as' => 'auth.linkedin', 'uses' => 'AuthenticateController@postLoginWithLinkedin']);

    /**
     * User Route
     */
    get('/user/profile', 'UsersController@getProfile');

    post('/user/{id}/profile', ['uses' => 'UsersController@postProfile']);
    post('/user/upload', ['uses' => 'UsersController@uploadImage']);
    get('/user/status', ['uses' => 'UsersController@getStatus']);

    /**
     * Template Route
     */
    get('template', ['uses' => 'TemplatesController@getTemplates']);
    get('template/edit-content/{id}/{section}', ['uses' => 'TemplatesController@showEditContent']);
    get('template/market', ['uses' => 'TemplatesController@getAllTemplatesFromMarket']);
    get('template/{id}', ['uses' => 'TemplatesController@getDetailTemplate']);
    get('template/full/{id}', 'TemplatesController@getFull');
    post('template', ['uses' => 'TemplatesController@postTemplates']);
    post('template/edit/{id}', ['as' => 'frontend.template.post.edit', 'uses' => 'TemplatesController@edit']);
    /**
     * Market Route
     */
    get('market/all-template', ['uses' => 'MarketPlaceController@getAllTemplateMarket']);
    get('market/detail-template/{id}/{name}', ['uses' => 'MarketPlaceController@getDetailTemplateMarket']);
});
get('/abcd', 'API\TemplatesController@convertHtmlToImage');
get('test', function() {
  //  $pdf = App::make('dompdf.wrapper');
     /*\PDF::loadFile(public_path('test1.html'))
        ->setPaper('a3')
        ->setOrientation('landscape')
        ->save(public_path('test5.pdf'));*/
    /*$file = File::get(public_path('CURRICULUM VITAE_TanHt.docx'));

    $response = response()->make($file, 200);
    $response->header('Content-type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    $response->header('Content-Disposition', ' attachment;filename="' . public_path('CURRICULUM VITAE_TanHt.docx') . '"');
     return $response;*/
     var_dump((new \App\Models\User)->newQuery());
    // return view()->make('frontend.testword');
    /*$templateProcessor = new TemplateProcessor(public_path('CURRICULUM VITAE_TanHt.docx'));
    echo "<prev>";

    dd($templateProcessor->cloneBlock('Tan'));
    echo "/<prev>";*/
});
