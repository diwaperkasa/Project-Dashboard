<?php

namespace App\Exceptions\Custom;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\MessageBag;

class ErrorValidator extends HttpException
{
    private $errorValidation;

    /**
     * @param string|null     $message  The internal exception message
     * @param \Throwable|null $previous The previous exception
     * @param int             $code     The internal exception code
     */
    public function __construct(?string $message = '', MessageBag $errorValidation, int $code = 422, array $headers = [])
    {
        $this->errorValidation = $errorValidation;

        parent::__construct(422, $message, null, $headers, $code);
    }

    public function getErrorValidation()
    {
        return $this->errorValidation;
    }
}
