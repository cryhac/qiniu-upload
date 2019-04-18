<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')
        <input type="file" class="{{$class}}" id="{{$name}}_file" name="file" {!! $attributes !!} />
        <input type="hidden" name="{{$name}}" value="{{$value}}" id="{{$name}}_value">
        
        @include('admin::form.help-block')

    </div>
</div>
