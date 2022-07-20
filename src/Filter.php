<?php

namespace Mrstock\Mjc;

use Mrstock\Mjc\Http\Request;

class Filter
{
    protected function filterWords($v)
    {
        $pattern = array(
            "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
            "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
            "/select\b|insert\b|update\b|delete\b|drop\b|\/\*|\.\.\/|\.\/|into|load_file|outfile|dump/is"
        );

        $v = preg_replace($pattern, '', $v);
//        $v = strip_tags($v);
        $v = trim($v);
        return $v;
    }

    protected function filterArray($data)
    {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $data[$k] = $this->filterWords($v);
            }
        }
        return $data;
    }

    public function handle(Request $request, \Closure $next)
    {
        $request->param = $this->filterArray($request->param);

        return $next($request);
    }
}