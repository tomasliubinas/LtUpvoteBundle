<?php

namespace Lt\UpvoteBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

class VisitorIdentifier
{
    /**
     * Returns pseudo unique visitor identifier.
     *
     * @param Request $request
     *
     * @return null|string
     */
    public function getVisitorId(Request $request)
    {
        return $request->getClientIp();
    }
}