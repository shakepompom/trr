import $ from 'jquery';

export default class FunFact {
  constructor(selector) {
    this.$app = $(selector);
    this.$btnClose = this.$app.find('.close');
  }

  closeFunFact() {
    if (!this.$app.hasClass('funfact')) return;

    this.$app.on('click', e => {
      const $content = this.$app.find('.content');

      if ($content.is(e.target) || $content.has(e.target).length !== 0) return;

      const href = this.$btnClose.attr('href');

      window.location.href = href;
    });
  }

  init() {
    this.closeFunFact();
  }
}
