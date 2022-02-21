<div class="header-advert-area">
    @isset($adHeader)
         <a href="{{ $adHeader->link }}">
             <img src="{{ Storage::url($adHeader->image) }}" alt="{{ $adHeader->name }}">
         </a>
    @endisset
</div>
