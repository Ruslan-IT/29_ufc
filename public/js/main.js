function ShopingCart() {
	var tikets = (getLS() ? getLS() : []);
	var arrayActivedPlaces = [];
	tikets.forEach(function(arrayItem) {
		ticket = arrayItem;
		setActived(ticket.place, ticket.row, ticket.price, ticket.sector);
	});
	$('feDropShadow').attr('stdDeviation', 3 / d3.zoomTransform(svg.node()).k);
	$('.sh-path').attr('stroke-width', 3 / d3.zoomTransform(svg.node()).k);
	result();

	// Publick metods for DOM
	this.addPlace = function(item) {
		try {
			var place = item.attr('pp'),
				row = item.attr('pr'),
				price = item.attr('ps'),
				sector = item.closest('.sh-sector').attr('sn'),
                sector_id = item.closest('.sh-sector').attr('sid'),
				cx = item.attr('cx'),
				cy = item.attr('cy'),
				r = item.attr('r'),
				fill = d3.select(item.get(0)).style('fill');
		} catch (err) {
			tikets = [];
			result();
			removeAllActived();
		}
		appendCirlceToActivedPlaces(activedPlaces, cx, cy, r, fill, place, row, price, sector)
		tikets.push({
			place: place,
			row: row,
			price: price,
			sector: sector,
            sector_id: sector_id
		});
		result();
	}

	this.removeTicket = function(ticket) {
		var place = ticket.attr('pp'),
			row = ticket.attr('pr'),
			price = ticket.attr('ps'),
			sector = ticket.attr('sn');
		removeFromArray(place, row, price, sector);
	};

	this.countTickets = function() {
		return tikets.length;
	}
	this.removeTicketFormOutput = function(item) {
		var place = item.attr('pp'),
			row = item.attr('pr'),
			price = item.attr('ps'),
			sector = item.attr('sn');
		removeFromArray(place, row, price, sector);
		removeActived(place, row, price, sector);
	};

	this.clearArray = function() {
		tikets = [];
		result();
		removeAllActived();
	}

	// Work with LS
	function getLS() {
		var data;
		if (typeof(localStorage['tikets_leps']) == 'undefined') {
			data = false;
		} else {
			data = JSON.parse(localStorage['tikets_leps']);
		}
		return data;
	}

	function writeToLs() {
		localStorage['tikets_leps'] = JSON.stringify(tikets);
	}

	// Private Metods
	function setActived(place, row, price, sector) {
		try {
			var circle = $(`[sn="${sector}"] .pa[pp="${place}"][ps="${price}"][pr="${row}"]`),
				cx = circle.attr('cx'),
				cy = circle.attr('cy'),
				r = circle.attr('r'),
				fill = d3.select(circle.get(0)).style('fill');
		} catch (err) {
			tikets = [];
			result();
			removeAllActived();
		}
		rZommed = r / d3.zoomTransform(svg.node()).k * sizeActiveCircle;
		appendCirlceToActivedPlaces(activedPlaces, cx, cy, r, fill, place, row, price, sector);
	}

	function removeActived(place, row, price, sector) {
		$(`.actived-place[pp="${place}"][ps="${price}"][pr="${row}"][sn="${sector}"]`).remove();
		arrayActivedPlaces.forEach(function(arrayItem, i) {
			if (JSON.stringify(arrayItem) === JSON.stringify({
					place: place,
					row: row,
					price: price,
					sector: sector
				})) {
				removed = i;
			}
		});
		arrayActivedPlaces.splice(removed, 1);
	}

	function removeAllActived() {
		activedPlaces.html('');
		arrayActivedPlaces = [];
	}

	function countPrice() {
		var allPrice = 0;
		tikets.forEach(function(arrayItem) {
			allPrice += parseFloat(arrayItem.price);
		});
		return allPrice;
	};

	function removeFromArray(place, row, price, sector) {
		var removed;
		tikets.forEach(function(arrayItem, i) {
			if (JSON.stringify(arrayItem) === JSON.stringify({
					place: place,
					row: row,
					price: price,
					sector: sector
				})) {
				removed = i;
			}
		});
		tikets.splice(removed, 1);
		result();
	};

	function result() {
		writeToLs();
		var box = $('.modal-tickets .inner .content.maket .panel-controll .row .your-choose .choosed'),
			table = $('.your-choose-table tbody');

		box.find('.number').html(tikets.length);
		$('.btn.buy .number.billets').html(tikets.length);
		box.find('.wrap-popup .list-tickets').html('');

		table.html('');

		for (let i = 0; i < tikets.length; i++) {
			ticket = tikets[i];
			var template = getTempleateForBox(ticket.place, ticket.row, ticket.price, ticket.sector);
			box.find('.wrap-popup .list-tickets').append(template);

			template = getTempleateForTable(ticket.place, ticket.row, ticket.price, ticket.sector, ticket.sector_id);
			table.append(template);
			niceScrollLeft.resize();
		}
		if (countPrice() == 0) {
			$('.modal-tickets .inner .content.maket .panel-controll').addClass('hide');
			$('.your-choose .choosed').removeClass('show-modal');
		} else {
			$('.modal-tickets .inner .content.maket .panel-controll').removeClass('hide');
		}
		$('.panel-controll span.price').html(countPrice());
		$('.total-table .price .number').html(countPrice());

		$('.btn.buy .number.price').html(countPrice());
	}

	function getTempleateForTable(place, row, price, sector, sector_id) {
		return `<tr class="ticket" pr="${row}" pp="${place}" ps="${price}" sn="${sector}" sid="${sector_id}" data-ticket_id="${sector}_${row}_${place}"><td class="number"></td><td class="ticket-sector">${sector}</td><td class="ticket-row">${row}</td><td class="ticket-place">${place}</td><td class="ticket-price">${price}</td><td><span class="remove"><img src="../images/trash-table.svg" alt=""></span></td></tr>`;
	}

	function getTempleateForBox(place, row, price, sector) {
		return `<li pr="${row}" pp="${place}" ps="${price}" sn="${sector}" data-ticket_id="${sector}_${row}_${place}">${sector}, Row ${row}, Seat ${place} <span class="right"><strong>${price} ₽</strong><span class="delete" data-modal-open="ticket-delete"><img src="../images/trash.svg" alt=""></span></span></li>`;
	}

	function appendCirlceToActivedPlaces(container, cx, cy, r, fill, place, row, price, sector) {
		rZommed = r / d3.zoomTransform(svg.node()).k * sizeActiveCircle;

		console.log(rZommed);

		var max = maxSizeActiveCircle / d3.zoomTransform(svg.node()).k * sizeActiveCircle;
		if (rZommed > max) {
			rZommed = max;
		}

		container.append("circle")
			.classed('actived-place', true)
			.attr("cx", cx)
			.attr("cy", cy)
			.attr("r", rZommed)
			.attr("init-r", r)
			.attr('stroke', fill)
			.attr('stroke-width', rZommed / borderCoef)
			.attr("pp", place)
			.attr("pr", row)
			.attr("ps", price)
			.attr("sn", sector);
		arrayActivedPlaces.push({
			place: place,
			row: row,
			price: price,
			sector: sector
		});
	}
};

