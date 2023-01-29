<div class="columns">
    <div class="column is-2">
        @if(isset($backUrl))
            <a href="{{ $backUrl }}" class="button is-circle is-rounded">
                <i class="fa fa-arrow-left"></i>
            </a>
        @endif
    </div>
    <div class="column has-text-centered">
        <h5 class="is-uppercase has-text-grey-light is-size-7 spacer">{{ $title }}</h5>
    </div>
    <div class="column is-2"></div>
</div>
