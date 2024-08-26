<x-system-layout>
    @include('pages.system.error_web', [ 'title' => __('acp/error.title.unknown'), 'code' => 490, 'message' => $data['message'] ] )
</x-system-layout>
