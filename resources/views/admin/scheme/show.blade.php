@extends('layouts.admin')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Возникли ошибки
        </div>
    @endif

    <h4><strong>Локация: </strong>{{ $scheme->getLocationName() }}</h4>

    <div class="row">
        <div class="col-xl-6 col-md-6 col-12">
            <div class="box box-body text-center bg-blue">
                <div class="font-size-40 font-weight-200">{{ $sector_count }}</div>
                <div>Секторов</div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6 col-12">
            <div class="box box-body text-center bg-blue">
                <div class="font-size-40 font-weight-200">{{ $place_count }}</div>
                <div>Мест</div>
            </div>
        </div>
    </div>
@endsection