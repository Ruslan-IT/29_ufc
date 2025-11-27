@extends('layouts.admin')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Возникли ошибки
        </div>
    @endif

    {{ Form::model($participant, ['url' => 'admin/participants/' . $participant->id, 'method' => 'put', 'files' => true]) }}
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Изменить</h3>
            </div>
            <div class="box-body">
                @include('admin.participant.form')
            </div>
            <div class="box-footer">
                {{ Form::submit('Сохранить', ['class' => 'btn btn-success']) }}
                <a href="{{ $cancel_link }}" class="btn btn-danger">Отменить</a>
                <button class="btn btn-danger pull-right object-delete" data-action="{{ url('admin/participants/' . $participant->id) }}" type="button">Удалить</button>
            </div>
        </div>
    {{ Form::close() }}

    {{ Form::open(['method' => 'delete', 'url' => 'admin/participants/' . $participant->id, 'id' => 'object-form-delete']) }}
    {{ Form::close() }}
@endsection