<?php
/**
 * Copyright © Scherbak Electronics All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Shch\Mono\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;
use Shch\Mono\Gateway\Http\Client\Mono;

class ResponseCodeValidator extends AbstractValidator
{
    const RESULT_CODE = 'RESULT_CODE';

    /**
     * Performs validation of result code
     *
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject): ResultInterface
    {
        if (!isset($validationSubject['response']) || !is_array($validationSubject['response'])) {
            throw new \InvalidArgumentException('Response does not exist');
        }

        $response = $validationSubject['response'];

        if ($this->isSuccessfulTransaction($response)) {
            return $this->createResult(
                true,
                []
            );
        } else {
            return $this->createResult(
                false,
                [__('Gateway rejected the transaction.')]
            );
        }
    }

    /**
     * @param array $response
     * @return bool
     */
    private function isSuccessfulTransaction(array $response): bool
    {
        return isset($response[self::RESULT_CODE])
        && $response[self::RESULT_CODE] !== Mono::FAILURE;
    }
}
