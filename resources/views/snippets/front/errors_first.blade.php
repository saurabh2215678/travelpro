@if ($errors->has($param))
    <span class="help-block" style="color:red;">
        {{ $errors->first($param) }}
    </span>
@endif