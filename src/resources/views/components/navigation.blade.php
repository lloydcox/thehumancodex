
<user-navbar-menu>

    @if(Auth::check())
    <template slot="desktop">
        <div class="navbar-center">
            <div class="navbar-item">
                @include('components._search')
            </div>
        </div>

        <div class="navbar-end">


            <a href="{{ url('activities') }}" class="navbar-item{{ request()->is("activities") ? ' is-active' : '' }}">

                <!-- ------------notification bell icon and count badge------------ -->

                <i class="far fa-bell"> <span id="spanid" class='badge badge-secondary'>
                        <!-- {{auth()->user()->notifications()->get()->count()}} -->
                    </span></i>
            </a>
            <a class="navbar-item profile-item{{ request()->is("profile") ? ' is-active' : '' }}" href="{{ url('profile') }}">
                <figure class="image is-24x24">
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar" class="is-rounded">
                </figure>
                {{ Auth::user()->first_name }}
            </a>
            <a href="{{ url('settings') }}" class="navbar-item{{ request()->is("settings*") ? ' is-active' : '' }}">
                <i class="fas fa-cog"></i>
            </a>
        </div>
    </template>


    <template slot="touch">
        <div style="margin-top: 88px">
            <div class="navbar-item">
                @include('components._search')
            </div>
            <a href="{{ url('activities') }}" class="navbar-item{{ request()->is("activities") ? ' is-active' : '' }}">
                Activities
            </a>
            <a href="{{ url('profile') }}" class="navbar-item{{ request()->is("profile") ? ' is-active' : '' }}">
                Profile
            </a>
            <a href="{{ url('settings') }}" class="navbar-item{{ request()->is("settings*") ? ' is-active' : '' }}">
                Settings
            </a>
            <a href="{{ url('logout') }}" class="navbar-item has-text-danger">
                Log out
            </a>
        </div>
    </template>
    @endif

</user-navbar-menu>


<script type="application/javascript" src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

<!-- ----------a prototype of real time ajax function-------------- -->

<script type="application/javascript">
    function fetchdata() {
        $.ajax({
            url: '/countNot',
            type: 'get',

            success: function(response) {
                $("#spanid").html(response);

            }
        });
    }
    $(document).ready(function() {
        setInterval(fetchdata, 200000);
    });
</script>
