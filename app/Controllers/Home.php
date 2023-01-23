<?php

namespace App\Controllers;

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
		$posts = scandir(
			BLOG_PUBLISHED,
			SCANDIR_SORT_DESCENDING
		);

		$postsToDisplay = [];
		$count = 0;
		foreach ($posts as $key => $post) {
			if ($count >= 3) break;

			$post = strtolower($post);
			if (str_ends_with($post, '.md')) {
				$postsToDisplay[] = $post;
			}

			$count++;
		}

		$blogPosts = [];
		foreach ($postsToDisplay as $post) {
			$blogPosts[] = new \App\Helpers\BlogPost(
				BLOG_PUBLISHED . "/{$post}"
			);
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
}
