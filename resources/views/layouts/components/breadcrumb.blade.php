<div class="content-heading">
    <div class="heading-left">
        <h1 class="page-title">{{ $title }}</h1>
        <p class="page-subtitle">Sistem informasi {{ replaceUnderscore(env('APP_NAME')) }}.</p>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @php
                $segments = '';
            @endphp
            @for($i = 1; $i <= count(Request::segments()); $i++)
                @php
                    $segments .= '/'. Request::segment($i);
                @endphp
                @if($i < count(Request::segments()))
                    <li class="breadcrumb-item">{!! ($i == 1 ? '<i class="fa fa-home"></i> ' : '').ucwords(replaceMin(Request::segment($i))) !!}</li>
                @else
                    <li class="breadcrumb-item active">{!! ($i == 1 ? '<i class="fa fa-home"></i> ' : '').ucwords(replaceMin(Request::segment($i))) !!}</li>
                @endif
            @endfor
            @if($segments == '')
                <li class="breadcrumb-item active"><i class="fa fa-home"></i> Dashboard</li>
            @endif
        </ol>
    </nav>
</div>