<?php

namespace Domain\Test\Controllers;

use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function index(): void
    {
        updateFolderPermissions(storage_path());
    }
}
