<?php
namespace App\Http\Controllers\Index;

use App\Models\JobResponse;
use App\Models\NewsCategory;
use App\Models\Programm;
use App\Models\Users;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\News;
use App\Models\View;
use App\Models\VideoArchive;
use App\Models\Vacancy;
use App\Models\TVProgramm;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
use App\Models\NewsArchiveKz;
use App\Models\NewsArchiveRu;
use App\Models\Menu;
use App\Models\AlarmProgramm;
use App\Models\Push;
use App\Models\Delivery;
use Jenssegers\Date\Date;
use App\Models\NewsTag;
use App\Models\JobPosition;
use App\Models\Employer;
use App\Models\Blog;
use App\Models\Favorite;
use App\Models\Event;
use App\Models\Media;

class IndexController extends Controller
{
    public function index(){
        $kz_news_row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                            ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
                            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                            ->where("is_main_news","=",1)->where("is_active","=",1)
                            ->take(30)
                            ->orderByRaw("news_tab.is_fix desc")
                            ->orderByRaw("news_tab.date desc")
                            ->get();

        $almaty_news_row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                            ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
                            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                            ->where("is_almaty","=","1")->where("is_active","=",1)
                            ->take(30)
                            ->orderByRaw("news_tab.is_fix desc")
                            ->orderByRaw("news_tab.date desc")
                            ->get();
        $locale = App::getLocale();
        if (App::getLocale() == 'kz'){
            Date::setLocale('kk');
        }
        else {
            Date::setLocale(App::getLocale());
        }

        $rec_events = null;
        $rec_events = Event::selectLocaled($locale)->with(['media'=> function($query){ 
                $query->select('*');
            }])->where('published', true)->where('date_start', '>=' , Date::now())->orderBy('date_start', 'desc')->take(2)->get();

        $programs = Programm::selectMain($locale)->orderBy('main_order_num')->get();
        
        $fav = Favorite::orderBy('created_at', 'DESC')->where('is_active_'. $locale, true)->take(4)->get();
        $fav = $this->correctFav($fav);
        $two = DB::table('two_blocks')->orderBy('created_at', 'DESC')->where('is_active_'. $locale, true)->take(2)->get();
        $two = $this->correctFav($two);
        $rec_blogs = DB::table('two_blogs')->orderBy('created_at', 'DESC')->where('is_active_'. $locale, true)->take(2)->get();
        $rec_blogs = $this->correctFav($rec_blogs);
        
        return view('index.index',['kz_news_row' => $kz_news_row, 'almaty_news_row' => $almaty_news_row, 'favorites' => $fav, 'programs' => $programs,'rec_blogs'=>$rec_blogs, 'rec_events'=> $rec_events, 'two'=>$two]);
    }

    public function correctFav($fav){
        $locale = App::getLocale();
        foreach ($fav as $item) {
            if($item->type=='news'){
                $source = News::select('view_count', DB::raw('news_title_ru as title_ru'), DB::raw('news_title_kz as title_kz'), DB::raw('news_url_name as url'), 'date', 'image', 'news_category_id')->where('news_id', $item->source_id)->where('news_title_'. App::getLocale(), '<>', "")->first();
                if ($source) {
                    $item->url = '/news/news/'.$source->url;
                    $item->image = ($source->image ? '/news_photo/'.$source->image : null);
                    $item->view = $source->view_count;
                    $category = $source->category()->selectLocaled($locale)->first();
                    if($category){
                        $item->type_word = $category->name;
                    } else {
                        $item->type_word = trans('messages.Новости');
                    }

                    $item->date = Date::parse($source->date)->format('d.m.Y');
                }
            } else if($item->type=='blogs'){
                $source = Blog::select(DB::raw('blog_title_kz as title_kz'),DB::raw('blog_title_ru as title_ru'), DB::raw('blog_url_name as url'), 'date', 'image', 'author_id', 'view_count')->where('blog_id', $item->source_id)->where('is_active_'. App::getLocale(), true)->first();
               if ($source) {
                    $item->url = $source->url;
                    $item->type_word = 'Блог';
                    $item->image = ($source->image ? $source->image : null);
                    $item->author = $source->author->fio;
                    if(strlen($item->author)>17){
                        $item->author = mb_substr($source->author->fio, 0, 17) . '...';    
                    }
                    $item->author_url = "/bloggers/".($source->author->username ? $source->author->username : $source->author->email);
                    $item->author_img = ($source->author->image ? '/user_photo/'.$source->author->image : '/user_photo/user.png');
                    $item->view = $source->view_count;
                    $item->date = $source->date;
               }
            } else {
                $source = Event::select(DB::raw('event_title as title_kz'), DB::raw('event_url_name as url'),DB::raw('date_start as date'), 'address')->where('event_id', $item->source_id)->first();
               
               if ($source) {
                    $media = Media::where('event_id', $item->source_id)->first();
                    $item->url = '/events/'.$source->url;
                    $item->type_word = trans('messages.События');
                    $source->title_ru = $source->title_kz;
                    if($media->count()){
                        $item->image = '/event_photo/'. $media->link;
                    }
                    $item->address = mb_substr($source->address, 0, 17) . '...';

                    $item->date = Date::parse($source->date)->format('d.m.Y');
                   }

            }
            
            if ($source) {
                $item->title_kz = $source->title_kz;
                $item->title_ru = $source->title_ru;
            }
        }
        return $fav;
    }

    public function languageSet(Request $request){
        $lang_name = $request->lang_name;
        \Session::put('locale', $lang_name);
        \Session::put('app.locale', $lang_name);
        echo var_dump(\Session::get('locale'));
        cookie('app.locale', $lang_name);
        $as = new \Mcamara\LaravelLocalization\LaravelLocalization;
        $as->setLocale($lang_name);
        \Session::put('locale', $lang_name);
//        \App::setLocale($lang_name);
        return Redirect::back();
    }

