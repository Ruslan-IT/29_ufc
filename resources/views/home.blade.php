@extends('layouts.app')
@section('content')
    <div class="menu-fixed fixed{{ ($message['status'] != 0) ? ' single-page' : '' }}">
        <div class="wrapper">
            <div class="menu-button"></div>
            <img class="logo-menu" src="images/logo.png" alt="UFC Fight Night Moscow" title="UFC Fight Night Moscow">
            <nav id="menu-fixed">
                <ul>
                    <li data-menuanchor="home"><a href="#home">Home</a></li>
                    <li data-menuanchor="video"><a href="#video">Video</a></li>
                    <li data-menuanchor="map"><a href="#map">Venue</a></li>
                    <li data-menuanchor="vip"><a href="#vip">VIP Tickets</a></li>
                    <li data-menuanchor="buy"><a href="#buy">Stay Updated</a></li>
                </ul>
            </nav>
            <div class="right">
                <a class="phone" href="tel:+74951505802">+7 (495) 150-58-02</a>
                @if($message['status'] == 0)
                    <a class="btn btn-style buy" href="#" data-open-modal="1">Buy Ticket</a>
                @endif
            </div>
        </div>
    </div>

    <div class="menu-mobile">
        <div class="menu-mobile-header">
            <div class="menu-close">
                <span>&nbsp;</span>
                <div class="line">&nbsp;</div>
            </div>
            <img class="logo-menu" src="images/logo.png" alt="UFC Fight Night Moscow" title="UFC Fight Night Moscow">
        </div>
        <ul class="menu-items">
            <li data-menuanchor="home"><a href="#home">Home</a></li>
            <li data-menuanchor="video"><a href="#video">Video</a></li>
            <li data-menuanchor="map"><a href="#map">Venue</a></li>
            <li data-menuanchor="vip"><a href="#vip">VIP Tickets</a></li>
            <li data-menuanchor="buy"><a href="#buy">Stay Updated</a></li>
        </ul>
        <a class="menu-mobile-buy" href="#" data-open-modal="1">Buy Ticket</a>
    </div>

    @if($message['status'] != 0)
        <div class="page single-page">
            <div class="message">
                @if($message['status'] == 1)
                    <h3>Your order has been placed!</h3>
                    <p><strong>{{$message['name']}}, thank you for your purchase!</strong></p>
                    <p>Your order # {{$message['order']}}</p>
                    <p>A manager will contact you shortly to clarify the details. For any questions regarding your order, please contact:</p>
                    <p><strong>+7 (495) 150-58-02</strong></p>
                @endif
                @if($message['status'] == 2)
                    <h3>Your order was not placed!</h3>
                    <p><strong>{{$message['name']}}, please try again!</strong></p>
                    <p>For any questions regarding your order, please contact:</p>
                    <p><strong>+7 (495) 150-58-02</strong></p>
                @endif
                <a class="btn" href="{{ url('/') }}">Home</a>
            </div>
        </div>
    @else

        <div class="fullpage-wrap">
            <div id="fullpage">
                <section class="section home" data-anchor="home">
                    <img class="men" src="{{ url('/images/men.png') }}" alt="UFC Fight Night Moscow" title="UFC Fight Night Moscow">
                    <div class="wrapper">
                        <div class="inner">
                            <div class="caption">
                                <h1>For the first time <span class="type-1">in Moscow</span> tournament <div class="type-2"><span>UFC</span><span class="line"></span></div></h1>
                                <h3>Olympic Sports Complex</h3>
                                <div class="info">
                                    <div class="date">September 15, 2018 at 17:30</div>
                                    <a href="#" class="btn btn-style buy" data-open-modal="1">Buy Tickets</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scroll-down">
                        <a class="scroll-link" href="#video-sect">
                            <div class="mouse">
                                <span class="mouse__wheel"></span>
                            </div>
                        </a>
                    </div>
                </section>
                <section class="section media" data-anchor="video">
                    <div class="inner">
                        <div class="row">
                            <div class="block-left">
                                <a class="block" data-fancybox href="https://www.youtube.com/watch?v=46M01aonbv8">
                                    <img src="{{ url('/images/icon-play.png') }}" alt="Play video" title="Play video">
                                    <div class="text"><div class="auther">The Ultimate Fighter 27 Finale</div></div>
                                </a>
                            </div>
                            <div class="block-right">
                                <a class="block first" data-fancybox href="https://www.youtube.com/watch?v=S4kw-g6ORAE">
                                    <img src="{{ url('/images/icon-play.png') }}" alt="Play video" title="Play video">
                                    <div class="text"><div class="auther">Miocic vs. Cormier</div></div>
                                </a>
                                <a class="block second" data-fancybox href="https://www.youtube.com/watch?v=BwV5v2aJqaY">
                                    <img src="{{ url('/images/icon-play.png') }}" alt="Play video" title="Play video">
                                    <div class="text"><div class="auther">Dos Santos vs. Ivanov</div></div>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="section place" data-anchor="map">
                    <div class="inner">
                        <div class="column">
                            <div class="top">
                                <div class="when">September 15, 2018 at 17:30</div>
                                <div class="where"><span>Olympic Sports Complex</span></div>
                                <div class="addres">Russia, Moscow,<br>Olympic Avenue, 16, bld. 1,2.</div>
                            </div>
                            <div class="bottom">
                                <div id="contact-map"></div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="section info" data-anchor="vip">
                    <div class="inner">
                        <div class="wrapper">
                            <div class="block-buy">
                                <div class="vip-caption">&nbsp;</div>
                                <div class="vip-items">
                                    <div class="item">&nbsp;</div>
                                    <div class="item">&nbsp;</div>
                                    <div class="item">&nbsp;</div>
                                    <div class="item">&nbsp;</div>
                                    <div class="item">&nbsp;</div>
                                </div>
                                <a href="#" class="btn btn-style buy" data-open-modal="1">Buy VIP Tickets</a>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="section home home-last" data-anchor="buy">
                    <img class="men" src="{{ url('/images/men-3.png') }}" alt="UFC Fight Night Moscow" title="UFC Fight Night Moscow">
                    <div class="wrapper">
                        <div class="inner">
                            <div class="caption">
                                <h1>For the first time <span class="type-1">in Moscow</span> tournament <div class="type-2"><span>UFC</span><span class="line"></span></div></h1>
                                <h3>Olympic Sports Complex</h3>
                                <div class="info">
                                    <div class="date">September 15, 2018 at 17:30</div>
                                    <a href="#" class="btn btn-style buy" data-open-modal="1">Buy Tickets</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>


        <div class="modal-tickets" data-modal="1">
            <div class="inner">
                <div class="wrap-content">
                    <img src="{{ url('/images/icon-close.png') }}" alt="Close" title="Close" class="close">
                    <div class="content maket show" data-content="1">
                        <h3>UFC Fight Night Moscow</h3>
                        <p class="where"><span class="event-date"></span> | Moscow, Olympic Sports Complex</p>
                        <div class="map-block"></div>
                        <div class="panel-controll">
                            <div class="row">
                                <div class="clear">
                                    <span><img src="images/trash-grey.svg" alt=""></span> clear order
                                </div>
                                <div class="your-choose">
                                    <span>You selected: </span>
                                    <span class="choosed">
                                        <span class="button-open-popup">
                                            <span class="number">0</span> tickets <img src="images/arrow-choose.png" alt="">
                                        </span>
                                    <div class="wrap-popup">
                                        <div class="tickets popup">
                                            <h3>your order</h3>
                                            <ul class="list-tickets nice-scroll-right">
                                            </ul>
                                        </div>
                                    </div>
                                    </span>
                                </div>
                                <div class="total">
                                    <span>Total (<strong>₽</strong>): </span>
                                    <span class="price">0</span>
                                </div>
                                <div class="btn buy mobile">
                                    Buy
                                    <span class="number billets"></span>
                                    tickets for
                                    <span class="number price"></span> ₽</div>
                                <div class="btn buy">place order</div>
                            </div>
                        </div>
                    </div>
                    <div id="form-order" class="content order nice-scroll-right">
                        <div class="back" data-back><img src="images/arrow-left.png" alt=""> Back</div>
                        <div class="stiker-top"></div>
                        <div class="top">
                            <div class="stiker">
                                <p class="top">UFC Fight Night<br>Moscow</p>
                                <p>September 15, 17:30<br> Moscow, Olympic Sports Complex</p>
                                <div class="btn buy" data-back>add ticket</div>
                            </div>
                            <div class="your-choose-table">
                                <div class="row name-step">
                                    <div class="number">1</div>
                                    <div class="name">Your order:</div>
                                </div>
                                <div class="wrapper-top-table">
                                    <table>
                                        <thead>
                                        <tr>
                                            <td>№</td>
                                            <td>Sector</td>
                                            <td>Row</td>
                                            <td>Seat</td>
                                            <td>Price (<strong>₽</strong>)</td>
                                            <td><a href=""><img src="images/icon-remove.png" alt=""></a></td>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="nice-scroll">
                                    <div id="wrapper">
                                        <table id="form-order-ticket">
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="total-table right">
                                    <span>Total:</span>
                                    <span class="price"><span class="number">0</span> ₽</span>
                                </div>
                            </div>
                        </div>
                        <div class="bottom">
                            <div class="left">
                                <div class="row name-step">
                                    <div class="number">2</div>
                                    <div class="name">Contact information:</div>
                                </div>
                                <div class="grid form">
                                    <div class="field block-12">
                                        <input name="form_order_field_name" type="text" placeholder="Name" required="required">
                                        <div class="error-field" id="error_field_name"></div>
                                    </div>
                                    <div class="field block-6">
                                        <input name="form_order_field_phone" type="text" data-inputmask="'mask': '+7 (999) 999 99 99', 'placeholder': '+7 (---) --- -- --'" required="required">
                                        <div class="error-field" id="error_field_phone"></div>
                                    </div>
                                    <div class="field block-6">
                                        <input name="form_order_field_email" type="email" placeholder="E-mail">
                                        <div class="error-field" id="error_field_email"></div>
                                    </div>
                                    <div class="field block-12 hide-if-card">
                                        <input name="form_order_field_address" type="text" placeholder="Delivery address">
                                        <div class="error-field" id="error_field_address"></div>
                                    </div>
                                    <div class="field block-12">
                                        <textarea name="form_order_field_comment" placeholder="Comment"></textarea>
                                        <div class="error-field" id="error_field_comment"></div>
                                    </div>
                                    <div class="block-12">
                                        <p><span>*</span> Required fields</p>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="row name-step">
                                    <div class="number">3</div>
                                    <div class="name">Payment methods:</div>
                                </div>
                                <div class="checkboxes">
                                    <label>
                                        <input type="radio" name="form_order_field_payment" value="2" checked>
                                        <span class="name">Cash to courier</span>
                                        <span>The courier will arrive at a convenient time for you, having previously notified you by phone. Cash payment only.</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="form_order_field_payment" value="1" id="pay-card">
                                        <span class="name">VISA / MASTERCARD card</span>
                                        <span>After payment, tickets will be sent to your email</span>
                                        <img src="/images/payment-final-2.png">
                                    </label>
                                    <div class="error-field" id="error_field_payment"></div>
                                    <input id="form_order_field_token" type="hidden" value="{{ csrf_token() }}">
                                    <div class="btn buy" id="form-order-button">buy ticket</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-block" data-info-block="how-give">
                        <div class="header">
                            <h2>How to order</h2>
                            <img src="images/icon-close-modal.svg" alt="" class="close_info">
                        </div>
                        <div class="info-content">
                            <div class="wrap">
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_1">Ticket ordering rules</h3>
                                    <p>By placing an order, the Buyer enters into an agreement with LLC "TIVO" for the provision of services related to the purchase of tickets under the conditions set forth in these Rules. The agreement is considered concluded from the moment the Buyer completes the ordering procedure and its confirmation by LLC "TIVO" until the moment the Buyer (or his authorized person) receives the tickets or the order is canceled.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_2">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_3">Order validity period:</h3>
                                    <p>1. When paying by bank card, electronic cash, etc. - up to 24 hours depending on the date of the event.</p>
                                    <p>2. Acceptance of ticket orders via the Internet stops one hour after the start time of the event specified in the concert information.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_4">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_5">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <div class="btn-back"><img src="/images/arrow-left.png" alt="">Back</div>
                        </div>
                    </div>
                    <div class="info-block" data-info-block="sale">
                        <div class="header">
                            <h2>Payment</h2>
                            <img src="images/icon-close-modal.svg" alt="" class="close_info">
                        </div>
                        <div class="info-content">
                            <div class="wrap">
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_1">Ticket ordering rules</h3>
                                    <p>By placing an order, the Buyer enters into an agreement with LLC "TIVO" for the provision of services related to the purchase of tickets under the conditions set forth in these Rules. The agreement is considered concluded from the moment the Buyer completes the ordering procedure and its confirmation by LLC "TIVO" until the moment the Buyer (or his authorized person) receives the tickets or the order is canceled.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_2">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_3">Order validity period:</h3>
                                    <p>1. When paying by bank card, electronic cash, etc. - up to 24 hours depending on the date of the event.</p>
                                    <p>2. Acceptance of ticket orders via the Internet stops one hour after the start time of the event specified in the concert information.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_4">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_5">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <div class="btn-back"><img src="/images/arrow-left.png" alt="Back" title="Back">Back</div>
                        </div>
                    </div>
                    <div class="info-block" data-info-block="contact">
                        <div class="header">
                            <h2>Contacts</h2>
                            <img src="/images/icon-close-modal.svg" alt="" class="close_info">
                        </div>
                        <div class="info-content">
                            <div class="wrap">
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_1">Ticket ordering rules</h3>
                                    <p>By placing an order, the Buyer enters into an agreement with LLC "TIVO" for the provision of services related to the purchase of tickets under the conditions set forth in these Rules. The agreement is considered concluded from the moment the Buyer completes the ordering procedure and its confirmation by LLC "TIVO" until the moment the Buyer (or his authorized person) receives the tickets or the order is canceled.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_2">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_3">Order validity period:</h3>
                                    <p>1. When paying by bank card, electronic cash, etc. - up to 24 hours depending on the date of the event.</p>
                                    <p>2. Acceptance of ticket orders via the Internet stops one hour after the start time of the event specified in the concert information.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_4">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                                <div class="how_to_block">
                                    <h3 class="big-title" id="how_to_5">Ways to order tickets</h3>
                                    <p>The Buyer can order tickets by placing an order online on <a class="link_blue" href="https://widget.tiwo.ru" target="_blank">widget.tiwo.ru</a>. For orders placed online, the number of tickets in one order cannot exceed 10 pieces. LLC "TIVO" has the right to introduce additional restrictions on the number of tickets sold to one Buyer depending on the demand for the event and the Buyer's order history. Orders cannot be edited.</p>
                                </div>
                            </div>
                        </div>
                        <div class="footer">
                            <div class="btn-back"><img src="/images/arrow-left.png" alt="">Back</div>
                        </div>
                    </div>
                </div>
                <div class="footer-menu">
                    <ul class="left">
                        <li><a href="#" data-show-block="how-give">How to order?</a></li>
                        <li><a href="#" data-show-block="sale">Payment</a></li>
                        <li><a href="#" data-show-block="contact">Contacts</a></li>
                    </ul>
                    <div class="right phone">
                        <a href="tel:+74951505802">+7 (495) 150-58-02</a>
                    </div>
                </div>
                <div class="message">
                    <div class="text" data-message="2">
                        <p>Do you really want<br>to finish purchasing tickets?</p>
                        <div class="btn buy white" data-close-modal>Exit</div>
                        <div class="btn buy" data-close-message>Continue purchase</div>
                    </div>
                    <div class="text" data-message="3">
                        <p>Do you really want<br>to clear the order?</p>
                        <div class="btn buy white" data-clear-cart>Clear</div>
                        <div class="btn buy" data-close-message>Close window</div>
                    </div>
                    <div class="text" data-message="ticket-delete">
                        <p>Do you really want<br>to delete the ticket?</p>
                        <div class="btn buy white" data-ticket-delete>Delete</div>
                        <div class="btn buy" data-close-message>Close window</div>
                    </div>
                    <div class="overlay"></div>
                </div>
            </div>
            <div class="overlay"></div>
        </div>

    @endif
    <div class="button-up"></div>
@endsection