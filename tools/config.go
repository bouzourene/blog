package tools

import "github.com/spf13/viper"

func LoadConfig() {
	viper.SetConfigFile(".env")
	err := viper.ReadInConfig()
	if err != nil {
		panic(err)
	}

	// Default values
	viper.SetDefault("BIND_ADDR", "127.0.0.1")
	viper.SetDefault("BIND_PORT", "3000")
	viper.SetDefault("BEHIND_PROXY", "false")
	viper.SetDefault("MAILTO", "")
	viper.SetDefault("GOATCOUNTER", "")
}

func ConfigValue(key string) string {
	return viper.GetString(key)
}