    public function indexDemo(){
    	$locale = App::getLocale();
        $kz_news_row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                            ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
                            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                            ->where("is_main_news","=",1)->where("is_active","=",1)
                            ->where("news_title_".$locale, "<>", "")
                            ->take(3)
                            ->orderByRaw("news_tab.is_fix desc")
                            ->orderByRaw("news_tab.date desc")
                            ->get();
        // dd($kz_news_row);

        $almaty_news_row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                            ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
                            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                            ->where("is_almaty","=","1")->where("is_active","=",1)
                            ->where("news_title_".$locale, "<>", "")
                            ->take(4)
                            ->orderByRaw("news_tab.is_fix desc")
                            ->orderByRaw("news_tab.date desc")
                            ->get();
        // dd($almaty_news_row);
        $row_new = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                                ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_en', 'programm_tab.programm_name_kz',
                                        DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                                ->where("news_tab.is_active","=","1")
                                ->where("news_title_".$locale, "<>", "")
                                ->orderByRaw("news_tab.date desc")
                                ->take(4)->get();
        // dd($row_new);
        

        if ($locale == 'kz'){
            Date::setLocale('kk');
        } else {
            Date::setLocale($locale);
        }

        $rec_events = null;
        $rec_events = Event::selectLocaled($locale)->with(['media'=> function($query){ 
                $query->select('*');
            }])->where('published', true)->where('date_start', '>=' , Date::now())->orderBy('date_start', 'desc')->take(2)->get();
    
        $programs = Programm::selectMain($locale)->orderBy('main_order_num')->get();
        $fav = Favorite::orderBy('created_at', 'DESC')->where('is_active_'. $locale, true)->take(4)->get();
        $fav = $this->correctFav($fav);
        $two = DB::table('two_blocks')->orderBy('created_at', 'DESC')->where('is_active_'. $locale, true)->take(2)->get();
        $two = $this->correctFav($two);
        $rec_blogs = DB::table('two_blogs')->orderBy('created_at', 'DESC')->where('is_active_'. $locale, true)->take(2)->get();
        $rec_blogs = $this->correctFav($rec_blogs);
        
        return view('index.index_demo',['kz_news_row' => $kz_news_row, 'almaty_news_row' => $almaty_news_row, 'row_new' => $row_new, 'favorites' => $fav, 'programs' => $programs,'rec_blogs'=>$rec_blogs, 'rec_events'=> $rec_events, 'two'=>$two]);
    }

    public function rss(){
        $news_str_row = "";
        $news_row =  News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                            ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
                            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                            ->where("is_active","=",1)
                            ->where("is_mail_ru","=",1)
                            ->orderByRaw("news_tab.is_fix desc")
                            ->orderByRaw("news_tab.date desc")
                            ->get();
        header('Content-type: text/xml');
        echo "<"."?xml version=\"1.0\" encoding=\"utf-8\"?".">\n";
        echo "<rss version=\"2.0\"";
        echo " xmlns:atom=\"http://www.w3.org/2005/Atom\" xmlns:mailru=\"http://news.mail.ru/\">\n";

        echo "<channel>
    <title>Телеканал Алматы</title>
    <link>http://almaty.tv/</link>
    <description>Новости телеканала Алматы</description>
    <language>ru</language>
    <pubDate>". date("r")."</pubDate>
    <atom:link href=\"http://almaty.tv/rss\" rel=\"self\" type=\"application/rss+xml\" />
    <lastBuildDate>". date("r")."</lastBuildDate>
    <generator>rss generator</generator>";

        if(count($news_row) > 0){
            foreach($news_row as $key => $news_item){
                if(strlen($news_item['news_title_ru']) > 0){
                    $category_str = "";
                    if(strlen($news_item['news_category_name_ru']) > 0){
                        $category_str = '<category>' . $news_item['news_category_name_ru'] . '/ </category>';
                    }
                    echo '<item>
                                             <guid isPermaLink="false">' . $news_item['news_id']  .'_almaty.tv</guid>
                                             <title><![CDATA[' . $news_item['news_title_ru'] . ']]></title>
                                             <link>http://almaty.tv/news/news/' . $news_item['news_url_name'] . '</link>
                                             ' . $category_str .  '
                                             <description><![CDATA[' . mb_substr(strip_tags(str_replace("&nbsp;","",$news_item['news_text_ru'])),0,300) . '...]]></description>
                                             <mailru:full-text><![CDATA[' . strip_tags(str_replace("&nbsp;","",$news_item['news_text_ru'])) . ']]></mailru:full-text>
                                             <enclosure url="http://almaty.tv/news_photo/' . $news_item->image .'" length="11048" type="image/jpeg"/>
                                             <pubDate>' . gmdate(DATE_RSS, strtotime($news_item['date'])) . '</pubDate>
                                        </item>';
                }
            }
        }

        echo "</channel>\n";
        echo "</rss>\n";
        return;
        echo '<?xml version="1.0" encoding="UTF-8"?>
                 <rss version="2.0" xmlns:mailru="http://news.mail.ru/">
                    <channel>
                        <title>RSS новостей Almaty.tv</title>
                        <link>http://almaty.tv</link>
                        <description>Описание RSS</description>
                        <language>ru</language>
                        <generator>rss generator</generator>' .
                        $news_str_row
                  . '
                  </channel>
                 </rss>
                ';
    }

