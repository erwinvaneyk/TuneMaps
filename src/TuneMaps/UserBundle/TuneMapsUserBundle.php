<?php

namespace TuneMaps\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class TuneMapsUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
