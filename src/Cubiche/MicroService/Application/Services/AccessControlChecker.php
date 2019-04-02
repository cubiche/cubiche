<?php

/**
 * This file is part of the Cubiche application.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cubiche\MicroService\Application\Services;

/**
 * AccessControlChecker interface.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
class AccessControlChecker implements AccessControlCheckerInterface
{
    /**
     * @var TokenContextInterface
     */
    protected $tokenContext;

    /**
     * AccessControlChecker constructor.
     *
     * @param TokenContextInterface $tokenContext
     */
    public function __construct(TokenContextInterface $tokenContext)
    {
        $this->tokenContext = $tokenContext;
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted(array $permissions)
    {
        $granted = array();
        if ($this->tokenContext->hasToken()) {
            $token = $this->tokenContext->getToken();

            $granted = $token->permissions();
        }

        foreach ($permissions as $permission) {
            if ($permission === 'app.open') {
                return true;
            }

            foreach ($granted as $userPermission) {
                if (strpos($permission, $userPermission) !== false) {
                    return true;
                }
            }
        }

        return false;
    }
}
