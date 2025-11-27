<?php

namespace App\Http\Controllers\Admin;


use App\Event;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AdminController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$data = [];
		$data['page_title'] = 'Главная панель';
		$data['breadcrumbs'] = [
			0 => [
				'title' => '<i class="fa fa-dashboard"></i> Панель',
				'link' => false
			]
		];

		$data['events'] = [];
		$events = Event::where('status', 1)->get();
		foreach ($events as $event) {
			$data['events'][] = [
				'title' => $event->name,
				'date' => Carbon::parse($event->date_start)->formatLocalized('%e %B, %Y'),
				'image' => $event->image ? url('img/cache/thumb/' . $event->image) : '',
				'edit_link' => url('admin/events/' . $event->id . '/edit')
			];
		}

		return view('admin.index', $data);
	}
}