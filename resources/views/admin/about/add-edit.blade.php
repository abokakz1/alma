@extends('admin.layout')

@section('content')
    <style>
        select,input{
            margin-bottom: 0 !important;
        }
        .right-block{
            width: auto;
            text-align: center;
        }
    </style>
    <div class="row-fluid">
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#main_data">
                        <i class="green icon-home bigger-110"></i>
                        Основные данные
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane in active" id="main_data">
                    <p style="color: red; font-size: 14px; text-align: center;">
                    @if(! empty($errors->all()))
                        @foreach ($errors->all() as $message)
                            {{$message}} <br>
                        @endforeach
                    @endif
                    </p>
                    <form id="myform" enctype="multipart/form-data" method="post" action="/admin/about-edit/{{$row->id}}">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$row->id}}">
                            <input type="hidden" name="menu_id" value="{{$row->menu_id}}">

                            <div class="left-block">
                                <p>Имя(Каз)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="name_kz" value="{{$row->name_kz}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Имя(Рус)</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="name_ru" value="{{$row->name_ru}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Порядок</p>
                            </div>
                            <div class="right-block">
                                <input type="number" name="order" value="{{$row->order}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Ccылка</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="url" value="{{$row->url}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Тип</p>
                            </div>
                            <div class="right-block tag-select-block" style="width: 535px;">
                            <div class="tag-id-select" style="margin-bottom: 5px;">
                                <select name="type" style="float: left; width: 178px">
                                    <option value="empty">Пустой тип</option>
                                    <option value="page" @if($row->type == 'page') selected @endif>Page</option>
                                    <option value="employer" @if($row->type == 'employer') selected @endif>Employer</option>
                                    <option value="document" @if($row->type == 'document') selected @endif>Document</option>
                                </select>
                                <div class="clearfloat"></div>
                            </div>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Парент</p>
                            </div>
                            <div class="right-block tag-select-block" style="width: 535px;">
                            <div class="tag-id-select" style="margin-bottom: 5px;">
                                <select name="parent_id" style="float: left; width: 178px">
                                    <option value="0">Выберите парент</option>
                                    @if(count($main) > 0)
                                        @foreach($main as $key => $item)
                                            @if($item->id != $row->id)
                                                <option value="{{$item->id}}" @if($item->id == $row->parent_id) selected @endif>{{$item->name_ru}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div class="clearfloat"></div>
                            </div>
                            </div>
                            <div class="clearfloat"></div>

                            <div style="margin-top: 10px; float: right;">
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </div>
                            <div class="clearfloat"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<script>
$(document).ready(function(){
    $("input[name=name_ru]").on('input', function() {
        let val = $(this).val();
        val = transliterate(sanitize(val));
        $("input[name=url]").val(val);
    });
});

function sanitize (value){
    let slug
    let valueLower = value.toLowerCase()
    slug = valueLower.replace(/e|é|è|ẽ|ẻ|ẹ|ê|ế|ề|ễ|ể|ệ/gi, 'e')
    slug = slug.replace(/a|á|à|ã|ả|ạ|ă|ắ|ằ|ẵ|ẳ|ặ|â|ấ|ầ|ẫ|ẩ|ậ/gi, 'a')
    slug = slug.replace(/o|ó|ò|õ|ỏ|ọ|ô|ố|ồ|ỗ|ổ|ộ|ơ|ớ|ờ|ỡ|ở|ợ/gi, 'o')
    slug = slug.replace(/u|ú|ù|ũ|ủ|ụ|ư|ứ|ừ|ữ|ử|ự/gi, 'u')
    slug = slug.replace(/đ/gi, 'd')
    slug = slug.replace(/\s+/g, '-')
    slug = slug.replace(/\-\-+/g, '-')
    slug = slug.replace(/\_+/g, '-')
    slug = slug.replace(/\'+/g, '')
    slug = slug.replace(/\s*$/g, '')
    slug = slug.replace(/\s+/g, '-')
    return this.transliterate(slug)
}

function transliterate (value) {
    let cyrillic = ['щ', 'ш', 'ч', 'ц',  'ю', 'я', 'ё',
            'ж', 'ъ', 'ы', 'э', 'а', 'б', 'в', 'г', 'д',
            'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о',
            'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ь', 'ә',
            'і', 'ң', 'ғ', 'ү', 'ұ', 'қ', 'ө', 'һ'],
        latinic = ['shh', 'sh', 'ch', 'cz', 'yu', 'ya', 'yo',
            'zh', '', 'y', 'e', 'a', 'b', 'v', 'g', 'd',
            'e', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
            'p', 'r', 's', 't', 'u', 'f', 'h', '', 'a',
            'i', 'n', 'g', 'u', 'u', 'k', 'o', 'h']

    for(let i = 0; i < cyrillic.length; i++) {
        value = value.split(cyrillic[i]).join(latinic[i]);
    }
    return value
}
</script>
@endsection
