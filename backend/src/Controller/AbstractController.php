<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * Transforms payload of the JSON request to readable data.
     */
    final protected function transformJsonBody(Request $request): ?Request
    {
        if (!$data = json_decode($request->getContent(), true)) {
            return null;
        }

        $request->request->replace($data);

        return $request;
    }
}
