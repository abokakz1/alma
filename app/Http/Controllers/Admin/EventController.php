<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Blog;
use App\Models\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\BlogTag;
use App\Models\Push;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Intervention\Image\Constraint;
use App\Models\Media;
use App\Models\Favorite;

class EventController extends Controller
{

    public function eventList(Request $request, $user_id = null){

        $event_date = "";
        $event_date_to = "";
        $name = "";
        $event_lang_id = "";
        $category_id = "";
        
        if(isset($request->date)){
            $event_date = $request->date;
        }

        if(isset($request->date_to)){
            $event_date_to = $request->date_to;
        }

        if(isset($request->name)){
            $name = $request->name;
        }

        if(isset($request->category_id)){
            $category_id = $request->category_id;
        }

        if(isset($request->event_lang_id)){
            $event_lang_id = $request->event_lang_id;
        }

        $row = Event::select('event_tab.*',
                DB::raw('DATE_FORMAT(event_tab.date_start,"%d.%m.%Y  %T") as date'))
            ->orderByRaw("event_tab.created_at desc");

        if(strlen($event_date) > 0){
            $row->where(DB::raw('DATE_FORMAT(event_tab.date_start,"%Y-%m-%d")'),">=", date('Y-m-d', strtotime($event_date)));
        }

        if(strlen($event_date_to) > 0){
            $row->where(DB::raw('DATE_FORMAT(event_tab.date_start,"%Y-%m-%d")'),"<=", date('Y-m-d', strtotime($event_date_to)));
        }

        if(strlen($event_lang_id) > 0 && $event_lang_id > 0){
            if($event_lang_id == 1){
                $row->where("event_tab.is_main_event", true);
            }
            else if($event_lang_id == 2){
                $row->where("event_tab.published", true);
            }
            else if($event_lang_id == 3){
                $row->where("event_tab.in_calendar", true);
            }
            else if($event_lang_id == 4){
                $row->where("event_tab.price", '=', 0);
            }
            else if($event_lang_id == 5){
                $row->where("event_tab.price", '>', 0);
            }
        }

        if(strlen($name) > 0){
            $row->where('event_tab.event_title',"like", '%'.$name.'%');
        }

        if(strlen($category_id) > 0 && $category_id > 0){
            $row->LeftJoin("event_category_tab","event_tab.event_id","=","event_category_tab.event_id")->where('event_category_tab.category_id',"=",$category_id)->groupBy("event_tab.event_id");
        }

        // $row = $row->get();
        $row = $row->paginate(20);

        return view('admin.event.list', [ 'row' => $row,'event_date' => $event_date, 'name' => $name, 'event_date_to' => $event_date_to, 'category_id' => $category_id, 'event_lang_id' => $event_lang_id ]);
    }

    public function deleteEvent(Request $request){
        $event_id = $request->event_id;
        $event_row = Event::find($event_id);
        if(count($event_row) > 0){
            $this->deleteFile("event_photo",$event_row->image);

            $medias = Media::where('event_id','=',$event_row->event_id)->get();
            foreach($medias as $media){
                $this->deleteFile("event_photo", $media->link);
                $media->delete();  
            }
            Favorite::where('source_id',$event_id)->where('type','events')->delete();
            DB::table('two_blocks')->where('source_id',$event_id)->where('type','events')->delete();
            DB::table('two_blogs')->where('source_id',$event_id)->where('type','events')->delete();
        }
        $result = Event::where('event_id', '=', $event_id)->delete();
        return response()->json(['result'=>$result]);
    }

    public function eventEdit(Request $request, $event_id){
        $row = Event::with(['media'=>function($query){ $query->select('*');}])
        // ->select("event_tab.*", DB::raw('DATE_FORMAT(event_tab.date_start,"%d.%m.%Y  %T")'))
        ->where("event_id","=",$event_id)->first();
        if(count($row) < 1){
            $row = new Event();
            $row->event_id = 0;
        }

        // dd($row);
        if ($row){
            return view('admin.event.add-edit', [ 'row' => $row ]);
        }
        else {
            abort(404);
        }
    }

