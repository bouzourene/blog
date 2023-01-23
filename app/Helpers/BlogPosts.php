<?php

namespace App\Helpers;

use League\CommonMark\CommonMarkConverter;

class BlogPosts
{
	private function getList(string $path): array
	{
		$posts = scandir(
			$path,
			SCANDIR_SORT_DESCENDING
		);

		$postsToDisplay = [];
		foreach ($posts as $key => $post) {
			$post = strtolower($post);
			if (str_ends_with($post, '.md')) {
				$postsToDisplay[] = "{$path}/{$post}";
			}
		}

		return $postsToDisplay;
	}

	public function getDrafts(): array
	{
		return $this->getList(
			\BLOG_DRAFT
		);
	}

	public function getPublished(): array
	{
		return $this->getList(
			\BLOG_PUBLISHED
		);
	}

	public function getDeleted(): array
	{
		return $this->getList(
			\BLOG_DELETED
		);
	}

	public function getAll(): array
	{
		return array_merge(
			$this->getDrafts(),
			$this->getPublished(),
			$this->getDeleted()
		);
	}
}