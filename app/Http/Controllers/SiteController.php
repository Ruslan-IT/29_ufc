<?php

namespace App\Http\Controllers;

use App\Event;
use App\EventPriceColor;
use App\EventPrice;
use App\Order;
use App\Participant;
use App\SchemePlace;
use App\SchemeSector;
use App\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Mail;
use Carbon\Carbon;

class SiteController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$data = [];

	    $data['message'] = [
		    'status' => 0,
		    'name' => '',
		    'order' => ''
	    ];

	    if (\Request::route()->getName() == 'order-success') {
		    $order = Order::find(Input::get('order_id'));
		    $data['message'] = [
			    'status' => 1,
			    'name' => $order->name,
			    'order' => 'UFC_' . $order->id
		    ];
	    }

	    if (\Request::route()->getName() == 'order-payment-success' || \Request::route()->getName() == 'order-success') {
		    $order = Order::find(Input::get('order_id'));
		    if ($order && $order->payment_status == 0) {
			    $order->payment_status = 1;
			    $order->save();
			    $data['message'] = [
				    'status' => 1,
				    'name' => $order->name,
				    'order' => 'UFC_' . $order->id
			    ];
		    }
	    }

	    if (\Request::route()->getName() == 'order-payment-error') {
		    $order = Order::find(Input::get('order_id'));
		    if ($order && $order->payment_status == 0) {
			    $order->payment_status = 2;
			    $order->save();
			    $data['message'] = [
				    'status' => 2,
				    'name' => $order->name,
				    'order' => 'UFC_' . $order->id
			    ];
		    }
	    }

        return view('home', $data);
    }

    public function ajax_order_create()
    {
    	$result = [
    		'error' => true,
		    'message' => 'Error'
	    ];

	    if (\Request::ajax()) {

		    $event_id = Input::get('event_id');
	    	$name = Input::get('name');
		    $phone = Input::get('phone');
		    $email = Input::get('email');
		    $address = Input::get('address');
		    $comment = Input::get('comment');
		    $payment = Input::get('payment');
		    $tickets = Input::get('tickets');

		    $payments = [
			    1 => 'Картой VISA / MASTERCARD',
			    2 => 'Наличными курьеру'
		    ];

		    $event = Event::find($event_id);

		    $order = new Order();
		    $order->name = $name;
		    $order->phone = $phone;
		    $order->email = $email;
		    $order->address = $address;
		    $order->comment = $comment;
		    $order->payment = $payment;
		    $order->status = 1;
		    $order->save();

		    $total = 0;
		    foreach ($tickets as $ticket) {
		    	$ticket_add = new Ticket();
			    $ticket_add->order_id = $order->id;
			    $ticket_add->event_id = $event_id;
			    $ticket_add->price = $ticket['price'];
			    $ticket_add->sector_id = $ticket['sector_id'];
			    $ticket_add->sector_name = $ticket['sector'];
			    $ticket_add->row_name = $ticket['row'];
			    $ticket_add->place_name = $ticket['place'];
			    $ticket_add->status = 1;
			    $ticket_add->save();
			    $total += $ticket['price'];
		    }

		    $order->total = $total;
		    $order->save();

		    $form = false;
		    if ($payment == 1) {
			    $form = '<form action="https://money.yandex.ru/eshop.xml" method="post" name="myYForm" id="checkout_form" style="display: none;">
		                    <input name="shopId" value="531449" type="hidden"/>
		                    <input name="scid" value="788633" type="hidden"/>
		                    <input name="sum" value="' . $order->total . '" type="hidden">
		                    <input name="customerNumber" value="UFC_' . $order->id . '" type="hidden"/>
		                    <input name="orderNumber" value="' . $order->id . '" type="hidden"/>
		                    <input name="shopSuccessURL" value="' . url('order-payment-success?order_id=' . $order->id) . '" type="hidden"/>
		                    <input name="shopFailURL" value="' . url('order-payment-error?order_id=' . $order->id) . '" type="hidden"/>
		                    <input name="shopDefaultUrl" value="' . url('/') . '" type="hidden"/>
		                    <input name="avisoUrl" value="' . url('order-payment-aviso') . '" type="hidden"/>
		                </form>';
		    }

		    if ($payment == 2) {
			    $form = '<form action="' . url('/success') . '" method="post" name="myYForm" id="checkout_form" style="display: none;">
			            <input name="order_id" value="' . $order->id . '" type="hidden"/>
			            <input type="hidden" name="_token" value="' . csrf_token() . '" />
			        </form>';
		    }

		    $email_message = 'Пользователь оформил заказ:<br><br>';
		    $email_message .= 'ID заказа: UFC_' . $order->id . '<br>';
		    $email_message .= 'Событие: ' . $event->name . '<br>';
			$email_message .= 'Оплата: ' . $payments[$order->payment] . '<br><br>';
			$email_message .= 'Места:<br>';
			$email_message .= '<br>';
		    foreach ($tickets as $ticket) {
			    $email_message .= 'Сектор: ' . $ticket['sector'] . ', Ряд: ' . $ticket['row'] . ', Место: ' . $ticket['place'] . ', Цена: ' . $ticket['price'] . ' руб<br>';
		    }
			$email_message .= '<br>';
			$email_message .= 'Итого ' . count($tickets) . ' шт. - ' . $order->total . ' руб.<br>';
			$email_message .= '<br>';
			if ($name) $email_message .= 'Имя: ' . $name . '<br>';
			if ($phone) $email_message .= 'Телефон: ' . $phone . '<br>';
			if ($email) $email_message .= 'Email: ' . $email . '<br>';
		    if ($address) $email_message .= 'Адрес: ' . $address . '<br>';
		    if ($comment) $email_message .= 'Комментарий: ' . $comment . '<br>';
			$email_message .= 'Сумма заказа: ' . $order->total . ' руб<br>';

		    $mail_data = [
		    	'to' => 'cds-ticket@yandex.ru',
			    'from' => 'info@sufc2018.ru',
			    'subject' => 'ufc2018.ru | Заказ UFC_' . $order->id,
		    	'message' => $email_message
		    ];

		    Mail::send([], [], function ($mail) use ($mail_data) {
			    $mail->to($mail_data['to'])
			         ->subject($mail_data['subject'])
			         ->from($mail_data['from'], 'ufc2018.ru')
			         ->setBody($mail_data['message'], 'text/html');
		    });

		    if ($email) {
			    $email_message = $name . ', спасибо за покупку!<br>Ваш заказ # UFC_' . $order->id . ' успешно оформлен.<br>В ближайшее время с Вами свяжется менеджер для уточнения деталей.<br>По всем вопросам, касательно заказа обращайтесь по телефону: +7 (495) 150-58-02';

			    $mail_data = [
				    'to' => $email,
				    'from' => 'info@ufc2018.ru',
				    'subject' => 'Вы оформили заказ # UFC_' . $order->id,
				    'message' => $email_message
			    ];

			    Mail::send([], [], function ($mail) use ($mail_data) {
				    $mail->to($mail_data['to'])
				         ->subject($mail_data['subject'])
				         ->from($mail_data['from'], 'ufc2018.ru')
				         ->setBody($mail_data['message'], 'text/html');
			    });
		    }

		    $result = [
			    'error' => false,
			    'order_id' => $order->id,
			    'form' => $form,
			    'message' => 'Success'
		    ];
	    }

	    return $result;
    }

    function yandex_kassa_check() {

    }

	function yandex_kassa_aviso() {
		$order = Order::find(Input::get('orderNumber'));
		if ($order) {
			$order->payment_yandex = serialize(Input::all());
			$order->save();
		}
	}

	function event_get_map()
	{
		$result = [
			'error' => true,
			'message' => 'Error'
		];

		if (\Request::ajax()) {
			$event_id = Input::get('event_id');
			$event = Event::find($event_id);

			$svg_conf = $event->getActivePlaces();

			$colors = [];
			$colors_data = EventPriceColor::where('event_id', $event_id)->where('status', 1)->orderBy('from')->get();
			foreach ($colors_data as $color) {
				$colors[] = [
					'title' => $color->from . ' +',
					'label' => $color->from . ' - ' . $color->to . ' ₽',
					'from' => $color->from,
					'to' => $color->to,
					'color' => $color->color,
					'available' => 0
				];
			}

			$color_min_key = 0;
			$color_max_key = 0;
			$color_min = PHP_INT_MAX;
			$color_max = 0;
			foreach ($colors as $key => $color) {
				if ($color_min > $color['from']) {
					$color_min = $color['from'];
					$color_min_key = $key;
				}
				if ($color_max < $color['to']) {
					$color_max = $color['to'];
					$color_max_key = $key;
				}
			}

			if (isset($colors[$color_min_key]) && $colors[$color_min_key]['from'] < $svg_conf['price_min']) {
				$colors[$color_min_key]['from'] = $svg_conf['price_min'];
				$colors[$color_min_key]['title'] = $svg_conf['price_min'] . ' +';
				$colors[$color_min_key]['label'] = $svg_conf['price_min'] . ' - ' . $colors[$color_min_key]['to'] . ' ₽';
			}

			if (isset($colors[$color_max_key]) && $colors[$color_max_key]['to'] > $svg_conf['price_max']) {
				$colors[$color_max_key]['to'] = $svg_conf['price_max'];
				$colors[$color_max_key]['title'] = $colors[$color_max_key]['from'] . ' +';
				$colors[$color_max_key]['label'] = $colors[$color_max_key]['from'] . ' - ' . $svg_conf['price_max'] . ' ₽';
			}

			foreach ($svg_conf['zones'] as $sector) {
				foreach ($sector['places'] as $place) {
					foreach ( $colors as $color_key => $color ) {
						if ( $color['from'] <= $place['price'] && $place['price'] < $color['to'] ) {
							$colors[$color_key]['available'] = 1;
						}
					}
				}
			}

			$_monthsList = [
				1 => 'января',
				2 => 'февраля',
				3 => 'марта',
				4 => 'апреля',
				5 => 'мая',
				6 => 'июня',
				7 => 'июля',
				8 => 'августа',
				9 => 'сентября',
				10 => 'октября',
				11 => 'ноября',
				12 => 'декабря'
			];

			$result = [
				'error' => false,
				'message' => 'Success',
				'map_html' => view('event.map')->with('svg_conf', $svg_conf['zones'])->with('colors', $colors)->render(),
				'date_text' => Carbon::parse($event->date_start)->format('d ' . $_monthsList[Carbon::parse($event->date_start)->format('n')] . ' H:i')
			];
		}

		return $result;
	}
}
