<?php

namespace Verbum\Dict;

use Verbum\Core\Response;

class IndexController
{
    public function indexAction(Response $r)
    {
        $template = new MainTemplate();
        $r->setContent($template->render());
        $r->setHeader('Content-Type', 'text/html; charset=utf-8');
    }
}
