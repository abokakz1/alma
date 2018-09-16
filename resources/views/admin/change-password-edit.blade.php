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
                    <form id="myform" method="post" action="/admin/change-password-edit" style="margin-bottom: 0px;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <div class="left-block">
                            <p>Старый пароль</p>
                        </div>
                        <div class="right-block">
                            <input type="password" name="old_password" value="">
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Новый пароль</p>
                        </div>
                        <div class="right-block">
                            <input type="password" name="new_password" value="">
                        </div>
                        <div class="clearfloat"></div>

                        <div class="left-block">
                            <p>Повторо нового пароля</p>
                        </div>
                        <div class="right-block">
                            <input type="password" name="repeat_new_password" value="">
                        </div>
                        <div class="clearfloat"></div>

                        <div style="margin-top: 200px; float: right;">
                            <input type="submit" class="btn btn-primary" value="Изменить">
                        </div>

                        <div class="clearfloat"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
