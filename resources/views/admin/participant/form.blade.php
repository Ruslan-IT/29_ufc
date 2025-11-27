<div class="row">
	<div class="col-xl-6 col-lg-12">

        <div class="form-group">
            <button type="button" class="switch-button btn btn-toggle btn-primary{{ isset($participant) && $participant->status ? ' active' : '' }}" data-toggle="button" aria-pressed="true" data-switch_hidden_id="field_participant_status">
                <span class="handle"></span>
            </button>
            {{ Form::hidden('status', null, ['class' => 'switch-hidden', 'data-switch_hidden_id' => 'field_participant_status']) }}
        </div>

		<div class="form-group{{ empty($errors->get('name')) ? '' : ' has-error' }}">
			{!! Form::label('name', 'Название участника <span class="text-danger">*</span>', [], false) !!}
            {{ Form::text('name', null, ['class' => 'form-control']) }}
            @foreach($errors->get('name') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
		</div>

        <div class="form-group{{ empty($errors->get('text')) ? '' : ' has-error' }}">
            {!! Form::label('text', 'Текст', [], false) !!}
            {{ Form::textarea('text', null, ['class' => 'form-control']) }}
            @foreach($errors->get('text') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('country')) ? '' : ' has-error' }}">
            {!! Form::label('country', 'Страна <span class="text-danger">*</span>', [], false) !!}
            {{ Form::select('country', $countries, null, ['class' => 'form-control select-select2']) }}
            @foreach($errors->get('country') as $error_item)
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
                    @if($participant_image)
                        <img src="{{ $participant_image }}">
                    @endif
                </div>
            </div>
            @foreach($errors->get('image') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('image_large')) ? '' : ' has-error' }}">
            {!! Form::label('image_large', 'Картинка (большая)', [], false) !!}
            <div class="file-image-button">
                <label>
                    {{ Form::file('image_large', null, ['class' => 'form-control']) }}
                    <div class="btn btn-info">Выбрать файл</div>
                </label>
                <div class="file-image-button-image">
                    @if($participant_image_large)
                        <img src="{{ $participant_image_large }}">
                    @endif
                </div>
            </div>
            @foreach($errors->get('image') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>

        <div class="form-group{{ empty($errors->get('sort')) ? '' : ' has-error' }}">
            {!! Form::label('sort', 'Сортировка', [], false) !!}
            {{ Form::text('sort', $sort ? $sort : 0, ['class' => 'form-control']) }}
            @foreach($errors->get('sort') as $error_item)
                <span class="help-block">{{ $error_item }}</span>
            @endforeach
        </div>
	</div>
</div>