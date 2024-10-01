<?php

namespace App\Middleware;

use App\Core\Middleware;
use App\Core\Request;
use App\Exceptions\AuthException;

class AdminMiddleware extends Middleware
{
  public function handle(Request $request): void
  {
    $user = $request->getUser();

    if (!in_array("admin", $user["roles"])) {
      throw AuthException::notAuthorized();
    }
  }
}
