package main

import (
	"embed"
	"encoding/base64"
	"fmt"
	"net/http"
	"time"

	"github.com/bouzourene/blog/controllers"
	"github.com/bouzourene/blog/middlewares"
	"github.com/bouzourene/blog/tools"
	"github.com/gofiber/fiber/v2"
	"github.com/gofiber/fiber/v2/middleware/filesystem"
	"github.com/gofiber/template/jet/v2"
)

//go:embed static/*
var embedStatic embed.FS

//go:embed views
var embedViews embed.FS

//go:embed blog
var embedBlog embed.FS

func main() {
	// Very first thing we do is start the logger
	logger := tools.GetLogger()

	// Load config on application start
	logger.Info("Loading config from .env file")
	tools.LoadConfig()

	// Send blog embed to package
	tools.EmbedBlog = embedBlog

	// Create templates engine
	engine := jet.NewFileSystem(
		http.FS(embedViews),
		".jet",
	)

	// Generate webapp config
	appConfig := fiber.Config{
		Views: engine,
	}

	// If app behind proxy, use headers for IP
	if tools.ConfigValue("BEHIND_PROXY") == "true" {
		appConfig.ProxyHeader = fiber.HeaderXForwardedFor
	}

	// Create webapp with config
	app := fiber.New(appConfig)

	// Add global vars to template engine
	engine.Templates.AddGlobal("copyright_year", time.Now().Year())
	engine.Templates.AddGlobal("mailto", base64.StdEncoding.EncodeToString(
		[]byte(tools.ConfigValue("MAILTO")),
	))

	// Serve static content
	app.Use("/static", filesystem.New(filesystem.Config{
		Root:       http.FS(embedStatic),
		PathPrefix: "static",
		Browse:     false,
	}))

	// Apply middlewares
	app.Use(middlewares.DefaultHeaders)

	// Define routes
	app.Get("/", controllers.Index)
	app.Get("/home", controllers.Home)
	app.Get("/about", controllers.About)
	app.Get("/tools", controllers.Tools)
	app.Get("/blog", controllers.BlogList)
	app.Get("/blog/:slug", controllers.BlogPost)

	bindStr := fmt.Sprintf(
		"%s:%s",
		tools.ConfigValue("BIND_ADDR"),
		tools.ConfigValue("BIND_PORT"),
	)

	logger.Info(fmt.Sprintf(
		"Webserver starting on address: %s",
		bindStr,
	))

	err := app.Listen(bindStr)
	if err != nil {
		logger.Fatal(err.Error())
	}
}
