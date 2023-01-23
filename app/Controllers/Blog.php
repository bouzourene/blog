<?php

namespace App\Controllers;

class Blog extends BaseController
{
	public function index()
	{
		$posts = scandir(
			BLOG_PUBLISHED,
			SCANDIR_SORT_DESCENDING
		);

		$postsToDisplay = [];
		foreach ($posts as $key => $post) {
			$post = strtolower($post);
			if (str_ends_with($post, '.md')) {
				$postsToDisplay[] = $post;
			}
		}

		$blogPosts = [];
		foreach ($postsToDisplay as $post) {
			$blogPosts[] = new \App\Helpers\BlogPost(
				BLOG_PUBLISHED . "/{$post}"
			);
		}

		return $this->twig->display("blog/list", [
			'posts' => $blogPosts
		]);
	}

	public function post($slug)
	{
		$slugToFile = str_replace('-', '_', $slug) . ".md";
		if (!file_exists(BLOG_PUBLISHED . "/{$slugToFile}")) {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		$blogPost = new \App\Helpers\BlogPost(
			BLOG_PUBLISHED . "/{$slugToFile}"
		);

		return $this->twig->display("blog/post", [
			'title' => $blogPost->getTitle(),
			'post' => $blogPost
		]);
	}
}
