<?php

namespace App\Http\Controllers\Index;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\BlogTag;
use App\Models\Push;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use App\Models\Users;
use App\Models\Role;
use Illuminate\Routing\Redirect;

class BloggerController extends Controller
{

	public function __construct()
	{
	   	$this->middleware('auth');
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


    public function blogList(Request $request, $user_id = null){

        $blog_date = "";
        $blog_date_to = "";
        $name = "";
        $blog_lang_id = "";
        $tag_id = "";
        if(isset($request->date)){
            $blog_date = $request->date;
        }

        if(isset($request->date_to)){
            $blog_date_to = $request->date_to;
        }

        if(isset($request->name)){
            $name = $request->name;
        }

        if(isset($request->tag_id)){
            $tag_id = $request->tag_id;
        }

        if(isset($request->blog_lang_id)){
            $blog_lang_id = $request->blog_lang_id;
        }

        // if(Auth::user()->role_id != 1){
        //     $user_id = Auth::user()->user_id;
        // }
        // $row = Blog::select('blog_tab.*',
        //         DB::raw('DATE_FORMAT(blog_tab.date,"%d.%m.%Y  %T") as date'))
        //         ->when($user_id, function ($query) use ($user_id) {
        //             return $query->where('author_id', $user_id);
        //         })
        //     ->orderByRaw("blog_tab.date desc");

        // if(Auth::user()->role_id != 1){
            $user_id = Auth::user()->user_id;
            $row = Blog::select('blog_tab.*')
                // ->when($user_id, function ($query) use ($user_id) {
                //     return $query->where('author_id', $user_id);
                // })
            ->where('author_id', $user_id)
            ->orderByRaw("blog_tab.date desc");
        // } else{
        //     $row = Blog::select('blog_tab.*',
        //         DB::raw('DATE_FORMAT(blog_tab.date,"%d.%m.%Y  %T") as date'))
        //         // ->when($user_id, function ($query) use ($user_id) {
        //         //     return $query->where('author_id', $user_id);
        //         // })
        //     ->orderByRaw("blog_tab.date desc");    
        // }

        if(strlen($blog_date) > 0){
            $row->where('blog_tab.date',">=", date('Y-m-d', strtotime($blog_date)));
        }

        if(strlen($blog_date_to) > 0){
            $row->where('blog_tab.date',"<=", date('Y-m-d', strtotime($blog_date_to)));
        }

        if(strlen($blog_lang_id) > 0 && $blog_lang_id > 0){
            if($blog_lang_id == 1){
                $row->whereNotNull("blog_tab.blog_title_kz")->where("blog_tab.blog_title_kz", "!=","");
                if(strlen($name) > 0){
                    $row->where('blog_tab.blog_title_kz',"like", '%'.$name.'%');
                }
            }
            else{
                $row->whereNotNull("blog_tab.blog_title_ru")->where("blog_tab.blog_title_ru", "!=","");
                if(strlen($name) > 0){
                    $row->where('blog_tab.blog_title_ru',"like", '%'.$name.'%');
                }
            }
        }
        else if(strlen($name) > 0){
            $row->where('blog_tab.blog_title_kz',"like", '%'.$name.'%')->orWhere('blog_tab.blog_title_ru',"like", '%'.$name.'%');
        }

        if(strlen($tag_id) > 0 && $tag_id > 0){
            $row->LeftJoin("blog_tag_tab","blog_tab.blog_id","=","blog_tag_tab.blog_id")->where('blog_tag_tab.tag_id',"=",$tag_id)->groupBy("blog_tab.blog_id");
        }

        $row = $row->get();

        return view('admin.blogger.admin-blogs', [ 'row' => $row,'blog_date' => $blog_date, 'name' => $name, 'blog_date_to' => $blog_date_to, 'tag_id' => $tag_id, 'blog_lang_id' => $blog_lang_id ]);
    }

    public function deleteBlog(Request $request){
        $blog_id = $request->blog_id;
        $blog_row = Blog::find($blog_id);
        if(count($blog_row) > 0){
            if(strlen($blog_row->image)>6){
                $this->deleteFile("blog_photo",$blog_row->image);
            }
            BlogTag::where('blog_id','=', $blog_row->blog_id)->delete();
        }

        $result = Blog::where('blog_id', '=', $blog_id)->delete();
        if(count($blog_row)>0){
            Favorite::where('source_id',$blog_id)->where('type','blogs')->delete();
            DB::table('two_blocks')->where('source_id',$blog_id)->where('type','blogs')->delete();
            DB::table('two_blogs')->where('source_id',$blog_id)->where('type','blogs')->delete();
        }
        return response()->json(['result'=>$result]);
    }

    public function blogEdit(Request $request, $blog_id){
        if (Auth::user()->role_id == 1){
            $row = Blog::select("blog_tab.*")->where("blog_id","=",$blog_id)->first();
            if(count($row) < 1){
                $row = new Blog();
                $row->blog_id = 0;
            }
        }
        else {
            $row = Blog::select("blog_tab.*")
                ->where('author_id', Auth::user()->user_id)
                ->where("blog_id","=",$blog_id)->first();
            if($blog_id < 1){
                $row = new Blog();
                $row->blog_id = 0;
            }
        }

        if ($row){
            return view('admin.blogger.admin-add-blog', [ 'row' => $row ]);
        }
        else {
            abort(404);
        }
    }

    public function saveBlog(Request $request){
        $messages = array(
            'date.required' => 'Укажите Дату',
            'time.required' => 'Укажите Время'
        );
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'time' => 'required'
        ], $messages);

