<?php

namespace App\Http\Controllers\Admin;

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

class BlogController extends Controller
{

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
        $row=[];
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

        if(Auth::user()->role_id != 1){
            $user_id = Auth::user()->user_id;
                    $row = Blog::select('blog_tab.*')
                // ->when($user_id, function ($query) use ($user_id) {
                //     return $query->where('author_id', $user_id);
                // })
            ->where('author_id', $user_id)
            ->orderByRaw("blog_tab.date desc");
        } else{
            $row = Blog::select('blog_tab.*')
                // ->when($user_id, function ($query) use ($user_id) {
                //     return $query->where('author_id', $user_id);
                // })
            ->orderByRaw("blog_tab.date desc");    
        }


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

        // $row = $row->get();
        $row = $row->paginate(20);

        return view('admin.blog.list', [ 'row' => $row,'blog_date' => $blog_date, 'name' => $name, 'blog_date_to' => $blog_date_to, 'tag_id' => $tag_id, 'blog_lang_id' => $blog_lang_id ]);
    }

    public function deleteBlog(Request $request){
        $blog_id = $request->blog_id;
        $blog_row = Blog::find($blog_id);
        if(Auth::user()->user_id == $blog_row->author_id || Auth::user()->role_id==1){
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
        return response()->json(['result'=>0]);
    }

    public function blogEdit(Request $request, $blog_id){

        // DB::raw('DATE_FORMAT(blog_tab.date,"%d.%m.%Y  %T") as date')
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
            return view('admin.blog.add-edit', [ 'row' => $row ]);
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

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.blog-edit', [ 'row' => $request, 'result' => $result ]);
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

            //dd($image);
            Storage::disk('blog_photo')->put($fullPath, (string) $image );

            $image2 = Image::make($file)
                ->fit(300, 300, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->encode($file->getClientOriginalExtension(), 75);

            Storage::disk('blog_photo')->put($fullPath2, (string) $image2 );

            $blog_item->image = $fullPath;
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
        $blog_item->is_has_foto = $request->is_has_foto;
        $blog_item->is_has_video = $request->is_has_video;
        $blog_item->video_url = $request->video_url;
        if ($request->has('video_url') && $request->video_url != "") {
            $v_url = explode("/", $request->video_url);
            $v_url = explode("=", array_pop($v_url));
            $blog_item->video_url = array_pop($v_url);
        }

        if(!$blog_item->author_id){
            $blog_item->author_id = Auth::user()->user_id;
        }
        //$blog_item->author_id = Auth::user()->user_id;

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

            return redirect('/admin/blog-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.blog.edit', [ 'row' => $request, 'result' => $result ]);
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
}
