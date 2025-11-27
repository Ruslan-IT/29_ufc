<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
	use SoftDeletes;

    protected $table = 'event';

	protected $price_methods = [
		0 => 'core',
		1 => 'yandex',
	];

	public function getActivePlaces($returnCount = false)
	{
		$data = [
			'zones' => [],
			'price_min' => PHP_INT_MAX,
			'price_max' => 0
		];

		$place_sold = [];
		$tickets = DB::table('order')
              ->select('ticket.sector_id as sector_id', 'ticket.row_name as row', 'ticket.place_name as place')
              ->leftJoin('ticket', 'ticket.order_id', '=', 'order.id')
              ->where('order.status', '<>', 0)
              ->where('ticket.event_id', $this->id)
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

		$sectors = DB::table('scheme_sector')->select('id', 'name', 'svg_path', 'svg_place_radius', 'svg_place_radius_max')->where('scheme_id', $this->scheme_id)->where('status', 1)->get();
		foreach ($sectors as $sector) {
			$data['zones'][$sector->id] = [
				'id' => $sector->id,
				'title' => $sector->name,
				'path' => $sector->svg_path,
				'place_radius' => $sector->svg_place_radius ? $sector->svg_place_radius : 7,
				'place_radius_max' => $sector->svg_place_radius_max ? $sector->svg_place_radius_max : false,
				'places' => [],
				'price_min' => PHP_INT_MAX,
				'price_max' => 0,
			];
		}

		if ($this->price_method == 2) {
			$price_colors = [];
			$prices = DB::table('event_price')
			           ->select('event_price.price as price')
			           ->leftJoin('scheme_place', 'scheme_place.id', '=', 'event_price.place_id')
			           ->where('event_price.event_id', $this->id)
			           ->where('event_price.limit', 0)
			           ->where('event_price.price', '<>', 0)
			           ->where('event_price.source', $this->price_methods[0])
			           ->whereNotIn('scheme_place.id', $place_sold)->orderBy('price')->groupBy('price')->get();
			$colors = DB::table('event_price_color')->select('from', 'to', 'color')->where('event_id', $this->id)->where('status', 1)->get();
			foreach ($prices as $price) {
				foreach ($colors as $color) {
					if ($color->from <= $price->price && $price->price < $color->to) {
						$price_colors[$price->price] = $color->color;
						break;
					}
				}
			}

			$parse_price_ids = DB::table('event_price')
			                     ->select('event_price.place_id as place_id')
			                     ->leftJoin('scheme_place', 'scheme_place.id', '=', 'event_price.place_id')
			                     ->where('event_price.event_id', $this->id)
			                     ->where('event_price.limit', 0)
			                     ->where('event_price.source', $this->price_methods[1])
			                     ->whereNotIn('scheme_place.id', $place_sold)
			                     ->where('scheme_place.status', 1)
			                     ->where('event_price.status', 1)
			                     ->pluck('place_id')->toArray();

			$event_prices = DB::table('event_price')
			                  ->select('scheme_place.sector_id as sector_id', 'scheme_place.svg_x as x', 'scheme_place.svg_y as y', 'scheme_place.svg_row as row', 'scheme_place.svg_place as place', 'event_price.price as price', 'event_price.status as status', 'event_price.place_id as place_id')
			                  ->leftJoin('scheme_place', 'scheme_place.id', '=', 'event_price.place_id')
			                  ->where('event_price.event_id', $this->id)
			                  ->where('event_price.limit', 0)
			                  ->where('event_price.price', '<>', 0)
			                  ->where('event_price.source', $this->price_methods[0])
			                  ->whereNotIn('scheme_place.id', $place_sold)
			                  ->get();

			foreach ($event_prices as $event_price) {
				if (isset($data['zones'][$event_price->sector_id]['places'])) {

					if ($event_price->status == 0 && !in_array($event_price->place_id, $parse_price_ids)) continue;

					$data['zones'][$event_price->sector_id]['places'][] = [
						'x' => $event_price->x,
						'y' => $event_price->y,
						'row' => $event_price->row,
						'place' => $event_price->place,
						'price' => $event_price->price,
						'color' => $price_colors[$event_price->price]
					];

					if ($data['price_min'] > $event_price->price) $data['price_min'] = $event_price->price;
					if ($data['price_max'] < $event_price->price) $data['price_max'] = $event_price->price;

					if ($data['zones'][$event_price->sector_id]['price_min'] > $event_price->price) $data['zones'][$event_price->sector_id]['price_min'] = $event_price->price;
					if ($data['zones'][$event_price->sector_id]['price_max'] < $event_price->price) $data['zones'][$event_price->sector_id]['price_max'] = $event_price->price;
				}
			}
		} else {
			$price_colors = [];
			$prices = DB::table('event_price')->select('price')->where('event_id', $this->id)->where('status', 1)->orderBy('price')->groupBy('price')->get();
			$colors = DB::table('event_price_color')->select('from', 'to', 'color')->where('event_id', $this->id)->where('status', 1)->get();
			foreach ($prices as $price) {
				foreach ($colors as $color) {
					if ($color->from <= $price->price && $price->price < $color->to) {
						$price_colors[$price->price] = $color->color;
						break;
					}
				}
			}

			$event_prices = DB::table('event_price')
			                  ->select('scheme_place.sector_id as sector_id', 'scheme_place.svg_x as x', 'scheme_place.svg_y as y', 'scheme_place.svg_row as row', 'scheme_place.svg_place as place', 'event_price.price as price')
			                  ->leftJoin('scheme_place', 'scheme_place.id', '=', 'event_price.place_id')
			                  ->where('event_price.event_id', $this->id)
			                  ->where('event_price.limit', 0)
			                  ->where('event_price.price', '<>', 0)
			                  ->where('event_price.source', $this->price_methods[$this->price_method])
			                  ->whereNotIn('scheme_place.id',  $place_sold)
			                  ->where('scheme_place.status', 1)
			                  ->where('event_price.status', 1)
			                  ->get();

			foreach ($event_prices as $event_price) {
				if (isset($data['zones'][$event_price->sector_id]['places'])) {

					$data['zones'][$event_price->sector_id]['places'][] = [
						'x' => $event_price->x,
						'y' => $event_price->y,
						'row' => $event_price->row,
						'place' => $event_price->place,
						'price' => $event_price->price,
						'color' => $price_colors[$event_price->price]
					];

					if ($data['price_min'] > $event_price->price) $data['price_min'] = $event_price->price;
					if ($data['price_max'] < $event_price->price) $data['price_max'] = $event_price->price;

					if ($data['zones'][$event_price->sector_id]['price_min'] > $event_price->price) $data['zones'][$event_price->sector_id]['price_min'] = $event_price->price;
					if ($data['zones'][$event_price->sector_id]['price_max'] < $event_price->price) $data['zones'][$event_price->sector_id]['price_max'] = $event_price->price;
				}
			}
		}

		$totalPlaces = 0;
		foreach ($data['zones'] as $key => $value) {
			if (empty($value['places'])) unset($data['zones'][$key]);
			$totalPlaces += count($value['places']);
		}

		if ($returnCount) return $totalPlaces;

		return $data;
	}
}
