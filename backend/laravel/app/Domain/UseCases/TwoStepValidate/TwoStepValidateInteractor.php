<?php

namespace App\Domain\UseCases\TwoStepValidate;

use App\Domain\Interfaces\UserFactory;
use App\Domain\Interfaces\UserRepository;
use App\Domain\Interfaces\ViewModel;
use RobThree\Auth\TwoFactorAuth;

class TwoStepValidateInteractor implements TwoStepValidateInputPort
{
    public function __construct(
        private TwoStepValidateOutputPort $output,
        private UserRepository $db,
        private UserFactory $factory,
        private TwoFactorAuth $tfa,
    ) {
    }

    public function verifyCode(TwoStepValidateRequestModel $data): ViewModel
    {
        $user = $this->db->get($this->factory->make([
            'id' => $data->getUuid()
        ]));

        if (!$user) {
            return $this->output->codeInvalid();
        }

        $ok = $this->tfa->verifyCode($user->getTwoStepSecret(), $data->getCode());

        if ($ok) {
            return $this->output->codeValid();
        } else {
            return $this->output->codeInvalid();
        }
    }
}