$(document).ready(function() {
    $('html').addClass('hide-spiner');

	$('#fullpage').fullpage({
        licenseKey: 'OPEN-SOURCE-GPLV3-LICENSE',
		anchors: ['home', 'video', 'map', 'vip', 'buy'],
		menu: '#menu-fixed',
        afterRender: function(){
		    var height = 67;
            $('.section').each(function(){
                var _this = $(this),
                    _height = _this.height() - height;
                _this.height(_height);
                _this.find('.fp-tableCell').height(_height);
            });
        }
	});

	$('.scroll-link').click(function(e) {
		e.preventDefault();
		$.fn.fullpage.moveSectionDown();
		return false;
	});

	$('.menu a').click(function() {
		$('.inform-section').toggleClass('open');
	})

	$(".body.media").on("mouseenter", ".video__prev img", function() {
		var gif = $(this).attr("data-gif");
		if (gif) {
			$(this).attr('src', gif)
		}
	});
	$(".body.media").on("mouseleave", ".video__prev img", function() {
		var image = $(this).attr("data-image");
		if (image) {
			$(this).attr('src', image)
		}
	});
	$('.popup .close').click(function() {
		closePopup();
	});

	$('.share a').on('click', function() {
		var id = $(this).data('id');
		if (id) {
			var description = '';
			var metas = document.getElementsByTagName('meta');
			for (var x = 0, y = metas.length; x < y; x++) {
				if (metas[x].name.toLowerCase() == "description") {
					description = metas[x].content;
				}
			}
			var url = window.location.href.split('#')[0],
				title = document.getElementsByTagName("title")[0].innerHTML || '',
				description;
			Shares.share(id, url, title, description);
			return false;
		}
	});
	$("[data-fancybox]").fancybox({
		beforeShow: function() {
			$.fn.fullpage.setAllowScrolling(false);
			$.fn.fullpage.setKeyboardScrolling(false);
		},
		afterClose: function() {
			$.fn.fullpage.setAllowScrolling(true);
			$.fn.fullpage.setKeyboardScrolling(true);
		}
	})

	$(document).on('click', '.button-up', function(){
        $.fn.fullpage.moveTo('home');
	})

    var swiper_slider = new Swiper('.swiper-slider', {
        slidesPerView: 3,
        spaceBetween: 20,
        loop: true
    });

	$(document).on('click', '.slider-buttons-left', function(){
        swiper_slider.slidePrev();
	})

    $(document).on('click', '.slider-buttons-right', function(){
        swiper_slider.slideNext();
    })

	$(document).on('click', '.checkout-event-select tr[data-event_id]', function(){
		var event_id = $(this).attr('data-event_id'),
            _token = $(this).attr('data-token');

        $.ajax({
            type: "POST",
            url: '/event-get-map',
            data: {
                _token: _token,
                event_id: event_id,
            },
			beforeSend: function(){
                $('.map-block').removeClass('hide-spiner');
			},
            success: function(data) {
                if (!data.error) {
                    localStorage.removeItem('tikets_leps');
                    $('.content.event-select').removeClass('show');
                    $('.content.maket').addClass('show');
                    $('.map-block').html(data.map_html);
                    $('.content.maket .event-date, .content.order .event-date').html(data.date_text);
                    $('#form-order').attr('data-event_id', event_id);
                }
            }
        })
    })


	// Show first event
    $.ajax({
        type: "POST",
        url: '/event-get-map',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            event_id: 1,
        },
        beforeSend: function(){
            $('.map-block').removeClass('hide-spiner');
            localStorage.removeItem('tikets_leps');
        },
        success: function(data) {
            if (!data.error) {
                $('.content.event-select').removeClass('show');
                $('.content.maket').addClass('show');
                $('.map-block').html(data.map_html);
                $('.content.maket .event-date, .content.order .event-date').html(data.date_text);
                $('#form-order').attr('data-event_id', 1);
            }
        }
    })


    $('.modal-participants .content .info .photos .swiper-container').each(function(){
        var _this = $(this),
            modal_slider = new Swiper(_this, {
                slidesPerView: 3,
                spaceBetween: 20,
                loop: true,
                navigation: {
                    nextEl: _this.closest('.photos').find('.slider-buttons-right'),
                    prevEl: _this.closest('.photos').find('.slider-buttons-left')
                }
            })
    })


	$(document).on('click', '.menu-fixed .menu-button', function(){
        $.fn.fullpage.setAllowScrolling(false);
        $.fn.fullpage.setKeyboardScrolling(false);
	    $('.menu-mobile').addClass('show');
    })

    $(document).on('click', '.menu-mobile .menu-close span, .menu-mobile .menu-items a, .menu-mobile .menu-mobile-buy', function(){
        $.fn.fullpage.setAllowScrolling(true);
        $.fn.fullpage.setKeyboardScrolling(true);
        $('.menu-mobile').removeClass('show');
    })




});

