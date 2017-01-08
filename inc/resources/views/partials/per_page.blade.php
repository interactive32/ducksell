<div class="text-right per-page">
    <label>
        <select id="page_length" class="input-sm" onchange="location = this.options[this.selectedIndex].value;">
            @foreach(config('global.per_page') as $val)
                <option @if(session('per_page') == $val)selected="selected"@endif value="{{ url('profile/per_page/'.$val) }}">{{ $val  }}</option>
            @endforeach
        </select>
    </label>
</div>