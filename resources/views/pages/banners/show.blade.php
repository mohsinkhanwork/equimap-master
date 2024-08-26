<x-system-layout>
    <!--begin::Page bg image-->
    <style>
        body {
            background-image: url('{{ image('auth/bg10.jpeg') }}');
        }

        [data-bs-theme="dark"] body {
            background-image: url('{{ image('auth/bg10-dark.jpeg') }}');
        }

        .page-content {
            text-align: justify;
        }
    </style>
    <!--end::Page bg image-->

    <!--begin::Title-->
    <h1 class="fw-bold fs-2qx text-gray-900 mb-4">
        {{ $page->name }}
    </h1>
    <!--end::Title-->

    <!--begin::Text-->
    <div class="page-content fw-semibold fs-7 text-gray-500 mb-7">
        {!! $page->content !!}
    </div>
    <!--end::Text-->
</x-system-layout>