function initMap() {

    if ($('#contact-map').length > 0) {

        var posMark1 = {
            lat: 55.781030,
            lng: 37.626670
        }
        var map = new google.maps.Map(document.getElementById('contact-map'), {
            zoom: 16,
            center: posMark1,
            mapTypeControl: false,
            streetViewControl: false,
            zoomControl: true,
            scaleControl: true,
            scrollwheel: false
        })
        var marker1 = new google.maps.Marker({
            position: posMark1,
            map: map,
            icon: '/images/icon-map.png'
        })
    }
}

function openModal(id) {
	$('body').addClass('has-modal');
	$('.modal-tickets[data-modal="' + id + '"]').addClass('show');
	$.fn.fullpage.setAllowScrolling(false);
	$.fn.fullpage.setKeyboardScrolling(false);
	$(".nice-scroll-right").getNiceScroll().resize();
}

function closeModal() {
	let message = 'Do you really want<br>to finish purchasing tickets?',
		buttons = '<div class="btn buy white" data-close-modal>Exit</div> <div class="btn buy" data-close-message>Continue purchase</div>';
	showMessage('', message, buttons);
}

$('.button-open-popup').on('click', function() {
	if (SC.countTickets() > 0) {
		$(this).closest('.choosed').toggleClass('show-modal');
		$(".nice-scroll-right").getNiceScroll().resize();
		setTimeout(() => {
			$(".nice-scroll-right").getNiceScroll().resize();
		}, 300);
	} else {
		$(this).closest('.choosed').removeClass('show-modal');
	}
});
var niceScrollLeft = $('.nice-scroll').niceScroll("#wrapper", {
	cursoropacitymin: .3,
	railalign: "left",
});

