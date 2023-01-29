<header class="profile-header">
    <div class="bar"></div>
    <figure class="avatar image is-128x128">
        <img src="{{ $user->avatar }}" alt="Avatar" class="is-rounded">
    </figure>
    <div class="columns">
        <div class="column is-6 is-offset-one-quarter">
            <h2 class="is-size-5 has-text-black">
                {{ $user->fullName }}
            </h2>
            <h5 class="is-size-7 has-text-black">
                {{ $user->username }}
            </h5>
        </div>
        <div class="column is-3">
            @if(empty($connection) && $user->id !== request()->user()->id)
                <add-connection-button :user="{{ $user }}"/>
            @elseif(!empty($connection->accepted))
                <remove-connection-button :user="{{ $user }}"/>
            @endif
        </div>
        
    </div>
    <div class="columns">
        <div class="column is-6 is-offset-one-quarter">
            <p class="description">
                {{ $user->description }}
            </p>
            <p class="is-size-7 has-text-black">
                <span class="icon is-left has-text-grey-light is-text-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </span>
                {{ $user->location }}
                <span class="icon is-left has-text-grey-light is-text-icon">
                    <i class="fas fa-calendar-alt"></i>
                </span>
                Born on {{ $user->age->format('F d, Y') }}
                
            </p>
        </div>
        <div class="column is-3">
            @if(!empty($connection->accepted))
                <connection-category-dropdown :user="{{ $user }}"></connection-category-dropdown>
            @endif
        </div>
    </div>
</header>