<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if (session()->has('alert-' . $msg))
            <span class="help-block-{{ $msg }}" role="alert" style="color:red;">
                {{ session('alert-' . $msg) }}
                {{session()->forget('alert-'.$msg)}}
              <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
            </span>
        @endif
    @endforeach
    @if (session()->has('status'))
    <span class="help-block-{{ $msg }}" role="alert" style="color:red;"> 
        {{ session('status') }}
        {{session()->forget('status')}}
      <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
    </span>
    @endif
</div>
