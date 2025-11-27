@extends('layouts.auth')
@section('content')
    <div class="login-box">

        @if (count($errors) > 0)
            <div class="callout callout-danger">
                <p class="text-center">{{$error_message}}</p>
            </div>
        @endif

        <div class="login-box-body">
            <form action="{{ route('login') }}" method="post" class="form-element">
                {{ csrf_field() }}
                <div class="form-group has-feedback">
                    <input type="text" name="login" class="form-control" placeholder="Логин" value="{{ old('login') }}">
                    <span class="ion ion-email form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" class="form-control" placeholder="Пароль">
                    <span class="ion ion-locked form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="checkbox">
                            <input type="checkbox" id="input-remember" name="remember" {{ old('remember') ? ' checked="checked"' : '' }}>
                            <label for="input-remember">Запомнить меня</label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-info btn-block margin-top-10">ВХОД</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection