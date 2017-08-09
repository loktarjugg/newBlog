<?php
namespace App\Traits;

use App\Exceptions\ErrorException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use \Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;

/**
 * Class RestExceptionHandlerTrait
 * @package App\Exceptions
 */
trait RestExceptionHandlerTrait
{
    /**
     * @param Request $request
     * @param Exception $exception
     */
    protected function getJsonResponseForException(Request $request, Exception $exception)
    {

        switch (true) {
            case $this->isAuthorizationException($exception) : //判断是授权异常
                $response = $this->authorizationException();
                break;
            case $this->isModelNotFoundException($exception): //判断模型未找到异常
                $response = $this->modelNotFoundException();
                break;
            case $this->isValidationException($exception): //判断验证异常
                $response = $this->validationException($exception);
                break;
            case $this->isHttpException($exception); //判断http异常
                $response = $this->httpException();
                break;
            case $this->isNotFoundException($exception): //判断404异常
                $response = $this->notFoundException();
                break;
            case $this->isErrorException($exception) :
                $response = $this->errorException($exception->getMessage());
                break;
            case $this->isAuthenticationException($exception) : //判断登录过期
            case $this->isOAuthServerException($exception):
                $response = $this->authenticationException();
                break;
            default:
                $response = $this->badRequest($exception->getMessage()); //默认处理
        }
        return $response;
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function badRequest($message, $statusCode = 400)
    {
        return $this->jsonResponse($message , $statusCode);
    }

    protected function authenticationException($message = '您的账号在别处登录或已失效,请重新登录' , $statusCode = 401)
    {
        return $this->jsonResponse($message ,$statusCode);
    }

    /**
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function httpException($message = '错误的路由' , $status = 400)
    {
        return $this->jsonResponse($message , $status);
    }

    /**
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function notFoundException($message = '没有找到' , $status = 404)
    {
        return $this->jsonResponse($message , $status);
    }

    /**
     * 抛出模型未找到异常
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function modelNotFoundException($message = '没有找到该记录' , $statusCode = 404){

        return $this->jsonResponse($message , $statusCode);
    }

    protected function wxPayException($message)
    {
        return $this->jsonResponse($message);
    }

    /**
     * 抛出授权异常
     * @param Exception $exception
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function authorizationException($message ='没有权限操作', $statusCode = 401)
    {
        return $this->jsonResponse($message , $statusCode);
    }

    protected function errorException($message = '操作失败' , $statusCode = 400)
    {
        return $this->jsonResponse($message , $statusCode);
    }

    /**
     * 抛出表单验证异常
     * @param $exception
     * @return JsonResponse
     */
    protected function validationException($exception , $statusCode = 422)
    {
        $errors = $exception->validator->errors()->getMessages();
        
        return response()->json([
            'error' => '验证没有通过',
            'data' => $errors
        ], $statusCode);

    }

    /**
     * 判断用户授权异常
     * @param Exception $exception
     * @return bool
     */
    protected function isAuthorizationException(Exception $exception)
    {
        return $exception instanceof AuthorizationException;
    }

    protected function isErrorException(Exception $exception)
    {
        return $exception instanceof ErrorException;
    }

    /**
     * 判断模型未找到异常
     * @param Exception $exception
     * @return bool
     */
    protected function isModelNotFoundException(Exception $exception)
    {
        return $exception instanceof ModelNotFoundException;
    }

    /**
     * 判断表单验证异常
     * @param Exception $exception
     * @return bool
     */
    protected function isValidationException(Exception $exception)
    {
        return $exception instanceof ValidationException;
    }

    /**
     * @param Exception $exception
     * @return bool
     */
    protected function isOAuthServerException(Exception $exception)
    {
        return $exception instanceof OAuthServerException;
    }

    /**
     * @param Exception $exception
     * @return bool
     */
    protected function isHttpException(Exception $exception)
    {
        return $exception instanceof HttpException;
    }

    /**
     * @param Exception $exception
     * @return bool
     */
    protected function isNotFoundException(Exception $exception)
    {
        return $exception instanceof NotFoundHttpException;
    }

    protected function isAuthenticationException(Exception $exception)
    {
        return $exception instanceof AuthenticationException;
    }

    protected function isWxPayException(Exception $exception)
    {
        return $exception instanceof Exception;
    }

    /**
     * 创建一个 Json 响应
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function jsonResponse($message = '未知的错误' , $statusCode = 400)
    {
        return new JsonResponse([
            'error' => $message
        ] , $statusCode);


    }
}