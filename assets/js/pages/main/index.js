import 'bootstrap';
import '../../../scss/pages/main/index.scss';
import '../../modules/svg';
import Slider from '../../modules/slider';
import ChooseAnswer from '../../modules/exam/chooseAnswer';
import CheckQuiz from '../../modules/exam/checkQuiz';
import FunFact from '../../modules/funfact/funfact';
import ProgressBar from '../../modules/progressBar/progressBar';

const slider = new Slider('.js-task-slider');
const chooseAnswer = new ChooseAnswer('.wrapper');
const checkQuiz = new CheckQuiz('.wrapper');
const funFact = new FunFact('body');
const progressBar = new ProgressBar('.js-progress');

slider.init();
chooseAnswer.init();
checkQuiz.init();
funFact.init();
progressBar.init();
