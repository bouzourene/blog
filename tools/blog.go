package tools

import (
	"embed"
	"fmt"
	"slices"
	"strings"
	"time"

	"github.com/gomarkdown/markdown"
	"github.com/gomarkdown/markdown/html"
	"github.com/gomarkdown/markdown/parser"
)

var EmbedBlog embed.FS

type BlogEntry struct {
	Title  string
	Author string
	Date   string
	Slug   string
	Html   string
}

func BlogGetEntires() []BlogEntry {
	var entries []BlogEntry

	items, err := EmbedBlog.ReadDir("blog")
	if err == nil {
		for _, item := range items {
			name := strings.ToLower(item.Name())
			if !item.IsDir() && strings.HasSuffix(name, ".md") {
				path := fmt.Sprintf("blog/%s", item.Name())
				file, err := EmbedBlog.ReadFile(path)
				if err != nil {
					continue
				}

				content := string(file)
				entries = append(entries, BlogEntry{
					Title: BlogGetTitleFromContent(content),
					Date:  BlogGetDateFromName(name),
					Slug:  BlogGetSlugFromName(name),
				})
			}
		}
	}

	// Get the most recent entries first
	slices.Reverse(entries)

	return entries
}

func BlogGetEntry(slug string) BlogEntry {
	var entry BlogEntry

	name := fmt.Sprintf("%s.md", slug)
	name = strings.ReplaceAll(name, "-", "_")
	path := fmt.Sprintf("blog/%s", name)

	file, err := EmbedBlog.ReadFile(path)
	if err == nil {
		content := string(file)

		entry.Slug = slug
		entry.Date = BlogGetDateFromName(name)
		entry.Author = BlogGetAuthorFromContent(content)
		entry.Title = BlogGetTitleFromContent(content)
		entry.Html = BlogGetHtmlFromContent(content)
	}

	return entry
}

func BlogGetSlugFromName(name string) string {
	name = strings.ReplaceAll(name, ".md", "")
	name = strings.ReplaceAll(name, "_", "-")

	return name
}

func BlogGetDateFromName(name string) string {
	slice := strings.Split(name, "_")
	date, err := time.Parse("20060102", slice[0])
	if err != nil {
		return ""
	}

	return date.Format("02.01.2006")
}

func BlogGetTitleFromContent(content string) string {
	return BlogGetMetadataFromContent(1, content)
}

func BlogGetAuthorFromContent(content string) string {
	return BlogGetMetadataFromContent(2, content)
}

func BlogGetMetadataFromContent(index int, content string) string {
	lines := strings.Split(content, "\n")
	if len(lines) < (index + 1) {
		return ""
	}

	line := strings.Split(lines[index], ":")
	if len(line) < 2 {
		return ""
	}

	return strings.TrimSpace(line[1])
}

func BlogGetHtmlFromContent(content string) string {
	// create markdown parser with extensions
	extensions := parser.CommonExtensions
	p := parser.NewWithExtensions(extensions)
	doc := p.Parse([]byte(content))

	// create HTML renderer with extensions
	htmlFlags := html.CommonFlags | html.HrefTargetBlank
	renderer := html.NewRenderer(html.RendererOptions{
		Flags: htmlFlags,
	})

	html := markdown.Render(doc, renderer)

	return string(html)
}
