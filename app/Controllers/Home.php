<?php

namespace App\Controllers;

use App\Helpers\BlogPost;
use App\Helpers\BlogPosts;

class Home extends BaseController
{
	private $mailto;

	public function __construct() {
		$this->mailto = base64_encode("mailto:william@bouzourene.ch");
	}

	public function index()
	{
		header("Location: /home");
		exit;
	}

	public function home()
	{
		$postsToDisplay = [];
		$allPosts = (new BlogPosts)->getPublished();
		
		$count = 0;
		foreach ($allPosts as $post) {
			$count++;
			$postsToDisplay[] = $post;
			
			if ($count >= 3) {
				break;
			}
		}

		$blogPosts = [];
		foreach ($postsToDisplay as $post) {
			$blogPosts[] = new BlogPost($post);
		}

		return $this->twig->display("home/index", [
			'mailto' => $this->mailto,
			'posts' => $blogPosts
		]);
	}

	public function about()
	{
		return $this->twig->display("home/about", [
			'mailto' => $this->mailto
		]);
	}

	public function tools()
	{
		return $this->twig->display("home/tools");
	}
}
