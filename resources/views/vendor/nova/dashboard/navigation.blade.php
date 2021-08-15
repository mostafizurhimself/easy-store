<h4 class="mb-4 text-xs text-white-50% uppercase tracking-wide">{{ __('Dashboards') }}</h4>
<router-link exact tag="h3" :to="{
        name: 'dashboard.custom',
        params: {
            name: 'main'
        }
    }" class="cursor-pointer flex items-center font-normal dim text-white mb-4 text-base no-underline">
    <i class="fas fa-home mr-3"></i>
    <span class="sidebar-label">{{ __('Home') }}</span>
</router-link>
@if (\Laravel\Nova\Nova::availableDashboards(request()))
    <ul class="list-reset mb-8">
        @foreach (\Laravel\Nova\Nova::availableDashboards(request()) as $dashboard)
            <li class="leading-wide font-normal text-white mb-4 text-base no-underline dim">

                <router-link :to='{
                    name: "dashboard.custom",
                    params: {
                        name: "{{ $dashboard::uriKey() }}",
                    },
                    query: @json($dashboard->meta()),
                }' exact class="text-white no-underline dim">
                    {{-- Icon --}}
                    @if (method_exists($dashboard, 'icon'))
                        <i class="{{ $dashboard::icon() }} mr-3"></i>
                    @else
                        <svg class="sidebar-icon fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill="var(--sidebar-icon)"
                                d="M3 1h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2V3c0-1.1045695.8954305-2 2-2zm0 2v4h4V3h-4zM3 11h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2H3c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4H3zm10-2h4c1.1045695 0 2 .8954305 2 2v4c0 1.1045695-.8954305 2-2 2h-4c-1.1045695 0-2-.8954305-2-2v-4c0-1.1045695.8954305-2 2-2zm0 2v4h4v-4h-4z" />
                        </svg>
                    @endif
                    {{-- Label --}}
                    {{ $dashboard::label() }}
                </router-link>
            </li>
        @endforeach
    </ul>
@endif
