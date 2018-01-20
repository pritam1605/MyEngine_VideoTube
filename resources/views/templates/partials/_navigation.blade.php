<nav class="navbar has-shadow">
  <div class="navbar-brand">

    <a class="navbar-item" href="{{ route('home') }}">
      <img src="{{ config('myengine.cdn.cloudfront.images') . 'myengine_logo.png' }}" alt="myengine-logo">
    </a>

    <div class="navbar-burger burger" data-target="navigation_bar">
      <span></span>
      <span></span>
      <span></span>
    </div>

  </div>

  <div id="navigation_bar" class="navbar-menu">

    <div class="navbar-start">
      <searchbox></searchbox>
    </div>

    @if (!isset($smallNavBar))
      <div class="navbar-end">
        @if (Auth::check())
          <a class="navbar-item is-tab" href="{{ route('video.upload') }}">
            <span class="icon is-small"><i class="fa fa-cloud-upload"></i></span>&nbsp;Upload
          </a>

          <div class="navbar-item has-dropdown is-hoverable">
            <a class="navbar-link">
              <img class="avatar-photo" src="{{ Auth::user()->getImageUrl() }}">
            </a>

            <div class="navbar-dropdown is-right">
              <a class="navbar-item">
                <p>
                  <span class="icon is-small"><i class="fa fa-user-circle-o"></i></span>&nbsp;{{ Auth::user()->getNameOrUsername() }}
                  <br>
                  {{ Auth::user()->email }}
                </p>
              </a>
              <hr class="navbar-divider">

              <a class="navbar-item" href="{{ route('user.profile', Auth::user()) }}">
                <span class="icon is-small"><i class="fa fa-user"></i></i></span>&nbsp;My Profile
              </a>

              <a class="navbar-item" href="{{ route('all.videos', Auth::user()) }}">
                <span class="icon is-small"><i class="fa fa-video-camera"></i></span>&nbsp;My Videos
              </a>

              <a class="navbar-item" href="{{ route('all.channels', Auth::user()) }}">
                <span class="icon is-small"><i class="fa fa-television"></i></span>&nbsp;My Channels
              </a>

              <a class="navbar-item" href="{{ route('user.purchases', Auth::user()) }}">
                <span class="icon is-small"><i class="fa fa-credit-card"></i></span>&nbsp;My Purchases
              </a>

              <a class="navbar-item" href="{{ route('user.subscriptions', Auth::user()) }}">
                <span class="icon is-small"><i class="fa fa-newspaper-o"></i></span>&nbsp;My Subscriptions
              </a>

              <a class="navbar-item" href="{{ route('show.create.channel.page') }}">
                <span class="icon is-small"><i class="fa fa-plus"></i></span>&nbsp;Create Channel
              </a>

              <a class="navbar-item" href="{{ route('password.change') }}">
                <span class="icon is-small"><i class="fa fa-key"></i></span>&nbsp;Change Password
              </a>

              <hr class="navbar-divider">
              <a class="navbar-item" href="{{ route('logout') }}">
                <span class="icon is-small"><i class="fa fa-sign-out"></i></span>&nbsp;Logout
              </a>
            </div>
          </div>
        @else
          <a class="nav-item is-tab" href="{{ route('login') }}">
            <span class="icon"><i class="fa fa-sign-in"></i></span>&nbsp;Login
          </a>
        @endif
      </div>
    @endif
  </div>
</nav>