<dropdown-trigger class="h-9 flex items-center">
    @isset($user->email)
        <img
            @if (Auth::user()->hasMedia('avatar'))
            src="{{Auth::user()->getFirstMediaUrl('avatar')}}"
            @else
            src="https://secure.gravatar.com/avatar/{{ md5($user->email) }}?size=512"
            @endif
            class="rounded-full w-8 h-8 mr-3"
        />
    @endisset

    <span class="text-90" id="username">
        {{ $user->name ?? $user->email ?? __('Nova User') }}
    </span>
</dropdown-trigger>

<dropdown-menu slot="menu" width="200" direction="rtl">
    <ul class="list-reset">
        <li>
            <router-link to="/resources/users/{{Auth::user()->id}}/edit" class=" block no-underline text-90 hover:bg-30 p-3">
                <i class="fas fa-lock ml-2" style="min-width:2rem"></i>Profile
            </router-link>
        </li>
        <li>
            <a href="{{ route('nova.logout') }}" class=" block no-underline text-90 hover:bg-30 p-3">
               <i class="fas fa-sign-out-alt ml-2" style="min-width:2rem;"></i>Logout
            </a>
        </li>
        <li>
            <nova-dark-theme-toggle
                label="{{ __('Dark Theme') }}"
            ></nova-dark-theme-toggle>
        </li>
    </ul>
</dropdown-menu>