    public function news(Request $request){
        $news_sort_id = 1;
        $locale = App::getLocale();
        if(isset($request->news_sort_id) && $request->news_sort_id == 2){
            $news_sort_id = $request->news_sort_id;

            $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en',
                    DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                ->where("news_tab.is_active","=","1")
                ->where("news_tab.news_title_". $locale, '<>', "")
                ->orderByRaw("news_tab.view_count desc")
                ->paginate(8);
        }
        else{
            $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                        ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en',
                            DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                        ->where("news_tab.is_active","=","1")
                        ->where("news_tab.news_title_". $locale, '<>', "")
                        ->orderByRaw("news_tab.date desc")
                        // ->get();
                        ->paginate(12);
        }

        return view('index.news', [ 'row' => $row, 'news_sort_id' => $news_sort_id ]);
    }

    public function newsView(Request $request){
        $news_url_name = $request->news_url_name;
        $locale = App::getLocale();
        $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
            ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en'
                ,'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_en',
                'news_tab.date as date2', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
            ->where("news_tab.is_active","=","1")
            ->where("news_tab.news_url_name","=",$news_url_name)
            ->first();

        // dd(!$row);
            // abort(404);
        if(!$row){
            return abort(404);//$this->error404();
        }

        $yesterday = Date::parse($row->date2)->sub('1 day');
        $row_next = News::select('news_tab.*')
            ->where("news_tab.is_active","=","1")
            ->where("news_tab.news_title_{$locale}","<>","")
            ->where("news_tab.date", '<', $row->date2)
            // ->where("news_tab.date", '>=', $yesterday)
            ->orderBy("news_tab.date", "DESC")
            ->first();

        $news_tag_ids = NewsTag::LeftJoin("tag_tab","news_tag_tab.tag_id","=","tag_tab.tag_id")->select("news_tag_tab.*","tag_tab.tag_name")->where("news_id","=",$row->news_id)->pluck('tag_id');
        
        $rec_array = collect([]);
        $rec_news = News::LeftJoin('news_tag_tab','news_tab.news_id','=','news_tag_tab.news_id')
            ->select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
            ->where("is_active","=",1)->where('news_title_'. App::getLocale(), '<>', "")
            ->whereIn('news_tag_tab.tag_id', $news_tag_ids)->where('news_tab.news_id','<>',$row->news_id)
            ->orderByRaw("news_tab.is_fix desc")
            ->orderByRaw("news_tab.date desc")
            ->groupBy('news_tab.news_id')
            ->take(4)->get();
            
        foreach ($rec_news as $rc_new) {
            $rec_array->push($rc_new);
        }
        
        $date = Date::parse('-1 month');
        
        if (count($rec_array)<4){
            $rec_news = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                ->where("is_active","=",1)->where('news_title_'. App::getLocale(), '<>', "")
                ->whereNotIn('news_id', $rec_array->pluck('news_id'))
                ->where('date','>=', $date)
                ->orderByRaw("RAND()")
                ->take(4)->get();

            foreach ($rec_news as $o_new){
                $rec_array->push($o_new);
            }
        }

        return view('index.news-view2', [ 'row' => $row, 'kz_news_row' => $rec_array, 'row_next'=>$row_next]);
    }

    public function about(){
        return view('index.about');
    }

    public function history(){
        return view('index.history');
    }

    public function aboutUs(Request $request){
    	$locale = App::getLocale();
        if($request->id){
            $employers = Employer::selectLocaled($locale)->get();
            return response()->json(['employers' => $employers]);    
        }
    	$job_positions = JobPosition::selectLocaled($locale)->get();
    	$employers = Employer::selectLocaled($locale)->get();
    	
        return view('index.about-us', ['job_positions' => $job_positions, 'employers' => $employers]);
    }

    public function obratnayaSvyaz(){
        return view('index.obratnaya-svyaz');
    }

    public function vakansii(){
        $vacancy_row = Vacancy::all();
        return view('index.vakansii', ['vacancy_row' => $vacancy_row]);
    }

    public function ads(){
        $pricelist = DB::table('ads_tab')->where('locale', App::getLocale())->where('type','pricelist')->orderBy('created_at', 'desc')->first();
        $presentation = DB::table('ads_tab')->where('locale', App::getLocale())->where('type','presentation')->orderBy('created_at', 'desc')->first();
        return view('index.ads', ['pricelist'=> $pricelist, 'presentation'=>$presentation]);
    }

    public function videoArchive(Request $request){
        $programm_id = 0;
        if($request->programm_id > 0){
            $programm_id = (int)$request->programm_id;
        }
        return view('index.video-archive', ['programm_id' => $programm_id]);
    }

    public function tvProgramm(){
        $allparam['date'] = date("d.m.Y");
        $row = TVProgramm::where('date', '=', date("Y-m-d", strtotime($allparam['date'])))->get();
        return view('index.tv-programm', [ 'row' => $row, 'allparam' => $allparam ]);
    }

    public function setNewsView(Request $request){
        $token = $request->_token;
        $news_id = $request->news_id;
        if(strlen($token) > 0 && $news_id > 0){
            $check_view = View::where("news_id","=",$news_id)->where("session","=",$token)->get();
            if(count($check_view) < 1){
                $new_view = new View();
                $new_view->news_id = $news_id;
                $new_view->session = $token;
                $new_view->save();
            }
        }
    }

    public function videoArchiveProgrammListBlock(Request $request, $programm_id = null){
        return view('index.video-archive-programm-list-block',['date_start' => $request->date_start, 'date_end' => $request->date_end, 'language_name' => $request->language_name, 'programm_id' => $request->programm_id]);
    }

    public function videoArchiveProgrammListBlockMobile(Request $request){
        return view('index.video-archive-programm-list-block-mobile',['date_start' => $request->date_start, 'date_end' => $request->date_end, 'language_name' => $request->language_name]);
    }

    public function allVideoArchiveProgrammListBlock(Request $request){
        // dd($request->year);
        return view('index.all-video-archive-programm-list-block',['language_name' => $request->language_name, 'year'=>$request->year]);
    }

    public function videoArchiveProgrammItemListBlock(Request $request){
        return view('index.video-archive-programm-item-list-block',['date_start' => $request->date_start, 'date_end' => $request->date_end, 'programm_id' => $request->programm_id, 'language_name' => $request->language_name]);
    }

