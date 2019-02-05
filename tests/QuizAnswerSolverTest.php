<?php

namespace App\Tests;

use App\Entity\Quiz;
use App\Entity\QuizResult;
use App\Entity\QuizUserAnswer;
use App\Entity\Task;
use App\Quizes\QuizAnswerSolver;
use PHPUnit\Framework\TestCase;

class QuizAnswerSolverTest extends TestCase
{
    public function testCalculateResult()
    {
        $solver = new QuizAnswerSolver();
        $quiz = new Quiz();
        $quiz->addTask($this->createTaskMock(1, 'a'));
        $quiz->addTask($this->createTaskMock(2, 'b'));
        $quiz->addTask($this->createTaskMock(3, 'c'));

        $answers = [];
        $answers[] = $this->createAnswerMock(1, 'a');
        $answers[] = $this->createAnswerMock(3, 'c');
        $answers[] = $this->createAnswerMock(2, 'b');

        $result = $solver->calculateResult($quiz, $answers);

        $this->assertEquals(3, $result->getCorrect());
        $this->assertEquals(3, $result->getTotal());
    }

    public function testResultWithErrors()
    {
        $solver = new QuizAnswerSolver();
        $quiz = new Quiz();
        $quiz->addTask($this->createTaskMock(1, 'b'));
        $quiz->addTask($this->createTaskMock(2, 'b'));
        $quiz->addTask($this->createTaskMock(5, 'c'));

        $answers = [];
        $answers[] = $this->createAnswerMock(1, 'a');
        $answers[] = $this->createAnswerMock(3, 'c');
        $answers[] = $this->createAnswerMock(2, 'b');

        $result = $solver->calculateResult($quiz, $answers);

        $this->assertEquals(1, $result->getCorrect());
        $this->assertEquals(3, $result->getTotal());
    }

    private function createTaskMock(int $id, string $answer): Task
    {
        $taskMock = $this->getMockBuilder(Task::class)->getMock();
        $taskMock->method('getId')->willReturn($id);
        $taskMock->method('getAnswer')->willReturn($answer);

        return $taskMock;
    }

    private function createAnswerMock(int $taskId, string $answer): QuizUserAnswer
    {
        $taskMock = $this->getMockBuilder(Task::class)->getMock();
        $taskMock->method('getId')->willReturn($taskId);

        $userAnswer = new QuizUserAnswer();
        $userAnswer->setTask($taskMock);
        $userAnswer->setAnswer($answer);

        return $userAnswer;
    }
}
