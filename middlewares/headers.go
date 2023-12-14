package middlewares

import (
	"runtime"

	"github.com/gofiber/fiber/v2"
)

func DefaultHeaders(c *fiber.Ctx) error {
	c.Set(fiber.HeaderXPoweredBy, runtime.Version())

	return c.Next()
}
