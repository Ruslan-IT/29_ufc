@if ($colors)
    <div class="map-prices">
        <span class="caption">Фильтр по цене:</span>
        <ul>
            @foreach($colors as $color)
                @if ($color['available'])
                    <li p-min="{{ $color['from'] }}" p-max="{{ $color['to'] }}">
                        <p class="price">{{ $color['title'] }}</p>
                        <div class="line" style="background:#{{ $color['color'] }}"></div>
                        <div class="tooltip">{{ $color['label'] }}</div>
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
@endif
<div class="map-scheme">
    <div class="tooltip" data-for="place">
        <p class="about-price"><span></span> ₽</p>
        <p class="about-sector"></p>
        <div class="block-right">
            <span class="about-row">
                <span>ряд</span>
                <span class="number"></span>
            </span>,
            <span class="about-place">
                <span>место</span>
                <span class="number"></span>
            </span>
        </div>
    </div>
    <div class="tooltip" data-for="sector">
        <p class="name"></p>
        <div class="about-price"><span class="number"></span></div>
        <div class="free-place">Свободных мест: <span class="number"></span></div>
    </div>
    <div class="hover-place"></div>
    <svg id="svg-map" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0">
        <g id="svg-map-container">
            <filter id="dropShadow" x="-100%" y="-100%" width="300%" height="300%">
                <feDropShadow stdDeviation="3" flood-color="#444" flood-opacity=".5" dx="0" dy="0"></feDropShadow>
            </filter>
            <g class="sh-decor">
                <image xlink:href="{{ url('images/scheme_decor.png') }}" x="0" y="1.5" width="7846px" height="3456px" />
            </g>

            @foreach($svg_conf as $sector)
                <g class="sh-sector" sn="{{ $sector['title'] }}" sid="{{ $sector['id'] }}" fp="{{ count($sector['places']) }}" pc="{{ $sector['price_min'] == $sector['price_max'] ? $sector['price_min'] : $sector['price_min'] . ' - ' . $sector['price_max'] }} ₽">
                    @foreach($sector['places'] as $place)
                        <circle class="pa" cx="{{ $place['x'] }}" cy="{{ $place['y'] }}" r="{{ $sector['place_radius'] }}"{{ $sector['place_radius_max'] ? ' mx=' . $sector['place_radius_max'] : '' }} pp="{{ $place['place'] }}" pr="{{ $place['row'] }}" ps="{{ $place['price'] }}" style="fill: #{{ $place['color'] }}; stroke: #{{ $place['color'] }}" />
                    @endforeach
                    <path class="sh-path" d="{{ $sector['path'] }}"/>
                </g>
            @endforeach

            <g class="sh-actived-places"></g>
        </g>
    </svg>
    <div class="controls">
        <button class="change-zoom svg-map-button" type="button" data-zoom_type="+"><img src="images/icon-controll-plus.png"></button>
        <button class="change-zoom svg-map-button disabled" type="button" data-zoom_type="-"><img src="images/icon-controll-minus.png"></button>
        <button class="disabled" id="reset" type="button">
            <img src="images/icon-controll-home.png" alt="">
        </button>
    </div>
</div>
<script>
    //Init SVG
    var svg = d3.select("#svg-map"),
        svg_width = svg.node().getBoundingClientRect().width,
        svg_height = svg.node().getBoundingClientRect().height,

        container = d3.select("#svg-map-container"),
        container_width = container.node().getBoundingClientRect().width,
        container_height = container.node().getBoundingClientRect().height,

        controlWidth = parseFloat($('.controls').width()) + parseFloat($('.controls').css('right')) + 5,
        scale_coof = 0.5,
        init_scale = d3.min([(svg_width - controlWidth) / container_width, svg_height / container_height]),
        init_x = (svg_width - controlWidth) / 2 - container_width / 2 * init_scale,
        init_y = svg_height / 2 - container_height / 2 * init_scale,
        scale_max = init_scale + 2.5 * scale_coof,

        sizeActiveCircle = 3,
        maxSizeActiveCircle = 4.5,

        borderCoef = 1.5,

        zoom = d3.zoom().on("zoom", zoomed).on("end", check_zoom),

        tooltipPlace = d3.select('.map-scheme .tooltip[data-for="place"]'),
        tooltipSector = d3.select('.map-scheme .tooltip[data-for="sector"]'),
        hoverPlace = d3.select('.map-scheme .hover-place'),
        showPlace = true,
        activedPlaces = d3.select('.sh-actived-places');

    $('[mx]').each(function() {
        $(this).attr('old-r', $(this).attr('r'));
    });

    svg.call(zoom).on("wheel.zoom", null).on("dblclick.zoom", null);;
    wrapper = d3.select('.map-scheme');

    svg.call(zoom.transform, d3.zoomIdentity.translate(init_x, init_y).scale(init_scale)).call(removeSpiner);

    var SC = new ShopingCart();
    d3.selectAll('.svg-map-button').on('click', zoomClick);
</script>