function modal_success(scroll_off, message) {
    scroll_off = (typeof scroll_off === 'undefined') ?  false : scroll_off;
    message = (typeof message === 'undefined') ? 'You have successfully purchased tickets' : message;

    let buttons = '<div class="btn buy" data-close-message>Continue purchase</div>';
    showMessage('', message, buttons, scroll_off);
}

if (window.location.hash == '#success') {
    modal_success();
}


//Function for work with SVG
function zoomed() {
	container.attr("transform", d3.event.transform);
	d3.selectAll('.actived-place').each(function(d, i) {
		r = d3.select(this).attr('init-r') / d3.zoomTransform(svg.node()).k * sizeActiveCircle;

		var max = maxSizeActiveCircle / d3.zoomTransform(svg.node()).k * sizeActiveCircle;

		if (r > max) r = max;

		d3.select(this).attr('r', r).attr('stroke-width', r / borderCoef);
	});
	$('[mx]').each(function() {
		let difR = parseFloat($(this).attr('mx')) - parseFloat($(this).attr('old-r'));
		let difS = scale_max - init_scale;
		let newR = parseFloat($(this).attr('mx')) - (d3.zoomTransform(svg.node()).k - init_scale) * (difR / difS)
		$(this).attr('r', newR);

		if ((d3.zoomTransform(svg.node()).k - init_scale) / difS > 0.2) $(this).attr('r', $(this).attr('old-r'));
	});
	$('feDropShadow').attr('stdDeviation', 3 / d3.zoomTransform(svg.node()).k);
	$('.sh-path').attr('stroke-width', 3 / d3.zoomTransform(svg.node()).k);
	tooltipSector.classed("show", false);
	tooltipPlace.classed("show", false);
	hoverPlace.classed("show", false);
};

function zoomClick() {
	if ($(this).hasClass('disabled')) return;
	var transform = d3.zoomTransform(svg.node()),
		type = $(this).attr('data-zoom_type'),
		scale_direction = 1,
		center = [svg_width / 2, svg_height / 2],
		translate0 = [],
		view = {
			x: transform.x,
			y: transform.y,
			k: transform.k
		};

	if (type == '-') scale_direction = -1;

	scale = transform.k * (1 + scale_direction * scale_coof);

	if (scale < init_scale) scale = init_scale;
	if (scale > scale_max) scale = scale_max;

	svg.transition().duration(350).call(zoom.transform, d3.zoomIdentity
		.translate(transform.x * (scale / transform.k) - center[0] * ((scale / transform.k) - 1), transform.y * (scale / transform.k) - center[1] * ((scale / transform.k) - 1))
		.scale(scale)
	);
}