        if ($validator->fails() || ($request->blog_title_ru=="" && $request->blog_title_kz=="")) {
            $messages = $validator->errors();
            $error = $messages->all();

            if($request->blog_title_ru=="" && $request->blog_title_kz==""){
                array_push($error, 'Заполняйте имя блога');
            }

            $result['value'] = $error;
            $result['status'] = false;
            
            return view('admin.blogger.admin-add-blog', [ 'row' => $request, 'result' => $result ]); // Tekseru kerek
        }

        $old_file_name = "";
        $new_blog = 0;
        if($request->blog_id > 0) {
            $blog_item = Blog::find($request->blog_id);
            $old_file_name = $blog_item->image;
        }
        else{
            $blog_item = new Blog();
            $new_blog = 1;
        }

        if($request->hasFile('image')){
            if(strlen($blog_item->image)>6){
                $this->deleteFile("blog_photo",$old_file_name);
            }

            $file = $request->image;
            $filename = Str::random(20);
            $fullPath = $filename.'.'.$file->getClientOriginalExtension();
            $fullPath2 = $filename.'-300x300.'.$file->getClientOriginalExtension();
            $image = Image::make($file)
                ->resize(960, null, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($file->getClientOriginalExtension(), 75);

            Storage::disk('blog_photo')->put($fullPath, (string) $image );

            $image2 = Image::make($file)
                ->fit(300, 300, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($file->getClientOriginalExtension(), 75);

            Storage::disk('blog_photo')->put($fullPath2, (string) $image2 );

            $blog_item->image = $fullPath;
        } else if($new_blog == 1){
            $blog_item->image = $request->def_images . '.jpg';
        }

        $blog_item->blog_title_kz = $request->blog_title_kz;
        $blog_item->blog_title_ru = $request->blog_title_ru;
        $blog_item->blog_title_en = $request->blog_title_en;
        $blog_item->blog_text_kz = $request->blog_text_kz;
        $blog_item->blog_text_ru = $request->blog_text_ru;
        $blog_item->blog_text_en = $request->blog_text_en;
        $blog_item->is_main_blog = $request->is_main_blog;
        $blog_item->date = date('Y-m-d', strtotime($request->date)) . " " . $request->time;
        $blog_item->is_active_ru = $request->is_active_ru;
        $blog_item->is_active_kz = $request->is_active_kz;
        $blog_item->is_fix = ($request->is_fix==null ? 0 : 1);
        //dd($request->is_fix==null ? 0 : 1);
        $blog_item->blog_meta_desc = $request->blog_meta_desc;
        $blog_item->is_has_foto = ($request->is_has_foto==null ? 0 : 1);
        $blog_item->is_has_video = ($request->is_has_video==null ? 0 : 1);
        $blog_item->video_url = $request->video_url;
        if ($request->has('video_url') && $request->video_url != "") {
            $v_url = explode("/", $request->video_url);
            $v_url = explode("=", array_pop($v_url));
            $blog_item->video_url = array_pop($v_url);
        }
        $blog_item->author_id = Auth::user()->user_id;

        if(strlen($request->blog_title_ru) > 0){
            $blog_item->blog_url_name = $this->cyr2lat($request->blog_title_ru);
        }
        else{
            $blog_item->blog_url_name = $this->cyr2lat($request->blog_title_kz);
        }
        if($blog_item->save()){

            if(isset($request['tag_id'])){
                foreach($request['tag_id'] as $key => $value){
                    if($value > 0 && $blog_item->blog_id > 0){
                        $row_item2 = BlogTag::find($key);
                        $row_item2->tag_id = $value;
                        $row_item2->blog_id = $blog_item->blog_id;
                        $row_item2->save();
                    }
                }
            }

            if(isset($request['tag_id_new'])){
                foreach($request['tag_id_new'] as $key => $value){
                    if($value > 0 && $blog_item->blog_id > 0) {
                        $row_item2 = new BlogTag();
                        $row_item2->tag_id = $value;
                        $row_item2->blog_id = $blog_item->blog_id;
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
                            $param['message'] = 'У нас новый блог для вас';
                            $param['id'] = $blog_item->blog_id;
                            $param['kind'] = 'blog';
                            $message = $param;
                            $result1 = $push->sendMessageToAndroid($registration_id,$message);
                        }
                        else if($val->os == 'iOS'){
                            $registration_id = array($val->registration_id);
                            $param['message'] = 'У нас новый блог для вас';
                            $param['id'] = $blog_item->blog_id;
                            $param['kind'] = 'blog';
                            $message = $param;
                            $result1 = $push->sendMessageToIOS($registration_id,$message);
                        }
                    }
                }
            }

            return redirect('/blogger/blog-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            
            return view('admin.blogger.admin-add-blog', [ 'row' => $request, 'result' => $result ]);
        }
    }

    public function deleteFile($path,$old_file_name){
        if(strlen($old_file_name)>5){
            if(strlen($old_file_name) > 0 && File::exists(public_path( $path . '/' . $old_file_name))){
                Storage::disk($path)->delete($old_file_name);

                $img300 = explode(".",$old_file_name);
                $img300 = strlen(end($img300))+1;
                $img300 = substr($old_file_name, 0, -$img300 ) . '-300x300' . substr($old_file_name, -$img300);
                
                if(File::exists(public_path( $path . '/' . $img300))){
                    Storage::disk($path)->delete($img300);    
                }
            }
        }
    }

    public function userEdit(Request $request, $user_id = null){
        
        $user_id = Auth::user()->user_id;
        $row = Users::find($user_id);

        if(count($row) < 1){
            $row = new Users();
            $row->user_id = 0;
        }
        return view('admin.blogger.admin-user', ['row' => $row]);
    }

    public function saveUser($user_id = null, Request $request){
        if (empty($user_id)){
            $user_id = $request->user_id;
        }

        $row = Users::find($user_id);
        
        if(count($row) > 0){
            $messages = array(
                'fio.required' => 'Укажите ФИО',
                'email.required' => 'Укажите Email',
                'username.required' => 'Укажите имя Пользователь',
                'email.email' => 'Неправильный формат Email',
            );
            $validator = Validator::make($request->all(), [
                'fio' => 'required',
                'email' => 'required|email',
                'username' => 'required'
            ], $messages);

            $emails = Users::where('user_id', '<>', $user_id)->where('email', '=', $request->email)->get();
            $usernames = Users::where('user_id', '<>', $user_id)->where('username', '=', $request->username)->get();

            if ($validator->fails() || (count($emails)>0 || count($usernames)>0)) {
                $messages = $validator->errors();
                $error = $messages->all();
                $result['value'] = $error;
                $result['status'] = false;

                if(count($emails)>0){
                    array_push($result['value'], 'Пользователь с таким Email уже существует');
                }
                if(count($usernames)>0){
                    array_push($result['value'], 'Пользователь с таким именем(Login) уже существует');   
                }
                return view('admin.blogger.admin-user', [ 'row' => $request, 'result' => $result ]);
                // return redirect()->back()->withErrors([ 'row' => $request, 'role_list' => $role_list, 'result' => $result ]);
            }

            $row->fio = $request->fio;
            $row->email = strtolower($request->email);
            $row->quote = $request->quote;
            $row->username = strtolower($request->username);

            if($request->hasFile('image')){

                $old_file_name = $row->image;
                $this->deleteFile("user_photo",$old_file_name);
                $file = $request->image;
                $file_name = time() . "_user.";
                $file_extension = $file->extension($file_name);
                $file_name = $file_name . $file_extension;

                Storage::disk('user_photo')->put($file_name,  File::get($file));
                $row->image = $file_name;
            }

            $row->save();

            return redirect()->route('user-edit_new', ['user_id' => $row->user_id] );
        }
    }

    public function deleteBlogTag(Request $request){
        $blog_tag_id = $request->id;
        $result = BlogTag::find($blog_tag_id)->delete();

        return response()->json(['result'=>$result]);
    }
}
