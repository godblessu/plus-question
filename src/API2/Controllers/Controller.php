<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use SlimKit\PlusQuestion\Traits\ResolveUserTrait;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests, ResolveUserTrait;
}
