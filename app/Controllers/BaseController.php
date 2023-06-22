<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
	/**
	 * Instance of the main Request object.
	 *
	 * @var CLIRequest|IncomingRequest
	 */
	protected $request;

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */
	protected $helpers = [];

	/**
	 * Be sure to declare properties for any property fetch you initialized.
	 * The creation of dynamic property is deprecated in PHP 8.2.
	 */
	protected $session, $twig, $uri;

	/**
	 * Constructor.
	 */
	public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		$this->twig = new \Kenjis\CI4Twig\Twig();
		$this->session = \Config\Services::session();
		$this->uri = service('uri');

		$currentPath = strtolower($this->uri->getPath());
		if (str_starts_with($currentPath, 'admin')) {
			$this->authAdmin();
		}
	}

	private function authAdmin()
	{
		if (empty($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_PW'])) {
			$this->forbiddenPage();
		} else {
			if (
				strtolower($_SERVER['PHP_AUTH_USER']) != strtolower($_ENV['ADMIN_USER']) ||
				!password_verify($_SERVER['PHP_AUTH_PW'], $_ENV['ADMIN_PASS'])
			) {
				$this->forbiddenPage();
			}
		}
	}

	private function forbiddenPage()
	{
		header('WWW-Authenticate: Basic realm="Login"');
		header('HTTP/1.0 401 Unauthorized');
		
		echo "Forbidden!";
		exit;
	}
}
