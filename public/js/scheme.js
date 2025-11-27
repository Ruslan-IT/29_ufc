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
			sector: sector
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
		box.find('.wrap-popup .list-tickets').html('');

		table.html('');

		for (let i = 0; i < tikets.length; i++) {
			ticket = tikets[i];
			var template = getTempleateForBox(ticket.place, ticket.row, ticket.price, ticket.sector);
			box.find('.wrap-popup .list-tickets').append(template);

			template = getTempleateForTable(ticket.place, ticket.row, ticket.price, ticket.sector);
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
	}

	function getTempleateForTable(place, row, price, sector) {
		return `<tr class="ticket" pr="${row}" pp="${place}" ps="${price}" sn="${sector}"><td class="number"></td><td class="ticket-sector">${sector}</td><td class="ticket-row">${row}</td><td class="ticket-place">${place}</td><td class="ticket-price">${price}</td><td><span class="remove"><img src="../images/trash-table.svg" alt=""></span></td></tr>`;
	}

	function getTempleateForBox(place, row, price, sector) {
		return `<li pr="${row}" pp="${place}" ps="${price}" sn="${sector}" data-ticket_id="${sector}_${row}_${place}">${sector}, Ряд ${row}, Место ${place} <span class="right"><strong>${price} ₽</strong><span class="delete" data-modal-open="ticket-delete"><img src="../images/trash.svg" alt=""></span></span></li>`;
	}

	function appendCirlceToActivedPlaces(container, cx, cy, r, fill, place, row, price, sector) {
		rZommed = r / d3.zoomTransform(svg.node()).k * sizeActiveCircle;

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
var svg = d3.select("#svg-map"),
	svg_width = svg.node().getBoundingClientRect().width,
	svg_height = svg.node().getBoundingClientRect().height,

	container = d3.select("#svg-map-container"),
	container_width = container.node().getBoundingClientRect().width,
	container_height = container.node().getBoundingClientRect().height,

	scale_coof = 0.5,
	init_scale = d3.min([(svg_width - 80) / container_width, svg_height / container_height]),
	init_x = (svg_width - 80) / 2 - container_width / 2 * init_scale,
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


svg.call(zoom).on("wheel.zoom", null).on("dblclick.zoom", null);;
wrapper = d3.select('.map-scheme');

svg.call(zoom.transform, d3.zoomIdentity.translate(init_x, init_y).scale(init_scale)).call(removeSpiner);

function zoomed() {
	container.attr("transform", d3.event.transform);
	d3.selectAll('.actived-place').each(function(d, i) {
		r = d3.select(this).attr('init-r') / d3.zoomTransform(svg.node()).k * sizeActiveCircle;

		var max = maxSizeActiveCircle / d3.zoomTransform(svg.node()).k * sizeActiveCircle;

		if (r > max) r = max;

		d3.select(this).attr('r', r).attr('stroke-width', r / borderCoef);
	});
	$('feDropShadow').attr('stdDeviation', 3 / d3.zoomTransform(svg.node()).k);
	$('.sh-path').attr('stroke-width', 3 / d3.zoomTransform(svg.node()).k);
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
	}, 100)

}

to_bounding_box = function(W, H, center, w, h) {
	var k, kh, kw, x, y;
	k = init_scale + 1.6 * scale_coof;
	x = W / 2 - center.x * k;
	y = H / 2 - center.y * k;
	return d3.zoomIdentity.translate(x, y).scale(k);
};

var SC = new ShopingCart();