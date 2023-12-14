package controllers

import (
	"github.com/bouzourene/blog/tools"
	"github.com/gofiber/fiber/v2"
)

func BlogList(c *fiber.Ctx) error {
	return c.Render("views/blog", fiber.Map{
		"head_title": "Blog",
		"posts":      tools.BlogGetEntires(),
	})
}

func BlogPost(c *fiber.Ctx) error {
	slug := c.Params("slug")
	post := tools.BlogGetEntry(slug)

	if len(post.Slug) < 1 {
		return c.Status(fiber.StatusNotFound).SendString("404 not found")
	}

	return c.Render("views/blogpost", fiber.Map{
		"head_title": post.Title,
		"post":       post,
	})
}
