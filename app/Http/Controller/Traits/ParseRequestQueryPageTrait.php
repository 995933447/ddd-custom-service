<?php
namespace App\Http\Controller\Traits;
use App\Http\IO\DefaultIOContextFactory;
use Infrastructure\Shared\Config\Config;

trait ParseRequestQueryPageTrait
{
    protected $queryPage;

    protected $queryPageLimit;

    public function getQueryPage()
    {
        if (is_null($this->queryPage)) {
            $this->parse();
        }

        return $this->queryPage;
    }

    public function getQueryPageLimit()
    {
        if (is_null($this->queryPageLimit)) {
            $this->parse();
        }

        return $this->queryPageLimit;
    }

    protected function parse()
    {
        $page_var = Config::get('base.page_var');
        $page_limit_var = Config::get('base.page_limit_var');

        $request = DefaultIOContextFactory::get()->getRequest();
        $this->queryPage = $request->input($page_var) ?: 1;
        $this->queryPageLimit = $request->input($page_limit_var) ?: (int) Config::get('base.page_limit_default');
    }
}