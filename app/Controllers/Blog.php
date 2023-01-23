<?php

namespace App\Controllers;

use App\Helpers\BlogPost;
use App\Helpers\BlogPosts;

class Blog extends BaseController
{
	public function index()
	{
		$postsToDisplay = (new BlogPosts)->getPublished();

		$blogPosts = [];
		foreach ($postsToDisplay as $post) {
			$blogPosts[] = new BlogPost($post);
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

		$blogPost = new BlogPost(
			BLOG_PUBLISHED . "/{$slugToFile}"
		);

		return $this->twig->display("blog/post", [
			'title' => $blogPost->getTitle(),
			'post' => $blogPost
		]);
	}
}
