@extends('layouts.admin')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Возникли ошибки
        </div>
    @endif

    {{ Form::open(['url' => 'admin/locations', 'method' => 'post', 'files' => true]) }}
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Добавить</h3>
            </div>
            <div class="box-body">
                @include('admin.location.form')
            </div>
            <div class="box-footer">
                {{ Form::submit('Добавить', ['class' => 'btn btn-success']) }}
                <a href="{{ $cancel_link }}" class="btn btn-danger">Отменить</a>
            </div>
        </div>
    {{ Form::close() }}
@endsection