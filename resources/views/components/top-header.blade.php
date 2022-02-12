<div class="top-header">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <!-- Breaking News Area -->
            <div class="col-12 col-md-6">
                <div class="breaking-news-area">
                    <h5 class="breaking-news-title">@lang('main.breaking_news')</h5>
                    <div id="breakingNewsTicker" class="ticker">
                        <ul>
                            @foreach($posts as $post)
                                <li><a href="{{ route('newspost', [$post->category, $post]) }}">{{ $post->title }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        <!-- Stock News Area -->
            <div class="col-12 col-md-6">
                <div class="stock-news-area">
                    <div id="stockNewsTicker" class="ticker">
                        <ul>
                            @forelse($stocks as $stockLine)
                                <li>
                                    @foreach($stockLine as $stockItem)
                                        <div class="single-stock-report">
                                            <div class="stock-values">
                                                <span>{{ $stockItem->title }}</span>
                                                <span>{{ $stockItem->value }}</span>
                                            </div>
                                            @isset($stockItem->trend)
                                                <div class="stock-index {{$stockItem->trend}}-index">
                                                    <h4>{{ $stockItem->trend_diff }}</h4>
                                                </div>
                                            @endisset
                                        </div>
                                    @endforeach
                                </li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
