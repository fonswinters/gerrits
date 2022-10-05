<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return Exception
     * @throws Exception
     */
    public function report(\Throwable $e)
    {
        //Send error to sentry if sentry is installed
        if (app()->bound('sentry') && $this->shouldReport($e)) {
            app('sentry')->captureException($e);
        }

        //Prevent the logging / displaying of database credentials and information
        if(is_a($e, \PDOException::class)) {
            if(app()->environment('production') && config('app.debug') == false) {
                Log::error('ExceptionHandler: A PDO Exception has occurred. Check database settings.');
                throw new Exception('A PDO Exception has occurred. Check database settings since they might be incorrect.');
            }
        }

        parent::report($e);
        return $e;
    }

    /**
     * Render the given HttpException.
     *
     * @param  HttpExceptionInterface  $e
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $this->registerErrorViewPaths();

        // Check for custom error message
        $view = 'errors.'.$e->getStatusCode();
        if (!view()->exists($view)){

            // Give debug info
            if(app()->environment() !== 'production') debug('Exceptions\Handler@renderHttpException: Error ' . $e->getStatusCode() . ' not implemented');

            $view = 'errors.show';
        }

        return response()->view($view, [
            'errors' => new ViewErrorBag,
            'exception' => $e,
        ], $e->getStatusCode(), $e->getHeaders());
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     *
     * @return \Illuminate\Http\JsonResponse|Response
     */
    public function render($request, \Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            if($request->ajax()) return response()->json('Not found', Response::HTTP_NOT_FOUND);
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if($e instanceof AuthorizationException) {
            if($request->ajax()) return response()->json(__('KMS::auth.unauthorized_explanation'), Response::HTTP_UNAUTHORIZED);
            return response()->view('KMS::auth.unauthorized');
        }

        //Render the page
        return parent::render($request, $e);
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  \Exception $e
     * @return void
     */
    public function renderForConsole($output, \Throwable $e)
    {
        if(app()->environment() !== 'production') $output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
        parent::renderForConsole($output, $e);
    }


    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED);
        }

        if($request->segment(1) == 'kms') {
            return redirect()->guest(route('kms.login'));
        } else {
            return redirect()->guest(route('site.login'));
        }
    }
}
