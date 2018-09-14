<?php

namespace App\Http\Controllers\Index;

use App\Models\Advertisement;
use App\Models\BlogTag;
use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\Users;
use App\User;
use Illuminate\Support\Facades\App;
use App\Models\Blog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Event;
use App\Models\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    public function __construct()
    {
        if (App::getLocale() == 'kz'){
            Date::setLocale('kk');
        }
        else {
            Date::setLocale(App::getLocale());
        }
    }

    public function index(){
        $locale = App::getLocale();
        $featured_blogs = Blog::selectLocaled($locale)
            ->where('is_main_blog', true)
            ->orderBy('date', 'DESC')
            ->take(7)
            ->get();


        $popular_blogs = Blog::selectLocaled($locale)
            ->orderBy('view_count', 'DESC')
            ->orderBy('date', 'DESC')
            ->take(4)
            ->get();

        $new_blogs = Blog::selectLocaled($locale)
            ->orderBy('date', 'DESC')
            ->take(4)
            ->get();

        $ads1 = Advertisement::where('is_main_advertisement', 4)
            ->where('is_active', true)
            ->orderByRaw("RAND()")
            ->first();
        
        $ads2 = Advertisement::where('is_main_advertisement', 5)
            ->where('is_active', true)
            ->orderByRaw("RAND()")
            ->first();

        $bloggers = [];
        if(Auth::check()){
            $bloggers = Users::select(DB::raw('COUNT(blog_tab.blog_id) as amount'),'blog_tab.author_id', 'user_tab.*')
            ->LeftJoin("blog_tab","blog_tab.author_id","=","user_tab.user_id")
            ->where('user_tab.user_id', '<>', Auth::user()->user_id)
            ->groupBy('blog_tab.author_id')->orderByRaw('amount DESC')->take(5)->get();

        } else{
            $bloggers = Users::select(DB::raw('COUNT(blog_tab.blog_id) as amount'),'blog_tab.author_id', 'user_tab.*')
            ->LeftJoin("blog_tab","blog_tab.author_id","=","user_tab.user_id")
            ->where('blog_tab.is_active_ru', true)->orWhere('blog_tab.is_active_kz', true)
            ->groupBy('blog_tab.author_id')->orderByRaw('amount DESC')->take(5)->get();
        }

        $events = Event::selectLocaled($locale)->with(['media'=> function($query){ 
                $query->select('*');
            }])->where('published', true)->where('date_start', '>=' , Date::now())->orderBy('date_start', 'desc')->take(10)->get();

        foreach ($events as $i_event){
            $i_event->time_start = Date::parse($i_event->date_start)->format('H:i');
            $i_event->date_start = Date::parse($i_event->date_start)->format('j F');
        }

        $events_news = DB::select("SELECT * FROM ( 
            SELECT news_id as id,
            created_at, 
            news_url_name as url,
            date,
            image,
            news_title_".$locale." as title, 1 as which
            FROM news_tab WHERE is_main_news = 1 AND is_active = 1 AND news_title_".$locale." != ''
            UNION ALL 
            SELECT event_tab.event_id as id, 
            event_tab.created_at,
            event_tab.event_url_name as url,
            event_tab.date_start as date,
            media_tab.link as image,
            event_tab.event_title as title, 2 as which
            FROM event_tab LEFT JOIN media_tab ON event_tab.event_id=media_tab.event_id 
            
            INNER JOIN(
                SELECT event_id, MIN(media_id) as media_id
                FROM media_tab
                GROUP BY event_id
            ) b ON media_tab.event_id = b.event_id AND
                    media_tab.media_id = b.media_id
            
            WHERE event_tab.published = 1 AND event_tab.is_main_event=1 AND event_tab.date_start > NOW()
        ) AS A ORDER BY created_at DESC LIMIT 4");
        foreach ($events_news as $ev_news){
            $ev_news->date = Date::parse($ev_news->date)->format('d.m.Y');//->format('j F');
            if($ev_news->which==1){
                $ev_news->url = '/news/news/'.$ev_news->url;
            } else{
                $ev_news->url = '/events/'.$ev_news->url;
            }
        }
        $kz_news_row = $events_news;
        // dd($kz_news_row);
        
        return view('index.blogs', compact('featured_blogs', 'popular_blogs', 'new_blogs', 'ads1', 'ads2', 'bloggers', 'events', 'kz_news_row'));
    }

    public function getPost(Request $request) {
        $locale = App::getLocale();
        $data = [];
        if ($request->type == 'popular') {
            $data = Blog::selectLocaled($locale)
            ->orderBy('view_count', 'DESC')
            ->orderBy('date', 'DESC')
            ->skip($request->count)
            ->take(1)
            ->first();
        } elseif ($request->type == 'new') {
             $data = Blog::selectLocaled($locale)
            ->orderBy('date', 'DESC')
            ->skip($request->count)
            ->take(1)
            ->first();
        } elseif ($request->type == 'bloggers') {
            if(Auth::check()){
                $data = Users::select(DB::raw('COUNT(blog_tab.blog_id) as amount'),'blog_tab.author_id', 'user_tab.*')
                ->LeftJoin("blog_tab","blog_tab.author_id","=","user_tab.user_id")
                ->where('user_tab.user_id', '<>', Auth::user()->user_id)
                ->groupBy('blog_tab.author_id')->orderByRaw('amount DESC')->skip($request->count)
            ->take(1)->first();

            } else{
                $data = Users::select(DB::raw('COUNT(blog_tab.blog_id) as amount'),'blog_tab.author_id', 'user_tab.*')
                ->LeftJoin("blog_tab","blog_tab.author_id","=","user_tab.user_id")
                ->where('blog_tab.is_active_ru', true)->orWhere('blog_tab.is_active_kz', true)
                ->groupBy('blog_tab.author_id')->orderByRaw('amount DESC')->skip($request->count)
            ->take(1)->first();
            }

            return view('index.blogger-card', ['blogger' => $data]);
        }

        return view('index.blog-card', ['blog' => $data]);

    }


    public function single($url){
        $locale = App::getLocale();
        $recommended_blogs = [];
        $blog = null;
        if ($blog) {
            if ($blog->is_active_kz || $blog->is_active_ru) {
                if($locale == 'ru'){
                    if($blog->blog_title_ru != ''){
                        $blog->id = $blog->blog_id;
                        $blog->title = $blog->blog_title_ru;
                        $blog->text = $blog->blog_text_ru;
                        $blog->url = $blog->blog_url_name;
                        $blog->video = $blog->video_url;
                    } else{
                        $blog->id = $blog->blog_id;
                        $blog->title = $blog->blog_title_kz;
                        $blog->text = $blog->blog_text_kz;
                        $blog->url = $blog->blog_url_name;
                        $blog->video = $blog->video_url;
                    }
                } else{
                    if($blog->blog_title_kz != ''){
                        $blog->id = $blog->blog_id;
                        $blog->title = $blog->blog_title_kz;
                        $blog->text = $blog->blog_text_kz;
                        $blog->url = $blog->blog_url_name;
                        $blog->video = $blog->video_url;
                    } else{
                        $blog->id = $blog->blog_id;
                        $blog->title = $blog->blog_title_ru;
                        $blog->text = $blog->blog_text_ru;
                        $blog->url = $blog->blog_url_name;
                        $blog->video = $blog->video_url;
                    }
                }
            }
            else {
                abort(404);   
            }
        } else {
            $blog = Blog::selectLocaled($locale)->where('blog_url_name', $url)->first();
            if (!$blog) {
                abort(404); 
            }
        }

        if($blog){
            // $blog->date = Date::parse($blog->date)->toFormattedDateString();

            $count = $blog->view_count + 1;
            Blog::where('blog_url_name', $url)->update(['view_count' => $count]);
            
            $rec_array = collect([]);
            $recommended_blogs = Blog::selectLocaled($locale)->where('author_id', $blog->author_id)->where('blog_id', '<>', $blog->id)->orderBy('created_at', 'DESC')->take(4)->get();
            
            foreach ($recommended_blogs as $rc_blog) {
                $rec_array->push($rc_blog);
            }

            if (count($rec_array)<4){
                $alternativeBlog = Blog::where('blog_url_name', $url)->first();

                $tags = $alternativeBlog->tags()->pluck('blog_tag_tab.tag_id');
                $ids = [];
                if(count($tags)>0){
                    $ids = BlogTag::whereIn('tag_id', $tags)->pluck('blog_id');
       
                    $rec_blogs = Blog::selectLocaled($locale)->where('author_id', '<>', $blog->author_id)->whereIn('blog_id', $ids)->get();
                    foreach ($rec_blogs as $o_blog){
                        $rec_array->push($o_blog);
                    }
                }
                if(count($rec_array)<4){
                    $rec_blogs = Blog::selectLocaled($locale)->where('author_id', '<>', $blog->author_id)->whereNotIn('blog_id', $ids)->orderByRaw("RAND()")->take(4)->get();
                    foreach ($rec_blogs as $o_blog){
                        $rec_array->push($o_blog);
                    }
                }
            }

            $recommended_blogs = $rec_array->chunk(4)[0];

            $ads1 = Advertisement::where('is_main_advertisement', 4)
                ->where('is_active', true)
                ->orderByRaw("RAND()")
                ->first();

            $ads2 = Advertisement::where('is_main_advertisement', 5)
                ->where('is_active', true)
                ->orderByRaw("RAND()")
                ->first();        
        } else{
            $recommended_blogs = Blog::selectLocaled($locale)
                ->orderBy('date', 'DESC')
                ->take(4)
                ->get();
        }

        return view('index.blog', compact('blog', 'recommended_blogs','ads1', 'ads2'));
    }

    public function blogger($email){
        $blogger = Users::where('username', $email)->orWhere('email', $email)->first();
        return view('index.blogger', compact('blogger'));
    }

    public function getList(Request $request){
        $list = [];
        $locale = App::getLocale();
        if($request->id==2){
            $list = DB::select("SELECT event_tab.event_id as id, 
            event_tab.created_at,
            event_tab.event_url_name as url,
            event_tab.date_start as date,
            media_tab.link as image,
            event_tab.event_title as title, 2 as which
            FROM event_tab LEFT JOIN media_tab ON event_tab.event_id=media_tab.event_id 
            
            INNER JOIN(
                SELECT event_id, MIN(media_id) as media_id
                FROM media_tab
                GROUP BY event_id
            ) b ON media_tab.event_id = b.event_id AND
                    media_tab.media_id = b.media_id
            
            WHERE event_tab.published = 1 AND event_tab.date_start > NOW()
            ORDER BY created_at DESC LIMIT 4");
        } else{
            $list = News::select(DB::raw('news_id as id'),DB::raw('news_title_'. $locale.' as title'), 'image',DB::raw('news_url_name as url'), 'date', DB::raw('1 as which'))
                ->where("is_active","=",1)->where('news_title_'. $locale, '<>', "")
                ->take(4)
                ->orderByRaw("news_tab.is_fix desc")
                ->orderByRaw("news_tab.date desc")
                ->get();
        }
        foreach ($list as $l){
            $l->date = Date::parse($l->date)->format('d.m.Y');//->format('j F');
            if($l->which==1){
                $l->url = '/news/news/'.$l->url;
                $l->view = count(View::where("news_id","=",$l->id)->get());
            } else{
                $l->url = '/events/'.$l->url;
            }
        }

        return response()->json(['result' => true, 'list' => $list]);
    }
}