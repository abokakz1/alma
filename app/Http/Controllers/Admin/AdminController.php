<?php

namespace App\Http\Controllers\Admin;

use App\Models\Delivery;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\News;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Programm;
use App\Models\TVProgramm;
use App\Models\ProgrammCategory;
use App\Models\Category;
use App\Models\Vacancy;
use App\Models\VideoArchive;
use App\Models\Role;
use App\Models\JobResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Helpers;
use Auth;
use Hash;
use Mail;
use DB;
use App\Models\ProgrammTime;
use App\Models\Tag;
use App\Models\NewsTag;
use App\Models\Advertisement;
use App\Models\Push;
use App\Models\NewsCategory;
use App\Models\Menu;
use App\Models\ConstTVProgramm;
use App\Models\NewsArchiveKz;
use App\Models\NewsArchiveRu;
use App\Models\ArchiveNewsTag;
use App\Models\TranslationLink;
use App\Models\Employer;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use App\Models\Favorite;
use Jenssegers\Date\Date;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::check()){
            return redirect('/admin/index');
        }

        if(isset($request->email)){
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $user_item = Users::where("email","=",$request->email)->first();
                $is_first_login = $user_item->is_first_login;
                if(count($user_item) > 0){
                    $offset= strtotime("+6 hours 0 minutes");
                    $user_item->date_last_login = date("Y-m-d H:i:s",$offset);
                    $user_item->is_first_login = 0;
                    $user_item->save();
                }


                if($is_first_login == 1){
                    $error[0] = "В целях безопасности просим Вас изменить пароль после первого входа!";
                    $result['value'] = $error;
                    $result['status'] = false;
                    return view('admin.change-password-edit', ['result' => $result ]);
                }
                else{
                    return redirect('/admin/index');
                }
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

    public function index(){
        if(!Auth::check()){
            return redirect('/admin/login');
        }

        return view('admin.index');
    }

    public function newsList(Request $request){
        $news_date = "";
        $news_date_to = "";
        $name = "";
        $news_category_id = "";
        $news_lang_id = "";
        $tag_id = "";
        if(isset($request->date)){
            $news_date = $request->date;
        }

        if(isset($request->date_to)){
            $news_date_to = $request->date_to;
        }

        if(isset($request->name)){
            $name = $request->name;
        }

        if(isset($request->news_category_id)){
            $news_category_id = $request->news_category_id;
        }

        if(isset($request->tag_id)){
            $tag_id = $request->tag_id;
        }

        if(isset($request->news_lang_id)){
            $news_lang_id = $request->news_lang_id;
        }

        $row = News::LeftJoin('programm_tab','news_tab.programm_id','=','programm_tab.programm_id')
                    ->select('news_tab.*','programm_tab.programm_name_ru',
                        DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y") as date'))
                    ->orderByRaw("news_tab.date desc");

        if(strlen($news_date) > 0){
            $row->where(DB::raw('DATE_FORMAT(news_tab.date,"%Y-%m-%d")'),">=", date('Y-m-d', strtotime($news_date)));
        }

        if(strlen($news_date_to) > 0){
            $row->where(DB::raw('DATE_FORMAT(news_tab.date,"%Y-%m-%d")'),"<=", date('Y-m-d', strtotime($news_date_to)));
        }

        if(strlen($news_lang_id) > 0 && $news_lang_id > 0){
            if($news_lang_id == 1){
                $row->whereNotNull("news_tab.news_title_kz")->where("news_tab.news_title_kz", "!=","");
                if(strlen($name) > 0){
                    $row->where('news_tab.news_title_kz',"like", '%'.$name.'%');
                }
            }
            else{
                $row->whereNotNull("news_tab.news_title_ru")->where("news_tab.news_title_ru", "!=","");
                if(strlen($name) > 0){
                    $row->where('news_tab.news_title_ru',"like", '%'.$name.'%');
                }
            }
        }
        else if(strlen($name) > 0){
            $row->where('news_tab.news_title_kz',"like", '%'.$name.'%')->orWhere('news_tab.news_title_ru',"like", '%'.$name.'%');
        }

        if(strlen($news_category_id) > 0 && $news_category_id > 0){
            $row->where('news_tab.news_category_id',"=",$news_category_id);
        }

        if(strlen($tag_id) > 0 && $tag_id > 0){
            $row->LeftJoin("news_tag_tab","news_tab.news_id","=","news_tag_tab.news_id")->where('news_tag_tab.tag_id',"=",$tag_id)->groupBy("news_tab.news_id");
        }

        // $row = $row->get();
        $row = $row->paginate(20);

        return view('admin.news-list', [ 'row' => $row,'news_date' => $news_date, 'name' => $name, 'news_date_to' => $news_date_to, 'news_category_id' => $news_category_id, 'tag_id' => $tag_id, 'news_lang_id' => $news_lang_id ]);
    }

    public function deleteNews(Request $request){
        $news_id = $request->news_id;
        $news_row = News::find($news_id);
        if(count($news_row) > 0){
            $this->deleteFile("news_photo",$news_row->image);
        }
        $result = News::where('news_id', '=', $news_id)->delete();
        if(count($news_row)>0){
        	Favorite::where('source_id',$news_id)->where('type','news')->delete();
         	DB::table('two_blocks')->where('source_id',$news_id)->where('type','news')->delete();
         	DB::table('two_blogs')->where('source_id',$news_id)->where('type','news')->delete();
        }
        return response()->json(['result'=>$result]);
    }

    public function newsEdit(Request $request){
        $news_id = $request->news_id;

        $row = News::select("news_tab.*", DB::raw('DATE_FORMAT(news_tab.date,"%d.%m.%Y  %T") as date'))->where("news_id","=",$news_id)->first();
        if(count($row) < 1){
            $row = new News();
            $row->news_id = 0;
        }
        $programm_list = Programm::all();

        return view('admin.news-edit', [ 'row' => $row, 'programm_list' => $programm_list ]);
    }

    public function saveNews(Request $request){
        $messages = array(
            'date.required' => 'Укажите Дату',
            'time.required' => 'Укажите Время'
        );
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'time' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            $programm_list = Programm::all();
            return view('admin.news-edit', [ 'row' => $request, 'programm_list' => $programm_list, 'result' => $result ]);
        }

        $old_file_name = "";
        $old_file_name_big = "";
        $new_news = 0;
        if($request->news_id > 0) {
            $news_item = News::find($request->news_id);
            $old_file_name = $news_item->image;
            $old_file_name_big = $news_item->image_big;
        }
        else{
            $news_item = new News();
            $new_news = 1;
        }

        if($request->hasFile('image')){
            $this->deleteFile("news_photo",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_news_s.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('news_photo')->put($file_name,  File::get($file));
            $news_item->image = $file_name;
        }
        else if($request->image_select1 > 0){
            $file = './css/images/no_news_img' . $request->image_select1 .  '.png';
            $newfile = './news_photo/' . time() . "_news_s.png";
            if (!copy($file, $newfile)) {

            }
            $news_item->image = time() . "_news_s.png";
        }

        if($request->hasFile('image_big')){
            $this->deleteFile("news_photo",$old_file_name_big);
            $file = $request->image_big;
            $file_name = time() . "_news_b.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('news_photo')->put($file_name,  File::get($file));
            $news_item->image_big = $file_name;
        }
        else if($request->image_select2 > 0){
            $file = './css/images/no_news_img' . $request->image_select2 .  '.png';
            $newfile = './news_photo/' . time() . "_news_b.png";
            if (!copy($file, $newfile)) {

            }
            $news_item->image_big = time() . "_news_b.png";
        }

        $news_item->news_title_kz = $request->news_title_kz;
        $news_item->news_title_ru = $request->news_title_ru;
        $news_item->news_title_en = $request->news_title_en;
        $news_item->news_text_kz = htmlspecialchars_decode($request->news_text_kz);
        $news_item->news_text_ru = htmlspecialchars_decode($request->news_text_ru);
        $news_item->news_text_en = htmlspecialchars_decode($request->news_text_en);
        $news_item->is_main_news = $request->is_main_news;
        $news_item->date = date('Y-m-d', strtotime($request->date)) . " " . $request->time;
        $news_item->is_active = $request->is_active;
        $news_item->programm_id = $request->programm_id;
        $news_item->news_category_id = $request->news_category_id;
        $news_item->is_fix = $request->is_fix;
        $news_item->is_almaty = $request->is_almaty;
        $news_item->news_meta_desc_kz = $request->news_meta_desc_kz;
        $news_item->news_meta_desc_ru = $request->news_meta_desc_ru;
        $news_item->is_whatsapp = $request->is_whatsapp;
        $news_item->is_has_foto = $request->is_has_foto;
        $news_item->is_has_video = $request->is_has_video;
        $news_item->is_mail_ru = $request->is_mail_ru;

        if($new_news === 1) {
            $new_time = str_replace(":","",$request->time);
            if(strlen($request->news_title_ru) > 0){
                $news_item->news_url_name = $new_time."-".$this->cyr2lat($request->news_title_ru);
            }
            else{
                $news_item->news_url_name = $new_time."-".$this->cyr2lat($request->news_title_kz);
            }
        }

        if($news_item->save()){

            if(isset($request['tag_id'])){
                foreach($request['tag_id'] as $key => $value){
                    if($value > 0 && $news_item->news_id > 0){
                        $row_item2 = NewsTag::find($key);
                        $row_item2->tag_id = $value;
                        $row_item2->news_id = $news_item->news_id;
                        $row_item2->save();
                    }
                }
            }

            if(isset($request['tag_id_new'])){
                foreach($request['tag_id_new'] as $key => $value){
                    if($value > 0 && $news_item->news_id > 0) {
                        $row_item2 = new NewsTag();
                        $row_item2->tag_id = $value;
                        $row_item2->news_id = $news_item->news_id;
                        $row_item2->save();
                    }
                }
            }

            if($request->is_push == 1){
                $device = Push::all();
                if(count($device) > 0){
                    foreach($device as $val){
                        $push = new Push();
                        if($val->os == 'Android'){
                            $registration_id = array($val->registration_id);
                            $param['message'] = 'У нас новая новость для вас';
                            $param['id'] = $news_item->news_id;
                            $param['kind'] = 'news';
                            $message = $param;
                            $result1 = $push->sendMessageToAndroid($registration_id,$message);
                        }
                        else if($val->os == 'iOS'){
                            $registration_id = array($val->registration_id);
                            $param['message'] = 'У нас новая новость для вас';
                            $param['id'] = $news_item->news_id;
                            $param['kind'] = 'news';
                            $message = $param;
                            $result1 = $push->sendMessageToIOS($registration_id,$message);
                        }
                    }
                }
            }

            return redirect('/admin/news-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            $programm_list = Programm::all();
            return view('admin.news-edit', [ 'row' => $request, 'programm_list' => $programm_list, 'result' => $result ]);
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

    public function programmList(Request $request){
        $programm_lang_id = 0;
        $category_id = 0;
        $name = "";

        $row = Programm::select("programm_tab.*");

        if(strlen($request->name) > 0){
            $name = $request->name;
            $row->where("programm_tab.programm_name_ru","like","%".$name."%");
        }

        if(isset($request->category_id) && $request->category_id > 0){
            $category_id = $request->category_id;
            $row->LeftJoin("programm_category_tab","programm_tab.programm_id","=","programm_category_tab.programm_id")->where("programm_category_tab.category_id","=",$category_id)->groupBy("programm_tab.programm_id");
        }

        if(isset($request->programm_lang_id) && $request->programm_lang_id > 0){
            $programm_lang_id = $request->programm_lang_id;
            $row->where("programm_tab.pr_lang_id","=",$request->programm_lang_id);
        }

        $row = $row->orderBy("order_num","asc")->get();

        return view('admin.programm-list', [ 'row' => $row, 'programm_lang_id' => $programm_lang_id, 'category_id' => $category_id, 'name' => $name]);
    }

    public function programmEdit(Request $request){
        $programm_id = $request->programm_id;

        $row = Programm::LeftJoin('programm_category_tab','programm_tab.programm_id','=','programm_category_tab.programm_id')
                        ->select('programm_tab.*','programm_category_tab.category_id as category_id')
                        ->where('programm_tab.programm_id','=',$programm_id)
                        ->first();
        $category_list = Category::all();
        if(count($row) < 1){
            $row = new Programm();
            $row->programm_id = 0;
        }
        return view('admin.programm-edit', [ 'row' => $row, 'category_list' => $category_list]);
    }

    public function deleteProgramm(Request $request){
        $programm_id = $request->programm_id;
        $programm_row = Programm::find($programm_id);
        if(count($programm_row) > 0){
            $this->deleteFile("programm_photo",$programm_row->image);
        }
        $result = ProgrammTime::where('programm_id', '=', $programm_id)->delete();
        $result = Programm::where('programm_id', '=', $programm_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function deleteProgrammLogo(Request $request){
        $programm_id = $request->programm_id;
        $programm_row = Programm::find($programm_id);
        if(count($programm_row) > 0){
            $this->deleteFile("programm_photo",$programm_row->programm_logo);
        }
        $programm_row->programm_logo = null;
        $programm_row->save();
        return response()->json(['result'=>$programm_row]);
    }



    public function saveProgramm(Request $request){
        $messages = array(
            'category_id.not_in' => 'Укажите Категорию',
            'time.required' => 'Укажите Время',
            'day_week_kz.required' => 'Укажите дни недели(каз)',
            'day_week_ru.required' => 'Укажите дни недели(рус)',
            'main_image.required' => 'Зарузите фото для главной страницы',
        );
        $validation = [
            'category_id' => 'required|not_in:0'
        ];
        if($request->is_main)
        {
            $validation['time'] = 'required';
            $validation['day_week_kz'] = 'required';
            $validation['day_week_ru'] = 'required';
        }
        $validator = Validator::make($request->all(), $validation, $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            $category_list = Category::all();
            return view('admin.programm-edit', [ 'row' => $request, 'result' => $result, 'category_list' => $category_list ]);
        }

        $old_file_name = "";
        $old_file_name2 = "";
        $old_file_name3 = "";
        if($request->programm_id > 0) {
            $programm_item = Programm::LeftJoin('programm_category_tab','programm_tab.programm_id','=','programm_category_tab.programm_id')
                                        ->select('programm_tab.*','programm_category_tab.category_id as category_id')
                                        ->where('programm_tab.programm_id','=',$request->programm_id)
                                        ->first();
            $old_file_name = $programm_item->image;
            $old_file_name2 = $programm_item->programm_logo;
            $old_file_name3 = $programm_item->main_image;
        }
        else {
            $programm_item = new Programm();
        }

        if($request->hasFile('image')){
            $this->deleteFile("programm_photo",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_programm.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('programm_photo')->put($file_name,  File::get($file));
            $programm_item->image = $file_name;
        }

        if($request->hasFile('programm_logo')){
            $this->deleteFile("programm_photo",$old_file_name2);
            $file = $request->programm_logo;
            $file_name = time() . "_programm_logo.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('programm_photo')->put($file_name,  File::get($file));
            $programm_item->programm_logo = $file_name;
        }

        if($request->hasFile('main_image')){
            $this->deleteFile("programm_photo",$old_file_name3);
            $file = $request->main_image;
            $file_name = "main_image_" . time() . ".";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('programm_photo')->put($file_name,  File::get($file));
            $programm_item->main_image = $file_name;
        }

        $programm_item->programm_name_kz = $request->programm_name_kz;
        $programm_item->programm_name_ru = $request->programm_name_ru;
        $programm_item->programm_name_en = $request->programm_name_en;
        $programm_item->programm_description_kz = $request->programm_description_kz;
        $programm_item->programm_description_ru = $request->programm_description_ru;
        $programm_item->programm_description_en = $request->programm_description_en;
        $programm_item->order_num = $request->order_num;
        $programm_item->pr_lang_id = $request->pr_lang_id;
        $programm_item->is_archive = $request->is_archive;
        $programm_item->is_spec_project = $request->is_spec_project;
        $programm_item->is_main = ($request->is_main==1 ? 1 : 0);    
        $programm_item->time = $request->time;
        $programm_item->day_week_kz = $request->day_week_kz;
        $programm_item->day_week_ru = $request->day_week_ru;
        $programm_item->main_order_num = $request->main_order_num;
        $programm_item->show_link = $request->show_link;
        $programm_item->main_description_kz = $request->main_description_kz;
        $programm_item->main_description_ru = $request->main_description_ru;
        $programm_item->link_videoarchive_kz = $request->link_videoarchive_kz;
        $programm_item->link_programm_kz = $request->link_programm_kz;
        $programm_item->link_videoarchive_ru = $request->link_videoarchive_ru;
        $programm_item->link_programm_ru = $request->link_programm_ru;
    
        if(strlen($request->programm_name_ru) > 0) {
            $programm_item->programm_url_name = $this->cyr2lat($request->programm_name_ru);
        }
        else{
            $programm_item->programm_url_name = $this->cyr2lat($request->programm_name_kz);
        }
        if($programm_item->save()){
            $programm_category_row = ProgrammCategory::where('programm_id','=', $programm_item->programm_id)->first();

            $category_id = null;
            if($request->category_id > 0){
                if(count($programm_category_row) > 0){
                    $programm_category_row->category_id = $request->category_id;
                }
                else{
                    $programm_category_row = new ProgrammCategory();
                    $programm_category_row->programm_id = $programm_item->programm_id;
                    $programm_category_row->category_id = $request->category_id;
                }
                $programm_category_row->save();
            }
            else{
                ProgrammCategory::where('programm_id', '=', $programm_item->programm_id)->delete();
            }

            if(isset($request['day_id'])){
                foreach($request['day_id'] as $key => $value){
                    if($request['day_id'][$key] > 0 && $programm_item->programm_id > 0){
                        $programm_time = ProgrammTime::find($key);
                        $programm_time->day_id = $request['day_id'][$key];
                        $programm_time->time = $request['time'][$key];
                        $programm_time->save();
                    }
                }
            }

            if(isset($request['day_id_new'])){
                foreach($request['day_id_new'] as $key => $value){
                    if($request['day_id_new'][$key] > 0 && $programm_item->programm_id > 0){
                        $programm_time = new ProgrammTime();
                        $programm_time->programm_id = $programm_item->programm_id;
                        $programm_time->day_id = $request['day_id_new'][$key];
                        $programm_time->time = $request['time_new'][$key];
                        $programm_time->save();
                    }
                }
            }

            return redirect('/admin/programm-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;

            $category_list = Category::all();
            return view('admin.programm-edit', [ 'row' => $request, 'result' => $result, 'category_list' => $category_list ]);
        }
    }

    public function categoryList(){
        $row = Category::all();
        return view('admin.category-list', [ 'row' => $row ]);
    }

    public function categoryEdit(Request $request){
        $category_id = $request->category_id;

        $row = Category::find($category_id);
        if(count($row) < 1){
            $row = new Category();
            $row->category_id = 0;
        }
        return view('admin.category-edit', [ 'row' => $row]);
    }

    public function deleteCategory(Request $request){
        $category_id = $request->category_id;
        $category_row = Category::find($category_id);
        if(count($category_row) > 0){
            $this->deleteFile("category_photo",$category_row->image);
        }
        $result = Category::where('category_id', '=', $category_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function saveCategory(Request $request){
        $messages = array(
            'category_name_kz.required' => 'Укажите название категории на казахском!',
            'category_name_ru.required' => 'Укажите название категории на русском!'
        );
        $validator = Validator::make($request->all(), [
            'category_name_kz' => 'required',
            'category_name_ru' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.category-edit', [ 'row' => $request, 'result' => $result ]);
        }

        $old_file_name = "";
        if($request->category_id > 0) {
            $category_item = Category::find($request->category_id);
            $old_file_name = $category_item->image;
        }
        else {
            $category_item = new Category();
        }

        if($request->hasFile('image')){
            $this->deleteFile("category_photo",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_category.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('category_photo')->put($file_name,  File::get($file));
            $category_item->image = $file_name;
        }

        $category_item->category_name_kz = $request->category_name_kz;
        $category_item->category_name_ru = $request->category_name_ru;
        $category_item->category_name_en = $request->category_name_en;
        $category_item->category_url_name = $this->cyr2lat($request->category_name_ru);
        if($category_item->save()){
            return redirect('/admin/category-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.category-edit', [ 'row' => $request, 'result' => $result ]);
        }
    }

    public function tvProgrammList(Request $request){
        $allparam['date'] = date("d.m.Y");
        if(isset($request->date)){
            $allparam['date'] = $request->date;
        }
        $row = TVProgramm::where('date', '=', date("Y-m-d", strtotime($allparam['date'])))->get();
        return view('admin.tv-programm-list', [ 'row' => $row, 'allparam' => $allparam ]);
    }

    public function deleteTvProgramm(Request $request){
        $tv_programm_id = $request->tv_programm_id;
//        $tv_programm_row = TVProgramm::find($tv_programm_id);
//        if(count($tv_programm_row) > 0){
//            $this->deleteFile("tv_programm_photo",$tv_programm_row->image);
//        }
        $result = TVProgramm::where('tv_programm_id', '=', $tv_programm_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function tvProgrammEdit(Request $request){
        $tv_programm_id = $request->tv_programm_id;

        $row = TVProgramm::select('tv_programm_tab.*',
                           DB::raw('DATE_FORMAT(tv_programm_tab.date,"%d.%m.%Y") as date'))
                           ->where("tv_programm_tab.tv_programm_id","=",$tv_programm_id)
                           ->first();
        if(count($row) < 1){
            $row = new TVProgramm();
            $row->tv_programm_id = 0;
        }
        return view('admin.tv-programm-edit', ['row' => $row]);
    }

    public function saveTvProgramm(Request $request){
        $messages = array(
            'date.required' => 'Укажите Дату!',
            'time.required' => 'Укажите Время!'
        );
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'time' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.tv-programm-edit', [ 'row' => $request, 'result' => $result]);
        }

        $old_file_name = "";
        if($request->tv_programm_id > 0) {
            $tv_programm_item = TVProgramm::find($request->tv_programm_id);
            $old_file_name = $tv_programm_item->image;
        }
        else {
            $tv_programm_item = new TVProgramm();
        }

        if($request->hasFile('image')){
            $this->deleteFile("tv_programm_photo",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_tvprogramm.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('tv_programm_photo')->put($file_name,  File::get($file));
            $tv_programm_item->image = $file_name;
        }

        $tv_programm_item->tv_programm_name_kz = $request->tv_programm_name_kz;
        $tv_programm_item->tv_programm_name_ru = $request->tv_programm_name_ru;
        $tv_programm_item->date = date("Y-m-d", strtotime($request->date));
        $tv_programm_item->time = $request->time;
        $tv_programm_item->time_end = $request->time_end;
        $tv_programm_item->tv_programm_description_kz = $request->tv_programm_description_kz;
        $tv_programm_item->tv_programm_description_ru = $request->tv_programm_description_ru;
        $tv_programm_item->tv_programm_short_description_kz = $request->tv_programm_short_description_kz;
        $tv_programm_item->tv_programm_short_description_ru = $request->tv_programm_short_description_ru;
        $tv_programm_item->is_main_programm = $request->is_main_programm;
        $tv_programm_item->category_id = $request->category_id;
        $tv_programm_item->tv_programm_programm_id = $request->tv_programm_programm_id;
        if($tv_programm_item->save()){
            return redirect('/admin/tv-programm-list/' . $request->date);
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.tv-programm-edit', [ 'row' => $request, 'result' => $result]);
        }
    }

    public function vacancyList(){
        $row = Vacancy::all();
        return view('admin.vacancy-list', [ 'row' => $row]);
    }


    public function vacancyEdit(Request $request){
        $vacancy_id = $request->vacancy_id;

        $row = Vacancy::find($vacancy_id);
        if(count($row) < 1){
            $row = new Vacancy();
            $row->vacancy_id = 0;
        }
        return view('admin.vacancy-edit', ['row' => $row]);
    }

    public function saveVacancy(Request $request){
        $messages = array(
            'vacancy_position_name_kz.required' => 'Укажите название должности на казахском!',
            'vacancy_position_name_ru.required' => 'Укажите название должности на русском!'
        );
        $validator = Validator::make($request->all(), [
            'vacancy_position_name_kz' => 'required',
            'vacancy_position_name_ru' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.vacancy-edit', [ 'row' => $request, 'result' => $result]);
        }

        if($request->vacancy_id > 0) {
            $vacancy_item = Vacancy::find($request->vacancy_id);
        }
        else {
            $vacancy_item = new Vacancy();
        }

        $vacancy_item->vacancy_position_name_kz = $request->vacancy_position_name_kz;
        $vacancy_item->vacancy_position_name_ru = $request->vacancy_position_name_ru;
        $vacancy_item->vacancy_position_name_en = $request->vacancy_position_name_en;
        $vacancy_item->vacancy_description_kz = $request->vacancy_description_kz;
        $vacancy_item->vacancy_description_ru = $request->vacancy_description_ru;
        $vacancy_item->vacancy_description_en = $request->vacancy_description_en;
        if($vacancy_item->save()){
            return redirect('/admin/vacancy-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.vacancy-edit', [ 'row' => $request, 'result' => $result]);
        }
    }

    public function deleteVacancy(Request $request){
        $vacancy_id = $request->vacancy_id;
        $result = Vacancy::where('vacancy_id', '=', $vacancy_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function videoArchiveList(Request $request){
        $date = "";
        $date_to = "";
        $name = "";
        $programm_id = "";
        $programm_lang_id = "";
        if(isset($request->date)){
            $date = $request->date;
        }

        if(isset($request->date_to)){
            $date_to = $request->date_to;
        }

        if(isset($request->name)){
            $name = $request->name;
        }

        if(isset($request->programm_id)){
            $programm_id = $request->programm_id;
        }

        if(isset($request->programm_lang_id)){
            $programm_lang_id = $request->programm_lang_id;
        }

        $row = VideoArchive::LeftJoin('programm_tab','video_archive_tab.programm_id','=','programm_tab.programm_id')
                            ->select('video_archive_tab.*','programm_tab.programm_name_ru', DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'));

        if(strlen($date) > 0){
            $row->where(DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%Y-%m-%d")'),">=", date('Y-m-d', strtotime($date)));
        }

        if(strlen($date_to) > 0){
            $row->where(DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%Y-%m-%d")'),"<=", date('Y-m-d', strtotime($date_to)));
        }

        if($programm_id > 0){
            $row->where('video_archive_tab.programm_id',"=", $programm_id);
        }

        if(strlen($programm_lang_id) > 0 && $programm_lang_id > 0){
            if($programm_lang_id == 1){
                $row->whereNotNull("video_archive_tab.video_archive_title_kz")->where("video_archive_tab.video_archive_title_kz", "!=","");
                if(strlen($name) > 0){
                    $row->where('video_archive_tab.video_archive_title_kz',"like", '%'.$name.'%');
                }
            }
            else{
                $row->whereNotNull("video_archive_tab.video_archive_title_ru")->where("video_archive_tab.video_archive_title_ru", "!=","");
                if(strlen($name) > 0){
                    $row->where('video_archive_tab.video_archive_title_ru',"like", '%'.$name.'%');
                }
            }
        }
        else if(strlen($name) > 0){
            $row->where('video_archive_tab.video_archive_title_ru',"like", '%'.$name.'%')->orWhere('video_archive_tab.video_archive_title_kz',"like", '%'.$name.'%');
        }

        $row = $row->paginate(100);

        return view('admin.video-archive-list', [ 'row' => $row, 'date' => $date, 'name' => $name, 'date_to' => $date_to, 'programm_id' => $programm_id, 'programm_lang_id' => $programm_lang_id]);
    }


    public function videoArchiveEdit(Request $request){
        $video_archive_id = $request->video_archive_id;

        $row = VideoArchive::select("video_archive_tab.*", DB::raw('DATE_FORMAT(video_archive_tab.video_archive_date,"%d.%m.%Y") as video_archive_date'))
                            ->where("video_archive_tab.video_archive_id","=",$video_archive_id)
                            ->first();
        $programm_list = Programm::all();
        if(count($row) < 1){
            $row = new VideoArchive();
            $row->video_archive_id = 0;
        }
        return view('admin.video-archive-edit', ['row' => $row, 'programm_list' => $programm_list]);
    }

    public function deleteVideoArchive(Request $request){
        $video_archive_id = $request->video_archive_id;
        $video_archive_row = VideoArchive::find($video_archive_id);
        if(count($video_archive_row) > 0){
            $this->deleteFile("video_archive_photo",$video_archive_row->image);
        }

        $result = VideoArchive::where('video_archive_id', '=', $video_archive_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function saveVideoArchive(Request $request){
        $messages = array(
            'video_archive_title_ru.required' => 'Укажите заголовок видеоархив на русском!',
            'video_archive_date.required' => 'Укажите дату!',
            'youtube_link.required' => 'Укажите ссылку на YouTube!',
            'programm_id.not_in' => 'Укажите программу!'
        );
        $validator = Validator::make($request->all(), [
            'video_archive_title_ru' => 'required',
            'video_archive_date' => 'required',
            'youtube_link' => 'required',
            'programm_id' => 'required|not_in:0'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            $programm_list = Programm::all();
            return view('admin.video-archive-edit', [ 'row' => $request, 'programm_list' => $programm_list, 'result' => $result ]);
        }

        $old_file_name = "";
        if($request->video_archive_id > 0) {
            $video_archive_item = VideoArchive::find($request->video_archive_id);
            $old_file_name = $video_archive_item->image;
        }
        else {
            $video_archive_item = new VideoArchive();
        }

        if($request->hasFile('image')){
            $this->deleteFile("video_archive_photo",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_videoarchive.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('video_archive_photo')->put($file_name,  File::get($file));
            $video_archive_item->image = $file_name;
        }

        $video_archive_item->video_archive_title_kz = $request->video_archive_title_kz;
        $video_archive_item->video_archive_title_ru = $request->video_archive_title_ru;
        $video_archive_item->video_archive_title_en = $request->video_archive_title_en;
        $video_archive_item->programm_id = $request->programm_id;
        $video_archive_item->youtube_link = $request->youtube_link;
        $video_archive_item->youtube_video_code = $request->youtube_video_code;
        $video_archive_item->video_archive_meta_desc = $request->video_archive_meta_desc;
        $video_archive_item->programm_lang_id = $request->programm_lang_id;
        $video_archive_item->video_archive_date = date('Y-m-d', strtotime($request->video_archive_date));
        $video_archive_item->video_archive_url_name = $this->cyr2lat($request->video_archive_title_ru);
        $video_archive_item->video_description_kz = $request->video_description_kz;
        $video_archive_item->video_description_ru = $request->video_description_ru;
        if($video_archive_item->save()){
            return redirect('/admin/video-archive-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            $programm_list = Programm::all();
            return view('admin.video-archive-edit', [ 'row' => $request, 'programm_list' => $programm_list, 'result' => $result ]);
        }
    }

    public function userList(){
        $row = Users::LeftJoin('role_tab','user_tab.role_id','=','role_tab.role_id')
            ->select('user_tab.*','role_tab.role_name')
            ->paginate(30);
        return view('admin.user-list', [ 'row' => $row]);
    }

    public function userEdit(Request $request){
        $user_id = $request->user_id;

        $row = Users::find($user_id);
        $role_list = Role::all();
        if(count($row) < 1){
            $row = new Users();
            $row->user_id = 0;
        }
        return view('admin.user-edit', ['row' => $row, 'role_list' => $role_list]);
    }

    public function deleteUser(Request $request){
        $user_id = $request->user_id;
        $user_row = Users::find($user_id);
        if(count($user_row) > 0){
            $this->deleteFile("user_photo",$user_row->image);
        }

        $result = Users::where('user_id', '=', $user_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function saveUser(Request $request){
        $messages = array(
            'fio.required' => 'Укажите ФИО',
            'role_id.not_in' => 'Укажите Роль',
            'email.required' => 'Укажите Email',
            'email.email' => 'Неправильный формат Email',
            'email.unique' => 'Пользователь с таким Email уже существует'
        );
        $validator = Validator::make($request->all(), [
            'fio' => 'required',
            'role_id' => 'required|not_in:0',
            'email' => 'required|email|unique:user_tab'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            $role_list = Role::all();
            return view('admin.user-edit', [ 'row' => $request, 'role_list' => $role_list, 'result' => $result ]);
        }

        $is_new_user = 0;
        $rand_str = "";
        $old_file_name = "";
        if($request->user_id > 0) {
            $user_item = Users::find($request->user_id);
            $old_file_name = $user_item->image;
        }
        else {
            $user_item = new Users();
            $is_new_user = 1;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            for ($i = 0; $i < 14; $i++) {
                $rand_str .= $characters[rand(0, strlen($characters) - 1)];
            }
            $user_item->password = Hash::make($rand_str);
        }

        if($request->hasFile('image')){
            $this->deleteFile("user_photo",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_user.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;

            Storage::disk('user_photo')->put($file_name,  File::get($file));
            $user_item->image = $file_name;
        }

        $user_item->fio = $request->fio;
        $user_item->email = strtolower($request->email);
        $user_item->role_id = $request->role_id;
        $user_item->is_blocked = $request->is_blocked;

        if($user_item->save()){
            if($is_new_user == 1){
                $email_to = $request->email;
                $message_str = 'Уважаемый (-ая), ' . $request->fio .'!<br>Ваш пароль для входа в личный кабинет Almaty.tv: ' . $rand_str;
                Mail::send(['html' => 'admin.email'], ['text' => $message_str], function($message) use ($email_to)
                {
                    $message->to($email_to)->subject("Регистрация на сайте  Almaty.tv");
                });
            }
            return redirect('/admin/user-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            $role_list = Role::all();
            return view('admin.user-edit', [ 'row' => $request, 'role_list' => $role_list, 'result' => $result ]);
        }
    }

    public function deleteFile($path,$old_file_name){
        if(strlen($old_file_name) > 0 && File::exists(public_path( $path . '/' . $old_file_name))){
            Storage::disk($path)->delete($old_file_name);
        }
    }

    public function changePasswordEdit(){
        return view('admin.change-password-edit');
    }

    public function changePassword(Request $request){
        $messages = array(
            'old_password.required' => 'Укажите старый пароль!',
            'new_password.required' => 'Укажите новый пароль!',
            'new_password.different' => 'Новый пароль совпадает со старым паролем!',
            'repeat_new_password.required' => 'Укажите повтор нового пароля!',
            'repeat_new_password.same' => 'Повтор пароля не совпадает!'
        );
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|different:old_password',
            'repeat_new_password' => 'required|same:new_password'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.change-password-edit', ['result' => $result ]);
        }

        $user = Users::where('user_id','=',Auth::user()->user_id)->first();
        $count = Hash::check($request->old_password, $user->password);
        if($count == false){
            $error[0] = "Неправильно указан старый пароль!";
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.change-password-edit', ['result' => $result ]);
        }

        $user = Users::where('user_id','=',Auth::user()->user_id)->first();
        $user->password = Hash::make($request->new_password);
        $offset= strtotime("+6 hours 0 minutes");
        $user->password_changed_time = date("Y-m-d H:i:s",$offset);
        if($user->save()){
            $error[0] = "Пароль успешно изменен!";
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.change-password-edit', ['result' => $result ]);
        }
        else{
            $error[0] = "Ошибка при изменени пароля!";
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.change-password-edit', ['result' => $result ]);
        }
    }

    public function resetPassword(){
        $email = "";
        $error = "";
        return view('admin.reset-password',['email' => $email, 'error' => $error]);
    }

    public function resetPasswordEdit(Request $request){
        $messages = array(
            'reset_email.required' => 'Укажите Email!'
        );
        $validator = Validator::make($request->all(), [
            'reset_email' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();
            return view('admin.reset-password',['email' => $request->reset_email, 'error' => $error[0]]);
        }

        $user = Users::where('email','=',$request->reset_email)->first();

        if(count($user) > 0){
            $rand_str = "";
            $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
            for ($i = 0; $i < 14; $i++) {
                $rand_str .= $characters[rand(0, strlen($characters) - 1)];
            }
            $user->password = Hash::make($rand_str);
            $offset= strtotime("+6 hours 0 minutes");
            $user->password_changed_time = date("Y-m-d H:i:s",$offset);
            $user->save();

            $email_to = $request->reset_email;
            $message_str = 'Ваш новый пароль для входа в личный кабинет Almaty.tv: ' . $rand_str;
            Mail::send(['html' => 'admin.email'], ['text' => $message_str], function($message) use ($email_to)
            {
                $message->to($email_to)->subject("Сброс пароля на сайте  Almaty.tv");
            });
            return view('admin.reset-password',['email' => $request->reset_email, 'error' => "Пароль успешно сброшен и отправлен на почту!"]);
        }
        else{
            return view('admin.reset-password',['email' => $request->reset_email, 'error' => "Пользователя с таким Email не существует!"]);
        }
    }

    public function jobResponseList(Request $request){
        $allparam['vacancy_id'] = 0;
        if(isset($request->vacancy_id)){
            $allparam['vacancy_id'] = $request->vacancy_id;
        }
        $row = JobResponse::select("job_response_tab.*", DB::raw('DATE_FORMAT(job_response_tab.job_response_date,"%d.%m.%Y  %T") as job_response_date'))->where('vacancy_id', '=', $allparam['vacancy_id'])->orderByRaw("job_response_date desc")->get();
        return view('admin.job-response-list', [ 'row' => $row, 'allparam' => $allparam ]);
    }

    public function deleteJobResponse(Request $request){
        $job_response_id = $request->job_response_id;
        $row = JobResponse::find($job_response_id);
        if(count($row) > 0){
            $this->deleteFile("response_files",$row->filename);
        }
        $result = JobResponse::where('job_response_id', '=', $job_response_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function deleteProgrammTime(Request $request){
        $programm_time_id = $request->programm_time_id;
        $result = ProgrammTime::where('programm_time_id', '=', $programm_time_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function tagList(){
        $row = Tag::orderBy('created_at', 'DESC')->paginate(30);
        return view('admin.tag-list', [ 'row' => $row ]);
    }

    public function tagEdit(Request $request){
        $tag_id = $request->tag_id;
        $row = Tag::find($tag_id);
        if(count($row) < 1){
            $row = new Tag();
            $row->tag_id = 0;
        }
        return view('admin.tag-edit', [ 'row' => $row ]);
    }

    public function deleteTag(Request $request){
        $tag_id = $request->id;
        $result = Tag::where('tag_id', '=', $tag_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function saveTag(Request $request){
        $messages = array(
            'tag_name.required' => 'Укажите Название!'
        );
        $validator = Validator::make($request->all(), [
            'tag_name' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.tag-edit', [ 'row' => $request, 'result' => $result ]);
        }

        if($request->tag_id > 0) {
            $row_item = Tag::find($request->tag_id);
        }
        else {
            $row_item = new Tag();
        }

        $row_item->tag_name = $request->tag_name;
        if($row_item->save()){
            return redirect('/admin/tag-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.tag-edit', [ 'row' => $request, 'result' => $result]);
        }
    }

    public function deleteNewsTag(Request $request){
        $result = NewsTag::where('news_tag_id', '=', $request->id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function advertisementList(){
        $row = Advertisement::all();
        return view('admin.advertisement-list', [ 'row' => $row ]);
    }

    public function advertisementEdit(Request $request){
        $advertisement_id = $request->advertisement_id;
        $row = Advertisement::find($advertisement_id);
        if(count($row) < 1){
            $row = new Advertisement();
            $row->advertisement_id = 0;
        }
        return view('admin.advertisement-edit', [ 'row' => $row ]);
    }

    public function deleteAdvertisement(Request $request){
        $advertisement_id = $request->advertisement_id;
        $result = Advertisement::where('advertisement_id', '=', $advertisement_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function saveAdvertisement(Request $request){
        $old_file_name = "";
        if($request->advertisement_id > 0) {
            $row_item = Advertisement::find($request->advertisement_id);
            $old_file_name = $row_item->image;
        }
        else {
            $row_item = new Advertisement();
        }

        if($request->hasFile('image')){
            $this->deleteFile("adv",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_adv.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('adv')->put($file_name,  File::get($file));
            $row_item->image = $file_name;
        }

        $row_item->advertisement_title_kz = $request->advertisement_title_kz;
        $row_item->advertisement_title_ru = $request->advertisement_title_ru;
        $row_item->advertisement_title_en = $request->advertisement_title_en;
        $row_item->advertisement_text_kz = $request->advertisement_text_kz;
        $row_item->advertisement_text_ru = $request->advertisement_text_ru;
        $row_item->advertisement_text_en = $request->advertisement_text_en;
        $row_item->lang_id = $request->lang_id;
        $row_item->is_active = $request->is_active;
        $row_item->is_main_advertisement = $request->is_main_advertisement;
        $row_item->link = $request->link;
        if($row_item->save()){
            return redirect('/admin/advertisement-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.advertisement-edit', [ 'row' => $request, 'result' => $result]);
        }
    }

    public function addNewUserTag(Request $request){
        $row_item = Tag::where('tag_name', $request->tag_name)->first();
        
        if(!$row_item){
            $row_item = new Tag();
            $row_item->tag_name = $request->tag_name;
            $row_item->save();
        }

        if($request->is_new){
            return view('admin.new-user-tag-edit-2', [ 'row' => $row_item]);    
        }
        return view('admin.new-user-tag-edit', [ 'row' => $row_item]);
    }

    public function newsCategoryList(){
        $row = NewsCategory::all();
        return view('admin.news-category-list', [ 'row' => $row ]);
    }

    public function newsCategoryEdit(Request $request){
        $news_category_id = $request->news_category_id;

        $row = NewsCategory::find($news_category_id);
        if(count($row) < 1){
            $row = new NewsCategory();
            $row->news_category_id = 0;
        }
        return view('admin.news-category-edit', [ 'row' => $row]);
    }

    public function deleteNewsCategory(Request $request){
        $news_category_id = $request->news_category_id;
        $news_category_row = NewsCategory::find($news_category_id);
        if(count($news_category_row) > 0){
            $this->deleteFile("category_photo",$news_category_row->image);
        }
        $result = NewsCategory::where('news_category_id', '=', $news_category_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function saveNewsCategory(Request $request){
        $messages = array(
            'news_category_name_kz.required' => 'Укажите название категории на казахском!',
            'news_category_name_ru.required' => 'Укажите название категории на русском!'
        );
        $validator = Validator::make($request->all(), [
            'news_category_name_kz' => 'required',
            'news_category_name_ru' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.news-category-edit', [ 'row' => $request, 'result' => $result ]);
        }

        $old_file_name = "";
        if($request->news_category_id > 0) {
            $news_category_item = NewsCategory::find($request->news_category_id);
            $old_file_name = $news_category_item->image;
        }
        else {
            $news_category_item = new NewsCategory();
        }

        if($request->hasFile('image')){
            $this->deleteFile("category_photo",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_category.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('category_photo')->put($file_name,  File::get($file));
            $news_category_item->image = $file_name;
        }

        $news_category_item->news_category_name_kz = $request->news_category_name_kz;
        $news_category_item->news_category_name_ru = $request->news_category_name_ru;
        $news_category_item->news_category_name_en = $request->news_category_name_en;
        $news_category_item->news_category_url_name = $this->cyr2lat($request->news_category_name_ru);
        if($news_category_item->save()){
            return redirect('/admin/news-category-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.news-category-edit', [ 'row' => $request, 'result' => $result ]);
        }
    }

   public function menuList(){
       $row = Menu::orderBy("order_num","asc")->get();
       return view('admin.menu-list', [ 'row' => $row ]);
   }

    public function menuEdit(Request $request){
        $menu_id = $request->menu_id;

        $row = Menu::find($menu_id);
        if(count($row) < 1){
            $row = new Menu();
            $row->menu_id = 0;
        }
        return view('admin.menu-edit', [ 'row' => $row]);
    }

    public function deleteMenu(Request $request){
        $menu_id = $request->menu_id;
        $result = Menu::where('menu_id', '=', $menu_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function saveMenu(Request $request){
        if($request->menu_id > 0) {
            $row_item = Menu::find($request->menu_id);
        }
        else {
            $row_item = new Menu();
        }

        $row_item->menu_title = $request->menu_title;
        $row_item->menu_text = $request->menu_text;
        $row_item->order_num = $request->order_num;
        if($row_item->save()){
            return redirect('/admin/menu-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.menu-edit', [ 'row' => $request, 'result' => $result]);
        }
    }

    public function constTvProgrammList(Request $request){
        $row = ConstTVProgramm::all();
        return view('admin.const-tv-programm-list', [ 'row' => $row]);
    }

    public function deleteConstTvProgramm(Request $request){
        $tv_programm_id = $request->tv_programm_id;
//        $tv_programm_row = ConstTVProgramm::find($tv_programm_id);
//        if(count($tv_programm_row) > 0){
//            $this->deleteFile("tv_programm_photo",$tv_programm_row->image);
//        }
        $result = ConstTVProgramm::where('tv_programm_id', '=', $tv_programm_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function constTvProgrammEdit(Request $request){
        $tv_programm_id = $request->tv_programm_id;

        $row = ConstTVProgramm::select('const_tv_programm_tab.*',
            DB::raw('DATE_FORMAT(const_tv_programm_tab.date,"%d.%m.%Y") as date'))
            ->where("const_tv_programm_tab.tv_programm_id","=",$tv_programm_id)
            ->first();
        if(count($row) < 1){
            $row = new ConstTVProgramm();
            $row->tv_programm_id = 0;
        }
        return view('admin.const-tv-programm-edit', ['row' => $row]);
    }

    public function saveConstTvProgramm(Request $request){
        $messages = array(
            'time.required' => 'Укажите Время!'
        );
        $validator = Validator::make($request->all(), [
            'time' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.const-tv-programm-edit', [ 'row' => $request, 'result' => $result]);
        }

        $old_file_name = "";
        if($request->tv_programm_id > 0) {
            $tv_programm_item = ConstTVProgramm::find($request->tv_programm_id);
            $old_file_name = $tv_programm_item->image;
        }
        else {
            $tv_programm_item = new ConstTVProgramm();
        }

        if($request->hasFile('image')){
            $this->deleteFile("tv_programm_photo",$old_file_name);
            $file = $request->image;
            $file_name = time() . "_tvprogramm.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('tv_programm_photo')->put($file_name,  File::get($file));
            $tv_programm_item->image = $file_name;
        }

        $tv_programm_item->tv_programm_name_kz = $request->tv_programm_name_kz;
        $tv_programm_item->tv_programm_name_ru = $request->tv_programm_name_ru;
        $tv_programm_item->time = $request->time;
        $tv_programm_item->time_end = $request->time_end;
        $tv_programm_item->tv_programm_description_kz = $request->tv_programm_description_kz;
        $tv_programm_item->tv_programm_description_ru = $request->tv_programm_description_ru;
        $tv_programm_item->tv_programm_short_description_kz = $request->tv_programm_short_description_kz;
        $tv_programm_item->tv_programm_short_description_ru = $request->tv_programm_short_description_ru;
        $tv_programm_item->is_main_programm = $request->is_main_programm;
        $tv_programm_item->category_id = $request->category_id;
        $tv_programm_item->tv_programm_programm_id = $request->tv_programm_programm_id;
        if($tv_programm_item->save()){
            return redirect('/admin/const-tv-programm-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.const-tv-programm-edit', [ 'row' => $request, 'result' => $result]);
        }
    }

    public function addConstTvProgramm(Request $request){
        $row = ConstTVProgramm::where("tv_programm_id","=",$request->tv_programm_id)->first();

        $tv_programm_item = new TVProgramm();

        $tv_programm_item->tv_programm_name_kz = $row->tv_programm_name_kz;
        $tv_programm_item->tv_programm_name_ru = $row->tv_programm_name_ru;
        $tv_programm_item->date = date("Y-m-d", strtotime($request->date));
        $tv_programm_item->time = $row->time;
        $tv_programm_item->time_end = $row->time_end;
        $tv_programm_item->tv_programm_description_kz = $row->tv_programm_description_kz;
        $tv_programm_item->tv_programm_description_ru = $row->tv_programm_description_ru;
        $tv_programm_item->tv_programm_short_description_kz = $row->tv_programm_short_description_kz;
        $tv_programm_item->tv_programm_short_description_ru = $row->tv_programm_short_description_ru;
        $tv_programm_item->is_main_programm = $row->is_main_programm;
        $tv_programm_item->category_id = $row->category_id;
        $tv_programm_item->tv_programm_programm_id = $row->tv_programm_programm_id;
        $tv_programm_item->image = $row->image;
        if($tv_programm_item->save()){
            return response()->json(['result'=>true]);
        }
        else{
            return response()->json(['result'=>false]);
        }
    }

    public function addExpressTvProgramm(Request $request){
        $tv_programm_item = new TVProgramm();

        $tv_programm_item->tv_programm_name_kz = $request->tv_programm_name_kz;
        $tv_programm_item->tv_programm_name_ru = $request->tv_programm_name_ru;
        $tv_programm_item->date = date("Y-m-d", strtotime($request->date));
        $tv_programm_item->time = $request->time;
        $tv_programm_item->time_end = $request->time_end;
        $tv_programm_item->tv_programm_programm_id = $request->tv_programm_programm_id;
        $tv_programm_item->category_id = $request->category_id;

        if($tv_programm_item->save()){
            return response()->json(['result'=>true]);
        }
        else{
            return response()->json(['result'=>false]);
        }
    }

    public function loadTvProgramm(Request $request){
        $allparam['date'] = $request->date;
        $row = TVProgramm::where('date', '=', date("Y-m-d", strtotime($allparam['date'])))->orderBy("time","asc")->get();
        return view('admin.load-tv-programm', [ 'row' => $row, 'allparam' => $allparam ]);
    }

    public function newsArchiveList(Request $request){
        $news_date = "";
        $news_date_to = "";
        $name = "";
        $news_category_id = "";
        if(isset($request->date)){
            $news_date = $request->date;
        }

        if(isset($request->date_to)){
            $news_date_to = $request->date_to;
        }

        if(isset($request->name)){
            $name = $request->name;
        }

        if(isset($request->news_category_id)){
            $news_category_id = $request->news_category_id;
        }

        if(isset($request->tag_id)){
            $tag_id = $request->tag_id;
        }

        $row = "";
        if($request->lang == "kz"){
            $row = NewsArchiveKz::select('*', DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->orderBy("news_id","desc");

            if(strlen($name) > 0){
                $row->where('news_title_kz',"like", '%'.$name.'%');
            }

        }
        else if($request->lang == "ru"){
            $row = NewsArchiveRu::select('*', DB::raw('DATE_FORMAT(date,"%d.%m.%Y") as date'))->orderBy("news_id","desc");

            if(strlen($name) > 0){
                $row->where('news_title_ru',"like", '%'.$name.'%');
            }
        }

        if(strlen($news_date) > 0){
            $row->where(DB::raw('DATE_FORMAT(date,"%Y-%m-%d")'),">=", date('Y-m-d', strtotime($news_date)));
        }

        if(strlen($news_date_to) > 0){
            $row->where(DB::raw('DATE_FORMAT(date,"%Y-%m-%d")'),"<=", date('Y-m-d', strtotime($news_date_to)));
        }

        if($news_category_id > 0){
            $row->where('news_category_id',"=",$news_category_id);
        }

        $row = $row->paginate(20);

        return view('admin.news-archive-list',['row' => $row, 'lang' => $request->lang, 'news_date' => $news_date, 'name' => $name, 'news_date_to' => $news_date_to, 'news_category_id' => $news_category_id]);
    }

    public function deleteArchiveNews(Request $request){
        if($request->lang == "kz"){
            $news_id = $request->news_id;

            $news_row = NewsArchiveKz::find($news_id);
            if(count($news_row) > 0){
                $this->deleteFile("archive_news_kz",$news_row->image);
            }
            $result = NewsArchiveKz::where('news_id', '=', $news_id)->delete();
            return response()->json(['result'=>$result]);
        }
        else if($request->lang == "ru"){
            $news_id = $request->news_id;

            $news_row = NewsArchiveRu::find($news_id);
            if(count($news_row) > 0){
                $this->deleteFile("archive_news_ru",$news_row->image);
            }
            $result = NewsArchiveRu::where('news_id', '=', $news_id)->delete();
            return response()->json(['result'=>$result]);
        }
        else{
            return response()->json(['result'=>"false"]);
        }
    }

    public function newsArchiveEdit(Request $request){
        $row = null;
        if($request->lang == "kz"){
            $news_id = $request->news_id;

            $row = NewsArchiveKz::select("news_archive_kz_tab.*", DB::raw('DATE_FORMAT(news_archive_kz_tab.date,"%d.%m.%Y  %T") as date'))->where("news_id","=",$news_id)->first();
            if(count($row) < 1){
                $row = new NewsArchiveKz();
                $row->news_id = 0;
            }
        }
        else if($request->lang == "ru"){
            $news_id = $request->news_id;

            $row = NewsArchiveRu::select("news_archive_ru_tab.*", DB::raw('DATE_FORMAT(news_archive_ru_tab.date,"%d.%m.%Y  %T") as date'))->where("news_id","=",$news_id)->first();
            if(count($row) < 1){
                $row = new NewsArchiveRu();
                $row->news_id = 0;
            }
        }

        return view('admin.news-archive-edit', [ 'row' => $row, 'lang' => $request->lang ]);
    }

    public function saveNewsArchive(Request $request){
        $messages = array(
            'date.required' => 'Укажите Дату',
            'time.required' => 'Укажите Время'
        );
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'time' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            $programm_list = Programm::all();
            return view('admin.news-archive-edit', [ 'row' => $request, 'result' => $result ]);
        }

        $old_file_name = "";
        if($request->news_id > 0) {
            if($request->lang == "kz"){
                $news_item = NewsArchiveKz::find($request->news_id);
            }
            else{
                $news_item = NewsArchiveRu::find($request->news_id);
            }
            $old_file_name = $news_item->image;
        }
        else{
            if($request->lang == "kz"){
                $news_item = new NewsArchiveKz();
            }
            else{
                $news_item = new NewsArchiveRu();
            }
        }

        if($request->hasFile('image')){
            if($request->lang == "kz"){
                $this->deleteFile("archive_news_kz",$old_file_name);
            }
            else if($request->lang == "ru"){
                $this->deleteFile("archive_news_ru",$old_file_name);
            }

            $file = $request->image;
            $file_name = time() . "_news_archive_" . $request->lang . ".";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;

            if($request->lang == "kz"){
                Storage::disk('archive_news_kz')->put($file_name,  File::get($file));
            }
            else if($request->lang == "ru"){
                Storage::disk('archive_news_ru')->put($file_name,  File::get($file));
            }

            $news_item->image = $file_name;
        }
        else if($request->image_select2 > 0){
            $file = './css/images/no_news_img' . $request->image_select2 .  '.png';

            if($request->lang == "kz"){
                $newfile = './archive_news_kz/' . time() . "_news_archive" . $request->lang . ".png";
            }
            else if($request->lang == "ru"){
                $newfile = './archive_news_ru/' . time() . "_news_archive" . $request->lang . ".png";
            }

            if (!copy($file, $newfile)) {

            }
            $news_item->image = time() . "_news_archive" . $request->lang . ".png";
        }

        if($request->lang == "kz"){
            $news_item->news_title_kz = $request->news_title_kz;
            $news_item->news_text_kz = $request->news_text_kz;
            $news_item->news_url_name = $this->cyr2lat($request->news_title_kz);
        }
        else{
            $news_item->news_title_ru = $request->news_title_ru;
            $news_item->news_text_ru = $request->news_text_ru;
            $news_item->news_url_name = $this->cyr2lat($request->news_title_ru);
        }

        $news_item->date = date('Y-m-d', strtotime($request->date)) . " " . $request->time;
        $news_item->news_category_id = $request->news_category_id;

        if($news_item->save()){

            if(isset($request['tag_id'])){
                foreach($request['tag_id'] as $key => $value){
                    if($value > 0 && $news_item->news_id > 0){
                        $row_item2 = ArchiveNewsTag::find($key);
                        $row_item2->tag_id = $value;
                        $row_item2->news_id = $news_item->news_id;
                        $row_item2->save();
                    }
                }
            }

            if(isset($request['tag_id_new'])){
                foreach($request['tag_id_new'] as $key => $value){
                    if($value > 0 && $news_item->news_id > 0) {
                        $row_item2 = new ArchiveNewsTag();
                        $row_item2->tag_id = $value;
                        $row_item2->news_id = $news_item->news_id;
                        $row_item2->save();
                    }
                }
            }

            return redirect('/admin/news-archive-list/' . $request->lang);
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.news-archive-edit', [ 'row' => $request, 'result' => $result ]);
        }
    }

    public function copyTvProgramm(Request $request){
        $row = TVProgramm::where('date', '=', date("Y-m-d", strtotime($request['date_from'])))->get();

        if(count($row) > 0){
			$i = 0;
            foreach($row as $key => $row_item){
				$i++;
                $tv_programm_item = new TVProgramm();

                $tv_programm_item->tv_programm_name_kz = $row_item->tv_programm_name_kz;
                $tv_programm_item->tv_programm_name_ru = $row_item->tv_programm_name_ru;
                $tv_programm_item->date = date("Y-m-d", strtotime($request->date_to));
                $tv_programm_item->time = $row_item->time;
                $tv_programm_item->time_end = $row_item->time_end;
                $tv_programm_item->tv_programm_description_kz = $row_item->tv_programm_description_kz;
                $tv_programm_item->tv_programm_description_ru = $row_item->tv_programm_description_ru;
                $tv_programm_item->tv_programm_short_description_kz = $row_item->tv_programm_short_description_kz;
                $tv_programm_item->tv_programm_short_description_ru = $row_item->tv_programm_short_description_ru;
                $tv_programm_item->is_main_programm = $row_item->is_main_programm;
                $tv_programm_item->category_id = $row_item->category_id;
                $tv_programm_item->tv_programm_programm_id = $row_item->tv_programm_programm_id;

                if(strlen($row_item->image) > 0){
                    $file = './tv_programm_photo/' . $row_item->image;
                    $newfile = './tv_programm_photo/' . time() . $i . '_new.png';

                    if(file_exists('./tv_programm_photo/' . $row_item->image)) {
                        if (!copy($file, $newfile)) {

                        }
                        $tv_programm_item->image = time() . $i. '_new.png';
                    }
                }
                $tv_programm_item->save();
            }
        }
        return response()->json(['result'=>true]);
    }

    public function deliveryList(){
        $row = Delivery::paginate(30);
        return view('admin.delivery-list', [ 'row' => $row ]);
    }

    public function deliveryEdit(Request $request){
        $delivery_id = $request->delivery_id;

        $row = Delivery::find($delivery_id);
        if(count($row) < 1){
            $row = new Delivery();
            $row->delivery_id = 0;
        }
        return view('admin.delivery-edit', [ 'row' => $row]);
    }

    public function deleteDelivery(Request $request){
        $delivery_id = $request->delivery_id;
        $result = Delivery::where('delivery_id', '=', $delivery_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function saveDelivery(Request $request){
        $messages = array(
            'delivery_name.required' => 'Укажите ФИО',
            'delivery_email.required' => 'Укажите Email',
            'delivery_email.email' => 'Неправильный формат Email',
            'delivery_email.unique' => 'Подписчик с таким Email уже существует'
        );
        $validator = Validator::make($request->all(), [
            'delivery_name' => 'required',
            'delivery_email' => 'required|email|unique:delivery_tab'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.delivery-edit', [ 'row' => $request, 'result' => $result ]);
        }

        if($request->delivery_id > 0) {
            $row_item = Delivery::find($request->delivery_id);
        }
        else {
            $row_item = new Delivery();
        }

        $row_item->delivery_name = $request->delivery_name;
        $row_item->delivery_email = $request->delivery_email;
        if($row_item->save()){
            return redirect('/admin/delivery-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.delivery-edit', [ 'row' => $request, 'result' => $result]);
        }
    }

    public function multipleDelete(Request $request){
        if($request->table_name == "news"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $news_id = $arr_id;
                    $news_row = News::find($news_id);
                    if(count($news_row) > 0){
                        $this->deleteFile("news_photo",$news_row->image);
                    }
                    $result = News::where('news_id', '=', $news_id)->delete();
                }
            }
        }
        else if($request->table_name == "blog"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $blog_id = $arr_id;
                    $blog_row = Blog::find($blog_id);
                    if(count($blog_row) > 0){
                        $this->deleteFile("blog_photo",$blog_row->image);
                    }
                    $result = Blog::where('blog_id', '=', $blog_id)->delete();
                }
            }
        }
        else if($request->table_name == "event"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $event_id = $arr_id;
                    $event_row = Event::find($event_id);
                    if(count($event_row) > 0){
                        $this->deleteFile("event_photo",$event_row->image);
                    }
                    $result = Event::where('event_id', '=', $event_id)->delete();
                }
            }
        }
        else if($request->table_name == "news-archive-list-kz"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $news_id = $arr_id;

                    $news_row = NewsArchiveKz::find($news_id);
                    if(count($news_row) > 0){
                        $this->deleteFile("archive_news_kz",$news_row->image);
                    }
                    $result = NewsArchiveKz::where('news_id', '=', $news_id)->delete();
                }
            }
        }
        else if($request->table_name == "news-archive-list-ru"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $news_id = $arr_id;

                    $news_row = NewsArchiveRu::find($news_id);
                    if(count($news_row) > 0){
                        $this->deleteFile("archive_news_ru",$news_row->image);
                    }
                    $result = NewsArchiveRu::where('news_id', '=', $news_id)->delete();
                }
            }
        }
        else if($request->table_name == "programm-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $programm_id = $arr_id;
                    $programm_row = Programm::find($programm_id);
                    if(count($programm_row) > 0){
                        $this->deleteFile("programm_photo",$programm_row->image);
                    }
                    $result = ProgrammTime::where('programm_id', '=', $programm_id)->delete();
                    $result = Programm::where('programm_id', '=', $programm_id)->delete();
                }
            }
        }
        else if($request->table_name == "category-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $category_id = $arr_id;
                    $category_row = Category::find($category_id);
                    if(count($category_row) > 0){
                        $this->deleteFile("category_photo",$category_row->image);
                    }
                    $result = Category::where('category_id', '=', $category_id)->delete();
                }
            }
        }
        else if($request->table_name == "news-category-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $news_category_id = $arr_id;
                    $news_category_row = NewsCategory::find($news_category_id);
                    if(count($news_category_row) > 0){
                        $this->deleteFile("category_photo",$news_category_row->image);
                    }
                    $result = NewsCategory::where('news_category_id', '=', $news_category_id)->delete();
                }
            }
        }
        else if($request->table_name == "tv-programm-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $tv_programm_id = $arr_id;
                    $result = TVProgramm::where('tv_programm_id', '=', $tv_programm_id)->delete();
                }
            }
        }
        else if($request->table_name == "const-tv-programm-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $tv_programm_id = $arr_id;
                    $result = ConstTVProgramm::where('tv_programm_id', '=', $tv_programm_id)->delete();
                }
            }
        }
        else if($request->table_name == "vacancy-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $vacancy_id = $arr_id;
                    $result = Vacancy::where('vacancy_id', '=', $vacancy_id)->delete();
                }
            }
        }
        else if($request->table_name == "video-archive-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $video_archive_id = $arr_id;
                    $video_archive_row = VideoArchive::find($video_archive_id);
                    if(count($video_archive_row) > 0){
                        $this->deleteFile("video_archive_photo",$video_archive_row->image);
                    }

                    $result = VideoArchive::where('video_archive_id', '=', $video_archive_id)->delete();
                }
            }
        }
        else if($request->table_name == "tag-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $tag_id = $arr_id;
                    $result = Tag::where('tag_id', '=', $tag_id)->delete();
                }
            }
        }
        else if($request->table_name == "job-response-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $job_response_id = $arr_id;
                    $row = JobResponse::find($job_response_id);
                    if(count($row) > 0){
                        $this->deleteFile("response_files",$row->filename);
                    }
                    $result = JobResponse::where('job_response_id', '=', $job_response_id)->delete();
                }
            }
        }
        else if($request->table_name == "delivery-list"){
            if(count($request->arr) > 0){
                foreach($request->arr as $key => $arr_id){
                    $delivery_id = $arr_id;
                    $result = Delivery::where('delivery_id', '=', $delivery_id)->delete();
                }
            }
        }
    }

    public function saveTranslationLink(Request $request){
        $translation_link_row = TranslationLink::where("translation_link_id","=", $request->translation_link_id)->first();
        $translation_link_row->link = $request->link;
        if($translation_link_row->save()){
            return response()->json(['result'=>true]);
        }
        else{
            return response()->json(['result'=>false]);
        }
    }

    public function employerList(){
        $row = Employer::selectLocaled('ru')->orderBy(DB::raw('ISNULL(`order`), `order`'), 'ASC')->get();
        $items = DB::table('menu_items')->where('menu_id', 2)->where('type', 'employer')->get();
        return view('admin.employer.list', [ 'row' => $row, 'items'=> $items]);
    }
    public function employerEdit(Request $request, $id){
        $row = Employer::where("id","=",$id)->first();
        $items = DB::table('menu_items')->where('menu_id', 2)->where('type', 'employer')->get();
        
        if(count($row) < 1){
            $row = new Employer();
            $row->id = 0;
        }

        if ($row){
            return view('admin.employer.add-edit', [ 'row' => $row, 'items' => $items]);
        } else {
            abort(404);
        }
    }
    public function saveEmployer(Request $request){
        $messages = array('name.required' => 'Напишите Имя',);
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.employer.add-edit', [ 'row' => $request, 'result' => $result ]);
        }

        $old_file_name = "";
        $new_employer = 0;
        if($request->id > 0) {
            $item = Employer::find($request->id);
            $old_file_name = $item->image;
        }
        else{
            $item = new Employer();
            $new_employer = 1;
        }

        if($request->hasFile('image')){
            if(strlen($item->image)>6){
                $this->deleteFile("blog_photo",$old_file_name);
            }
            
            $file = $request->image;
            $filename = Str::random(20);
            $fullPath = $filename.'.'.$file->getClientOriginalExtension();
            $image = Image::make($file)
                ->resize(960, null, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($file->getClientOriginalExtension(), 75);

            Storage::disk('blog_photo')->put($fullPath, (string) $image );
            
            $item->image = $fullPath;
        }

        $item->name = $request->name;
        $item->position_kz = $request->position_kz;
        $item->position_ru = $request->position_ru;
        $item->description_kz = $request->description_kz;
        $item->description_ru = $request->description_ru;
        $item->mail = $request->mail;
        $item->number = $request->number;
        $item->menu_item_id = $request->menu_item_id;
        $item->fb = $request->fb;
        $item->insta = $request->insta;
        $item->telegram = $request->telegram;
        $item->vk = $request->vk;
        $item->youtube = $request->youtube;
        $item->order = $request->order;
        
        if($item->save()){
            return redirect('/admin/employer-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.employer.add-edit', [ 'row' => $request, 'result' => $result ]);
        }
    }
    public function deleteEmployer(Request $request){
        $id = $request->id;
        $row = Employer::find($id);
        if(count($row) > 0){
            if(count($row->image)>0){
                $this->deleteFile("blog_photo",$row->image);
            }
            $result = $row->delete();
            return response()->json(['result'=>$result]);
        }
        return response()->json(['result'=>false]);
    }

    public function favorites(){
        $row = Favorite::orderBy('created_at', 'DESC')->take(10)->get();
        $row = $this->correctFav($row);

        $row2 = DB::table('two_blocks')->orderBy('created_at', 'DESC')->take(10)->get();
        $row2 = $this->correctFav($row2);

        $row3 = DB::table('two_blogs')->orderBy('created_at', 'DESC')->take(10)->get();
        $row3 = $this->correctFav($row3);

        // dd($row3);
        return response()->view('admin.favorites.list', ['row'=>$row, 'row2'=>$row2, 'row3'=>$row3]);
    }

    public function favoritesSave(Request $request){
        $links = explode('/', $request->link);
        $url = array_pop($links);
        $type = array_pop($links);

        if($type=='news'){
            $source = News::select(DB::raw('news_id as id'),DB::raw('news_title_kz as is_active_kz'), DB::raw('news_title_ru as is_active_ru'))->where('news_url_name', $url)->first();
        } else if($type=='blogs'){
            $source = Blog::select(DB::raw('blog_id as id'), 'is_active_kz', 'is_active_ru')->where('blog_url_name', $url)->first();
        } else {
            $source = Event::select(DB::raw('event_id as id'))->where('event_url_name', $url)->first();
            $source->is_active_kz = 1;
            $source->is_active_ru = 1;
        }

        if($source){
            if($request->type == "fav"){
                $fav = new Favorite();
                $fav->type = $type;
                $fav->source_id = $source->id;
                $fav->is_active_kz = ($source->is_active_kz ? 1 : 0 );
                $fav->is_active_ru = ($source->is_active_ru ? 1 : 0 );
                $fav->save();    
            } else if($request->type == "two") {
                DB::table('two_blocks')->insert([
                    'type' => $type, 
                    'source_id' => $source->id,
                    'is_active_kz' => ($source->is_active_kz ? 1 : 0 ),
                    'is_active_ru' => ($source->is_active_ru ? 1 : 0 )
                ]);
            } else{
                if($type == 'blogs'){
                    DB::table('two_blogs')->insert([
                        'type' => $type, 
                        'source_id' => $source->id,
                        'is_active_kz' => ($source->is_active_kz ? 1 : 0 ),
                        'is_active_ru' => ($source->is_active_ru ? 1 : 0 )
                    ]);
                }
            }
        }
        return redirect('/admin/fav-list');
    }

    public function favoritesDelete(Request $request){
        if($request->type == 1){
            $fav = Favorite::find($request->fav_id);
            if($fav){
                $fav->delete();
                return response()->json(['result'=> true ]);
            }
        } else if($request->type == 2){
            $two = DB::table('two_blocks')->find($request->fav_id);
            if($two){
                DB::table('two_blocks')->where('id', $request->fav_id)->delete();
                return response()->json(['result'=> true ]);
            }
        } else{
            $blog = DB::table('two_blogs')->find($request->fav_id);
            if($blog){
                DB::table('two_blogs')->where('id', $request->fav_id)->delete();
                return response()->json(['result'=> true ]);
            }
        }
        return response()->json(['result'=> false]);
    }

    public function correctFav($row){
        foreach ($row as $item) {
            if($item->type=='news'){
                $source = News::select(DB::raw('news_title_ru as title_ru'), DB::raw('news_title_kz as title_kz'), DB::raw('news_url_name as url'), 'date')->where('news_id', $item->source_id)->first();
                $item->url = '/news/news/'.$source->url;
                $item->type_ru = 'Новости';
                $item->date = Date::parse($source->date)->format('d.m.Y');
            } else if($item->type=='blogs'){
                $source = Blog::select(DB::raw('blog_title_kz as title_kz'),DB::raw('blog_title_ru as title_ru'), DB::raw('blog_url_name as url'), 'date')->where('blog_id', $item->source_id)->first();
                $item->url = $source->url;
                $item->type_ru = 'Блог';
                $item->date = $source->date;
            } else {
                $source = Event::select(DB::raw('event_title as title_kz'), DB::raw('event_url_name as url'),DB::raw('date_start as date'))->where('event_id', $item->source_id)->first();
                $item->url = '/events/'.$source->url;
                $item->type_ru = 'События';
                $item->date = Date::parse($source->date)->format('d.m.Y');
            }
            $item->title_kz = $source->title_kz;
            $item->title_ru = $source->title_ru;
        }
        return $row;
    }

    public function footerList(){
        $row = DB::table('menu_items')->where('menu_id', 1)->orderBy('order')->get();
        $row = collect($row); 
        $main = $row->filter(function ($value, $key) {
            return !$value->parent_id;
        });
        return view('admin.footer.list', [ 'row' => $row, 'main' => $main]);
    }
    public function footerEdit(Request $request, $id){
        $row = DB::table('menu_items')->where("id","=",$id)->first();
        $items = DB::table('menu_items')->where('menu_id', 1)->orderBy('order')->get();
        $items = collect($items); 
        $main = $items->filter(function ($value, $key) {
            return !$value->parent_id;
        });
        
        if(count($row) < 1){
            $row = collect();
            $row->id = 0; $row->menu_id = 1;
            $row->name_kz = ""; $row->name_ru = "";
            $row->parent_id = null; $row->order = null; $row->url = null;
        }
        if ($row){
            return view('admin.footer.add-edit', [ 'row' => $row, 'main'=>$main ]);
        } else {
            abort(404);
        }
    }
    public function saveFooter(Request $request){
        // $messages = array('name.required' => 'Напишите Имя',);
        $validator = Validator::make($request->all(), [
            'name_kz' => 'required',
            'name_ru' => 'required',
            'menu_id' => 'required',
            // 'parent_id' => 'required',
            'order' => 'required',
            // 'url' => 'required'
        ]);

        if ($validator->fails()) {
            // return view('admin.employer.add-edit', [ 'row' => $request, 'result' => $result ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->id > 0) {
            $item = DB::table('menu_items')->where('id', $request->id)->update([
                'menu_id' => $request->menu_id,
                'name_kz' => $request->name_kz,
                'name_ru' => $request->name_ru,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'url' => $request->url
            ]);   
        } else{
            $item = [];
            DB::table('menu_items')->insert([
                'menu_id' => $request->menu_id,
                'name_kz' => $request->name_kz,
                'name_ru' => $request->name_ru,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'url' => $request->url
            ]);
        }
        return redirect('/admin/footer-list');
    }
    public function deleteFooter(Request $request){
        $id = $request->id;
        $row = DB::table('menu_items')->find($id);
        if(count($row) > 0){
            $result = DB::table('menu_items')->where('id', $id)->delete();
            DB::table('menu_items')->where('parent_id', $id)->update(['parent_id'=> null]);    
            return response()->json(['result'=>$result]);
        }
        return response()->json(['result'=>false]);
    }

    public function aboutList(){
        $row = DB::table('menu_items')->where('menu_id', 2)->orderBy('order')->get();
        $row = collect($row); 
        $main = $row->filter(function ($value, $key) {
            return !$value->parent_id;
        });
        return view('admin.about.list', [ 'row' => $row, 'main' => $main]);
    }
    public function aboutEdit(Request $request, $id){
        $row = DB::table('menu_items')->where("id","=",$id)->first();
        $items = DB::table('menu_items')->where('menu_id', 2)->orderBy('order')->get();
        $items = collect($items);
        $main = $items->filter(function ($value, $key) {
            return !$value->parent_id;
        });
        
        if(count($row) < 1){
            $row = collect();
            $row->id = 0; $row->menu_id = 2;
            $row->name_kz = ""; $row->name_ru = "";
            $row->parent_id = null; $row->order = null; $row->url = null;
            $row->type = null;
        }
        if ($row){
            return view('admin.about.add-edit', [ 'row' => $row, 'main'=>$main ]);
        } else {
            abort(404);
        }
    }
    public function saveAbout(Request $request){
        $validator = Validator::make($request->all(), [
            'name_kz' => 'required',
            'name_ru' => 'required',
            'menu_id' => 'required',
            // 'parent_id' => 'required',
            'order' => 'required',
            'url' => 'required',
            'type' => 'required'
        ]);

        if ($validator->fails()) {
            // return view('admin.employer.add-edit', [ 'row' => $request, 'result' => $result ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->id > 0) {
            $item = DB::table('menu_items')->where('id', $request->id)->update([
                'menu_id' => $request->menu_id,
                'name_kz' => $request->name_kz,
                'name_ru' => $request->name_ru,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'url' => $request->url,
                'type' => $request->type,
            ]);   
        } else{
            $item = [];
            DB::table('menu_items')->insert([
                'menu_id' => $request->menu_id,
                'name_kz' => $request->name_kz,
                'name_ru' => $request->name_ru,
                'parent_id' => $request->parent_id,
                'order' => $request->order,
                'url' => $request->url,
                'type' => $request->type,
            ]);
        }
        return redirect('/admin/about-list');
    }

    public function pageList(){
        $row = DB::table('pages')->get();
        $items = DB::table('menu_items')->where('menu_id', 2)->where('type', 'page')->get();
        return view('admin.pages.list', [ 'row' => $row, 'items'=> $items]);
    }
    public function pageEdit(Request $request, $id){
        $row = DB::table('pages')->where("id","=",$id)->first();
        $items = DB::table('menu_items')->where('menu_id', 2)->where('type', 'page')->get();
        
        if(count($row) < 1){
            $row = collect();
            $row->id = 0; $row->menu_item_id = 0;
            $row->text_kz = ""; $row->text_ru = "";
        }
        if ($row){
            return view('admin.pages.add-edit', [ 'row' => $row, 'items'=>$items ]);
        } else {
            abort(404);
        }
    }
    public function savePage(Request $request){
        $validator = Validator::make($request->all(), [
            'text_kz' => 'required',
            'text_ru' => 'required',
            // 'menu_item_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if($request->id > 0) {
            $item = DB::table('pages')->where('id', $request->id)->update([
                'menu_item_id' => $request->menu_item_id,
                'text_kz' => $request->text_kz,
                'text_ru' => $request->text_ru
            ]);   
        } else{
            DB::table('pages')->insert([
                'menu_item_id' => $request->menu_item_id,
                'text_kz' => $request->text_kz,
                'text_ru' => $request->text_ru
            ]);
        }
        return redirect('/admin/page-list');
    }
    public function deletePage(Request $request){
        $id = $request->id;
        $row = DB::table('pages')->find($id);
        if(count($row) > 0){
            $result = DB::table('pages')->where('id', $id)->delete();
            return response()->json(['result'=>$result]);
        }
        return response()->json(['result'=>false]);
    }

    public function documentList(){
        $row = DB::table('documents')->get();
        $items = DB::table('menu_items')->where('menu_id', 2)->where('type', 'document')->get();
        return view('admin.documents.list', [ 'row' => $row, 'items'=> $items]);
    }
    public function documentEdit(Request $request, $id){
        $row = DB::table('documents')->where("id","=",$id)->first();
        $items = DB::table('menu_items')->where('menu_id', 2)->where('type', 'document')->get();
        
        if(count($row) < 1){
            $row = collect();
            $row->id = 0; $row->menu_item_id = 0;
            $row->name_kz = ""; $row->name_ru = ""; $row->document = null;
        }
        if ($row){
            return view('admin.documents.add-edit', [ 'row' => $row, 'items'=>$items ]);
        } else {
            abort(404);
        }
    }
    public function saveDocument(Request $request){
        $validator = Validator::make($request->all(), [
            'name_kz' => 'required',
            'name_ru' => 'required',
            // 'document' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file_name = "";
        $file_extension = "";
        if($request->hasFile('document')){
            $file = $request->document;
            $file_name = time() . "_document.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('response_files')->put($file_name,  File::get($file));
        }
     
        if($request->id > 0) {
            if($request->hasFile('document')){
                $item = DB::table('documents')->where('id', $request->id)->first();
                $this->deleteFile("response_files", $item->document);
                
                $item = DB::table('documents')->where('id', $request->id)->update([
                    'menu_item_id' => $request->menu_item_id,
                    'name_kz' => $request->name_kz,
                    'name_ru' => $request->name_ru,
                    'document' => $file_name,
                    'type' => $file_extension
                ]);                
            } else {
                $item = DB::table('documents')->where('id', $request->id)->update([
                    'menu_item_id' => $request->menu_item_id,
                    'name_kz' => $request->name_kz,
                    'name_ru' => $request->name_ru
                ]);
            }
        } else{
            DB::table('documents')->insert([
                'menu_item_id' => $request->menu_item_id,
                'name_kz' => $request->name_kz,
                'name_ru' => $request->name_ru,
                'document' => $file_name,
                'type' => $file_extension
            ]);
        }
        return redirect('/admin/document-list');
    }
    public function deleteDocument(Request $request){
        $id = $request->id;
        $row = DB::table('documents')->find($id);
        if(count($row) > 0){
            $this->deleteFile("response_files", $row->document);
            $result = DB::table('documents')->where('id', $id)->delete();
            return response()->json(['result'=>$result]);
        }
        return response()->json(['result'=>false]);
    }

    public function adsList(){
        $row = DB::table('ads_tab')->orderBy('created_at','desc')->get();
        return view('admin.ads.list', [ 'row' => $row]);
    }
    public function adsEdit(Request $request, $id){
        $row = DB::table('ads_tab')->where("id","=",$id)->first();

        if(count($row) < 1){
            $row = collect();
            $row->id = 0; $row->file = null; $row->file_type = null;
            $row->locale = "ru"; $row->type = null; $row->name = "";
        }
        if ($row){
            return view('admin.ads.add-edit', [ 'row' => $row ]);
        } else {
            abort(404);
        }
    }
    public function saveAds(Request $request){
    	$validator = Validator::make($request->all(), [
            'locale' => 'required',
            'type' => 'required',
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file_name = "";
        $file_extension = "";
        if($request->hasFile('file')){
            $file = $request->file;
            $file_name = 'ads_'.time() . "_document.";
            $file_extension = $file->extension($file_name);
            $file_name = $file_name . $file_extension;
            Storage::disk('response_files')->put($file_name,  File::get($file));
        }
     
        if($request->id > 0) {
            if($request->hasFile('file')){
                $item = DB::table('ads_tab')->where('id', $request->id)->first();
                $this->deleteFile("response_files", $item->file);
                
                $item = DB::table('ads_tab')->where('id', $request->id)->update([
                    'locale' => $request->locale,
                    'type' => $request->type,
                    'file' => $file_name,
                    'name' => $request->name,
                    'file_type' => $file_extension
                ]);                
            } else {
                $item = DB::table('ads_tab')->where('id', $request->id)->update([
                    'locale' => $request->locale,
                    'type' => $request->type,
                    'name' => $request->name,
                ]);
            }
        } else{
            DB::table('ads_tab')->insert([
                'locale' => $request->locale,
                'type' => $request->type,
                'name' => $request->name,
                'file' => $file_name,
                'file_type' => $file_extension
            ]);
        }
        return redirect('/admin/ads-list');
    }
    public function deleteAds(Request $request){
        $id = $request->id;
        $row = DB::table('ads_tab')->find($id);
        if(count($row) > 0){
            $this->deleteFile("response_files", $row->file);
            $result = DB::table('ads_tab')->where('id', $id)->delete();
            return response()->json(['result'=>$result]);
        }
        return response()->json(['result'=>false]);
    }
}