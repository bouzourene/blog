package controllers

import (
	"github.com/bouzourene/blog/tools"
	"github.com/gofiber/fiber/v2"
)

func Index(c *fiber.Ctx) error {
	return c.Redirect("/home")
}

func Home(c *fiber.Ctx) error {
	posts := tools.BlogGetEntires()
	var lastPosts []tools.BlogEntry

	// Only show 3 most recent posts
	for i, post := range posts {
		if i > 2 {
			break
		}

		lastPosts = append(lastPosts, post)
	}

	return c.Render("views/home", fiber.Map{
		"posts": lastPosts,
	})
}

func About(c *fiber.Ctx) error {
	return c.Render("views/about", fiber.Map{
		"head_title": "About",
	})
}

func Tools(c *fiber.Ctx) error {
	return c.Render("views/tools", fiber.Map{
		"head_title": "Tools",
	})
}
