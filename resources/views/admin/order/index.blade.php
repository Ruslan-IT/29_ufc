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
            <h3 class="box-title">Все заказы</h3>
        </div>
        <div class="box-body">

            @if(count($orders))
                <div class="table-responsive">
                    <table class="table table-separated">
                        <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Дата</th>
                            <th>Имя</th>
                            <th>Телефон</th>
                            <th>Способ оплаты</th>
                            <th>Сумма</th>
                            <th>Билеты</th>
                            <th>Статус</th>
                        </tr>
                        @foreach($orders as $order)
                            <tr class="{{ $order->status ? 'bg-pale-success' : 'bg-pale-gray' }}">
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->phone }}</td>
                                <td>{{ $payments[$order->payment] }}</td>
                                <td>{{ $order->total }} ₽</td>
                                <td>
                                    @foreach($order->tickets as $ticket)
                                        <p>{{ $ticket->event->name }}<br>{{ $ticket->sector_name }} р:{{ $ticket->row_name }} м:{{ $ticket->place_name }} {{ $ticket->price }} ₽</p>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control order-list-status-change" data-order_id="{{ $order->id }}" data-token="{{ csrf_token() }}">
                                            <option value="0"{{ $order->status == 0 ? ' selected="selected"' : '' }}>Отменен</option>
                                            <option value="1"{{ $order->status == 1 ? ' selected="selected"' : '' }}>Оформлен</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Нет заказов</p>
            @endif
        </div>
    </div>

@endsection