    public function broadCasting(){
        return view('index.broadcasting');
    }

    public function videoArchivePage(Request $request){
        $row = VideoArchive::LeftJoin("programm_tab","video_archive_tab.programm_id","=","programm_tab.programm_id")->select("video_archive_tab.*", DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'), "programm_tab.programm_name_ru","programm_tab.programm_name_kz","programm_tab.programm_name_en", "programm_tab.programm_url_name")->where("video_archive_url_name","=",$request->video_archive_url_name)->first();
        return view('index.video-archive-page',['row' => $row]);
    }

    public function sendRequest(Request $request){
        $name = $request->name;
        $email = $request->email;
        $mess = "Email: " . $email . "<br>" . $request->mess;

        Mail::send(['html' => 'admin.email'], ['text' => $mess], function($mess) use ($email,$name)
        {
            $mess->to("webalmatytv@gmail.com")->subject("Новое сообщение от " . $name);
        });
        return response()->json(['result'=>"true"]);
    }

    public function sendVacancyResponse(Request $request){
        if(!(isset($request->response_file))){
            $result['success'] = "not_file";
            return $result;
        }
        $file = $request->response_file;
        $file_name = time() . "_response.";
        $file_extension = $file->extension($file_name);
        $file_name = $file_name . $file_extension;
        Storage::disk('response_files')->put($file_name,  File::get($file));

        $job_response_row = new JobResponse();
        $job_response_row->vacancy_id = $request->vacancy_id;
        $job_response_row->filename = $file_name;
        $offset= strtotime("+6 hours 0 minutes");
        $job_response_row->job_response_date = date("Y-m-d H:i:s",$offset);
        $job_response_row->save();

        if($file_extension == "pdf"){
            $body_text = 'Ссылка на вакансию: https://almaty.tv/response_files/' . $file_name;    
        } else{
            $body_text = 'Ссылка на вакансию: https://view.officeapps.live.com/op/view.aspx?src=https://almaty.tv/response_files/' . $file_name;
        }
        
        Mail::send(['html' => 'admin.email'], ['text' => $body_text], function($mess)
        {
            $mess->to("webalmatytv@gmail.com")->subject("Поступила новая вакансия");
        });
        $result['success'] = true;
        $result['file_name'] = $file_name;
        return $result;
    }

    public function programmaPeredach(){
        $weekStart = date('Y-m-d', strtotime(date('Y').'W'.date('W').'1'));
        $weekEnd = date('Y-m-d', strtotime(date('Y').'W'.date('W').'7'));
        $row = TVProgramm::where('date','<=',$weekEnd)
                            ->where('date','>=',$weekStart)
                            ->get();
        return view('index.programma-peredach',['row' => $row]);
    }

    public function setProgrammTimesByDate(Request $request){
        $row = TVProgramm::LeftJoin("category_tab","tv_programm_tab.category_id","=","category_tab.category_id")
                            ->select("tv_programm_tab.*","category_tab.image as category_image", "category_tab.category_name_kz", "category_tab.category_name_ru", "category_tab.category_name_en")
                            ->where('date',"=",$request->date)
                            ->orderBy("time","asc")
                            ->get();
        return view('index.programm-times-by-date',['row' => $row, 'type' => $request->type, 'lang' => $request->lang]);
    }

    public function programms(){
        $row = ProgrammTime::LeftJoin("programm_tab","programm_time_tab.programm_id","=","programm_tab.programm_id")->select("programm_time_tab.*")->groupBy("day_id")->where("programm_tab.programm_id",">",0)->get();
        return view('index.programms', ['row' => $row]);
    }

    public function setProgrammByDay(Request $request){
        $day_id = $request->day_id;
        $row = ProgrammTime::LeftJoin("programm_tab","programm_time_tab.programm_id","=","programm_tab.programm_id")
                            ->select("programm_tab.*")
                            ->where("programm_time_tab.day_id","=",$day_id)
                            ->where("programm_tab.programm_id",">","0")
                            ->orderBy("programm_tab.order_num","asc")
                            ->get();
        return view('index.programm-by-day', ['row' => $row, 'lang' => $request->lang]);
    }

    public function programmPersonalPage(Request $request){
        $programm_url_name = $request->programm_url_name;
        $row = Programm::LeftJoin("programm_category_tab","programm_tab.programm_id","=","programm_category_tab.programm_id")
            ->LeftJoin("category_tab","programm_category_tab.category_id","=","category_tab.category_id")
            ->select("programm_tab.*","category_tab.category_name_kz","category_tab.category_name_ru","category_tab.category_name_en")
            ->where("programm_url_name","=",$programm_url_name)->first();

        return view('index.one-programms-page', ['row' => $row]);
    }

    public function searchPage(Request $request){
        $row = null;
        $search_date = date("d.m.Y");

        $page = 1;
        $paginate_count = 8;

        $skip_count = 0;
        if(isset($request->page)){
            $page = $request->page;
            $skip_count = $request->page*5;
        }

        $row_all = null;
        $row_archive_all = null;


        if($request->search_type == 2){
            $row_all = News::LeftJoin("news_tag_tab","news_tab.news_id","=","news_tag_tab.news_id")->LeftJoin("tag_tab","news_tag_tab.tag_id","=","tag_tab.tag_id")->select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where('news_tab.news_title_' . App::getLocale(), '!=', '')->where("tag_tab.tag_name","=",$request->search_by_tag)->get();
            $row = News::LeftJoin("news_tag_tab","news_tab.news_id","=","news_tag_tab.news_id")->LeftJoin("tag_tab","news_tag_tab.tag_id","=","tag_tab.tag_id")->select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where('news_tab.news_title_' . App::getLocale(), '!=', '')->where("tag_tab.tag_name","=",$request->search_by_tag)->skip($skip_count)->paginate($paginate_count);
        }
        else if($request->search_type == 3){
            $row_all = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where("news_tab.news_category_id","=",$request->search_news_category_id)->where('news_title_' . App::getLocale(), '!=', '')->get();
            $row = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where("news_tab.news_category_id","=",$request->search_news_category_id)->where('news_title_' . App::getLocale(), '!=', '')->skip($skip_count)->paginate($paginate_count);
        }
        else{
            if(strlen($request->search_text) > 0){
                if($request->lang == "kz"){
                    $row_all = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where("news_title_kz","like","%" . $request->search_text ."%")->get();
                    $row = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where("news_title_kz","like","%" . $request->search_text ."%")->skip($skip_count)->paginate($paginate_count);
                }
                else if($request->lang == "en"){
                    $row_all = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where("news_title_en","like","%" . $request->search_text ."%")->get();
                    $row = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where("news_title_en","like","%" . $request->search_text ."%")->skip($skip_count)->paginate($paginate_count);
                }
                else{
                    $row_all = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where("news_title_ru","like","%" . $request->search_text ."%")->get();
                    $row = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where("news_title_ru","like","%" . $request->search_text ."%")->skip($skip_count)->paginate($paginate_count);
                }
            }
            else if(strlen($request->date) > 0){
                if($request->lang == "kz"){
                    $row_all = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where(DB::raw('DATE_FORMAT(news_tab.date,"%Y-%m-%d")'),"=", date("Y-m-d", strtotime($request->date)))->get();
                    $row = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where(DB::raw('DATE_FORMAT(news_tab.date,"%Y-%m-%d")'),"=", date("Y-m-d", strtotime($request->date)))->skip($skip_count)->paginate($paginate_count);
                }
                else if($request->lang == "en"){
                    $row_all = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where(DB::raw('DATE_FORMAT(news_tab.date,"%Y-%m-%d")'),"=", date("Y-m-d", strtotime($request->date)))->get();
                    $row = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where(DB::raw('DATE_FORMAT(news_tab.date,"%Y-%m-%d")'),"=", date("Y-m-d", strtotime($request->date)))->skip($skip_count)->paginate($paginate_count);
                }
                else{
                    $row_all = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where(DB::raw('DATE_FORMAT(news_tab.date,"%Y-%m-%d")'),"=", date("Y-m-d", strtotime($request->date)))->get();
                    $row = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))->where("news_tab.is_active","=","1")->where(DB::raw('DATE_FORMAT(news_tab.date,"%Y-%m-%d")'),"=", date("Y-m-d", strtotime($request->date)))->skip($skip_count)->paginate($paginate_count);
                }
                $search_date = $request->date;
            }
        }

