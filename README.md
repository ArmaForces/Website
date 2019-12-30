# ArmaForces Website

## OAuth and authentication setup

1. Create `.env.local` file from `.env` file*
2. Go to: https://discordapp.com/developers/applications
3. Create new application or select existing one.
4. From **General Information** tab copy **Client Id** and **Client Secret** and place them under `APP_SECURITY_OAUTH_DISCORD_CLIENT_ID` and `APP_SECURITY_OAUTH_DISCORD_CLIENT_SECRET` keys respectively in `.env.local` file.
5. Go to application **OAuth** tab and add new redirect to `https://<your domain>/security/connect/discord/check`.
6. Go to **Bot** tab and create new bot or use existing one. Bot doesn't need to have any privileges. Add bot to your server. Copy bot **Token** and place it under `APP_SECURITY_OAUTH_DISCORD_BOT_TOKEN` key in `.env.local` file.
7. Get your Discord **Server Id** from **Server Settings** → **Widget** tab and place it under `APP_SECURITY_OAUTH_DISCORD_SERVER_ID` key in `.env.local` file.
8. Enter role name that user must have assigned to be considered unit member and place it under `APP_SECURITY_OAUTH_DISCORD_MEMBER_ROLE` key in `.env.local` file.
 
\* You can also use system environment variables for this.
