<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

URL::forceSchema('https');


Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'before' => 'LaravelLocalizationRedirectFilter',
        'middleware' => [ 'web', 'localeSessionRedirect', 'localizationRedirect' ],
        'namespace' => 'Index',
    ],
    function()
    {
        Route::get('/', 'IndexController@indexDemo');
        Route::get('/index', 'IndexController@indexDemo');
        Route::get('/404', 'IndexController@error404');
        Route::get('/rss', 'IndexController@rss');
        Route::get('/about', 'IndexController@aboutMenu');
        Route::get('/about-old', 'IndexController@about');
        Route::get('/about-us', 'IndexController@aboutUs');
        Route::get('/history', 'IndexController@history');
        Route::get('/blogs', 'BlogController@index')->name('blogs');
        Route::get('/get-post', 'BlogController@getPost')->name('post');
        Route::get('/bloggers/{email}', 'BlogController@blogger')->name('blogger');
        Route::get('/blogs/{url}', 'BlogController@single');
        Route::get('/blogs_get_list', 'BlogController@getList');
        Route::get('/news', 'IndexController@news');
        Route::get('/obratnaya-svyaz', 'IndexController@obratnayaSvyaz');
        Route::get('/vakansii', 'IndexController@vakansii');
        Route::get('/ads', 'IndexController@ads');
        Route::get('/archive', 'IndexController@videoArchive');
        Route::get('/archive/{programm_url_name}/{video_archive_url_name}', 'IndexController@videoArchivePage');
        Route::post('/news', 'IndexController@news');
        Route::get('/news-archive', 'IndexController@newsArchive');
        Route::get('/news-archive/news/{news_url_name}', 'IndexController@newsArchiveView');
        Route::get('/broadcasting', 'IndexController@broadCasting');
        Route::get('/news/news/{news_url_name}', 'IndexController@newsView');
        // Route::get('/news/news2/{news_url_name}', 'IndexController@newsView2');
        Route::get('/programma-peredach/', 'IndexController@tvProgramm');
        Route::post('index/set-news-view', 'IndexController@setNewsView')->name('set_news_view');
        Route::get('index/send-request', 'IndexController@sendRequest');
        Route::get('index/video-archive-programm-list-block/{date_start}/{date_end}/{language_name}/{programm_id?}', 'IndexController@videoArchiveProgrammListBlock');
        Route::get('index/video-archive-programm-list-block-mobile/{date_start}/{date_end}/{language_name}', 'IndexController@videoArchiveProgrammListBlockMobile');
        Route::get('index/video-archive-programm-item-list-block/{date_start}/{date_end}/{programm_id}/{language_name}', 'IndexController@videoArchiveProgrammItemListBlock');
        Route::get('index/all-video-archive-programm-item-list-block/{page}/{language_name}/{programm_id}', 'IndexController@allVideoArchiveProgrammItemListBlock');
        Route::post('index/send-vacancy-response/{vacancy_id}', 'IndexController@sendVacancyResponse');
        Route::get('index/all-video-archive-programm-list-block/{language_name}', 'IndexController@allVideoArchiveProgrammListBlock');
        Route::get('/about/{about}', 'IndexController@aboutMenu');
        Route::get('/history-two', function(){
            return view('index.history-two');
        });
        Route::get('/channel-faces', function(){
            return view('index.channel-faces');
        });
        Route::get('/management', function(){
            return view('index.management');
        });
        Route::get('/documents', function(){
            return view('index.documents');
        });
        Route::get('/audio-god-finance', function(){
            return view('index.audio-god-finance');
        });
        Route::get('/about-us-test', function(){
            return view('index.about-us-test');
        });

        Route::get('/programms', 'IndexController@programms');
        Route::get('/programms/{programm_url_name}', 'IndexController@programmPersonalPage');
        Route::get('/programma-peredach', 'IndexController@programmaPeredach');
        Route::get('/search', 'IndexController@searchPage');
        Route::get('/page/{menu_id}', 'IndexController@menuPage');
        Route::get('/news-by-category/{news_category_id}', 'IndexController@newsByCategory');

        // Novaia Adminka Blogera
        Route::group([
            'prefix' => 'blogger',
        ], function() {
            Route::match(['get', 'post'], 'blog-list/{user_id?}', 'BloggerController@blogList')->name('blog-list_new');
            Route::get('blog-edit/{blog_id}', 'BloggerController@blogEdit');
            Route::post('blog-edit/{id}', 'BloggerController@saveBlog')->name('blog-edit_new');
            Route::get('delete-blog', 'BloggerController@deleteBlog')->name('delete-blog_new');
            Route::get('delete-file', 'BloggerController@deleteFileDemo');
            Route::get('edit/{user_id}', 'BloggerController@userEdit')->name('user-edit_new');
            Route::post('user-edit/{user_id}', 'BloggerController@saveUser')->name('user-save_new');
            Route::get('delete-blog-tag', 'BloggerController@deleteBlogTag')->name('delete-blog-tag_new');
        });
        // End of >>> Novaia Adminka Blogera

        // Events
        Route::post('/file-upload', 'EventController@fileUpload');
        Route::post('/file-delete', 'EventController@fileDelete')->name('file_delete');
        Route::get('/events', 'EventController@index');
        Route::post('/event/store', 'EventController@store')->name('save_event');
        Route::get('/events/{url}', 'EventController@show');
        Route::get('/events-load-more', 'EventController@loadMore')->name('load_more');
        Route::get('/events-category', 'EventController@getDates')->name('get_dates');
        Route::get('/events-will-go', 'EventController@willGo')->name('will_go');
        // End of >>> Events 

        // RSS Feed
        Route::get('feed', 'IndexController@feed');
        Route::get('feed-kz', 'IndexController@feedKz');
        // End of Rss Feed

        Route::get('/problem', 'IndexController@problem');
        Route::get('/google96f261f2b2f90d65.html', function(){
            return \File::get(public_path() . '/google96f261f2b2f90d65.html');
        });
    }
);