function check_zoom() {
	$('.controls button').removeClass('disabled');
	var transform_now = d3.zoomTransform(svg.node())
	if (transform_now.k == init_scale) {
		$('[data-zoom_type="-"]').addClass('disabled');
		if (transform_now.x == init_x && transform_now.y == init_y) {
			$('#reset').addClass('disabled');
		}
	}
	if (transform_now.k == scale_max) {
		$('[data-zoom_type="+"]').addClass('disabled');
	}
	if (transform_now.k >= init_scale + 1.3 * scale_coof) {
		$('.map-scheme').addClass('hide-sector');
	} else {
		$('.map-scheme').removeClass('hide-sector');
	}
}

function removeSpiner() {
	setTimeout(() => {
		$('.map-block').addClass('hide-spiner');
		niceScrollLeft.resize();
	}, 100)

}

to_bounding_box = function(W, H, center, w, h) {
	var k, kh, kw, x, y;
	k = init_scale + 1.6 * scale_coof;
	x = W / 2 - center.x * k;
	y = H / 2 - center.y * k;
	return d3.zoomIdentity.translate(x, y).scale(k);
};



$('.modal-tickets').on('click', function(e) {
	var container = $(".your-choose .choosed");
	if (container.has(e.target).length === 0) {
		$('.your-choose .choosed').removeClass('show-modal');
	}
});



$('.nice-scroll-right').niceScroll();
var infoContentScroll = $('.info-block .info-content').niceScroll();
$("[data-inputmask]").inputmask({
	clearMaskOnLostFocus: false,
});
$(document).on('click', '.info-block .close_info, .info-block .btn-back', function() {
	$(this).closest('.info-block').removeClass('show');
});
$(document).on('click', '[data-show-block]', function(e) {
	e.preventDefault();
	let id = $(this).data('show-block');
	$(`.info-block[data-info-block="${id}"]`).addClass('show').siblings('.info-block').removeClass('show');
	infoContentScroll.resize();
});


$('.maket .btn.buy').on('click', function() {
	$('.content.maket').removeClass('show');
	$('.content.order').addClass('show');
	niceScrollLeft.resize();
	setTimeout(() => {
		niceScrollLeft.resize();
	}, 1)
	$('.nice-scroll-right').getNiceScroll().doScrollPos(0);
	$(".nice-scroll-right").getNiceScroll().resize();
});



$('[data-back]').on('click', function() {
	let message = 'Do you really want<br>to return to ticket selection?',
		buttons = '<div class="btn buy white" data-move-back>Return</div> <div class="btn buy" data-close-message>Continue purchase</div>';
	showMessage('', message, buttons);
});


$('.modal-tickets .inner .close').on('click', function() {
	var _this = $(this);

	if (_this.closest('.modal-tickets').hasClass('modal-participants')) {
        $('body').removeClass('has-modal');
        $('.modal-tickets').removeClass('show');
        $.fn.fullpage.setAllowScrolling(true);
        $.fn.fullpage.setKeyboardScrolling(true);
    } else {
        closeModal();
    }
});

$('[data-open-modal]').on('click', function(e) {
	e.preventDefault();
	var idModal = $(this).data('open-modal');
	openModal(idModal);
});

function showMessage(title, message, buttons, scroll_off) {

    scroll_off = (typeof scroll_off === 'undefined') ?  false : scroll_off;

	$('#fancybox-modal .title').html(`${title}`);
	$('#fancybox-modal .message').html(`${message}`);
	$('#fancybox-modal .buttons').html(`${buttons}`);
	$.fancybox.open({
		src: '#fancybox-modal',
		type: 'inline',
		opts: {
			btnTpl: {
				close: false
			},
			smallBtn: false,
			touch: false,
            beforeShow: function() {
                if (scroll_off) {
                    $.fn.fullpage.setAllowScrolling(false);
                    $.fn.fullpage.setKeyboardScrolling(false);
                }
            },
            afterClose: function() {
                $('#fancybox-modal .title').html('');
                $('#fancybox-modal .message').html('');
                $('#fancybox-modal .buttons').html('');
                if (scroll_off) {
                    $.fn.fullpage.setAllowScrolling(true);
                    $.fn.fullpage.setKeyboardScrolling(true);
                }
            }
		}
	});
}

function closeMessage() {
	$.fancybox.close();
	$(".nice-scroll-right").niceScroll();
	$(".nice-scroll-right").removeClass('hideScroll');
}

