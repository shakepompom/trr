import $ from 'jquery';

export default class ProgressBar {
  constructor(selector) {
    this.progressBar = $(selector);
  }

  drawProgressBar() {
    const $currentProgress = this.progressBar.find('.js-progress-line');
    const $currentLevel = this.progressBar.find('.js-learnt-number');
    const learnt = this.progressBar.data('learnt');
    const total = this.progressBar.data('total');
    const totalWidth = $('.js-progress-bar').innerWidth();
    const learntWidth = learnt * totalWidth / total;

    $currentProgress.css('width', `${learntWidth}px`);
    $currentLevel.css('left', `${learntWidth}px`);
  }

  init() {
    this.drawProgressBar();
  }
}
