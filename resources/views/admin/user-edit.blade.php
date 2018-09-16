@extends('admin.layout')

@section('content')
    <style>
        select,input{
            margin-bottom: 0px !important;
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
                    @if(isset($result['status']))
                        <p style="color: red; font-size: 14px; text-align: center;">
                            @if(count($result['value']) > 0)
                                @foreach($result['value'] as $key => $error_item)
                                    {{ $error_item }} <br>
                                @endforeach
                            @endif
                        </p>
                    @endif
                    <form id="myform" method="post" enctype="multipart/form-data" action="/admin/user-edit/{{$row->user_id}}" style="margin-bottom: 0px;">
                        <div style="float: left; width: 900px;">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" value="{{$row->user_id}}">

                            <div class="left-block">
                                <p>ФИО</p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="fio" value="{{$row->fio}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Email </p>
                            </div>
                            <div class="right-block">
                                <input type="text" name="email" value="{{$row->email}}">
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Роль</p>
                            </div>
                            <div class="right-block">
                                <select name="role_id">
                                    <option value="0">Выберите роль</option>
                                    @if(count($role_list) > 0)
                                        @foreach($role_list as $key => $role_item)
                                            <option value="{{$role_item->role_id}}" @if($role_item->role_id == $row->role_id) selected @endif >{{$role_item['role_name']}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="clearfloat"></div>

                            <div class="left-block">
                                <p>Заблокирован</p>
                            </div>
                            <div class="right-block">
                                <input type="hidden" name="is_blocked" value="{{$row->is_blocked}}" class="hidden-checkbox-value">
                                <label style="margin-top: 5px;">
                                    <?php $checked1 = " "; ?>
                                        @if($row->is_blocked == 1)
                                            <?php $checked1 = " checked"; ?>
                                        @endif
                                    <input class="ace ace-switch ace-switch-5 <?php echo $checked1?>" type="checkbox" <?php echo $checked1?> value="{{$row->is_blocked}}" onchange="changeCheckboxValue(this)"/>
                                    <span class="lbl"></span>
                                </label>
                            </div>
                            <div class="clearfloat"></div>
                        </div>

                        <div style="float: left; margin: -6px 0px 0px -313px; text-align: center;">
                            <div>
                                @if(strlen($row->image) > 0)
                                    <img src="/user_photo/{{$row->image}}" width="150px" height="150px" id="user_image">
                                @else
                                    <img src="/css/image/no_photo.png" width="150px" height="150px" id="user_image">
                                @endif
                            </div>
                            <div style="text-align: center; margin: 10px 0px 0px 20px;">
                                {{--<input type="button" value="Загрузить" class="btn btn-primary" onclick = "$('#fileupload').click()">--}}
                                <input id="fileupload" type="file" name="image">
                            </div>
                        </div>

                        @if($row->user_id > 0)
                            <div style="margin-top: 200px; float: right;">
                                <input type="submit" class="btn btn-primary" value="Сохранить">
                            </div>
                        @else
                            <div style="margin-top: 200px; float: right;">
                                <input type="submit" class="btn btn-primary" value="Добавить">
                            </div>
                        @endif

                        <div class="clearfloat"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
