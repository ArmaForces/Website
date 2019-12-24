# ArmaForces Website

## OAuth and authentication setup

1. Go to: https://discordapp.com/developers/applications
2. Create new application or select existing one
3. From **General Information** tab copy **Client Id** and **Client Secret** and place them in environment variables on your server or in created **.env.local** file in application root folder under `APP_SECURITY_OAUTH_DISCORD_CLIENT_ID` and `APP_SECURITY_OAUTH_DISCORD_CLIENT_SECRET` keys respectively
4. Go to application OAuth tab
5. Add new redirect as `https://<your domain>/security/connect/discord/check` 
6. Get your Discord **Server Id** from **Server Settings** → **Widget** tab and place it in environment variable on your server or in created `.env.local` file in application root folder under `APP_SECURITY_OAUTH_DISCORD_SERVER_ID` key
