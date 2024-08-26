<div class="table-responsive">
    <table class="table table-row-bordered gy-5">
        <thead>
        <tr class="fw-bold fs-6 text-gray-800">
            <th>Active</th>
            <th>Approved</th>
            <th>Name</th>
            <th>Price</th>
            <th>Capacity</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @if( $services->isEmpty() )
            <tr>
                <td colspan="6">{{ __('acp/general.not_found') }}</td>
            </tr>
        @else
            @foreach( $services as $service )
                <tr>
                    <td>{{ $service->active == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $service->approved == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $service->name }}</td>
                    <td>{{ $service->price }} {{ $service->currency }}</td>
                    <td>{{ $service->capacity }}</td>
                    <td>
                        <a href="{{ route('acp.services.edit', [ 'service_id' => $service->id ]) }}">
                            <i class="fas fa-solid fa-pen-to-square fs-4"></i>
                            {{ __('acp/general.layout.edit') }}
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
