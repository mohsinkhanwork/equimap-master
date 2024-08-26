<div class="table-responsive">
    <table class="table table-row-bordered gy-5">
        <thead>
        <tr class="fw-bold fs-6 text-gray-800">
            <th>Active</th>
            <th>Name</th>
            <th>Phone</th>
        </tr>
        </thead>
        <tbody>
        @if( $trainers->isEmpty() )
            <tr>
                <td colspan="6">{{ __('acp/general.not_found') }}</td>
            </tr>
        @else
            @foreach( $trainers as $trainer )
                <tr>
                    <td>{{ $trainer->active == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $trainer->name }}</td>
                    <td>{{ $trainer->phone }}</td>
                    <td>
                        <a href="{{ route('acp.trainers.edit', [ 'trainer_id' => $trainer->id ]) }}">
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
