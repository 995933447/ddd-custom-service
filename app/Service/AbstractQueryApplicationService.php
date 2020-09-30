<?php
namespace App\Service;


/**
 * HttpServeApplication service abstract.
 * Class AbstractCommandApplicationService.backup
 * @package App\Service
 */
abstract class AbstractQueryApplicationService
{
    /**
     * Query logic.All query logic happen at here.
     * If has exception must wrap as ApplicationServeException instance
     * and put into QueryApplicationServeResult instance, than return it.
     * And final query value must put into QueryApplicationServeResult instance then return it.
     */
    final public function execute(AbstractDTO $input): QueryApplicationServeResult
    {
        if (method_exists($this, 'handle')) {
            return $this->handle($input);
        }

        throw new \RuntimeException("Please implements " . AbstractQueryApplicationService::class . '::handle($input_dto)');
    }
}