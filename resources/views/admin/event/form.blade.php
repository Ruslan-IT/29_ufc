<div class="row">
	<div class="col-xl-6 col-lg-12">

        <div class="form-group">
            <button type="button" class="switch-button btn btn-toggle btn-primary{{ isset($event) && $event->status ? ' active' : '' }}" data-toggle="button" aria-pressed="true" data-switch_hidden_id="field_event_status">
                <span class="handle"></span>
            </button>
            {{ Form::hidden('status', null, ['class' => 'switch-hidden', 'data-switch_hidden_id' => 'field_event_status']) }}
        </div>

		<div class="form-group{{ empty($errors->get('name')) ? '' : ' has-error' }}">
			{!! Form::label('name', 'Название события <span class="text-danger">*</span>', [], false) !!}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            @foreach($errors->get('name') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
		</div>

        <div class="form-group{{ empty($errors->get('image')) ? '' : ' has-error' }}">
            {!! Form::label('image', 'Картинка', [], false) !!}
            <div class="file-image-button">
                <label>
                    {{ Form::file('image', null, ['class' => 'form-control']) }}
                    <div class="btn btn-info">Выбрать файл</div>
                </label>
                <div class="file-image-button-image">
                    @if($event_image)
                        <img src="{{ $event_image }}">
                    @endif
                </div>
            </div>
            @foreach($errors->get('image') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('date_start')) ? '' : ' has-error' }}">
            {!! Form::label('date_start', 'Дата/время начала события <span class="text-danger">*</span>', [], false) !!}
            {{ Form::text('date_start', null, ['class' => 'form-control zebra-datepicker zebra-datepicker-range-start']) }}
            @foreach($errors->get('date_start') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('date_end')) ? '' : ' has-error' }}">
            {!! Form::label('date_end', 'Дата/время завершения события <span class="text-danger">*</span>', [], false) !!}
            {{ Form::text('date_end', null, ['class' => 'form-control zebra-datepicker zebra-datepicker-range-end']) }}
            @foreach($errors->get('date_end') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('scheme_id')) ? '' : ' has-error' }}">
            {!! Form::label('scheme_id', 'Схема площадки <span class="text-danger">*</span>', [], false) !!}
            {{ Form::select('scheme_id', $schemes, null, ['class' => 'form-control select-select2']) }}
            @foreach($errors->get('scheme_id') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group">
            <label>Метод задания цен</label>
            <div>
                {!! Form::radio('price_method', 0, $event_price_method == 0 ? true : false, ['class' => 'with-gap', 'id' => 'price_method_type_0']) !!}
                <label for="price_method_type_0">Вручную</label>
            </div>
            <div>
                {!! Form::radio('price_method', 1, $event_price_method == 1 ? true : false, ['class' => 'with-gap', 'id' => 'price_method_type_1']) !!}
                <label for="price_method_type_1">Парсер Яндекс.Афиша</label>
            </div>
            <div>
                {!! Form::radio('price_method', 2, $event_price_method == 2 ? true : false, ['class' => 'with-gap', 'id' => 'price_method_type_2']) !!}
                <label for="price_method_type_2">Смешанный</label>
            </div>
            @if (isset($event))
                <a class="btn btn-success btn-sm" href="{{ url('/admin/events/' . $event->id . '/price') }}">Редактирование цен</a>
            @endif
        </div>
	</div>
</div>