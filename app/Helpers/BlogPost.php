<?php

namespace App\Helpers;

use League\CommonMark\CommonMarkConverter;

class BlogPost
{
	private $rawContent = "";
	private $markdownContent = "";
	private $htmlContent = "";

	private $date;
	private $slug;

	private $title;
	private $author;

	function __construct(string $path) {
		$filename = basename($path, '.md');
		$parts = explode('_', $filename);

		$this->date = date('Y-m-d', strtotime($parts[0]));
		$this->slug = implode('-', $parts);

		$this->rawContent = file_get_contents($path);
	}

	function getDate() {
		return $this->date;
	}

	function getSlug() {
		return $this->slug;
	}

	private function decodeContent() {
		$lines = explode("\n", $this->rawContent);

		$commentMode = false;
		foreach ($lines as $line) {
			if ($line == "<!--") {
				$commentMode = true;
				continue;
			} else if ($line == '-->') {
				$commentMode = false;
				continue;
			}

			if ($commentMode) {
				$commentAttrs = [
					'TITLE' => 'title',
					'AUTHOR' => 'author',
				];

				foreach ($commentAttrs as $key => $varName)
				if ($commentMode && str_starts_with($line, "{$key}:")) {
					$value = explode(':', $line);
					$value = end($value);
					$value = trim($value);

					$this->$varName = $value;
				}
			} else {
				$this->markdownContent .= "{$line}\n";
			}
		}
	}

	public function getTitle()  {
		if (empty($this->title)) {
			$this->decodeContent();
		}

		return $this->title;
	}

	public function getAuthor()  {
		if (empty($this->author)) {
			$this->decodeContent();
		}

		return $this->author;
	}

	public function getMarkdown() {
		if (empty($this->markdownContent)) {
			$this->decodeContent();
		}

		return $this->markdownContent;
	}

	public function getHtml() {
		if (empty($this->htmlContent)) {
			$converter = new CommonMarkConverter([
				'html_input' => 'strip',
				'allow_unsafe_links' => false,
			]);
			
			$this->htmlContent = $converter->convert(
				$this->getMarkdown()
			);
		}
		
		return $this->htmlContent;
	}
}