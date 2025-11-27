@extends('layouts.admin')
@section('content')
    @if ($events)
        <div class="row">
            @foreach($events as $event)
                <div class="col-md-4">
                    <div class="box card-inverse bg-img text-center py-80" style="background-image: url({{ $event['image'] }})" data-overlay="5">
                        <div class="card-body">
                            <span class="bb-1 opacity-80 pb-2">{{ $event['date'] }}</span>
                            <br><br>
                            <h3>{{ $event['title'] }}</h3>
                            <br><br>
                            <a class="btn btn-outline no-radius btn-light btn-default" href="{{ $event['edit_link'] }}">Изменить</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p>Нет активных событий</p>
    @endif
@endsection