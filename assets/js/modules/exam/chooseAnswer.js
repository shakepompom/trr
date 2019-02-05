import $ from 'jquery';

export default class ChooseAnswer {
  constructor(selector) {
    this.$app = $(selector);
    this.$btnNext = this.$app.find('.js-next-task');
    this.$btnCheck = this.$app.find('.js-send-answers').closest('.btn');
    this.$slickSlide = this.$app.find('.js-task-slider');
  }

  checkAnswer(answer) {
    const $task = this.$app.find(answer).closest('.js-task');
    const $answers = $task.find('.js-answer');

    $answers.removeClass('task__answer--checked');
    answer.addClass('task__answer--checked');
  }

  putAnswer(answer) {
    const $task = this.$app.find(answer).closest('.js-task');
    const $empty = $task.find('.js-empty');
    const answerText = answer.text();

    $empty.text(answerText);
  }

  enableBtnNext() {
    this.$btnNext.removeClass('btn--disabled');
  }

  enableBtnCheck() {
    this.$btnCheck.removeClass('btn--disabled');
  }

  makeSlidePointDone() {
    const slidePointActive = this.$slickSlide.find('li.slick-active');

    slidePointActive.addClass('slick-done');
  }

  bindChooseAnswer() {
    this.$app.on('click', '.js-answer', e => {
      const $answer = $(e.currentTarget);

      this.checkAnswer($answer);
      this.putAnswer($answer);
      this.enableBtnNext();
      this.makeSlidePointDone();

      if (this.$btnCheck.is(':visible')) {
        this.enableBtnCheck();
      }
    });
  }

  disableBtnNext() {
    this.$btnNext.addClass('btn--disabled');
  }

  bindDisableBtnNext() {
    this.$btnNext.on('click', () => {
      this.disableBtnNext();
    });
  }

  init() {
    this.bindChooseAnswer();
    this.bindDisableBtnNext();
  }
}
