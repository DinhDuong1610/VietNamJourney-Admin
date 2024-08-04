<div class="card card-success mt-3">
    <div class="card-header d-flex align-items-center">
        <div class="card-title w-100 mb-0">
            @yield('card-title')
        </div>
    </div>

    <form>
        <div class="card-body">
            @yield('card-body')
        </div>

        {{-- <div class="card-footer">
           @yield('card-footer')
        </div> --}}
    </form>
</div>