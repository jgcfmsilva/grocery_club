@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('info'))
        <div id="info-alert" class="alert alert-info mb-4">
            {{ session('info') }}
        </div>
    @endif

@if(session('success'))
    <div id="success-alert" class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
@endif