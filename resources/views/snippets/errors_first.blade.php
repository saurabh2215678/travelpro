@if ($errors->has($param))
    <span class="text-danger">
        {{ $errors->first($param) }}
    </span>
@endif