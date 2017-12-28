<?php

namespace Lt\UpvoteBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

class VisitorIdentifier
{
    public function getVisitorId(Request $request)
    {
        return $request->getClientIp();
    }
}