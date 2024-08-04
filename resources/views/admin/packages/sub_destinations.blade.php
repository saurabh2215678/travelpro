<li>{{ $sub_destinations->name }}</li>
@if ($sub_destinations->destinations)
    <ul>
        @if(count($sub_destinations->destinations) > 0)
            @foreach ($sub_destinations->destinations as $subDestinations)
                @include('admin.packages.sub_destinations', ['sub_destinations' => $subDestinations])
            @endforeach
        @endif
    </ul>
@endif