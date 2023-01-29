<div class="profile-switcher {{ $class or '' }}">
    <div style="text-align:center;padding-top: 10px">{!!(Request::is('/') || empty($user)) ?'Feed':$user->fullname.'\'s journey so far'  !!}</div>
    <hr style="margin: 2px">
    <div class="card-content">
        <div class="columns is-gapless is-mobile text-center">
            <div class="column is-6-desktop is-6-mobile">
                <a href="{{ $profileUrl or '#' }}" class="{{ !request()->is('*/map') ? 'has-text-black' : '' }}">
                    <i class="fas fa-list"></i>
                    Profile
                </a>
            </div>
            <div class="column is-6-desktop is-6-mobile">
                <a href="{{ $mapUrl or '#' }}" class="{{ request()->is('*/map') ? 'has-text-black' : '' }}" style="{{ request()->url() === env('APP_URL') || request()->url() === env('APP_URL').'/codex'  ? 'float:center' : ''}}">
                    <i class="far fa-map"></i>
                    Geographically
                </a>
            </div>
            <div class="column is-6-desktop is-6-mobile">
                <a href="{{route('horizontal-timeline')}}" class="{{ !request()->is('*/map') ? 'has-text-black' : '' }}">
                <i class="fas fa-list"></i>
                    Chronologically
                </a>
            </div>
        </div>
    </div>
</div>