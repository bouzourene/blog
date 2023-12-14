package controllers

import (
	"github.com/gofiber/fiber/v2"
)

func Index(c *fiber.Ctx) error {
	return c.Redirect("/home")
}

func Home(c *fiber.Ctx) error {
	var posts []struct{}

	return c.Render("views/home", fiber.Map{
		"posts": posts,
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
