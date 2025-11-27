$(document).ready(function() {
    $('.zebra-datepicker.zebra-datepicker-range-start').Zebra_DatePicker({
        format: 'Y-m-d H:i',
        months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        days: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
        show_select_today : 'Cегодня',
        direction: false,
        pair: $('.zebra-datepicker.zebra-datepicker-range-end')
    });

    $('.zebra-datepicker.zebra-datepicker-range-end').Zebra_DatePicker({
        format: 'Y-m-d H:i',
        months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
        days: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
        show_select_today : 'Cегодня',
        direction: true,
        pair: $('.zebra-datepicker.zebra-datepicker-range-start')
    });

    $(document).on('click', '.object-delete', function(){
        if (confirm('Удалить?')) {
            $('#object-form-delete').submit();
        }
    });

    $(document).on('click', '.switch-button', function(){
        var switch_id = $(this).attr('data-switch_hidden_id'),
            value = $(this).hasClass('active') ? 1 : 0;
        $('.switch-hidden[data-switch_hidden_id="' + switch_id + '"]').val(value);
    });

    $(document).on('change', '.file-image-button input[type="file"]', function(){
        if (this.files && this.files[0]) {
            var reader = new FileReader(),
                parent = $(this).closest('.file-image-button');
            reader.onload = function(e) {
                parent.find('.file-image-button-image img').remove();
                parent.find('.file-image-button-image').append('<img src="' + e.target.result + '">');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    $('.select-select2').select2({
        language: 'ru'
    });

    //Init SVG
    var map_block = d3.select('#event-price-map'),
        svg = map_block.select('svg'),
        svg_width = !svg.empty() ? svg.node().getBoundingClientRect().width : 0,
        svg_height = !svg.empty() ? svg.node().getBoundingClientRect().height : 0,
        container = svg.select('.sh-container'),
        container_width = !container.empty() ? container.node().getBoundingClientRect().width : 0,
        container_height = !container.empty() ? container.node().getBoundingClientRect().height : 0,
        init_scale = d3.min([svg_width / container_width, svg_height / container_height]),
        init_x = svg_width / 2 - container_width / 2 * init_scale,
        init_y = svg_height / 2 - container_height / 2 * init_scale,
        scale_max_coof = 2;

    container.attr('transform', d3.zoomIdentity.translate(init_x, init_y).scale(init_scale)).call(map_remove_loading);

    function map_add_loading(){
        map_block.classed('loading', true);
    }

    function map_remove_loading(){
        map_block.classed('loading', false);
    }


    $(document).on('click', '.event-price-map .sh-path', function() {

        if (!map_block.classed('place-mode')) {
            map_add_loading();

            var bbox = d3.select(this).node().getBBox(),
                k = (init_scale + 0.8) * Math.min(svg_width / bbox.width, svg_height / bbox.height, scale_max_coof);

            map_block.classed('loading', true);

            container.attr('transform', d3.zoomIdentity.translate((svg_width - 10) / 2 - (bbox.x + bbox.width / 2) * k, (svg_height - 10) / 2 - (bbox.y + bbox.height / 2) * k).scale(k));
            map_block.classed('place-mode', true);

            var token = map_block.attr('data-token'),
                event_id = map_block.attr('data-event_id'),
                sector_id = $(this).closest('.sh-sector').attr('sid');

            $.ajax({
                type: 'POST',
                url: '/admin/events/' + event_id + '/price-get-places',
                data: 'event_id=' + event_id + '&sector_id=' + sector_id + '&_token=' + token,
                success: function (data) {
                    var places_html = '';

                    Object.keys(data.places).forEach(function(key) {
                        places_html += '<circle class="pa' + (data.places[key].price ? ' price' : '') + (data.places[key].status ? ' sale' : '') + (data.places[key].sold ? ' sold' : '') + '" ' + (data.places[key].price ? 'ps="' + data.places[key].price + '" ' : '') + 'cx="' + data.places[key].svg_x + '" cy="' + data.places[key].svg_y + '" r="7" pp="' + data.places[key].svg_place + '" pr="' + data.places[key].svg_row + '" data-html="true" data-toggle="tooltip" data-placement="top" title="ряд: ' + data.places[key].svg_row + '&nbsp;&nbsp;место: ' + data.places[key].svg_place + (data.places[key].price ? '<br>' + data.places[key].price + ' ₽' : '') + '"></circle>' + (data.places[key].parse ? '<circle class="pz" cx="' + (data.places[key].svg_x + 6) + '" cy="' + (data.places[key].svg_y - 6) + '" r="3"></circle>' : '');
                    })

                    map_block.select('.sh-actived-places').html(places_html);
                    map_block.select('.sh-actived-places').attr('sid', sector_id);
                    $('[data-toggle="tooltip"]').tooltip();
                },
                complete: function() {
                    map_remove_loading();
                }
            })
        }
    })

    $(document).on('click', '.event-price-map .event-price-action-return-mode', function(){
        map_add_loading();
        $(this).closest('.event-price-map').removeClass('place-mode');
        $(this).closest('.event-price-map').find('.sh-actived-places').html('');
        container.attr('transform', d3.zoomIdentity.translate(init_x, init_y).scale(init_scale));
        map_sector_update();
    })

    $(document).on('click', '.event-price-map .event-price-action-refresh', function(){
        $(this).closest('.event-price-map').find('.sh-actived-places .pa').removeClass('active');
    })

    $(document).on('click', '.event-price-map .event-price-action-select-all', function(){
        $(this).closest('.event-price-map').find('.sh-actived-places .pa').addClass('active');
    })



    $(document).on('click', '.event-price-map .event-price-action-on', function(){
        $(this).closest('.event-price-map').find('.sh-actived-places .pa.active').addClass('sale');
        $(this).closest('.event-price-map').find('.sh-actived-places .pa').removeClass('active');
    })

    $(document).on('click', '.event-price-map .event-price-action-off', function(){
        $(this).closest('.event-price-map').find('.sh-actived-places .pa.active').removeClass('sale');
        $(this).closest('.event-price-map').find('.sh-actived-places .pa').removeClass('active');
    })



    $(document).on('click', '.event-price-map .event-price-action-set', function(){
        $(this).closest('.event-price-map').find('.event-price-map-set-price').addClass('show');
        $(this).closest('.event-price-map').find('.event-price-map-set-price .event-price-map-set-price-value').focus();
    })

    $(document).on('click', '.event-price-map .event-price-map-set-price-buttons-close', function(){
        $(this).closest('.event-price-map-set-price').removeClass('show');
        $(this).closest('.event-price-map-set-price').find('.event-price-map-set-price-value').val('1000');
    })

    $(document).on('click', '.event-price-map .event-price-map-set-price-buttons-set', function(){
        map_add_loading();

        var price = $(this).closest('.event-price-map-set-price').find('.event-price-map-set-price-value').val();

        if (price == 0) {
            $('.event-price-map .event-price-map-set-price-buttons-remove').trigger('click');
        } else {
            $(this).closest('.event-price-map').find('svg .sh-actived-places .pa.active').each(function(){
                $(this).attr('ps', price).addClass('price').removeClass('active');
                $(this).attr('title', 'ряд: ' + $(this).attr('pr') + '&nbsp;&nbsp;место: ' + $(this).attr('pp') + '<br>' + price + ' ₽');
            })
            $('[data-toggle="tooltip"]').tooltip('dispose');
            $('[data-toggle="tooltip"]').tooltip();
            $(this).closest('.event-price-map-set-price').removeClass('show');
            $(this).closest('.event-price-map-set-price').find('.event-price-map-set-price-value').val('1000');
        }

        map_remove_loading();
    })

    $(document).on('click', '.event-price-map .event-price-map-set-price-buttons-remove', function(){
        $(this).closest('.event-price-map').find('svg .sh-actived-places .pa.active').each(function(){
            $(this).removeAttr('ps').removeClass('price active');
            $(this).attr('title', 'ряд: ' + $(this).attr('pr') + '&nbsp;&nbsp;место: ' + $(this).attr('pp'));
        })
        $('[data-toggle="tooltip"]').tooltip('dispose');
        $('[data-toggle="tooltip"]').tooltip();
        $(this).closest('.event-price-map-set-price').removeClass('show');
        $(this).closest('.event-price-map-set-price').find('.event-price-map-set-price-value').val('1000');
    })


    $(document).on('click', '.event-price-map svg .sh-actived-places .pa', function(){
        $(this).toggleClass('active');
    })

    $(document).on('dblclick', '.event-price-map svg .sh-actived-places .pa', function(){
        var _this = $(this);
        if (_this.hasClass('active')) {
            _this.closest('.sh-actived-places').find('.pa[pr=' + _this.attr('pr') + ']').removeClass('active')
        } else {
            _this.closest('.sh-actived-places').find('.pa[pr=' + _this.attr('pr') + ']').addClass('active')
        }
    })

    $(document).on('click', '.event-price-map .event-price-action-save', function(){

        map_add_loading();

        var map_parent = $(this).closest('.event-price-map'),
            token = map_parent.attr('data-token'),
            event_id = map_parent.attr('data-event_id'),
            sector_id = map_parent.find('.sh-actived-places').attr('sid'),
            places = [];

        map_parent.find('.sh-actived-places .pa').each(function(){
            places.push({
                row: $(this).attr('pr'),
                place: $(this).attr('pp'),
                price: $(this)[0].hasAttribute('ps') ? $(this).attr('ps') : 0,
                status: $(this).hasClass('sale')
            })
        })

        $.ajax({
            type: 'POST',
            url: '/admin/events/' + event_id + '/price-save-places',
            data: {
                _token: token,
                event_id: event_id,
                sector_id: sector_id,
                places: JSON.stringify(places)
            },
            success: function () {
                map_parent.removeClass('place-mode');
                map_parent.find('.sh-actived-places').html('');
                container.attr('transform', d3.zoomIdentity.translate(init_x, init_y).scale(init_scale));
                map_sector_update();
            },
            complete: function() {
                map_remove_loading();
            }
        })
    })

    function map_sector_update() {
        var token = map_block.attr('data-token'),
            event_id = map_block.attr('data-event_id');

        $.ajax({
            type: 'POST',
            url: '/admin/events/' + event_id + '/price-get-sectors',
            data: {
                event_id: event_id,
                _token: token
            },
            success: function (data) {
                var sectors_html = '';
                Object.keys(data.svg_sector).forEach(function(key) {
                    sectors_html += '<g class="sh-sector' + (data.svg_sector[key].place_count ? ' active' : '') + '" sn="' + data.svg_sector[key].title + '" sid="' + data.svg_sector[key].sector_id + '"><path class="sh-path" d="' + data.svg_sector[key].path + '" data-html="true" data-toggle="tooltip" data-placement="top" title="' + data.svg_sector[key].title + (data.svg_sector[key].place_count ? '<br>мест: ' + data.svg_sector[key].place_count + ' шт<br>цена: ' + data.svg_sector[key].price + ' ₽' : '') + '"/></g>';
                })
                map_block.select('.sh-sectors').html(sectors_html);
                map_remove_loading();
                $('[data-toggle="tooltip"]').tooltip();
            },
            complete: function() {
                map_remove_loading();
            }
        })
    }

    $(document).on('change', '.order-list-status-change', function(){
        var _this = $(this),
            status = _this.val();
        $.ajax({
            type: 'POST',
            url: '/admin/orders/ajax-change-status',
            data: {
                order_id: _this.attr('data-order_id'),
                _token: _this.attr('data-token'),
                status: status
            },
            beforeSend: function() {
                _this.closest('tr').addClass('loading');
            },
            success: function (data) {
                if (!data.error) {
                    _this.closest('tr').removeClass('bg-pale-gray');
                    if (status == 0) _this.closest('tr').addClass('bg-pale-gray');
                }
            },
            complete: function() {
                _this.closest('tr').removeClass('loading');
            }
        })
    })
});