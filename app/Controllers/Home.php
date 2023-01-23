<?php

namespace App\Controllers;

class Home extends BaseController
{
	public function index()
	{
		header("Location: /home");
		exit;
	}

	public function home()
	{
		$mailto = base64_encode("mailto:william@bouzourene.ch");

		return $this->twig->display("home/index", [
			'mailto' => $mailto
		]);
	}

	public function about()
	{
		return $this->twig->display("home/about");
	}
}
