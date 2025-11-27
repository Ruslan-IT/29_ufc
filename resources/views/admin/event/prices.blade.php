@extends('layouts.admin')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Возникли ошибки
        </div>
    @endif

    <div class="box">
        <div class="box-body">

            <div class="event-price-map loading" id="event-price-map" data-event_id="{{ $event_id }}" data-token="{{ csrf_token() }}">
                <h2 class="text-center event-price-map-mode-sector">Выберите сектор</h2>
                <h2 class="text-center event-price-map-mode-place">Выберите места</h2>
                <div class="map-buttons">
                    <button type="button" class="btn btn-info btn-block event-price-action-return-mode" data-toggle="tooltip" data-placement="left" title="Вернутся назад"><i class="fa fa-th"></i></button>
                    <button type="button" class="btn btn-info btn-block event-price-action-select-all" data-toggle="tooltip" data-placement="left" title="Выделить все места"><i class="fa fa-flag"></i></button>
                    <button type="button" class="btn btn-info btn-block event-price-action-refresh" data-toggle="tooltip" data-placement="left" title="Очистить выделение"><i class="fa fa-flag-o"></i></button>
                    <button type="button" class="btn btn-info btn-block event-price-action-on" data-toggle="tooltip" data-placement="left" title="Сделать свободным"><i class="fa fa-eye"></i></button>
                    <button type="button" class="btn btn-info btn-block event-price-action-off" data-toggle="tooltip" data-placement="left" title="Сделать занятым"><i class="fa fa-eye-slash"></i></button>
                    <button type="button" class="btn btn-info btn-block event-price-action-set" data-toggle="tooltip" data-placement="left" title="Задать цену"><i class="fa fa-rub"></i></button>
                    <button type="button" class="btn btn-info btn-block event-price-action-save" data-toggle="tooltip" data-placement="left" title="Сохранить"><i class="fa fa-save"></i></button>
                </div>
                <svg id="svg-map" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0">
                    <g class="sh-container">
                        <g class="sh-decor"><image href="{{ url('images/scheme_decor.png') }}" x="0" y="1.5" width="7846px" height="3456px" /></g>
                        <g class="sh-sectors">
                            @foreach($svg_sector as $sector)
                                <g class="sh-sector{{ $sector['place_count'] ? ' active' : '' }}" sn="{{ $sector['title'] }}" sid="{{ $sector['sector_id'] }}">
                                    <path class="sh-path" d="{{ $sector['path'] }}" data-html="true" data-toggle="tooltip" data-placement="top" title="{{ $sector['title'] }}{{ $sector['place_count'] ? '<br>мест: ' . $sector['place_count'] . ' шт<br>цена: ' . $sector['price'] . ' ₽' : '' }}"/>
                                </g>
                            @endforeach
                        </g>
                        <g class="sh-actived-places"></g>
                    </g>
                </svg>
                <div class="event-price-map-set-price">
                    <div class="inner">
                        <p>Введите цену</p>
                        <div class="form-group">
                            <input class="form-control event-price-map-set-price-value" type="text" value="1000">
                        </div>
                        <div class="event-price-map-set-price-buttons">
                            <button type="button" class="btn btn-info btn-lg event-price-map-set-price-buttons-set">Задать</button>
                            <button type="button" class="btn btn-danger btn-lg event-price-map-set-price-buttons-remove">Убрать</button>
                            <button type="button" class="btn btn-warning btn-lg event-price-map-set-price-buttons-close">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center" style="background: #dcdcdc;">
                <img src="{{ url('images/legend-map.jpg') }}" alt="">
            </div>
        </div>
    </div>
@endsection