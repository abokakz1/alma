<?php

namespace App\Http\Controllers\Api;
use App\Models\JobResponse;
use App\Models\Programm;
use App\Models\Push;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\News;
use App\Models\View;
use App\Models\VideoArchive;
use App\Models\Vacancy;
use App\Models\TVProgramm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use App\Helpers;
use Illuminate\Support\Facades\Redirect;
use DB;
use Mail;
use Mcamara\LaravelLocalization\Exceptions\SupportedLocalesNotDefined;
use Mcamara\LaravelLocalization\Exceptions\UnsupportedLocaleException;
use Illuminate\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\ProgrammTime;
use App\Models\AlarmProgramm;
use App\Models\TranslationLink;
use App\Models\NewsArchiveKz;
use App\Models\NewsArchiveRu;
use App\Models\Users;
use Illuminate\Support\Facades\Lang;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    public function getLatestArticles(Request $request){
        $page = 0;
        if($request->page > 0){
            $page = ($request->page-1)*$request->paginate_count;
        }
        $paginate_count = $request->paginate_count;

        $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                     ->LeftJoin("news_category_tab", "news_tab.news_category_id","=","news_category_tab.news_category_id")
                        ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_en', 'programm_tab.programm_name_kz',
                            'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_en',
                            DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                        ->where("news_tab.is_active","=","1")
                        ->whereRaw('LENGTH(news_tab.news_text_'.($request->lang ? 'kz' : 'ru').')> 0')
                        ->orderByRaw("news_tab.date desc")
                        ->skip($page)
                        ->paginate($paginate_count);

        $result['current_page'] = $row->currentPage();
        $result['next_page'] = $row->nextPageUrl();
        $result['total_page'] = $row->lastPage();
        $result['all_news_count'] = $row->total();
        $result['data'] = $row->items();
        $result['success'] = true;
        return response()->json($result);
    }

    public function getArchiveArticles(Request $request){
        $page = 0;
        if($request->page > 0){
            $page = ($request->page-1)*$request->paginate_count;
        }
        $paginate_count = $request->paginate_count;

        if($request->lang == 1) {
            $row = NewsArchiveKz::LeftJoin("news_category_tab","news_archive_kz_tab.news_category_id","=","news_category_tab.news_category_id")->select('news_archive_kz_tab.*',  "news_category_tab.news_category_name_ru","news_category_name_kz", DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->whereNotNull("news_title_kz")->orderBy("news_id", "desc")->skip($page)->paginate($paginate_count);
        }
        else{
            $row = NewsArchiveRu::LeftJoin("news_category_tab","news_archive_ru_tab.news_category_id","=","news_category_tab.news_category_id")->select('news_archive_ru_tab.*', "news_category_tab.news_category_name_ru","news_category_name_kz", DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->whereNotNull("news_title_ru")->orderBy("news_id","desc")->skip($page)->paginate($paginate_count);
        }

        $result['current_page'] = $row->currentPage();
        $result['next_page'] = $row->nextPageUrl();
        $result['total_page'] = $row->lastPage();
        $result['all_news_count'] = $row->total();
        $result['data'] = $row->items();
        $result['success'] = true;
        return response()->json($result);
    }

    public function getCurrentBroadcastInfo(){
        $row = DB::select("
                            SELECT t.*, t1.image as category_image, t1.category_name_kz, t1.category_name_ru, t1.category_name_en, t2.programm_url_name
                            from tv_programm_tab t
                            left join category_tab t1 on t.category_id = t1.category_id
                            left join programm_tab t2 on t.tv_programm_programm_id = t2.programm_id
                            where t.date = date_format(now(), '%Y-%m-%e')
                            and   unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) <= unix_timestamp(now())
                            and   unix_timestamp(concat(t.date,' ',  CASE WHEN t.time_end='00:00:00' THEN '24:00:00' ELSE t.time_end END)) >= unix_timestamp(now())
                            order by unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) desc
                            limit 1
                           ");

        $tv_broadcast_row_new = DB::select("
                                            SELECT t.*, t1.image as category_image
                                            from tv_programm_tab t
                                            left join category_tab t1 on t.category_id = t1.category_id
                                            where t.date = date_format(now(), '%Y-%m-%e')
                                            and   unix_timestamp(concat(t.date,' ', t.time)) > unix_timestamp(now())
                                            order by unix_timestamp(concat(t.date,' ', t.time)) asc
                                            limit 1
                                           ");

        if(count($row) > 0){
            $row[0]->time = substr($row[0]->time,0,5);
            $row[0]->time_end = substr($row[0]->time_end,0,5);
        }

        $translation_link = TranslationLink::first();

        $result['result'] = $row;
        if(count($translation_link) > 0) {
            $result['translation_link'] = $translation_link->link;
        }
        $result['success'] = true;
        return response()->json($result);
    }

    public function getMainArticleList(Request $request){
        $row = News::LeftJoin('programm_tab', 'news_tab.programm_id', '=', 'programm_tab.programm_id')
            ->LeftJoin("news_category_tab", "news_tab.news_category_id", "=", "news_category_tab.news_category_id")
            ->select('news_tab.*', 'programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
            ->where("is_main_news", "=", 1)->where("is_active", "=", 1)
            ->whereRaw('LENGTH(news_tab.news_text_'.($request->lang ? 'kz' : 'ru').')> 0')
            ->take(2)
            ->orderByRaw("news_tab.is_fix desc")
            ->orderByRaw("news_tab.date desc")
            ->get();
    

        $result['result'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }


    public function searchArchiveProgrammByDate(Request $request){
        $date_start = $request->date_start;
        $date_start_parts = explode("-",$date_start);
        $day_count = date("t",strtotime( $date_start_parts[0] . '-' . $date_start_parts[1] .'-01'));
        $date_end = date($date_start_parts[0] . '-' . $date_start_parts[1] . "-" . $day_count);

        if(isset($request->programm_id) && $request->programm_id > 0){
            $row = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                ->select('video_archive_tab.*', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                ->whereBetween('video_archive_tab.video_archive_date', array($date_start, $date_end))
                ->where("video_archive_tab.programm_id","=",$request->programm_id)
                ->get();
        }
        else{
            $row = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                ->select('programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en','video_archive_tab.programm_id')
                ->whereBetween('video_archive_tab.video_archive_date', array($date_start, $date_end))
                ->groupBy("video_archive_tab.programm_id")
                ->get();
        }

        $result['result'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }


    public function getPopularArticles(Request $request){
        if($request->page > 1){
            $page = ($request->page-1)*$request->paginate_count;
        }
        else{
            $page = 0;
        }
        $paginate_count = $request->paginate_count;

        $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                     ->LeftJoin("news_category_tab", "news_tab.news_category_id","=","news_category_tab.news_category_id")
                        ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_en', 'programm_tab.programm_name_kz',
                            'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_en',
                            DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                        ->where("news_tab.is_active","=","1")
                        ->whereRaw('LENGTH(news_tab.news_text_'.($request->lang ? 'kz' : 'ru').')> 0')
                        ->orderByRaw("news_tab.view_count desc")
                        ->skip($page)
                        ->paginate($paginate_count);


        $result['current_page'] = $row->currentPage();
        $result['next_page'] = $row->nextPageUrl();
        $result['total_page'] = $row->lastPage();
        $result['all_news_count'] = $row->total();
        $result['data'] = $row->items();
        $result['success'] = true;
        return response()->json($result);
    }

    public function getSlideArticles(Request $request){

        $row = News::LeftJoin('programm_tab', 'news_tab.programm_id', '=', 'programm_tab.programm_id')
                ->LeftJoin("news_category_tab", "news_tab.news_category_id", "=", "news_category_tab.news_category_id")
                ->select('news_tab.*', 'programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                    DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                ->where("is_main_news", "=", 1)->where("is_active", "=", 1)
                ->whereRaw('LENGTH(news_tab.news_text_'.($request->lang ? 'kz' : 'ru').')> 0')
                ->orderByRaw("news_tab.is_fix desc")
                ->orderByRaw("news_tab.date desc")
                ->take(3)
                ->get();

        $result['data'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }

    public function getProgrammTimesList(){
        $row = ProgrammTime::select("day_id")->groupBy("day_id")->get();
        $result['result'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }

    public function getProgrammByTime(Request $request){
        $day_id = $request->day_id;
        $row = ProgrammTime::LeftJoin("programm_tab","programm_time_tab.programm_id","=","programm_tab.programm_id")
            ->LeftJoin("programm_category_tab","programm_time_tab.programm_id","=","programm_category_tab.programm_id")
            ->LeftJoin("category_tab","programm_category_tab.category_id","=","category_tab.category_id")
            ->select("programm_time_tab.*","programm_tab.*","category_tab.*")
            ->where("programm_time_tab.day_id","=",$day_id)
            ->orderBy("programm_time_tab.time","desc")
            ->get();
        $result['result'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }

    public function programmPage(Request $request){
        $programm_id = $request->programm_id;
        $row = Programm::LeftJoin("programm_category_tab","programm_tab.programm_id","=","programm_category_tab.programm_id")
                        ->LeftJoin("category_tab","programm_category_tab.category_id","=","category_tab.category_id")
                        ->select("programm_tab.*","category_tab.category_name_kz","category_tab.category_name_ru","category_tab.category_name_en")
                        ->where("programm_tab.programm_id","=",$programm_id)->first();

        $archive_row = VideoArchive::select("video_archive_tab.*", DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))->where("programm_id","=",$row['programm_id'])->get();

        $programm_time_row = ProgrammTime::where("programm_id","=",$programm_id)->orderBy("day_id","asc")->orderBy("time","asc")->get();

        $result['result'] = $row;
        $result['programm_archive'] = $archive_row;
        $result['programm_time'] = $programm_time_row;
        $result['success'] = true;
        return response()->json($result);
    }

    public function vacancyList(){
        $row = Vacancy::all();
        $result['result'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }

    public function getProgrammPeredach(){
        $weekStart = date('Y-m-d', strtotime(date('Y').'W'.date('W').'1'));
        $weekEnd = date('Y-m-d', strtotime(date('Y').'W'.date('W').'7'));

        for($i = 1; $i < 8; $i++){
            $date_item = date('d.m.Y', strtotime(date('Y').'W'.date('W').$i));
            $item = TVProgramm::LeftJoin("category_tab","tv_programm_tab.category_id","=","category_tab.category_id")
                ->select("tv_programm_tab.*","category_tab.image as category_image", "category_tab.category_name_kz", "category_tab.category_name_ru", "category_tab.category_name_en")
                ->where('date',"=",date('Y-m-d', strtotime(date('Y').'W'.date('W').$i)))
                ->orderBy("time","asc")
                ->get();
            if(count($item) > 0){
                $row[$date_item] = $item;
            }
        }

        $result['result'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }

    public function getPeredachi(){
        $weekStart = date('Y-m-d', strtotime(date('Y').'W'.date('W').'1'));
        $weekEnd = date('Y-m-d', strtotime(date('Y').'W'.date('W').'7'));

        $item = TVProgramm::LeftJoin("category_tab","tv_programm_tab.category_id","=","category_tab.category_id")
                ->select("tv_programm_tab.*","category_tab.image as category_image", "category_tab.category_name_kz", "category_tab.category_name_ru", "category_tab.category_name_en")
                ->whereBetween('date', [$weekStart, $weekEnd])
                ->orderBy("date","asc")->orderBy("time", 'asc')
                ->get();

        $result['result'] = $item->groupBy('keydate');
        $result['success'] = true;
        return response()->json($result);
    }

    public function getArticle(Request $request){
        $news_id = $request->news_id;
        $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
            ->LeftJoin("news_category_tab", "news_tab.news_category_id","=","news_category_tab.news_category_id")
            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en',
                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'), 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_en')
            ->where("news_tab.is_active","=","1")
            ->where("news_tab.news_id","=",$news_id)
            ->first();

        $result['result'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }

    public function getProgrammWeekDay(){
        $day_arr = array();

        for($i = 1; $i < 8; $i++){
            $weekDay = date('d.m.Y', strtotime(date('Y').'W'.date('W').$i));
            array_push($day_arr, $weekDay);
        }

        $result['result'] = $day_arr;
        $result['success'] = true;
        return response()->json($result);
    }

    public function getProgrammByDay(Request $request){
        $date = $request->date;
        $row = TVProgramm::LeftJoin("category_tab","tv_programm_tab.category_id","=","category_tab.category_id")
            ->select("tv_programm_tab.*","category_tab.image as category_image", "category_tab.category_name_kz", "category_tab.category_name_ru", "category_tab.category_name_en")
            ->where('date',"=",date("Y-m-d", strtotime($date)))
            ->orderBy("time","asc")
            ->get();

        if(isset($request->registration_id)){
            foreach($row as $key => $row_item){
                $alarm_programm = AlarmProgramm::where("registration_id","=", $request->registration_id)->where("tv_programm_id","=",$row_item['tv_programm_id'])->get();
                if(count($alarm_programm)){
                    $row_item['is_alarm'] = 1;
                }
                else{
                    $row_item['is_alarm'] = 0;
                }
            }
        }

        $result['result'] = $row;
        $result['success'] = true;
        return response()->json($result);
    }

    public function sendFeedback(Request $request){
        $name = $request->name;
        $email = $request->email;
        $mess = "Email: " . $email . "<br>" . $request->message;
        if(Mail::send(['html' => 'admin.email'], ['text' => $mess], function($mess) use ($email,$name)
        {
            $mess->to("adik.khalikhov@mail.ru")->subject("Новое сообщение от " . $name);
        })){
            return response()->json(['result'=>"true"]);
        }
        else{
            return response()->json(['result'=>"false"]);
        }
    }

    public function addProgrammAlarm(Request $request){
        $result = AlarmProgramm::where('registration_id', '=', $request->registration_id)->where("tv_programm_id","=",$request->tv_programm_id)->delete();

        $row = new AlarmProgramm();
        $row->registration_id = $request->registration_id;
        $row->os = $request->os;
        $row->tv_programm_id = $request->tv_programm_id;
        $row->time = $request->time;
        $row->time_before = $request->time_before;
        if($row->save()){
            return response()->json(['result'=>"true"]);
        }
        else{
            return response()->json(['result'=>"false"]);
        }
    }

    public function deleteProgrammAlarm(Request $request){
        $result = AlarmProgramm::where('registration_id', '=', $request->registration_id)->where("tv_programm_id","=",$request->tv_programm_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function subscribe(Request $request){
        
        App::setLocale($request->lang);
        $blogger = Users::where('user_id', $request->email)->first();
        $subscription = Subscription::where('subscriber_id', Auth::user()->user_id)->where('subscribed_id', $blogger->user_id)->first();

        if (is_null($subscription)) {
            $subscription = new Subscription();
            $subscription->subscriber_id = Auth::user()->user_id;
            $subscription->subscribed_id = $blogger->user_id;
            $subscription->save();

            return response()->json([
                'input' => Lang::get('messages.unfollow'),
                'state' => 'followed'
            ]);
        }
        else {
            $subscription->delete();
            return response()->json([
                'input' => Lang::get('messages.follow'),
                'state' => 'unfollowed'
            ]);
        }
    }

    public function searchUser(Request $request){
        $search = '%' . strtolower($request->search) . '%';
        $subscriptions = Users::where('email', $request->user)->first()->search;
//        strtolower($request->search)
        //$subscriptions = Subscription::where('subscriber_id', $blogger->user_id)->whereIn('subscribed_id', $blogger->user_id)->get();
        return $subscriptions;
    }
}