// Listeners for event click on buttons in message
$(document).on('click', '[data-close-modal]', function() {
	$('body').removeClass('has-modal');
    $('.modal-tickets').removeClass('show');
	$.fn.fullpage.setAllowScrolling(true);
	$.fn.fullpage.setKeyboardScrolling(true);
	closeMessage()
});

$(document).on('click', '[data-close-message]', function() {
	closeMessage();
});
$(document).on('click', '[data-clear-cart]', function() {
	SC.clearArray();
	closeMessage()
});

function modal_move_back() {
    $('.content.order').removeClass('show');
    $('.content.maket').addClass('show');
    $(".nice-scroll-right").getNiceScroll().resize();
    $('.nice-scroll-right').getNiceScroll().doScrollPos(0);
    closeMessage()
}

$(document).on('click', '[data-move-back]', function() {
    modal_move_back();
});


//Listeners for work with Scheme
$(document).on('click', '#reset', function() {
	svg.transition().duration(500).call(zoom.transform, d3.zoomIdentity.translate(init_x, init_y).scale(init_scale));
});

$(document).on('click', '.sh-path', function() {
	bbox = d3.select(this).node().getBBox();
	var bx = bbox.x;
	var by = bbox.y;
	var bw = bbox.width;
	var bh = bbox.height;

	var center, transform;
	center = {
		x: bx + bw / 2,
		y: by + bh / 2
	};
	transform = to_bounding_box(svg_width, svg_height, center, bw, bh);
	svg.transition().duration(500).call(zoom.transform, transform);
	$('.map-scheme').addClass('hide-sector');
});


$(document).on('mouseover', '.pa', function(){
    position_wrapper = {
        x: wrapper.node().getBoundingClientRect().x,
        y: wrapper.node().getBoundingClientRect().y
    };
    position_circle = {
        x: d3.select(this).node().getBoundingClientRect().x,
        y: d3.select(this).node().getBoundingClientRect().y,
        w: d3.select(this).node().getBoundingClientRect().width,
        h: d3.select(this).node().getBoundingClientRect().height,
    };
    tooltipPlace.classed("show", true);
    tooltipPlace.select('.about-sector').html(this.parentElement.getAttribute('sn'));
    tooltipPlace.select('.about-row .number').html(this.getAttribute('pr'));
    tooltipPlace.select('.about-place .number').html(this.getAttribute('pp'));
    tooltipPlace.select('.about-price span').html(this.getAttribute('ps'));
    tooltipPlace.style("left", (position_circle.x - position_wrapper.x + position_circle.w / 2) + "px").style("top", (position_circle.y - position_wrapper.y) + "px");
    if (showPlace) {
        hoverPlace.classed("show", true);
        hoverPlace.html(this.getAttribute('pp'));
        hoverPlace.style("left", (position_circle.x - position_wrapper.x + position_circle.w / 2) + "px")
            .style("top", (position_circle.y - position_wrapper.y + position_circle.h / 2) + "px").style("background", d3.select(this).style('fill'));
    }
})

$(document).on('mouseout', '.pa', function(){
    tooltipPlace.classed("show", false);
    hoverPlace.classed("show", false);
})

$(document).on('mouseover', '.sh-path', function(){
    position_wrapper = {
        x: wrapper.node().getBoundingClientRect().x,
        y: wrapper.node().getBoundingClientRect().y
    };
    position_sector = {
        x: d3.select(this).node().getBoundingClientRect().x,
        y: d3.select(this).node().getBoundingClientRect().y,
        w: d3.select(this).node().getBoundingClientRect().width
    };
    tooltipSector.classed('show', true);
    tooltipSector.select('.name').html(this.parentElement.getAttribute('sn'));
    tooltipSector.select('.free-place .number').html(this.parentElement.getAttribute('fp'));
    tooltipSector.select('.about-price .number').html(this.parentElement.getAttribute('pc'));
    tooltipSector.style("left", (position_sector.x - position_wrapper.x + position_sector.w / 2) + "px").style("top", (position_sector.y - position_wrapper.y) + "px");
})

$(document).on('mouseout', '.sh-path', function(){
    tooltipSector.classed('show', false)
})


