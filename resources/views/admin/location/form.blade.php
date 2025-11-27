<div class="row">
	<div class="col-xl-6 col-lg-12">

        <div class="form-group">
            <button type="button" class="switch-button btn btn-toggle btn-primary{{ isset($location) && $location->status ? ' active' : '' }}" data-toggle="button" aria-pressed="true" data-switch_hidden_id="field_location_status">
                <span class="handle"></span>
            </button>
            {{ Form::hidden('status', null, ['class' => 'switch-hidden', 'data-switch_hidden_id' => 'field_location_status']) }}
        </div>

		<div class="form-group{{ empty($errors->get('name')) ? '' : ' has-error' }}">
			{!! Form::label('name', 'Название локации <span class="text-danger">*</span>', [], false) !!}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            @foreach($errors->get('name') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
		</div>

        <div class="form-group{{ empty($errors->get('address')) ? '' : ' has-error' }}">
            {!! Form::label('address', 'Адрес <span class="text-danger">*</span>', [], false) !!}
            {{ Form::text('address', null, ['class' => 'form-control']) }}
            @foreach($errors->get('address') as $error_item)
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
                    @if($location_image)
                        <img src="{{ $location_image }}">
                    @endif
                </div>
            </div>
            @foreach($errors->get('image') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>
	</div>
</div>