@section('content')
<div class="container">
  {{-- @dd($apartments) --}}
    <h1 class="text-center">Lista messaggi</h1>
    <a href="{{ route('admin.messages.trash') }}" class="btn btn-outline-danger my-3">Vai al cestino</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Apartment</th>
                <th scope="col">Address</th>
                <th scope="col">Sent by</th>
                <th scope="col">Message preview</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($apartments as $apartment)
              @if ($apartment->messages)
                @forelse ($apartment->messages as $message)
                  <tr>
                      <td>{{$apartment->title}}</td>
                      <td>{{$apartment->address}}</td>
                      <td>{{$message->email}}</td>
                      <td>{!! Str::limit($message->text, 15) !!}</td>
                      
                      <td class="d-flex">
                          <button class="trash bi bi-reply-fill text-success" data-bs-toggle="modal" data-bs-target="#restore-{{$message->id}}" href=""></button>
                          <button class="trash bi bi-trash-fill text-danger ms-3" data-bs-toggle="modal" data-bs-target="#delete-{{$message->id}}" href=""></button>
                      </td>
                  </tr>
                    
                @empty
                    
                @endforelse 
              @endif
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('modals')
<!-- Modal -->
@foreach($apartments as $apartment)
              @if ($apartment->messages)
                @forelse ($apartment->messages as $message)
                <div class="modal fade" id="delete-{{$message->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Elimina {{$message->email}}?</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Attenzione! Stai eliminando questo messaggio.<br>
                          Sei sicuro di volerlo eliminare?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                          <form action="{{route('admin.messages.forcedelete', $message)}}" method="POST">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn btn-danger">Elimina</button>
                            </form>
                        </div>
                      </div>
                    </div>
                </div> 

                <div class="modal fade" id="restore-{{$message->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Ripristina {{$message->email}}?</h1>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          Attenzione! Stai ripristinando questo messaggio.<br>
                          Sei sicuro di volerlo ripristinare?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                          <form action="{{route('admin.messages.restore', $message->id)}}" method="POST">
                                @method('put')
                                @csrf
                                <button type="submit" class="btn btn-success">Ripristina</button>
                            </form>
                        </div>
                      </div>
                    </div>
                </div>    
                @empty
                    
                @endforelse 
              @endif
      @endforeach
@endsection