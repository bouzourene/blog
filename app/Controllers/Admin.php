<?php

namespace App\Controllers;

use App\Helpers\BlogPost;
use App\Helpers\BlogPosts;
use App\Helpers\Authz;

class Admin extends BaseController
{
	public function index()
	{
		$postsToDisplay = (new BlogPosts)->getAll();

		$blogPosts = [];
		foreach ($postsToDisplay as $post) {
			$blogPosts[] = new BlogPost($post);
		}

		return $this->twig->display("admin/list", [
			'head_title' => "Admin",
			'posts' => $blogPosts
		]);
	}

	public function post_add()
	{
		if ($this->request->getPost('slug')) {
			$slug = $this->request->getPost('slug');
			$title = $this->request->getPost('title');
			$date = $this->request->getPost('date');
			$author = $this->request->getPost('author');

			$date = date('Ymd', strtotime($date));
			$filename = $date."_".str_replace('-', '_', $slug).".md";

			$slug2 = $date."-".$slug;

			$tpl = $this->twig->render('templates/post', [
				'title' => $title,
				'author' => $author
			]);

			file_put_contents(BLOG_DRAFT."/".$filename, $tpl);

			return redirect()->to("/admin/post/edit/{$slug2}");
		}

		return $this->twig->display("admin/post_add", [
			'Add post'
		]);
	}

	public function post_edit($slug)
	{
		$slugToFile = str_replace('-', '_', $slug) . ".md";

		if (file_exists(BLOG_PUBLISHED . "/{$slugToFile}")) {
			$status = "published";
			$path = BLOG_PUBLISHED;
		} elseif (file_exists(BLOG_DRAFT . "/{$slugToFile}")) {
			$status = "draft";
			$path = BLOG_DRAFT;
		} elseif (file_exists(BLOG_DISABLED . "/{$slugToFile}")) {
			$status = "disabled";
			$path = BLOG_DISABLED;
		} else {
			throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
		}

		if ($this->request->getPost('slug')) {
			$slug = $this->request->getPost('slug');
			$title = $this->request->getPost('title');
			$date = $this->request->getPost('date');
			$author = $this->request->getPost('author');
			$newStatus = $this->request->getPost('status');
			$content = $this->request->getPost('content');

			if ($status != $newStatus) {
				$oldpath = $path;
				$path = match ($newStatus) {
					'PUBLISHED' => BLOG_PUBLISHED,
					'DRAFT' => BLOG_DRAFT,
					'DISABLED' => BLOG_DISABLED,
				};

				unlink("$oldpath/$slugToFile");
			} 

			$date = date('Ymd', strtotime($date));
			$filename = $date."_".str_replace('-', '_', $slug).".md";

			$slug2 = $date."-".$slug;

			$tpl = $this->twig->render('templates/post', [
				'title' => $title,
				'author' => $author,
				'markdown' => $content
			]);

			file_put_contents($path."/".$filename, $tpl);

			return redirect()->to("/admin");
		}

		$blogPost = new BlogPost(
			"{$path}/{$slugToFile}"
		);

		$slug = explode('-', $blogPost->getSlug());
		unset($slug[0]);
		$slug = implode('-', $slug);

		return $this->twig->display("admin/post_edit", [
			'head_title' => $blogPost->getTitle(),
			'post' => $blogPost,
			'slug' => $slug,
			'status' => $status,
		]);
	}
}
