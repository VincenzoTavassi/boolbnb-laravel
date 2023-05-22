<nav class="navbar fixed-top navbar-expand-md bg-light">
    <div class="container ">
      <a class="navbar-brand d-flex align-items-center" href="{{ route('homepage') }}">
        <h3 class="text-danger mb-1">BoolBnB</h3>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Left Side Of Navbar -->
        @auth
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('apartments.index') }}">{{ __('Lista') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('messages.index') }}">{{ __('Lista messaggi') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{ route('apartments.create') }}">{{ __('Crea annuncio') }}</a>
          </li>
        @endauth
        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
          <!-- Authentication Links -->
          @guest
            <li class="nav-item">
              <a class="nav-link text-dark" href="{{ route('login') }}">{{ __('Login') }}</a>
            </li>
            @if (Route::has('register'))
              <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('register') }}">{{ __('Register') }}</a>
              </li>
            @endif
          @else
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false" v-pre>
                {{ Auth::user()->name }}
              </a>

              <div class="dropdown-menu dropdown-menu-right dropdown-menu text-light">
                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a>
                <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </div>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>