Route::group(
    [
        'middleware' => [ 'web'],
        'namespace' => 'Auth',
    ],
    function()
    {
        Route::get('auth/{provider}', 'AuthController@redirectToProvider')->name('provider');
        Route::get('auth/{provider}/callback', 'AuthController@handleProviderCallback');
    }
);

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
], function() {
    Route::get('reset-password', 'AdminController@resetPassword');
    Route::post('reset-password', 'AdminController@resetPasswordEdit');
    Route::get('delete-news-tag', 'AdminController@deleteNewsTag');
    Route::get('add-new-user-tag', 'AdminController@addNewUserTag')->name('add-new-user-tag');
    Route::post('copy-tv-programm', 'AdminController@copyTvProgramm');
});

Route::group([
    'prefix' => 'index',
    'namespace' => 'Index',
], function() {
    Route::get('/set-programm-times-by-date/{date}/{type}/{lang}', 'IndexController@setProgrammTimesByDate');
    Route::get('/set-week-programm/{lang}', 'IndexController@setWeekProgramm');
    Route::get('/programm-by-day/{day_id}/{lang}', 'IndexController@setProgrammByDay');
    Route::get('/all-programms-block/{lang}', 'IndexController@setProgrammByWeek');
    Route::get('/alarm-programm', 'IndexController@alarmProgramm');
    Route::post('/send-delivery', 'IndexController@sendDelivery');
    Route::get('/set-programm-archive-by-date/{date_begin}/{date_end}/{lang}/{programm_id}/{page}', 'IndexController@programmArchiveByDate');
    Route::get('/set-programm-archive-last/{programm_id}', 'IndexController@programmArchiveByDate');
});

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['web', 'auth']
], function() {

    Route::group([
        'middleware' => 'admin',
    ], function() {

        Route::get('fav-list', 'AdminController@favorites');
        Route::post('fav-list', 'AdminController@favoritesSave');
        Route::get('delete-fav', 'AdminController@favoritesDelete');

        Route::get('index', 'AdminController@index');
        Route::get('news-list', 'AdminController@newsList');
        Route::post('news-list', 'AdminController@newsList');
        Route::get('news-edit/{news_id}', 'AdminController@newsEdit');
        Route::post('news-edit/{news_id}', 'AdminController@saveNews');
        Route::get('delete-news', 'AdminController@deleteNews');

        Route::get('programm-list', 'AdminController@programmList');
        Route::post('programm-list', 'AdminController@programmList');
        Route::get('programm-edit/{programm_id}', 'AdminController@programmEdit');
        Route::post('programm-edit/{programm_id}', 'AdminController@saveProgramm');
        Route::get('delete-programm', 'AdminController@deleteProgramm');
        Route::get('delete-programm-logo', 'AdminController@deleteProgrammLogo');

        Route::get('category-list', 'AdminController@categoryList');
        Route::get('category-edit/{category_id}', 'AdminController@categoryEdit');
        Route::post('category-edit/{category_id}', 'AdminController@saveCategory');
        Route::get('delete-category', 'AdminController@deleteCategory');

        Route::get('tv-programm-list', 'AdminController@tvProgrammList');
        Route::get('tv-programm-list/{date}', 'AdminController@tvProgrammList');
        Route::post('tv-programm-list', 'AdminController@tvProgrammList');
        Route::get('tv-programm-edit/{tv_programm_id}', 'AdminController@tvProgrammEdit');
        Route::post('tv-programm-edit/{tv_programm_id}', 'AdminController@saveTvProgramm');
        Route::get('delete-tv-programm', 'AdminController@deleteTvProgramm');

        Route::get('vacancy-list', 'AdminController@vacancyList');
        Route::get('vacancy-edit/{vacancy_id}', 'AdminController@vacancyEdit');
        Route::post('vacancy-edit/{vacancy_id}', 'AdminController@saveVacancy');
        Route::get('delete-vacancy', 'AdminController@deleteVacancy');

        Route::get('video-archive-list', 'AdminController@videoArchiveList');
        Route::post('video-archive-list', 'AdminController@videoArchiveList');
        Route::get('video-archive-edit/{video_archive_id}', 'AdminController@videoArchiveEdit');
        Route::post('video-archive-edit/{video_archive_id}', 'AdminController@saveVideoArchive');
        Route::get('delete-video-archive', 'AdminController@deleteVideoArchive');

        Route::get('user-list', 'AdminController@userList')->name('userList');
        Route::get('user-edit/{user_id}', 'AdminController@userEdit');
        Route::post('user-edit/{user_id}', 'AdminController@saveUser');
        Route::get('delete-user', 'AdminController@deleteUser');

        Route::get('change-password-edit', 'AdminController@changePasswordEdit');
        Route::post('change-password-edit', 'AdminController@changePassword');

        Route::get('job-response-list', 'AdminController@jobResponseList');
        Route::post('job-response-list', 'AdminController@jobResponseList');
        Route::get('delete-job-response', 'AdminController@deleteJobResponse');

        Route::get('delete-programm-time', 'AdminController@deleteProgrammTime');

        Route::get('tag-list', 'AdminController@tagList');
        Route::get('tag-edit/{tag_id}', 'AdminController@tagEdit');
        Route::post('tag-edit/{tag_id}', 'AdminController@saveTag');
        Route::get('delete-tag', 'AdminController@deleteTag');

        Route::get('advertisement-list', 'AdminController@advertisementList');
        Route::get('advertisement-edit/{advertisement_id}', 'AdminController@advertisementEdit');
        Route::post('advertisement-edit/{advertisement_id}', 'AdminController@saveAdvertisement');
        Route::get('delete-advertisement', 'AdminController@deleteAdvertisement');

        Route::get('news-category-list', 'AdminController@newsCategoryList');
        Route::get('news-category-edit/{news_category_id}', 'AdminController@newsCategoryEdit');
        Route::post('news-category-edit/{news_category_id}', 'AdminController@saveNewsCategory');
        Route::get('delete-news-category', 'AdminController@deleteNewsCategory');

        Route::get('menu-list', 'AdminController@menuList');
        Route::get('menu-edit/{menu_id}', 'AdminController@menuEdit');
        Route::post('menu-edit/{menu_id}', 'AdminController@saveMenu');
        Route::get('delete-menu', 'AdminController@deleteMenu');

        Route::get('const-tv-programm-list', 'AdminController@constTvProgrammList');
        Route::get('const-tv-programm-edit/{tv_programm_id}', 'AdminController@constTvProgrammEdit');
        Route::post('const-tv-programm-edit/{tv_programm_id}', 'AdminController@saveConstTvProgramm');
        Route::get('delete-const-tv-programm', 'AdminController@deleteConstTvProgramm');

        Route::get('add-const-tv-programm', 'AdminController@addConstTvProgramm');
        Route::post('add-express-tv-programm', 'AdminController@addExpressTvProgramm');
        Route::get('load-tv-programm/{date}', 'AdminController@loadTvProgramm');

        Route::get('news-archive-list/{lang}', 'AdminController@newsArchiveList');
        Route::post('news-archive-list/{lang}', 'AdminController@newsArchiveList');
        Route::get('delete-archive-news', 'AdminController@deleteArchiveNews');

        Route::get('news-archive-edit/{news_id}/{lang}', 'AdminController@newsArchiveEdit');
        Route::post('news-archive-edit/{news_id}', 'AdminController@saveNewsArchive');

        Route::get('delivery-list', 'AdminController@deliveryList');
        Route::get('delivery-edit/{delivery_id}', 'AdminController@deliveryEdit');
        Route::post('delivery-edit/{delivery_id}', 'AdminController@saveDelivery');

        Route::get('about-list', 'AdminController@aboutList')->name('about-list');
        Route::get('about-edit/{id}', 'AdminController@aboutEdit');
        Route::post('about-edit/{id}', 'AdminController@saveAbout');
        Route::get('delete-about', 'AdminController@deleteAbout');

        Route::get('page-list', 'AdminController@pageList');
        Route::get('page-edit/{id}', 'AdminController@pageEdit');
        Route::post('page-edit/{id}', 'AdminController@savePage');
        Route::get('delete-page', 'AdminController@deletePage');

        Route::get('document-list', 'AdminController@documentList');
        Route::get('document-edit/{id}', 'AdminController@documentEdit');
        Route::post('document-edit/{id}', 'AdminController@saveDocument');
        Route::get('delete-document', 'AdminController@deleteDocument');

        Route::get('ads-list', 'AdminController@adsList');
        Route::get('ads-edit/{id}', 'AdminController@adsEdit');
        Route::post('ads-edit/{id}', 'AdminController@saveAds');
        Route::get('delete-ads', 'AdminController@deleteAds');
    });

    Route::group([
        'middleware' => 'admin',
    ], function() {
        Route::get('edit/{user_id}', 'AdminController@userEdit')->name('user-edit');
        Route::match(['get', 'post'], 'blog-list/{user_id?}', 'BlogController@blogList')->name('blog-list');
        Route::get('blog-edit/{id}', 'BlogController@blogEdit');
        Route::post('blog-edit/{id}', 'BlogController@saveBlog');
        Route::get('delete-blog', 'BlogController@deleteBlog');
    });

    Route::group([
        'middleware' => 'admin',
    ], function() {
        // Route::get('edit/{user_id}', 'AdminController@userEdit')->name('employer-edit');
        Route::match(['get', 'post'], 'employer-list', 'AdminController@employerList')->name('employer-list');
        Route::get('employer-edit/{id}', 'AdminController@employerEdit');
        Route::post('employer-edit/{id}', 'AdminController@saveEmployer');
        Route::get('delete-employer', 'AdminController@deleteEmployer');
    });

    Route::group([
        'middleware' => 'admin',
    ], function() {
        Route::match(['get', 'post'], 'footer-list', 'AdminController@footerList')->name('footer-list');
        Route::get('footer-edit/{id}', 'AdminController@footerEdit');
        Route::post('footer-edit/{id}', 'AdminController@saveFooter');
        Route::get('delete-footer', 'AdminController@deleteFooter');
    });

    // Novii Admin Events
    Route::group([
        'middleware' => 'admin',
    ], function() {
        Route::match(['get', 'post'], 'event-list/{user_id?}', 'EventController@eventList')->name('event-list');
        Route::get('event-edit/{id}', 'EventController@eventEdit');
        Route::post('event-edit/{id}', 'EventController@saveEvent');
        Route::get('delete-event', 'EventController@deleteEvent');
        Route::get('delete-event-category', 'EventController@deleteEventCategory');
        Route::post('file-upload/{event_id}', 'EventController@fileUpload');
        Route::post('change-avatar', 'EventController@changeAvatar')->name('event_avatar');
    });
    // End >>> Novii Admin Events



    Route::get('delete-delivery', 'AdminController@deleteDelivery');
    Route::get('multiple-delete', 'AdminController@multipleDelete');

    Route::post('user-edit/{user_id}', 'AdminController@saveUser')->name('user-save');
    Route::get('save-translation-link', 'AdminController@saveTranslationLink');
});

Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => 'web'
], function() {

    Route::post('login', 'AdminController@login');
    Route::get('login', 'AdminController@login');

    Route::get('logout', function(){
        Auth::logout();
        return redirect('/blogs');
    });
});

