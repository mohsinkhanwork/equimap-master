<div class="table-responsive">
    <table class="table table-row-bordered gy-5">
        <thead>
        <tr class="fw-bold fs-6 text-gray-800">
            <th>Active</th>
            <th>Approved</th>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @if( $courses->isEmpty() )
            <tr>
                <td colspan="6">{{ __('acp/general.not_found') }}</td>
            </tr>
        @else
            @foreach( $courses as $course )
                <tr>
                    <td>{{ $course->active == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $course->approved == 1 ? 'Yes' : 'No' }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->price }}</td>
                    <td>
                        <a href="{{ route('acp.courses.edit', [ 'course_id' => $course->id ]) }}">
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
