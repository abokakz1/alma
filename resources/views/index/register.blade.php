<div class="modal fade" id="login" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <ul class="nav nav-pills">
                    <li class="active"><a data-toggle="tab" href="#register-form">{{ trans('messages.registration') }}</a></li>
                    <li><a data-toggle="tab" href="#login-form">{{ trans('messages.login') }}</a></li>
                </ul>
            </div>
            <div class="modal-body">
                <ul class="modal-socials list-unstyled list-inline">
                    <li class="text-center"><a href="{{ route('provider', ['provider' => 'vkontakte']) }}" class="sign-vk"><i class="fa fa-vk"></i></a></li>
                    <li class="text-center"><a href="{{ route('provider', ['provider' => 'facebook']) }}" class="sign-facebook"><i class="fa fa-facebook"></i></a></li>
                    <li class="text-center"><a href="{{ route('provider', ['provider' => 'google']) }}" class="sign-google-plus"><i class="fa fa-google-plus"></i></a></li>
                </ul>
                <div class="or">{{ trans('messages.or') }}</div>

                <div class="tab-content">
                    <div class="tab-pane fade in active" id="register-form">
                    <div class="register-errors">
                    @if(isset($errors))
                       @foreach ($errors->all() as $error)
                          <p class="text-center" style="color: red">{{ $error }}</p>
                      @endforeach
                    @endif
                    </div>
                        <form class="form" method="post" action="{{ route('save-blogger') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{ trans('messages.form.name') }}</label>
                                        <input type="text" class="form-control" id="name" name="fio"  required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label for="username">{{ trans('messages.form.username') }}</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">{{ trans('messages.form.email') }}</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label for="password">{{ trans('messages.form.password') }}</label>
                                        <input type="password" class="form-control" name="password" required>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-6">
                                    <div class="form-group">
                                        <label for="confirmation_password">{{ trans('messages.form.confirmation_password') }}</label>
                                        <input type="password" class="form-control" id="confirmation_password" name="password_confirmation"  required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="file-upload btn">
                                    <div class="media">
                                        <div class="media-left media-middle">
                                            <div style="width:60px;"></div>
                                            <img src="/css/image/register-img.png" class="media-object" >
                                        </div>
                                        <div class="media-body">

                                            <input type="text" id="avatar_url" readonly placeholder="{{ trans('messages.form.image') }}">
                                        </div>
                                    </div>
                                    <input type="file" name="FileAttachment" id="avatar" class="upload">
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default">{{ trans('messages.registration') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="login-form">
                        <form class="form" method="post" action="{{ route('blogger-login') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="email">
                                    {{ trans('messages.form.username') }} {{ trans('messages.or') }} {{ trans('messages.form.email') }}
                                </label>
                                <input type="text" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">{{ trans('messages.form.password') }}</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default">{{ trans('messages.login') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>