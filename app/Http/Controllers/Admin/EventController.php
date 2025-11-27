<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\EventPrice;
use App\Http\Requests\RequestEvent;
use App\Http\Controllers\Controller;
use App\Scheme;
use App\SchemePlace;
use App\SchemeSector;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $data = [];
	    $data['page_title'] = 'Список событий';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'События',
			    'link' => false
		    ]
	    ];

	    $data['events'] = Event::all();

	    return view('admin.event.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$data = [];
    	$data['page_title'] = 'Добавить событие';

    	$data['breadcrumbs'] = [
    		0 => [
    		    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'События',
			    'link' => url('admin/events')
		    ],
		    2 => [
			    'title' => 'Создать',
			    'link' => false
		    ]
	    ];

	    $data['event_image'] = '';
	    $data['event_price_method'] = 0;

	    $data['schemes'] = [];
	    foreach (Scheme::where('status', '1')->get() as $scheme) {
		    $data['schemes'][$scheme->id] = $scheme->name;
	    }

	    $data['cancel_link'] = url('admin/events');

        return view('admin.event.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestEvent $request)
    {
	    $event = new Event();
	    $event->name = $request->input('name');
	    $event->status = $request->input('status') ? $request->input('status') : 0;
	    $event->scheme_id = $request->input('scheme_id');
	    $event->price_method = $request->input('price_method');
	    $event->date_start = $request->input('date_start');
	    $event->date_end = $request->input('date_end');

	    if ($request->file('image')) {
		    $image = Image::make($request->file('image'));
		    $mime = $image->mime;
		    $extension = 'jpg';
		    if ($mime == 'image/png') $extension = 'png';
		    if ($mime == 'image/gif') $extension = 'gif';
		    if ($mime == 'image/bmp') $extension = 'bmp';
		    if ($mime == 'image/tiff') $extension = 'tiff';
		    if ($mime == 'image/svg+xml') $extension = 'svg';
		    $filename = md5($request->file('image')->getClientOriginalName() . microtime()) . '.' . $extension;
		    $image->save(storage_path('uploads/images/' . $filename));
		    $event->image = $filename;
	    }

	    $event->save();

	    return redirect('admin/events')->with('message_success', 'Успешно додано!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function edit(Event $event)
    {
	    $data = [];
	    $data['page_title'] = 'Изменить событие';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'События',
			    'link' => url('admin/events')
		    ],
		    2 => [
			    'title' => 'Изменить',
			    'link' => false
		    ]
	    ];

	    $event->date_start = substr($event->date_start, 0, -3);
	    $event->date_end = substr($event->date_end, 0, -3);

	    $data['event'] = $event;
	    $data['event_image'] = $event->image ? url('img/cache/mini/' . $event->image) : '';
	    $data['event_price_method'] = $event->price_method;

	    $data['schemes'] = [];
	    foreach (Scheme::where('status', '1')->get() as $scheme) {
		    $data['schemes'][$scheme->id] = $scheme->name;
	    }

	    $data['cancel_link'] = url('admin/events');

	    return view('admin.event.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(RequestEvent $request, Event $event)
    {
	    $event->name = $request->input('name');
	    $event->scheme_id = $request->input('scheme_id');
	    $event->price_method = $request->input('price_method');
	    $event->status = $request->input('status') ? $request->input('status') : 0;

	    if ($request->file('image')) {
			$image = Image::make($request->file('image'));
			$mime = $image->mime;
			$extension = 'jpg';
			if ($mime == 'image/png') $extension = 'png';
			if ($mime == 'image/gif') $extension = 'gif';
			if ($mime == 'image/bmp') $extension = 'bmp';
			if ($mime == 'image/tiff') $extension = 'tiff';
			if ($mime == 'image/svg+xml') $extension = 'svg';
			$filename = md5($request->file('image')->getClientOriginalName() . microtime()) . '.' . $extension;
			$image->save(storage_path('uploads/images/' . $filename));
			$event->image = $filename;
		}

	    $event->date_start = $request->input('date_start');
	    $event->date_end = $request->input('date_end');
	    $event->save();

	    return redirect('admin/events')->with('message_success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
	    $event->delete();
	    return redirect('admin/events')->with('message_success', 'Успешно удалено!');
    }

    public function prices($event_id) {
	    $data = [];
	    $data['page_title'] = 'Редактирование цен на событие';

	    $data['breadcrumbs'] = [
		    0 => [
			    'title' => '<i class="fa fa-dashboard"></i> Панель',
			    'link' => url('admin')
		    ],
		    1 => [
			    'title' => 'События',
			    'link' => url('admin/events')
		    ],
		    2 => [
			    'title' => 'Цены',
			    'link' => false
		    ]
	    ];

	    $data['event_id'] = $event_id;

	    $event = Event::find($event_id);

	    $data['svg_sector'] = [];
	    $sectors = DB::table('scheme_sector')->select('id', 'name', 'svg_path')->where('scheme_id', $event->scheme_id)->where('status', 1)->get();
	    foreach ($sectors as $sector) {
		    $place_count = 0;
		    $price_min = PHP_INT_MAX;
		    $price_max = 0;
		    $event_prices = DB::table('event_price')
		                      ->select('scheme_place.sector_id as sector_id', 'scheme_place.svg_x as x', 'scheme_place.svg_y as y', 'scheme_place.svg_row as row', 'scheme_place.svg_place as place', 'event_price.price as price', 'event_price.status as status')
		                      ->leftJoin('scheme_place', 'scheme_place.id', '=', 'event_price.place_id')
		                      ->where('event_price.event_id', $event_id)
		                      ->where('event_price.limit', 0)
		                      ->where('source', 'core')
		                      ->where('scheme_place.status', 1)
		                      ->get();

		    foreach ($event_prices as $event_price) {
		    	if (!$event_price->status && !$event_price->price) continue;

	            if ($event_price->sector_id == $sector->id) {
	            	$place_count++;
		            if ($price_min > $event_price->price) $price_min = $event_price->price;
		            if ($price_max < $event_price->price) $price_max = $event_price->price;
	            }
		    }

		    $data['svg_sector'][$sector->id] = [
		    	'sector_id' => $sector->id,
			    'title' => $sector->name,
			    'path' => $sector->svg_path,
			    'place_count' => $place_count,
			    'price' => $price_min == $price_max ? $price_max : $price_min . ' - ' . $price_max
		    ];
	    }

	    return view('admin.event.prices', $data);
    }

	public function price_get_places()
	{
		$result = [
			'error' => true,
			'message' => 'Error'
		];

		$places_data = [];

		if (\Request::ajax()) {
			$event_id = Input::get('event_id');
			$sector_id = Input::get('sector_id');

			$event = Event::find($event_id);

			if ($event) {

				$place_sold = [];
				$tickets = DB::table('order')
				             ->select('ticket.sector_id as sector_id', 'ticket.row_name as row', 'ticket.place_name as place')
				             ->leftJoin('ticket', 'ticket.order_id', '=', 'order.id')
				             ->where('order.status', '<>', 0)
				             ->where('ticket.event_id', $event_id)
							 ->where('ticket.sector_id', $sector_id)
				             ->where('ticket.status', 1)
				             ->get();
				foreach ($tickets as $ticket) {
					$place = DB::table('scheme_place')
					           ->select('scheme_place.id as id')
					           ->where('scheme_place.sector_id', $ticket->sector_id)
					           ->where('scheme_place.svg_row', $ticket->row)
					           ->where('scheme_place.svg_place', $ticket->place)
					           ->where('scheme_place.status', 1)
					           ->first();
					if ($place) array_push($place_sold, $place->id);
				}

				$place_parser = [];
				$event_prices_parser = EventPrice::where('source', 'yandex')->where('event_id', $event_id)->where('status', 1)->get();
				foreach ($event_prices_parser as $event_price_parser) {
					$place_parser[$event_price_parser->place_id] = 1;
				}

				$places = SchemePlace::where('sector_id', $sector_id)->where('status', 1)->get();
				foreach ($places as $place) {
					$places_data[$place->id] = [
						'svg_place' => $place->svg_place,
						'svg_row' => $place->svg_row,
						'svg_x' => $place->svg_x,
						'svg_y' => $place->svg_y,
						'price' => 0,
						'status' => 0,
						'parse' => isset($place_parser[$place->id]) ? 1 : 0,
						'sold' => in_array($place->id, $place_sold) ? 1 : 0
					];
				}

				$event_prices = EventPrice::where('source', 'core')->where('event_id', $event_id)->get();
				foreach ($event_prices as $event_price) {
					if (isset($places_data[$event_price->place_id])) {
						$places_data[$event_price->place_id]['price'] = $event_price->price;
						$places_data[$event_price->place_id]['status'] = $event_price->status;
					}
				}

				$result = [
					'error' => false,
					'places' => $places_data,
					'message' => 'Success'
				];
			}
		}

		return $result;
	}

    public function price_get_sectors()
    {
	    $result = [
		    'error' => true,
		    'message' => 'Error'
	    ];

	    $svg_sector = [];

	    if (\Request::ajax()) {
		    $event_id = Input::get('event_id');

		    $event = Event::find($event_id);

		    if ($event) {

			    $sectors = DB::table('scheme_sector')->select('id', 'name', 'svg_path')->where('scheme_id', $event->scheme_id)->where('status', 1)->get();
			    foreach ($sectors as $sector) {

				    $place_count = 0;
				    $price_min = PHP_INT_MAX;
				    $price_max = 0;
				    $event_prices = DB::table('event_price')
				                      ->select('scheme_place.sector_id as sector_id', 'scheme_place.svg_x as x', 'scheme_place.svg_y as y', 'scheme_place.svg_row as row', 'scheme_place.svg_place as place', 'event_price.price as price', 'event_price.status as status')
				                      ->leftJoin('scheme_place', 'scheme_place.id', '=', 'event_price.place_id')
				                      ->where('event_price.event_id', $event_id)
				                      ->where('event_price.limit', 0)
				                      ->where('source', 'core')
				                      ->where('scheme_place.status', 1)
				                      ->get();


				    foreach ($event_prices as $event_price) {
					    if (!$event_price->status && !$event_price->price) continue;

					    if ($event_price->sector_id == $sector->id) {
						    $place_count++;
						    if ($price_min > $event_price->price) $price_min = $event_price->price;
						    if ($price_max < $event_price->price) $price_max = $event_price->price;
					    }
				    }

				    $svg_sector[$sector->id] = [
					    'sector_id' => $sector->id,
					    'title' => $sector->name,
					    'path' => $sector->svg_path,
					    'place_count' => $place_count,
					    'price' => $price_min == $price_max ? $price_max : $price_min . ' - ' . $price_max
				    ];
			    }

			    $result = [
				    'error' => false,
				    'svg_sector' => $svg_sector,
				    'message' => 'Success'
			    ];
		    }
	    }

	    return $result;
    }

	public function price_save_places()
	{
		$result = [
			'error' => true,
			'message' => 'Error'
		];

		if (\Request::ajax()) {
			$event_id = Input::get('event_id');
			$sector_id = Input::get('sector_id');
			$places = json_decode(Input::get('places'), 1);

			$event = Event::find($event_id);
			$sector = SchemeSector::find($sector_id);

			if ($event && $sector) {

				$place_ids = [];
				$sector_places = SchemePlace::where('sector_id', $sector_id)->get();
				foreach ($sector_places as $sector_place) {
					array_push($place_ids, $sector_place->id);
				}

				DB::table('event_price')->where('source', 'core')->where('event_id', $event_id)->whereIn('place_id', $place_ids)->delete();

				$event_prices = [];
				if ($places) {
					foreach ($places as $place) {
						$sector_place = SchemePlace::where('sector_id', $sector_id)->where('svg_row', $place['row'])->where('svg_place', $place['place'])->where('status', 1)->first();
						if ($sector_place) {
							$event_prices[] = [
								'event_id' => $event_id,
								'place_id' => $sector_place->id,
								'price'    => $place['price'],
								'status'   => $place['status'] ? 1 : 0,
								'source'   => 'core'
							];
						}
					}
				}

				DB::table('event_price')->insert($event_prices);

				$result = [
					'error' => false,
					'message' => 'Success'
				];
			}
		}

		return $result;
	}


}