    // STORE THE EVENT
    public function saveEvent(Request $request){
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
            // 'lat' => 'required',
            // 'lng' => 'required',
            // 'organizer_name' => 'required',
            // 'organizer_email' => 'required|email',
            'organizer_number' => 'required'    
        ], $messages);

        if ($validator->fails()) {
            $messages = $validator->errors();
            $error = $messages->all();

            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.event.add-edit', [ 'row' => $request, 'result' => $result ]);
        }

        $old_file_name = "";
        $new_event = 0;
        if($request->event_id > 0) {
            $event = Event::find($request->event_id);
            $old_file_name = $event->image;
        }
        else{
            $event = new Event();
            $new_event = 1;
        }

        // if($request->hasFile('image')){
        //     $this->deleteFile("event_photo",$old_file_name);

        //     $file = $request->image;
        //     $filename = Str::random(20);
        //     $fullPath = $filename.'.'.$file->getClientOriginalExtension();
        //     // $fullPath2 = $filename.'-300x300.'.$file->getClientOriginalExtension();
        //     $image = Image::make($file)
        //         ->resize(960, null, function (Constraint $constraint) {
        //             $constraint->aspectRatio();
        //             $constraint->upsize();
        //         })
        //         ->encode($file->getClientOriginalExtension(), 75);

        //     Storage::disk('event_photo')->put($fullPath, (string) $image );

            // $image2 = Image::make($file)
            //     ->fit(300, 300, function (Constraint $constraint) {
            //         $constraint->aspectRatio();
            //         $constraint->upsize();
            //     })
            //     ->encode($file->getClientOriginalExtension(), 75);

            // Storage::disk('event_photo')->put($fullPath2, (string) $image2 );

        //     $event->image = $fullPath;
        // }

        $event->event_title = $request->event_title;
        $event->event_text = $request->event_text;

        $event->date_start = date('Y-m-d H:i:s', strtotime($request->date_start .' '. $request->time));
        if($request->date_end != "" && $request->date_end != null){
            $event->date_end = date('Y-m-d', strtotime($request->date_end . ' ' . $request->time_end));
        }
        
        $event->price = ($request->price==null ? 0 : $request->price);
        $event->is_main_event = $request->is_main_event;
        $event->address = $request->address;
        $event->published = $request->published;
        // $event->lat = $request->lat;
        // $event->lng = $request->lng;
        
        $event->in_calendar = ($request->in_calendar==null ? 0 : 1);
        $event->organizer_name = $request->organizer_name;
        $event->organizer_email = $request->organizer_email;
        $event->organizer_number = $request->organizer_number;

        $event->event_url_name = $this->cyr2lat($request->event_title);

        $url = Event::where('event_url_name', $event->event_url_name)->get();
        if(count($url)>0){
            $event->event_url_name = $event->event_url_name . '_' . rand(99,999);
        }

        if($event->save()){
            if($request->category_id_new){
                for($j=0; $j<count($request->category_id_new); $j=$j+1){
                    $event->categories()->attach($request->category_id_new[$j]);
                }
            }
            
            return redirect('/admin/event-list');
        }
        else{
            $error[0] = 'Ошибка при сохранении';
            $result['value'] = $error;
            $result['status'] = false;
            return view('admin.event.add-edit', [ 'row' => $request, 'result' => $result ]);
            // return response()->json(['row' => $request, 'result' => $result ]);
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

    public function deleteFile($path,$old_file_name){
        if(strlen($old_file_name) > 0 && File::exists(public_path( $path . '/' . $old_file_name))){
            Storage::disk($path)->delete($old_file_name);
        }
    }

    public function deleteEventCategory(Request $request){
        $category_id = $request->id;
        $event_row = Event::find($request->event_id);

        $result = $event_row->categories()->detach($category_id);

        return response()->json(['result'=>$result]);
    }

    public function fileUpload($event_id, Request $request){
        
        if($request->hasFile('file')){
            $event = Event::find($event_id);

            if($event){
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
                $media->event_id = $event_id;
                $client_filename = $file->getClientOriginalName();

                if($media->save()){
                    return response()->json(['media_id' => $media->media_id, 'media_name'=> $client_filename], 200);
                }
            }
        }
        
        return response()->json(['media_id' => null], 400);
    }

    public function changeAvatar(Request $request){
        $event = Event::find($request->event_id);
        if($event){
            $event->image_id = $request->media_id;
            if($event->save()){
                return response()->json(['result' => true], 200);
            }
        }
        return response()->json(['result' => false], 200);
    }
}
