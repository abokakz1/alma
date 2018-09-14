<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Programm;
use App\Models\EvCategory;
use App\Models\Blog;
use App\Models\News;
use App\Models\View;
use App\Models\Jurnalist;
use App\Models\Media;
use Jenssegers\Date\Date;
use DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Mail;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
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

    public function index(Request $request){
        $locale = App::getLocale();
        $search = "";
        if($request->search){
            $search = $request->search;
            $events = Event::selectLocaled($locale)->with(['blogers' => function ($query) {
                $query->select(['user_id', 'fio', 'image','username']);
            }, 'categories'=> function($query) use ($locale){ 
                $query->select(['category_name_'. $locale . ' as name', 'color']);
            }, 'media'=> function($query){ 
                $query->select('*');
            } ])->where('event_title', 'LIKE', '%'.$request->search.'%')->where('published', true)->orderBy('created_at', 'desc')->get();               
        }else{
            $events = Event::selectLocaled($locale)->with(['blogers' => function ($query) {
                $query->select(['user_id', 'fio', 'image','username']);
            }, 'categories'=> function($query) use ($locale){ 
                $query->select(['category_name_'. $locale . ' as name', 'color']);
            }, 'media'=> function($query){ 
                $query->select('*');
            }])->where('published', true)->where('date_start', '>=' , Date::now())->orderBy('created_at', 'desc')->take(3)->get();
        }

        foreach ($events as $i_event){
            $i_event->date_start = Date::parse($i_event->date_start)->format('j F');
        }
        
        $categories = EvCategory::selectLocaled($locale)->get();

        $top_events = Event::select('event_id')->where('is_main_event', true)->where('published', true)->where('date_start', '>=' , Date::now())->get();
        $top_event = null;
        if(count($top_events)>0){
            $top_event_id = $top_events[rand(0, count($top_events)-1 )]->event_id;
            $top_event = Event::selectLocaled($locale)->with(['blogers' => function ($query) {
                    $query->select(['user_id', 'fio', 'image','username']);
                }, 'categories'=> function($query) use ($locale){ 
                    $query->select(['category_name_'. $locale . ' as name']);
                }, 'media'=> function($query){ 
                    $query->select('*');
                }])->find($top_event_id);
        }
        

        $blog = Blog::selectLocaled($locale)
            ->where('is_main_blog', true)
            ->where(function ($query){
                $query->where(function ($query){
                    $query->where('is_has_foto', true)
                        ->whereNotNull('image');
                });
            })
            ->with(['author'=>function($query){
                $query->select(['user_id', 'fio', 'image','username']);
            }])
            ->orderBy('date', 'DESC')
            ->first();
            // ->get();
        // dd($blogs);
        // $blog = $blogs[rand(0, count($blogs)-1 )];

        $news = News::select('news_tab.*', DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
            ->where("news_tab.is_main_news","=",1)->where("news_tab.is_active","=",1)
            ->where("news_tab.news_title_".$locale,"!=","")
            // ->take(5)
            ->orderByRaw("news_tab.is_fix desc")
            ->orderByRaw("news_tab.date desc")
            ->first();
            // ->get();

        // $news = $news[rand(0, count($news)-1 )];

        $programs = Programm::select('programm_id', 'programm_name_ru')->whereIn('programm_id', [224, 225, 173, 10,178,200, 110, 203])->get();

        $live = DB::select("SELECT t.*, t1.image as category_image, t1.category_name_kz, t1.category_name_ru, t1.category_name_en, t2.programm_url_name from tv_programm_tab t left join category_tab t1 on t.category_id = t1.category_id left join programm_tab t2 on t.tv_programm_programm_id = t2.programm_id where t.date = date_format(now(), '%Y-%m-%e') and   unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) <= unix_timestamp(now()) and   case when t.time_end like '00:%' THEN unix_timestamp(concat(t.date,' ', CASE WHEN t.time_end like '00:%' THEN concat('23',substr(t.time_end,3)) ELSE t.time_end END))+3600 >= unix_timestamp(now()) else unix_timestamp(concat(t.date,' ', t.time_end)) >= unix_timestamp(now()) end order by unix_timestamp(concat(t.date,' ',  CASE WHEN t.time='00:00:00' THEN '24:00:00' ELSE t.time END)) desc limit 1");
        
        $show_modal = 0;
        if($request->from_blog == 1){
            $show_modal = 1;
        }

        return view('index.events-demo',[
            'events' => $events, 'categories'=>$categories, 'top_event'=>$top_event ,'blog'=>$blog, 'news'=>$news, 'live'=>$live, 'search'=>$search, 'show_modal'=>$show_modal, 'programs' => $programs
        ]);
    }

    public function show($url){
        $locale = App::getLocale();
        
        $event = Event::selectLocaled($locale)->with(['blogers' => function ($query) {
            $query->select(['user_id', 'fio', 'image','username']);
        }, 'categories'=> function($query) use ($locale){ 
                $query->select(['category_name_'. $locale . ' as name']);
            }, 'media'=> function($query){ 
                $query->select('*');
            }])->where('event_url_name', $url)->first();
        
        // $event->date_start = Date::parse($event->date_start)->toFormattedDateString();
        // $event->date_end = Date::parse($event->date_end)->toFormattedDateString();
        $event->date_start = Date::parse($event->date_start)->format('H:i j F');
        if($event->date_end){
            $event->date_end = Date::parse($event->date_end)->format('H:i j F');    
        }
        
        // dd($event);
        // dd($event->blogers->contains(33));
        return view('index.events-open-demo', [
            'event' => $event
        ]);
    }

    // STORE THE EVENT
    public function store(Request $request){
    	// dd($request->all());
        // return response()->json($request->all(), 400);
        // $result['status'] = false;
        // return response()->json(['row' => $request->all(), 'result' => $result]);
        $messages = array(
            'event_title.required' => trans('messages.valid_title'),
            'event_text.required' => trans('messages.valid_tex'),
            'date_start.required' => trans('messages.valid_date_start'),
            'lat.required' => trans('messages.valid_lat'),
            'lng.required' => trans('messages.valid_lng'),
            // 'organizer_name.required' => trans('messages.valid_name'),
            // 'organizer_email.required' => trans('messages.valid_email_req'),
            // 'organizer_email.email' => trans('messages.valid_email'),
            'organizer_number.required' => trans('messages.valid_number'),
        );

        $validator = Validator::make($request->all(), [
            'event_title' => 'required',
            'event_text' => 'required',
            'date_start' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            // 'organizer_name' => 'required',
            // 'organizer_email' => 'required|email',
            'organizer_number' => 'required'    
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            // return view('index.events', [ 'row' => $request, 'result' => $result ]);
            return response()->json(['result' => $result ]);
        }

        $event = new Event();

        $event->event_title = $request->event_title;
        $event->event_text = $request->event_text;

        $event->date_start = date('Y-m-d H:i:s', strtotime($request->date_start .' '. $request->time));
        if($request->date_end != "" && $request->date_end != null){
            $event->date_end = date('Y-m-d', strtotime($request->date_end . ' ' . $request->time_end));
        }
        
        $event->price = ($request->price==null ? 0 : $request->price);
        $event->is_main_event = $request->is_main_event;
        $event->address = $request->address;
        $event->lat = $request->lat;
        $event->lng = $request->lng;
        
        $event->in_calendar = ($request->in_calendar==null ? 0 : 1);
        $event->organizer_name = $request->organizer_name;
        $event->organizer_email = $request->organizer_email;
        $event->organizer_number = $request->organizer_number;

        $event->event_url_name = $this->cyr2lat($request->event_title);

        $url = Event::where('event_url_name', $event->event_url_name)->get();
        if(count($url)>0){
            $event->event_url_name = $event->event_url_name . '_' . rand(99,999);
        }

        $media_id = $request->media_id;
        if($event->save()){
            for($j=0; $j<count($request->category_id); $j=$j+1){
                $event->categories()->attach($request->category_id[$j]["value"]);
            }
            for($i=0; $i<count($media_id); $i=$i+1){
            	Media::where('media_id', $media_id[$i]['media_id'])->update(['event_id' => $event->event_id]);
            }
            // $this->deleteEmptyMedia();
            
            if($request->programs && count($request->programs) >0){
                $prog_ids = array_pluck($request->programs, 'value');
                $jurnalists = Jurnalist::whereIn('programm_id', $prog_ids)->get();
                foreach ($jurnalists as $jurnalist) {
                    $mess = "Здравствуйте, организаторы Вас приглашает на событие https://almaty.tv/events/" . $event->event_url_name . "<br>";
                    $email = $jurnalist->email;

                    Mail::send(['html' => 'admin.email'], ['text' => $mess], function($mess) use ($email)
                    {
                        $mess->to($email)->subject("Приглашение на событие");
                    });
                }
            }

            $result['status'] = true;
            return response()->json(['row' => $request->all(), 'result' => $result]);
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            // return view('index.events', [ 'row' => $request, 'result' => $result ]);
            return response()->json(['result' => $result ]);
        }
    }

    public function cyr2lat ($text) {

        $cyr2lat_replacements = array (
            "А" => "a","Б" => "b","В" => "v","Г" => "g","Д" => "d",
            "Е" => "e","Ё" => "yo","Ж" => "dg","З" => "z","И" => "i",
            "Й" => "y","К" => "k","Л" => "l","М" => "m","Н" => "n",
            "О" => "o","П" => "p","Р" => "r","С" => "s","Т" => "t",
            "У" => "u","Ф" => "f","Х" => "kh","Ц" => "ts","Ч" => "ch",
            "Ш" => "sh","Щ" => "csh","Ъ" => "","Ы" => "i","Ь" => "",
            "Э" => "e","Ю" => "yu","Я" => "ya", 
            "Қ" => "k", "Ә" => "a", "І" => "i", "Ң" => "n", "Ғ" => "g", 
            "Ү" => "u", "Ұ" => "u", "Қ" => "k", "Ө" => "o",

            "а" => "a","б" => "b","в" => "v","г" => "g","д" => "d",
            "е" => "e","ё" => "yo","ж" => "dg","з" => "z","и" => "i",
            "й" => "y","к" => "k","л" => "l","м" => "m","н" => "n",
            "о" => "o","п" => "p","р" => "r","с" => "s","т" => "t",
            "у" => "u","ф" => "f","х" => "kh","ц" => "ts","ч" => "ch",
            "ш" => "sh","щ" => "sch","ъ" => "","ы" => "y","ь" => "",
            "э" => "e","ю" => "yu","я" => "ya",
            "(" => "", ")" => "", "," => "", "." => "",

            "-" => "-"," " => "-", "+" => "", "®" => "", "«" => "", "»" => "", '"' => "", "`" => "", "&" => "", "#" => "", ":" => "", ";" => "", "/" => "",
            "ә" => "a", "і" => "i", "ң" => "n", "ғ" => "g", "ү" => "u", "ұ" => "u", "қ" => "k", "ө" => "o", "Һ" => "h", "һ" => "h", "?" => "", "%" => "percent"

        );
        $a = str_replace("---","-",strtolower (strtr (trim($text),$cyr2lat_replacements)));
        $b = str_replace("--","-",$a);
        return $b;
    }

    public function loadMore(Request $request){
        $locale = App::getLocale();
        $last = $request->last;
        $events = Event::selectLocaled($locale)->with(['blogers' => function ($query) {
            $query->select(['user_id', 'fio', 'image','username']);
        }, 'categories'=> function($query) use ($locale){ 
            $query->select(['category_name_'. $locale . ' as name', 'color']);
        }, 'media'=> function($query){ 
                $query->select('*');
            } ])->where('published', true)->where('date_start', '>=' , Date::now())->orderBy('created_at', 'desc')->skip($last)->take(2)->get();

        foreach ($events as $i_event){
            $i_event->date_start = Date::parse($i_event->date_start)->format('j F');
            
            if($i_event->price==0){
                $i_event->price = trans('messages.event_free');
            } else {
                $i_event->price = trans('messages.event_entry') . ' ' . $i_event->price . 'тг';
            }

            $blogers_text = "";
            if(count($i_event->blogers)>0){
                for( $j=0; $j<4 && $j< count($i_event->blogers); $j++){
                    $b_img = '/user_photo/user.png';
                    if($i_event->blogers[$j]->image){
                        $b_img = '/user_photo/'. $i_event->blogers[$j]->image;
                    }
                    $blogers_text .= '<img src="'.$b_img.'" title="'.$i_event->blogers[$j]->fio.'" width="20">';        
                }
            }
            if( count($i_event->blogers) > 4){
                $blogers_text .= '<span>+ '.(count($i_event->blogers)-4).' '.trans('messages.bloggers').'</span>';
            }
            $i_event->blogers_text = $blogers_text;
        }
        return response()->json(['events' => $events, 'last'=>$last+2, 'result'=> true]);
    }

    public function getDates(Request $request){
        // return response()->json($request->date, 500);
        $date = new Date($request->date);
        $month = $date->month;
        $category_id = (int) $request->category_id;
        $response = [
            'events' => []
        ];

        if($request->type == 2){
            $ids = $request->ids;
            $locale = App::getLocale();
            $events = Event::selectLocaled($locale)->with(['blogers' => function ($query) {
                $query->select(['user_id', 'fio', 'image','username']);
            }, 'categories'=> function($query) use ($locale){ 
                $query->select(['category_name_'. $locale . ' as name', 'color']);
            }, 'media'=> function($query){ 
                    $query->select('*');
                } ])->where('published', true)
            ->whereIn('event_id', $ids)
            ->orderBy('created_at', 'desc')
            ->get();

            foreach ($events as $i_event){
                $i_event->time_start = Date::parse($i_event->date_start)->format('H:i');
                $i_event->date_start = Date::parse($i_event->date_start)->format('j F');
                
                if($i_event->price==0){
                    $i_event->price = trans('messages.event_free');
                } else {
                    $i_event->price = trans('messages.event_entry') . ' ' . $i_event->price . 'тг';
                }

                $blogers_text = "";
                if(count($i_event->blogers)>0){
                    for( $j=0; $j<4 && $j< count($i_event->blogers); $j++){
                        $b_img = '/user_photo/user.png';
                        if($i_event->blogers[$j]->image){
                            $b_img = '/user_photo/'. $i_event->blogers[$j]->image;
                        }
                        $blogers_text .= '<img src="'.$b_img.'" title="'.$i_event->blogers[$j]->fio.'" width="20">';        
                    }
                }
                if( count($i_event->blogers) > 4){
                    $blogers_text .= '<span>+ '.(count($i_event->blogers)-4).' '.trans('messages.bloggers').'</span>';
                }
                $i_event->blogers_text = $blogers_text;
            }

            $response['events'] = $events;
            return response()->json($response);
        }

        if($category_id){
            $event_db = EvCategory::find($category_id)->events()->where('event_tab.published', true)->whereRaw('MONTH(date_start) = ?',[$month])->get();
        } else{
            $event_db = Event::where('event_tab.published', true)->whereRaw('MONTH(date_start) = ?',[$month])->get();
        }

        foreach ($event_db as $event) {
            $categoryName = '';
            // $color = '';
            foreach ($event->categories as $category) {
                $categoryName .= ($categoryName ? ', ' . $category->category_name_ru : $category->category_name_ru);
                // $color += hexdec($category->color);
            }
            // $color = dechex($color);
            $eventDate = new Date($event->date_start);
            $response['events'][] = [
                'eventName' => $event->event_title,
                'calendar' => $categoryName,
                'color' => '68003d',//$color,
                'date' => $eventDate->day,
                'event_id' => $event->event_id
            ];
        }

        return response()->json($response);
    }

    public function willGo(Request $request){
        $event = Event::find($request->id);
        $contains = false;
        if($event->blogers->contains(Auth::user()->user_id)){
            $event->blogers()->detach(Auth::user()->user_id);
        } else {
            $event->blogers()->attach(Auth::user()->user_id);
            $contains = true;
        }
        
        $response = [
            'contains' => $contains
        ];
        return response()->json($response);
    }

    public function fileUpload(Request $request){
        
        if($request->hasFile('file')){
            $file = $request->file('file');
            $filename = 'test_' . Str::random(20);
            $fullPath = $filename.'.'.$file->getClientOriginalExtension();

            $image = Image::make($file)
                ->resize(960, null, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($file->getClientOriginalExtension(), 75);

            Storage::disk('event_photo')->put($fullPath, (string) $image );

            $media = new Media();
            $media->link = $fullPath;
            $media->type = "img";
            $client_filename = $file->getClientOriginalName();

            if($media->save()){
                return response()->json(['media_id' => $media->media_id, 'media_name'=> $client_filename], 200);
            }
        }
        
        return response()->json(['media_id' => null], 400);
    }

    public function fileDelete(Request $request){
        $media = Media::find($request->media_id);

        if(count($media)>0){
            $this->deleteFile("event_photo", $media->link);
            $media->delete();
            return response()->json(['result' => true]);  
        }
        return response()->json(['result' => false]);
    }

    public function deleteEmptyMedia(){
    	$medias = Media::where('event_id', 0)->get();
    	foreach ($medias as $media) {
    	    $this->deleteFile("event_photo", $media->link);
            $media->delete();
    	}
    }

    public function deleteFile($path,$old_file_name){
        if(strlen($old_file_name) > 0 && File::exists(public_path( $path . '/' . $old_file_name))){
            return Storage::disk($path)->delete($old_file_name);
        }
        return false;
    }
}
