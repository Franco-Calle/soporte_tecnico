@if(session('status'))
    <div class="mb-4 rounded-md border border-exito bg-fondo-suave px-4 py-2 text-primario text-sm">
        {{ session('status') }}
    </div>
@endif

@if($errors->any())
    <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-2 text-sm text-red-700">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
