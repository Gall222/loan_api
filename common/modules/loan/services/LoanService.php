<?php

namespace common\modules\loan\services;

use backend\responses\ProcessorResponse;
use backend\responses\LoanCreateResponse;
use common\modules\loan\jobs\DummyJob;
use common\modules\loan\jobs\ProcessUserLoansJob;
use common\modules\loan\mappers\LoanMapperInterface;
use common\modules\loan\models\Loan;
use common\modules\loan\repositories\LoanRepositoryInterface;
use common\modules\user\repositories\UserRepositoryInterface;
use Throwable;
use Yii;
use yii\db\Exception;

/**
 * {@inheritdoc}
 */
class LoanService implements LoanServiceInterface
{
    private LoanRepositoryInterface $loanRepository;
    private UserRepositoryInterface $userRepository;
    private LoanMapperInterface $loanMapper;

    private const APPROVE_CHANCE = 10;

    public function __construct(
        LoanRepositoryInterface $loanRepository,
        UserRepositoryInterface $userRepository,
        LoanMapperInterface $loanMapper
    ) {
        $this->loanRepository = $loanRepository;
        $this->userRepository = $userRepository;
        $this->loanMapper = $loanMapper;
    }

    /**
     * @throws Exception
     */
    public function create(array $request): LoanCreateResponse
    {
        $requestDto = $this->loanMapper->getCorrectRequest($request);

        if ($this->loanRepository->hasApprovedLoan($requestDto->getUserId())) {
            return new LoanCreateResponse(false);
        }

        $newLoan = new Loan();
        $newLoan->amount = $requestDto->getAmount();
        $newLoan->term = $requestDto->getTerm();
        $newLoan->user_id = $requestDto->getUserId();

        try {
            $this->loanRepository->save($newLoan);
        } catch (Throwable $exception){
            return new LoanCreateResponse(false);
        }

        Yii::$app->response->statusCode = 201;

        return new LoanCreateResponse(true, $newLoan->id);
    }

    /**
     * {@inheritdoc}
     */
    public function addChangeStatusesJob(int $delay): ProcessorResponse
    {
        $users = $this->userRepository->getAll();

        if ($this->canUseQueue()) {
            foreach ($users as $user) {
                Yii::$app->queue->push(
                    new ProcessUserLoansJob(['userId' => $user->id, 'delay' => $delay])
                );
            }
        } else {
            foreach ($users as $user) {
                $this->changeStatusesProcess($user->id, $delay);
            }
        }

        return new ProcessorResponse(true);
    }

    /**
     * {@inheritdoc}
     */
    public function changeStatusesProcess(int $userId, int $delay): void
    {
        $this->setStatuses($userId, $delay);
    }

    /**
     *  Устанавливаем статусы заявкам клиента
     *  Не может быть более одной одобренной
     *
     * @param int $userId
     * @param int $delay Имитация задержки между командами
     * @throws Exception
     * @throws \Throwable
     */
    private function setStatuses(int $userId, int $delay): void
    {
        $loans = $this->loanRepository->getAllByUserId($userId);

        $hasApproved = false;
        foreach ($loans as $loan) {

            if ($hasApproved) {
                $loan->status = Loan::STATUS_DECLINED;
            } else {
                $status = $this->getStatus();
                $loan->status = $status;

                if ($status === Loan::STATUS_APPROVED) {
                    try {
                        $this->loanRepository->save($loan);
                        $hasApproved = true;
                    } catch (\yii\db\IntegrityException $e) {
                        // Индекс не позволил создать второй approved
                        $loan->status = Loan::STATUS_DECLINED;
                        $this->loanRepository->save($loan);
                    }
                } else {
                    $this->loanRepository->save($loan);
                }
            }

            sleep($delay);
        }
    }

    /**
     * Возвращает статус одобрен или отклонен
     * Вероятность одобрения - 10%
     */
    private function getStatus(): string
    {
        $isApproved = (rand(1, 100)) <= self::APPROVE_CHANCE;

        return $isApproved ? Loan::STATUS_APPROVED : Loan::STATUS_DECLINED;
    }

    /**
     * Проверка, работает ли очередь
     */
    private function canUseQueue(): bool
    {
        if (!Yii::$app->has('redis')) {
            return false;
        }

        Yii::$app->queue->push(new DummyJob());

        try {
            $lastRun = Yii::$app->redis->get(DummyJob::DUMMY_JOB_KEY);
            return (bool)$lastRun;
        } catch (\Throwable $e) {
            return false;
        }
    }
}