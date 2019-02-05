import $ from 'jquery';

export default class CheckQuiz {
  constructor(selector) {
    this.$app = $(selector);
    this.$btnNext = this.$app.find('.js-next-task');
    this.$btnCheck = this.$app.find('.js-send-answers');
    this.$btnCheckWrapper = this.$btnCheck.closest('.btn');
    this.objectWithAnswers = [];
  }

  toggleButtons() {
    const hasClass = $($('.task__slide')[$('.task__slide').length - 1])
      .closest('.slick-slide')
      .hasClass('slick-active');

    if (!hasClass) return;

    this.$btnNext.hide();
    this.$btnCheckWrapper.show();
    this.$btnCheckWrapper.css('display', 'inline-block');
  }

  bindToggleButtons() {
    this.$btnNext.on('click', () => {
      this.toggleButtons();
    });
  }

  createObjectWithAnswers(answer) {
    const $answer = answer;
    const $task = answer.closest('.js-task');
    const taskId = $task.data('task-id');
    const answerText = $answer.text().trim();

    if (!this.objectWithAnswers.length > 0) {
      this.objectWithAnswers.push({ task: taskId, answer: answerText });
    } else {
      $.each(this.objectWithAnswers, (i, item) => {
        if (item.task === taskId) {
          this.objectWithAnswers.splice(i, 1);
        }
      });
      this.objectWithAnswers.push({ task: taskId, answer: answerText });
    }
  }

  bindCollectObjectWithAnswers() {
    this.$app.on('click', '.js-answer', e => {
      const $answer = $(e.currentTarget);

      this.createObjectWithAnswers($answer);
    });
  }

  sendQuizAnswers() {
    const url = this.$btnCheck.data('url');
    const hrefRedirect = this.$btnCheck.attr('href');

    $.ajax({
      url,
      type: 'POST',
      data: { answers: JSON.stringify(this.objectWithAnswers) },
      success: () => {
        window.location.href = hrefRedirect;
      },
    });
  }

  bindSendQuizAnswers() {
    this.$app.on('click', '.js-send-answers', e => {
      e.preventDefault();
      this.sendQuizAnswers();
    });
  }

  init() {
    this.bindToggleButtons();
    this.bindCollectObjectWithAnswers();
    this.bindSendQuizAnswers();
  }
}
