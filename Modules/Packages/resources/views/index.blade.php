<x-packages::layouts.master>
@section('content')
<h1>Packages</h1>
<ul>
@foreach($packages as $p)
  <li><a href="{{ route('packages.show', $p->slug) }}">{{ $p->name }} — {{ number_format($p->price,2) }} {{ $p->currency }}</a></li>
@endforeach
</ul>
{{ $packages->links() }}
</x-packages::layouts.master>