Route::get('/admin', function()
{
    return redirect('/admin/index');
});

Route::get('/login', function(){
    return redirect('/admin/login');
});

Route::group([
    'prefix' => 'api',
    'namespace' => 'Api',
    'middleware' => 'web'
], function() {
    Route::match(['get', 'post'], 'get-latest-news', 'ApiController@getLatestArticles');
    // Route::post('get-latest-articles', 'ApiController@getLatestArticles');

    Route::match(['get', 'post'], 'get-archive-news', 'ApiController@getArchiveArticles');
    // Route::post('get-archive-articles', 'ApiController@getArchiveArticles');

    Route::match(['get', 'post'], 'get-slide-news', 'ApiController@getSlideArticles');
    // Route::post('get-slide-articles', 'ApiController@getSlideArticles');
    
    Route::match(['get', 'post'], 'get-news', 'ApiController@getArticle');
    // Route::post('get-article', 'ApiController@getArticle');

    Route::match(['get', 'post'], 'get-popular-news', 'ApiController@getPopularArticles');
    // Route::post('get-popular-articles', 'ApiController@getPopularArticles');


    Route::match(['get', 'post'], 'get-current-broadcast-info', 'ApiController@getCurrentBroadcastInfo');

    Route::match(['get', 'post'], 'get-main-news-list', 'ApiController@getMainArticleList');
    // Route::post('get-main-article-list', 'ApiController@getMainArticleList');

    Route::match(['get', 'post'], 'search-archive-programm-by-date', 'ApiController@searchArchiveProgrammByDate');
    Route::get('get-programm-times-list', 'ApiController@getProgrammTimesList');
    Route::post('get-programm-by-time', 'ApiController@getProgrammByTime');
    Route::post('programm-page', 'ApiController@programmPage');
    Route::get('get-vacancy-list', 'ApiController@vacancyList');
    Route::match(['get', 'post'], 'get-programm-peredach', 'ApiController@getPeredachi');

    Route::get('get-programm-week-days', 'ApiController@getProgrammWeekDay');
    Route::post('get-programm-by-day', 'ApiController@getProgrammByDay');
    Route::post('send-feedback', 'ApiController@sendFeedback');

    Route::post('push', 'PushController@addDevice');
    Route::post('add-programm-alarm', 'ApiController@addProgrammAlarm');
    Route::post('delete-programm-alarm', 'ApiController@deleteProgrammAlarm');

    Route::post('subscribe', 'ApiController@subscribe');
    Route::post('users', 'ApiController@searchUser');


});


Route::group([
    'middleware' => 'web',
], function() {

    Route::post('user-save', 'Index\IndexController@saveBlogger')->name('save-blogger');
    Route::post('user-login', 'Index\IndexController@login')->name('blogger-login');
});

Route::get('/yandex.php', function(){
    return view('yandex');
});



// Route::get('/', 'Index\IndexController@problem');