        $row_archive = null;
        if($request->search_type != 2 && strlen($request->search_text) > 0) {
            if ($request->lang == "kz") {
                $row_archive_all = VideoArchive::LeftJoin("programm_tab","video_archive_tab.programm_id","=","programm_tab.programm_id")->select('video_archive_tab.*', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'), "programm_tab.programm_url_name")->where("video_archive_title_kz", "like", "%" . $request->search_text . "%")->get();
                $row_archive = VideoArchive::LeftJoin("programm_tab","video_archive_tab.programm_id","=","programm_tab.programm_id")->select('video_archive_tab.*', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'), "programm_tab.programm_url_name")->where("video_archive_title_kz", "like", "%" . $request->search_text . "%")->skip($skip_count)->paginate($paginate_count);
            } else {
                $row_archive_all = VideoArchive::LeftJoin("programm_tab","video_archive_tab.programm_id","=","programm_tab.programm_id")->select('video_archive_tab.*', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'), "programm_tab.programm_url_name")->where("video_archive_title_ru", "like", "%" . $request->search_text . "%")->get();
                $row_archive = VideoArchive::LeftJoin("programm_tab","video_archive_tab.programm_id","=","programm_tab.programm_id")->select('video_archive_tab.*', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'), "programm_tab.programm_url_name")->where("video_archive_title_ru", "like", "%" . $request->search_text . "%")->skip($skip_count)->paginate($paginate_count);
            }
        }

        return view('index.search', ['row' => $row, 'search_text' => $request->search_text, 'search_date' => $search_date, 'row_archive' => $row_archive, 'row_archive_all' => $row_archive_all, 'row_all' => $row_all]);
    }

    public function setWeekProgramm(Request $request){
        $weekStart = date('Y-m-d', strtotime(date('Y').'W'.date('W').'1'));
        $weekEnd = date('Y-m-d', strtotime(date('Y').'W'.date('W').'7'));

        $row = TVProgramm::LeftJoin("category_tab","tv_programm_tab.category_id","=","category_tab.category_id")
            ->select("tv_programm_tab.*","category_tab.image as category_image", "category_tab.category_name_kz", "category_tab.category_name_ru", "category_tab.category_name_en")
            ->whereBetween('tv_programm_tab.date', array($weekStart, $weekEnd))
            ->orderBy("date","asc")
            ->orderBy("time","asc")
            ->get();
        return view('index.week-programm',['row' => $row, 'lang' => $request->lang]);
    }

    public function newsArchive(){
        if(App::getLocale() == "kz"){
            $row = NewsArchiveKz::select('*', DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->whereNotNull("news_title_kz")->orderBy("news_id","desc")->paginate(20);
        }
        else{
            $row = NewsArchiveRu::select('*', DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->whereNotNull("news_title_ru")->orderBy("news_id","desc")->paginate(20);
        }
        return view('index.news-archive',['row' => $row]);
    }

    public function newsArchiveView(Request $request){
        $news_url_name = $request->news_url_name;

        if(App::getLocale() == "kz"){
            $row = NewsArchiveKz::select('*', DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->where("news_url_name","=",$news_url_name)->first();
        }
        else{
            $row = NewsArchiveRu::select('*', DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->where("news_url_name","=",$news_url_name)->first();
        }
        return view('index.news-archive-view',['row' => $row]);
    }

    public function menuPage(Request $request){
        $row = Menu::where("menu_id","=",$request->menu_id)->take(5)->first();
        return view('index.menu-page',['row' => $row]);
    }

    public function setProgrammByWeek(Request $request){
//        $row = ProgrammTime::select( DB::raw('DISTINCT(day_id)'))->orderBy("day_id","asc")->get();
        $row = Programm::orderBy("order_num","asc")->get();
        return view('index.all-programms-block', ['row' => $row, 'lang' => $request->lang]);
    }

    public function allVideoArchiveProgrammItemListBlock(Request $request){
        $skip = 0;
        if($request->page > 1){
            $skip = 20*($request->page-1);
        }

        if($request->programm_id > 0) {
            $all_video_archive_programm_row = VideoArchive::LeftJoin('programm_tab', 'video_archive_tab.programm_id', '=', 'programm_tab.programm_id')
                ->select('video_archive_tab.*', 'programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'programm_tab.programm_url_name', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                ->where("video_archive_tab.video_archive_date", "<", $request->year."-12-31")
                ->where("video_archive_tab.video_archive_date", ">", $request->year."-01-01")
                ->where("video_archive_tab.video_archive_title_ru", "!=", "")
                ->where("video_archive_tab.programm_id","=",$request->programm_id)
                ->whereNotNull("video_archive_tab.video_archive_title_ru")
                ->orderByRaw("video_archive_tab.video_archive_date asc")
                ->get();

            $video_archive_programm_row = VideoArchive::LeftJoin('programm_tab', 'video_archive_tab.programm_id', '=', 'programm_tab.programm_id')
                ->select('video_archive_tab.*', 'programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'programm_tab.programm_url_name', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                // ->where("video_archive_tab.video_archive_date", "<", "2016-08-01")
                ->where("video_archive_tab.video_archive_date", "<", $request->year."-12-31")
                ->where("video_archive_tab.video_archive_date", ">", $request->year."-01-01")
                ->where("video_archive_tab.video_archive_title_ru", "!=", "")
                ->where("video_archive_tab.programm_id","=",$request->programm_id)
                ->whereNotNull("video_archive_tab.video_archive_title_ru")
                ->orderByRaw("video_archive_tab.video_archive_date asc")
                ->skip($skip)
                ->take(20)
                ->get();
        }
        else{
            $all_video_archive_programm_row = VideoArchive::LeftJoin('programm_tab', 'video_archive_tab.programm_id', '=', 'programm_tab.programm_id')
                ->select('video_archive_tab.*', 'programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'programm_tab.programm_url_name', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                // ->where("video_archive_tab.video_archive_date", "<", "2016-08-01")
                ->where("video_archive_tab.video_archive_date", "<", $request->year."-12-31")
                ->where("video_archive_tab.video_archive_date", ">", $request->year."-01-01")
                ->where("video_archive_tab.video_archive_title_ru", "!=", "")
                ->whereNotNull("video_archive_tab.video_archive_title_ru")
                ->orderByRaw("video_archive_tab.video_archive_date asc")
                ->get();

            $video_archive_programm_row = VideoArchive::LeftJoin('programm_tab', 'video_archive_tab.programm_id', '=', 'programm_tab.programm_id')
                ->select('video_archive_tab.*', 'programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'programm_tab.programm_url_name', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                // ->where("video_archive_tab.video_archive_date", "<", "2016-08-01")
                ->where("video_archive_tab.video_archive_date", "<", $request->year."-12-31")
                ->where("video_archive_tab.video_archive_date", ">", $request->year."-01-01")
                ->where("video_archive_tab.video_archive_title_ru", "!=", "")
                ->whereNotNull("video_archive_tab.video_archive_title_ru")
                ->orderByRaw("video_archive_tab.video_archive_date asc")
                ->skip($skip)
                ->take(20)
                ->get();
        }
        $last_page = ceil(count($all_video_archive_programm_row)/20);
        if(isset($request->programm_id)){
            $programm_id = $request->programm_id;
        }
        else{
            $programm_id = 0;
        }

        return view('index.all-video-archive-programm-item-list-block',['video_archive_programm_row' => $video_archive_programm_row, 'language_name' => $request->language_name, 'page' => $request->page, 'last_page' => $last_page, 'programm_id' => $programm_id, 'year'=> $request->year]);
    }

    public function alarmProgramm(){
        $row = AlarmProgramm::all();
        if(count($row) > 0){
            foreach($row as $key => $row_item){
                $time = strtotime($row_item['time'] . ":00");
                if($row_item['time_before']*60 + strtotime("now") + 21600 >= $time){
                    $push = new Push();
                    if($row_item->os == 'Android'){
                        $registration_id = array($row_item->registration_id);
                        $param['message'] = 'Ваша программа началась';
                        $param['id'] = $row_item->tv_programm_id;
                        $param['kind'] = 'alarm_programm';
                        $message = $param;
                        $result1 = $push->sendMessageToAndroid($registration_id,$message);
                    }
                    else if($row_item->os == 'iOS'){
                        $registration_id = array($row_item->registration_id);
                        $param['message'] = 'У нас новая новость для вас';
                        $param['id'] = $row_item->tv_programm_id;
                        $param['kind'] = 'alarm_programm';
                        $message = $param;
                        $result1 = $push->sendMessageToIOS($registration_id,$message);
                    }
                }
            }
        }
    }

    public function newsByCategory(Request $request){
        $news_category = NewsCategory::find($request->news_category_id);

        $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en',
                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
            ->where("news_tab.is_active","=","1")
            ->where("news_category_id","=",$request->news_category_id)
            ->orderByRaw("news_tab.date desc")
            ->get();
        return view('index.news-by-category', ['row' => $row, 'news_category' => $news_category]);
    }

    public function sendDelivery(Request $request){
        $messages = array(
            'delivery_email.required' => 'Укажите Email',
            'delivery_email.email' => 'Неправильный формат Email',
            'delivery_email.unique' => 'Подписчик с таким Email уже существует'
        );
        $validator = Validator::make($request->all(), [
            'delivery_email' => 'required|email|unique:delivery_tab'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            return response()->json(['result'=>"false", 'value' => $error[0]]);
        }

        $row_item = new Delivery();
        $row_item->delivery_email = $request->delivery_email;
        if($row_item->save()){
            return response()->json(['result'=>"true"]);
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            return response()->json(['result'=>"true", 'value' => $error[0]]);
        }
    }

    public function programmArchiveByDate(Request $request){
        $skip = 0;
        $page = 1;
        if($request->page > 1){
            $skip = 21*($request->page-1);
            $page = $request->page;
        }

        $all_row = VideoArchive::LeftJoin("programm_tab","video_archive_tab.programm_id","=","programm_tab.programm_id")->select("video_archive_tab.*", DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'), "programm_tab.programm_url_name")->whereBetween('video_archive_tab.video_archive_date', array($request->date_begin, $request->date_end))->where("video_archive_tab.programm_id","=",$request->programm_id)->get();
        $last_page = ceil(count($all_row)/21);

        $row = VideoArchive::LeftJoin("programm_tab","video_archive_tab.programm_id","=","programm_tab.programm_id")->select("video_archive_tab.*", DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'), "programm_tab.programm_url_name")->whereBetween('video_archive_tab.video_archive_date', array($request->date_begin, $request->date_end))->where("video_archive_tab.programm_id","=",$request->programm_id)->skip($skip)->take(21)->get();
        return view('index.programm-archive-by-date', ['row' => $row, 'lang' => $request->lang, 'page' => $page, 'last_page' => $last_page, 'date_begin' => $request->date_begin, 'date_end' => $request->date_end, 'programm_id' => $request->programm_id]);
    }

    public function programmArchiveLast(Request $request){

        $row = VideoArchive::LeftJoin("programm_tab","video_archive_tab.programm_id","=","programm_tab.programm_id")->select("video_archive_tab.*", DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'), "programm_tab.programm_url_name")->where("video_archive_tab.programm_id","=",$request->programm_id)->orderBy('programm_id', 'desc')->take(2)->get();

        return view('index.programm-archive-prime-time', ['row' => $row, 'lang' => $request->lang, 'programm_id' => $request->programm_id]);
    }

    public function error404(){
        $kz_news_row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                            ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
                            ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_kz', 'news_category_tab.news_category_name_en',
                                DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                            ->where("is_main_news","=",1)->where("is_active","=",1)
                            ->take(5)
                            ->orderByRaw("news_tab.is_fix desc")
                            ->orderByRaw("news_tab.date desc")
                            ->get();

        return view('errors.404',['kz_news_row' => $kz_news_row, 'row'=>null]);
        // return view('index.footer_3');
        // $news_url_name = $request->url;
        // $locale = App::getLocale();
        // $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
        //     ->LeftJoin("news_category_tab","news_tab.news_category_id","=","news_category_tab.news_category_id")
        //     ->select('news_tab.*','programm_tab.programm_name_ru', 'programm_tab.programm_name_kz', 'programm_tab.programm_name_en'
        //         ,'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_ru', 'news_category_tab.news_category_name_en',
        //         'news_tab.date as date2', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
        //     ->where("news_tab.is_active","=","1")
        //     ->where("news_tab.news_url_name","=",$news_url_name)
        //     ->first();
        

        // $yesterday = Date::parse($row->date2)->sub('1 day');
        // // dd($yesterday);
        // $row_next = News::select('news_tab.*')
        //     ->where("news_tab.is_active","=","1")
        //     ->where("news_tab.news_title_{$locale}","<>","")
        //     // ->whereBetween("news_tab.date", [$date->format('Y-m-d'), $row->date])
        //     ->where("news_tab.date", '<', $row->date2)
        //     // ->where("news_tab.date", '>=', $yesterday)
        //     ->orderBy("news_tab.date", "DESC")
        //     ->first();
        //     // ->get();
        // dd($row_next);
    }

    public function problem(){
        return view('errors.problem');
    }

    // Added from Av.Almaty.tv
    public function saveBlogger(Request $request){
        $messages = array(
            'fio.required' => 'Укажите ФИО',
            'email.required' => 'Укажите Email',
            'username.required' => 'Укажите Login',
            'email.email' => 'Неправильный формат Email',
            'email.unique' => 'Пользователь с таким Email уже существует',
            'username.unique' => 'Пользователь с таким Login-ом уже существует',
            'password.confirmed' => 'Пароли не совпадают',
            'password.min' => 'Пароль должен быть не менее 6 символов'
        );

        $validator = Validator::make($request->all(), [
            'fio' => 'required',
            'email' => 'required|email|unique:user_tab',
            'username' => 'required|unique:user_tab',
            'password'  =>  'required|min:6|confirmed'
        ], $messages);


        if ($validator->fails()) {
            // return $validator->errors()->all();
            return back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $user_item = new Users();


        if($request->hasFile('FileAttachment')){
            $file = $request->FileAttachment;
            $file_name = time() . "_user.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;

            Storage::disk('user_photo')->put($file_name,  File::get($file));
            $user_item->image = $file_name;
        }

        $user_item->fio = $request->fio;
        $user_item->email = strtolower($request->email);
        $user_item->role_id = 4;
        $user_item->is_blocked = false;
        $user_item->is_first_login = false;
        $user_item->quote = $request->quote;
        $user_item->username = strtolower($request->username);
        $user_item->password = Hash::make($request->password);
        $user_item->save();

        //return redirect('/admin/user-list');
        return redirect('/blogs')->with('status', 'registered');

    }

    public function login(Request $request)
    {

        if(isset($request->email)){
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){

                $user_item = Users::where("email","=",$request->email)->first();
                if(count($user_item) > 0){
                    $offset= strtotime("+6 hours 0 minutes");
                    $user_item->date_last_login = date("Y-m-d H:i:s",$offset);
                    $user_item->is_first_login = 0;
                    $user_item->save();
                }

                return back();
            }
            elseif (Auth::attempt(['username' => $request->email, 'password' => $request->password])){
                $user_item = Users::where("username","=",$request->email)->first();
                if(count($user_item) > 0){
                    $offset= strtotime("+6 hours 0 minutes");
                    $user_item->date_last_login = date("Y-m-d H:i:s",$offset);
                    $user_item->is_first_login = 0;
                    $user_item->save();
                }

                return back();
            }
            else{
                return view('admin.login', [
                    'email' => $request->email,
                    'error' => 'Неправильный логин или пароль'
                ]);
            }
        }
        else{
            return view('admin.login', [
                'email' => '',
                'error' => ''
            ]);
        }
    }

    public function feed(){
        $feed = App::make("feed");

        $feed->setCache(60, 'laravelFeedKey');

        if (!$feed->isCached())
        {
            $date = new Date('-7 day');
            $posts = \DB::table('news_tab')->where('created_at', '>', $date->toDateTimeString())->orderBy('created_at', 'desc')->get();

           // set your feed's title, description, link, pubdate and language
           $feed->title = 'Телеканал "Алматы" онлайн';
           $feed->description = 'Новости, аналитика, программы, кино и музыка на сайте, в социальных сетях, медиаканалах и iOS/ Android-приложениях Алматы TV';
           $feed->logo = 'https://almaty.tv/css/img/logo.png';
           $feed->link = 'https://almaty.tv';
           $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
           $feed->pubdate = $posts[0]->created_at;
           $feed->lang = 'ru';
           $feed->author = 'https://almaty.tv';
           $feed->setShortening(true); // true or false
           $feed->setTextLimit(100); // maximum length of description text

           foreach ($posts as $post)
           {
                if(!empty($post->news_title_ru)){
                    // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                    $feed->add($post->news_title_ru, $post->news_id, url('news/news/'. $post->news_url_name), $post->created_at, $post->news_meta_desc_ru, $post->news_text_ru);
                }   
           }
        }

        $feed->ctype = "text/xml";

        return $feed->render('rss');
    }

    public function feedKz(){
        $feed = App::make("feed");

        // $feed->setCache(60, 'laravelFeedKey');

        // if (!$feed->isCached())
        // {
           $posts = \DB::table('news_tab')->orderBy('created_at', 'desc')->get();

           // set your feed's title, description, link, pubdate and language
           $feed->title = '«Алматы» телеарнасы онлайн';
           $feed->description = 'Жаңалықтар, аналитика, бағдарламалар, кино және музыка Алматы TV-ның сайтында, әлеуметтік желілерде, БАҚ арналарында және iOS/ Android-қосымшаларында';
           $feed->logo = 'https://almaty.tv/css/img/logo.png';
           $feed->link = 'https://almaty.tv';
           $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
           $feed->pubdate = $posts[0]->created_at;
           $feed->lang = 'kz';
           $feed->author = 'https://almaty.tv';
           $feed->setShortening(true); // true or false
           $feed->setTextLimit(100); // maximum length of description text

           foreach ($posts as $post)
           {
                if(!empty($post->news_title_kz)){
                    // set item's title, author, url, pubdate, description, content, enclosure (optional)*
                    $feed->add(utf8_encode($post->news_title_kz), $post->news_id, url('news/news/'.$post->news_url_name), $post->created_at, utf8_encode($post->news_meta_desc), utf8_encode($post->news_text_kz));
                }
           }
        // }
        $feed->ctype = "text/xml";

        return $feed->render('rss');
    }

    public function aboutMenu(Request $request){
        $locale = App::getLocale();
        $columns = ["name_{$locale} as name","menu_id","id","parent_id","order","url","type","created_at"];
        if(!$request->about){
            $current = DB::table('menu_items')
                ->select($columns)
                ->where('menu_id', 2)->orderBy('order')
                ->first();
        } else{
            $current = DB::table('menu_items')
                ->select($columns)
                ->where('menu_id', 2)->where('url', $request->about)
                ->first();
        }
        if($current){    
            $row = DB::table('menu_items')->select($columns)->where('menu_id', 2)->orderBy('order')->get();
            $row = collect($row); 
            $main = $row->filter(function ($value, $key) {
                return !$value->parent_id;
            });

            $view = "";
            if($current->type == 'page'){
                $content = DB::table('pages')->select(["text_{$locale} as text","menu_item_id","id","created_at"])
                    ->where('menu_item_id', $current->id)
                    ->orderBy('created_at', 'DESC')
                    ->first();
                $view = 'page';
            } else if($current->type == 'employer'){
                $content = Employer::selectLocaled($locale)->where('menu_item_id', $current->id)->orderBy('order')->get();
                $view = 'employer';
            } else if($current->type == 'document'){
                $content = DB::table('documents')
                    ->select(["name_{$locale} as name","menu_item_id","id","created_at","document","type"])
                    ->where('menu_item_id', $current->id)
                    ->orderBy('created_at', 'ASC')->get();
                $view = 'document';
            }
            return view('index.about.'.$view, [ 'row' => $row, 'main' => $main, 'content'=> $content, 'current'=>$current]);
        } else {
            abort(404);
        }
    }
}