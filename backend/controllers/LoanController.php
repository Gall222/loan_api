<?php

namespace backend\controllers;

use backend\responses\ProcessorResponse;
use common\modules\loan\services\LoanServiceInterface;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\Request;

/**
 * Контроллер для работы с заявками
 */
class LoanController extends Controller
{
    public $enableCsrfValidation = false;
    private LoanServiceInterface $loanService;

    public function __construct(
        $id,
        $module,
        $config,
        LoanServiceInterface $loanService
    ) {
        $this->loanService = $loanService;

        parent::__construct($id, $module, $config);
    }

    /**
     * Создать новую заявку
     * @throws InvalidConfigException
     */
    public function actionRequests(Request $request)
    {
        return $this->loanService->create($request->getBodyParams());
    }

    /**
     * Изменить статусы заявок
     * @param int $delay Задержка между созданием заявок
     */
    public function actionProcessor(int $delay = 0): ProcessorResponse
    {
        return $this->loanService->addChangeStatusesJob($delay);
    }
}