$(document).on('click', '.map-scheme:not(.has-active) .pa, .map-scheme.has-active .pa.active', function() {
	SC.addPlace($(this));
});

$(document).on('click', '.map-scheme.hide-sector .actived-place', function() {
	$(this).remove();
	showPlace = true;
	SC.removeTicket($(this));
});
$(document).on('mouseover', '.map-scheme.hide-sector .actived-place', function() {
	var place = $(this).attr('pp'),
		row = $(this).attr('pr'),
		price = $(this).attr('ps'),
		sector = $(this).attr('sn');
	showPlace = false;
	d3.selectAll(`[sn="${sector}"] .pa[pp="${place}"][ps="${price}"][pr="${row}"]`).dispatch("mouseover");
});
$(document).on('mouseout', '.map-scheme.hide-sector .actived-place', function() {
	var place = $(this).attr('pp'),
		row = $(this).attr('pr'),
		price = $(this).attr('ps'),
		sector = $(this).attr('sn');
	showPlace = true;
	d3.selectAll(`[sn="${sector}"] .pa[pp="${place}"][ps="${price}"][pr="${row}"]`).dispatch("mouseout");
});

$(document).on('click', '.map-scheme:not(.hide-sector) .actived-place', function() {
	var sector = $(this).attr('sn');
	$('[sn="' + sector + '"] .sh-path').trigger('click');
});

$(document).on('click', '.panel-controll .clear', function() {
	let message = 'Do you really want<br>to clear the order?',
		buttons = '<div class="btn buy white" data-clear-cart>Clear</div><div class="btn buy" data-close-message>Close window</div>';
	showMessage('', message, buttons);
});

$(document).on('click', '[data-modal-open]', function() {
	var parent = $(this).closest('li');
	let message = 'Do you really want<br>to delete the ticket?',
		buttons = '<div class="btn buy white" data-ticket-id="' + parent.attr('data-ticket_id') + '" data-ticket-delete>Delete</div><div class="btn buy" data-close-message>Close window</div>';
	showMessage('', message, buttons);
})
$(document).on('click', '[data-ticket-delete]', function() {
	SC.removeTicketFormOutput($('.list-tickets li[data-ticket_id="' + $(this).attr('data-ticket-id') + '"]'));
	$('[data-close-message]').trigger('click');
	return false;
})

$(document).on('click', '.your-choose-table table .remove', function() {
	var parent = $(this).closest('.ticket');
	let message = 'Do you really want<br>to delete the ticket?',
		buttons = '<div class="btn buy white" data-ticket-id="' + parent.attr('data-ticket_id') + '" data-ticket-delete>Delete</div><div class="btn buy" data-close-message>Close window</div>';
	showMessage('', message, buttons);
});

$(document).on('click', '.map-prices li', function() {
	$(this).toggleClass('active');
	var minPrice = parseFloat($(this).attr('p-min'));
	var maxPrice = parseFloat($(this).attr('p-max'));
	$('.pa').each(function() {
		thisPrice = parseFloat($(this).attr('ps'));
		if (minPrice <= thisPrice && maxPrice > thisPrice) {
			$(this).toggleClass('active');
		}
	})
	if ($('.map-prices li.active').length == $('.map-prices li').length) {
		$('.map-prices li.active').removeClass('active');
		$('.pa.active').removeClass('active');
		$('.map-scheme').removeClass('has-active');
	} else {
		if ($('.map-prices li.active').length > 0) {
			$('.map-scheme').addClass('has-active');
		} else {
			$('.map-scheme').removeClass('has-active');
		}
	}
});



