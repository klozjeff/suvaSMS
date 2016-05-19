jQuery(document).ready(function($) {
"use strict";

  //.parallax(xPosition, speedFactor, outerHeight) options:
  //xPosition - Horizontal position of the element
  //inertia - speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling
  //outerHeight (true/false) - Whether or not jQuery should use it's outerHeight option to determine when a section is in the viewport
  $('#header').parallax("50%", 0.3);
  $('.features').parallax("50%", 0.3);
  $('.footer').parallax("50%", 0.1);

  // FlexSlider Testimonials
  $('.flexslider').flexslider({
    animation: "slide",
    controlNav: false,
    directionNav: true,
    animationLoop: false,
    itemWidth: 210,
    itemMargin: 30,
    minItems: 2,
    maxItems: 7,
    move: 0
  });

  // Show Popup
  $('.open-popup').click(function(event) {
    $('.overlay').show();
    $('html, body').animate({scrollTop: "0px"}, 500);
    event.preventDefault();
  });

  // close Popup
  $('.close').click(function(event) {
    $('.overlay').hide();
    event.preventDefault();
  });

  // Scroll to top link
  $('.back-to-top').click(function(event) {
    $('html, body').animate({scrollTop: "0px"}, 500);
    event.preventDefault();
  });

   // InView
  var $fadeInDown = $('.header .cta h1, .header .cta h4, .header .cta p, .features div.block, .testimonials h2, .testimonials h4, .testimonials .block');
  var $fadeInLeft = $('.profiles h3, .features h2, .source-org p');
  var $fadeInRight = $('.header3 .reg-form');

  $fadeInDown.css('opacity', 0);
  $fadeInLeft.css('opacity', 0);
  $fadeInRight.css('opacity', 0);

  // InView - fadeInDown
  $fadeInDown.one('inview', function(event, visible) {
    if (visible) { $(this).addClass('animated fadeInDown'); }
  });

  // InView - fadeInLeft
  $fadeInLeft.one('inview', function(event, visible) {
    if (visible) { $(this).addClass('animated fadeInLeft'); }
  });

  // InView - fadeInRight
  $fadeInRight.one('inview', function(event, visible) {
    if (visible) { $(this).addClass('animated fadeInRight'); }
  });

});
