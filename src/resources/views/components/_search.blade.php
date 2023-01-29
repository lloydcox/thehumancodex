@php($search = request()->is('search*'))
<div class="field search-input-field">
    <div class="control has-icons-left{{ $search ? ' has-filter-button' : '' }}">
        <form action="{{ route('search') }}">
            <input type="text" name="q" placeholder="Search..." class="input is-rounded" value="{{ request()->get('q') }}">
            <span class="icon is-left">
                <i class="fas fa-search"></i>
            </span>
            @if($search)
                <span class="filter-button">
                    <button type="submit" class="is-hidden"></button>
                    <button formaction="{{ route('search.filters') }}" class="button is-small is-rounded is-primary is-flat is-mobile">Filters</button>
                </span>
            @endif
        </form>
    </div>
</div>