@section('text-align','text-center')
<!--begin::Page bg image-->
<style>
    body {
        background-image: url('{{ image('auth/bg10.jpeg') }}');
    }

    [data-bs-theme="dark"] body {
        background-image: url('{{ image('auth/bg10-dark.jpeg') }}');
    }
</style>
<!--end::Page bg image-->

<!--begin::Title-->
<h1 class="fw-bold fs-2qx text-gray-900 mb-4">
    {{ isset( $title ) ? $title : __('acp/general.error.title') }}
</h1>
<!--end::Title-->

<!--begin::Text-->
<div class="fw-semibold fs-6 text-gray-500 mb-7">
    {{ isset( $code ) && __('acp/error.message.' . $code ) != 'acp/error.message.' . $code ? __('acp/error.message.' . $code ) : __('acp/error.message.unknown') }}
</div>
<!--end::Text-->

<!--begin::Illustration-->
<div class="mb-5">
    <img src="{{ image('misc/horse-race.png') }}" class="mw-100 mh-300px theme-light-show" alt=""/>
    <img src="{{ image('misc/horse-race.png') }}" class="mw-100 mh-300px theme-dark-show" alt=""/>
</div>
<!--end::Illustration-->

<!--begin::Link-->
<div class="mb-0">
    <a href="{{ route('acp.dashboard') }}" class="btn btn-sm btn-primary">{{ __('acp/general.error.back_link') }}</a>
</div>
<!--end::Link-->
