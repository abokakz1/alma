<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Models\News;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\DB;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {

        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
         
        $kz_news_row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                            ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
                            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                            ->where("is_main_news","=",1)->where("is_active","=",1)
                            ->take(30)
                            ->orderByRaw("news_tab.is_fix desc")
                            ->orderByRaw("news_tab.date desc")
                            ->get();

        return response()->view('errors.404',['kz_news_row' => $kz_news_row],404);
        }

        return parent::render($request, $exception);
    }
}
