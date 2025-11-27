@extends('layouts.admin')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Возникли ошибки
        </div>
    @endif

    @if(session()->has('message_success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session()->get('message_success') }}
        </div>
    @endif

    @if(session()->has('message_error'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session()->get('message_error') }}
        </div>
    @endif


    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Все участники</h3>
            <div class="box-controls pull-right">
                <a class="btn btn-info" href="{!! url('admin/participants/create') !!}">Добавить</a>
            </div>
        </div>
        <div class="box-body">
            @if(count($participants))
                <div class="table-responsive">
                    <table class="table table-separated">
                        <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Название</th>
                            <th>Сортировка</th>
                            <th style="width: 40px">Действия</th>
                        </tr>
                        @foreach($participants as $participant)
                            <tr class="{{ $participant->status ? 'bg-pale-success' : 'bg-pale-pink' }}">
                                <td>{{ $participant->id }}</td>
                                <td>{{ $participant->name }}</td>
                                <td>{{ $participant->sort }}</td>
                                <td><a href="{{ url('admin/participants/' . $participant->id . '/edit') }}" class="btn btn-block btn-info btn-xs">Изменить</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Нет участников</p>
            @endif
        </div>
    </div>

@endsection