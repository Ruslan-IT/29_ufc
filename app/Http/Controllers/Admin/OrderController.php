<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class OrderController extends Controller
{
    public function index()
    {
	    $data = [];
	    $data['page_title'] = 'Список заказов';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'Список заказов',
			    'link' => false
		    ]
	    ];

	    $data['payments'] = [
		    1 => 'Картой VISA / MASTERCARD',
		    2 => 'Наличными курьеру'
	    ];
	    $data['orders'] = Order::orderBy('created_at', 'desc')->get();

	    return view('admin.order.index', $data);
    }

	public function ajax_change_status()
	{
		$result = [
			'error' => true,
			'message' => 'Error'
		];

		if (\Request::ajax()) {
			$order_id = Input::get('order_id');
			$status = Input::get('status');

			$order = Order::find($order_id);

			if ($order) {
				$order->status = $status;
				$order->save();

				$result = [
					'error' => false,
					'message' => 'Success'
				];
			}
		}

		return $result;
	}
}