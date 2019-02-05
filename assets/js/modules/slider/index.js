import 'slick-carousel';
import $ from 'jquery';

export default class Slider {
  constructor(selector) {
    this.$slider = $(selector);
  }

  initSlider() {
    this.$slider.slick({
      vertical: true,
      adaptiveHeight: true,
      arrows: true,
      dots: true,
      infinite: false,
      nextArrow: $('.js-next-task'),
      swipe: false,
      touchMove: false,
    });
  }

  sameHeight() {
    const $slickSlides = this.$slider.find('.slick-slide');
    let maxHeight = -1;

    $slickSlides.each(function() {
      if ($(this).height() > maxHeight) {
        maxHeight = $(this).height();
      }
    });
    $slickSlides.each(function() {
      if ($(this).height() < maxHeight) {
        $(this).css(
          'margin',
          `${Math.ceil((maxHeight - $(this).height()) / 2)}px 0`
        );
      }
    });
  }

  init() {
    this.initSlider();
    this.sameHeight();
  }
}
