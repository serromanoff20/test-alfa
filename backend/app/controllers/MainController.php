<?php namespace App\controllers;

require_once "../backend/app/models/User.php";

use App\models\User;
use App\controllers\ResponseController;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class MainController extends Controller
{
    public function frontend(): false|string
    {
        if (!isset($_COOKIE['cookie_id'])) {
            $uniqueUserId = uniqid('cookie_');

            setcookie('cookie_id', $uniqueUserId, time() + (30 * 24 * 60 * 60), "/");
        }

        return file_get_contents(__DIR__ . '/../../../frontend/dist/index.html');
    }

    public function backend(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return (new ResponseController())->actionGet(new User());
        } else {
            return (new ResponseController())->actionPost(new User());
        }
    }
}