// Validate
$('.checkboxes input').on('change', function() {
	if ($('#pay-card').is(':checked')) {
		$('.hide-if-card').addClass('hide');
	} else {
		$('.hide-if-card').removeClass('hide');
	}
});
$(document).on('click', '#form-order-button', function(e) {
	e.preventDefault();
	if (SC.countTickets() == 0) {
		let message = 'You have not selected any tickets',
			buttons = '<div class="btn buy white" data-move-back>Return to ticket selection</div> <div class="btn buy" data-close-message>Close window</div>';
		showMessage('', message, buttons);
		return false;
	}
	var phone_pattern = /\+7\s\([0-9]{3}\)\s[0-9]{3}\s[0-9]{2}\s[0-9]{2}/g,
		email_pattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
		validation = true,
		field_name_value = $('#form-order [name="form_order_field_name"]').val(),
		field_phone_value = $('#form-order [name="form_order_field_phone"]').val(),
		field_email_value = $('#form-order [name="form_order_field_email"]').val(),
		field_address_value = $('#form-order [name="form_order_field_address"]').val(),
		field_comment_value = $('#form-order [name="form_order_field_comment"]').val(),
		field_payment_value = $('#form-order [name="form_order_field_payment"]:checked').val(),
		field_token_value = $('#form-order #form_order_field_token').val(),
		event_id = $('#form-order').attr('data-event_id'),
		field_tickets = [];

	$('#form-order-ticket .ticket').each(function() {
		field_tickets.push({
			sector: $(this).attr('sn'),
            sector_id: $(this).attr('sid'),
			row: $(this).attr('pr'),
			place: $(this).attr('pp'),
			price: $(this).attr('ps')
		});
	})

	$('#form-order .field').removeClass('has-error');
	$('#form-order .error-field').html('');

	if (field_name_value.length < 1) {
		$('#form-order [name="form_order_field_name"]').closest('.field').addClass('has-error');
		$('#error_field_name').html('This field is required');
		validation = false;
	} else if (field_name_value.length > 255) {
		$('#form-order [name="form_order_field_name"]').closest('.field').addClass('has-error');
		$('#error_field_name').html('Field value exceeds 255 characters');
		validation = false;
	}

	if (field_phone_value.length < 1) {
		$('#form-order [name="form_order_field_phone"]').closest('.field').addClass('has-error');
		$('#error_field_phone').html('This field is required');
		validation = false;
	} else if (field_phone_value.length > 255) {
		$('#form-order [name="form_order_field_phone"]').closest('.field').addClass('has-error');
		$('#error_field_phone').html('Field value exceeds 255 characters');
		validation = false;
	} else if (!phone_pattern.test(field_phone_value)) {
		$('#form-order [name="form_order_field_phone"]').closest('.field').addClass('has-error');
		$('#error_field_phone').html('Invalid field value');
		validation = false;
	}

	if (field_email_value.length > 255) {
		$('#form-order [name="form_order_field_email"]').closest('.field').addClass('has-error');
		$('#error_field_email').html('Field value exceeds 255 characters');
		validation = false;
	} else if (field_email_value.length > 0 && !email_pattern.test(field_email_value)) {
		$('#form-order [name="form_order_field_email"]').closest('.field').addClass('has-error');
		$('#error_field_email').html('Invalid field value');
		validation = false;
	}

	if (field_payment_value == 2 && field_address_value.length > 255) {
		$('#form-order [name="form_order_field_address"]').closest('.field').addClass('has-error');
		$('#error_field_address').html('Field value exceeds 255 characters');
		validation = false;
	}

	if (field_tickets.length > 0) {
		if (validation) {
			$.ajax({
				type: "POST",
				url: '/order-create',
				data: {
					_token: field_token_value,
					tickets: field_tickets,
					event_id: event_id,
					name: field_name_value,
					phone: field_phone_value,
					email: field_email_value,
					address: field_payment_value == 2 ? field_address_value : '',
					comment: field_comment_value,
					payment: field_payment_value
				},
				success: function(msg) {
					if (!msg.error) {
                        if (msg.redirect) {
                            window.location.replace(msg.redirect);
                        } else {
                            if (msg.form) {
                                $('body').append(msg.form);
                                $('#checkout_form').submit();
                            } else {
                                $('#form-order [name="form_order_field_name"]').val('');
                                $('#form-order [name="form_order_field_phone"]').val('');
                                $('#form-order [name="form_order_field_email"]').val('');
                                $('#form-order [name="form_order_field_address"]').val('');
                                $('#form-order [name="form_order_field_comment"]').val('');
                                $('#form-order [name="form_order_field_payment"][value="2"]').prop('checked', true).trigger('change');
                                SC.clearArray();

                                modal_move_back();
                                $('[data-close-modal]').trigger('click');
                                modal_success();
                            }
                        }

					}
				}
			});
		}
	}
})