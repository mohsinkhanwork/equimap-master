<div class="table-responsive">
    <table class="table table-row-bordered gy-5">
        <thead>
        <tr class="fw-bold fs-6 text-gray-800">
            <th>Active</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Level</th>
        </tr>
        </thead>
        <tbody>
        @if( $horses->isEmpty() )
            <tr>
                <td colspan="6">{{ __('acp/general.not_found') }}</td>
            </tr>
        @else
            @foreach( $horses as $horse )
                <tr>
                    <td>{{ $horse->active == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $horse->name }}</td>
                    <td>{{ ucfirst( $horse->gender ) }}</td>
                    <td>{{ ucfirst( $horse->level ) }}</td>
                    <td>
                        <a href="{{ route('acp.horses.edit', [ 'horse_id' => $horse->id ]) }}">
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
