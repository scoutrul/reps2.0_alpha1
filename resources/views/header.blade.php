<div class="row header">
    <a href="/" class="header-logo-wrapper">
        <img src="/images/big-logo-header.jpg" class="header-logo" alt="">
    </a>
    <div class="col-md-7"></div>
    <div class="col-md-5">
        <div class="row">
            @if(Route::currentRouteName() !== 'registration_form')
                @if(!Auth::user())
                    <div class="col-md-4"></div>
                    <div class="col-md-8">
                        <!-- LOGIN FORM-->
                        <form method="POST" id="login-form" class="login-form" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                @if ($errors->has('email'))
                                    <div class="error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </div>
                                @endif
                                <input id="email" type="email"
                                       class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                       name="email" value="{{ old('email') }}" required autofocus>
                            </div>
                            <div class="form-group">
                                @if ($errors->has('password'))
                                    <div class="error">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </div>
                                @endif
                                <input id="password" type="password"
                                       class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                       name="password" required>
                            </div>
                            <button type="submit" class="btn btn-outline-info float-left submit-btn">Ok</button>
                            <a class="register-link float-right" href="{{route('registration_form')}}">Регистрация</a>
                        </form>
                        <!-- END LOGIN FORM-->
                    </div>
                @else
                    <div class="col-md-4"></div>
                    <div class="col-md-8 header-profile">
                        <p class="hello">
                            @if(Auth::user()->view_avatars == 1)
                                @if(Auth::user()->avatar)
                                    <img class="img-responsive profile-avatar-header" src="{{Auth::user()->avatar->link}}" alt="">
                                @else
                                    <span class="key">Аватар отсутствует</span>
                                @endif

                            @endif
                            Hello {{Auth::user()->name}}
                        </p>
                        <div class="user-menu-hover btn btn-default">
                            <span>Меню пользователя</span>
                            <div class="user-menu-wrapper">
                                <div>
                                    <a class="profile-link" href="{{route('user_profile',['id' =>Auth::id()])}}">Профиль
                                        пользователя</a>
                                    <a class="profile-link" href="{{route('edit_profile')}}">Настройки</a>

                                    <a class="profile-link" href="{{route('gallery.list_user', ['id' => Auth::id()])}}">Галерея</a>

                                    <a class="profile-link" href="{{route('user.get_rating', ['id' => Auth::id()])}}">Репутация</a>

                                    <a class="profile-link" href="{{route('forum.topic.my_list')}}">Мои посты</a>

                                    <a class="profile-link" href="{{route('replay.create')}}">Отправить свой/госу реплей</a>

                                    <a class="profile-link" href="{{route('replay.my_user')}}">Мои реплеи</a>
                                    <a class="profile-link" href="{{route('replay.my_gosu')}}">Мои госу реплеи</a>

                                    <a class="profile-link" href="{{route('user.messages_all')}}">
                                        Новые сообщения({{$general_helper->getNewUserMessage()}})
                                    </a>
                                    <a class="profile-link" href="{{route('user.friends_list')}}">Список друзей</a>
                                    <a class="profile-link" href="{{route('user.ignore_list')}}">Игнор лист</a>

                                    <a class="profile-link" href="{{route('logout',['id' =>Auth::id()])}}">Выход</a>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->user_role_id == 1)
                            <div class="admin-panel-wrapper">
                                <a href="{{route('admin.home')}}">Admin Panel</a>
                            </div>
                        @endif
                    </div>
                @endif
            @else
                <div class="col-md-12 header-profile"></div>
            @endif
        </div><!--close div /.row-->
        <div class="row">
            <div class="col-md-12">
                @include('search-home')
            </div>

        </div><!--close div /.row-->
    </div>

</div>