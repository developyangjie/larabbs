@foreach(['message','success','danger'] as $msg)
    @if(session()->has($msg))
        <div class="alert alert-{{$msg}} alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ session()->get($msg) }}
        </div>
    @endif
@endforeach