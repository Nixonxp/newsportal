@isset($adSidebar)
    <div class="advert-widget">
        <div class="widget-title">
            <h5>@lang('main.advert')</h5>
        </div>
        <div class="advert-thumb mb-30">
            <a href="{{ $adSidebar->link }}">
                <img src="{{ Storage::url($adSidebar->image) }}" alt="{{ $adSidebar->name }}">
            </a>
        </div>
    </div>
@endisset
