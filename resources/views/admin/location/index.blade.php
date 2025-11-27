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
            <h3 class="box-title">Все локации</h3>
            <div class="box-controls pull-right">
                <a class="btn btn-info" href="{!! url('admin/locations/create') !!}">Добавить</a>
            </div>
        </div>
        <div class="box-body">
            @if(count($locations))
                <div class="table-responsive">
                    <table class="table table-separated">
                        <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Название</th>
                            <th style="width: 40px">Действия</th>
                        </tr>
                        @foreach($locations as $location)
                            <tr class="{{ $location->status ? 'bg-pale-success' : 'bg-pale-pink' }}">
                                <td>{{ $location->id }}</td>
                                <td>{{ $location->name }}</td>
                                <td><a href="{{ url('admin/locations/' . $location->id . '/edit') }}" class="btn btn-block btn-info btn-xs">Изменить</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Нет локаций</p>
            @endif
        </div>
    </div>

@endsection