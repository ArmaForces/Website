<p align="center">
    <img src="https://avatars2.githubusercontent.com/u/50863181">
</p>
<p align="center">
    <a href="https://github.com/ArmaForces/Website/issues">
        <img src="https://img.shields.io/github/issues-raw/ArmaForces/Website.svg?label=Issues" alt="ArmaForces Website">
    </a>
    <a href="https://github.com/ArmaForces/Website/blob/master/LICENSE">
        <img src="https://img.shields.io/badge/License-GPLv3-red.svg" alt="ArmaForces Website">
    </a>
    <a href="https://github.com/ArmaForces/Website/actions">
        <img src="https://github.com/ArmaForces/Website/actions/workflows/application.yml/badge.svg?branch=dev">
    </a>
</p>

# ArmaForces Website

## OAuth and authentication setup

1. Go to: https://discordapp.com/developers/applications
2. Create new application or select existing one.
3. Go to **OAuth2** -> **General** tab.

    - Copy **Client Id** and **Client Secret** and place them under `APP_SECURITY_OAUTH_DISCORD_CLIENT_ID` and `APP_SECURITY_OAUTH_DISCORD_CLIENT_SECRET` environment variables respectively.

    - Add a new redirect to `https://<your domain>/security/connect/discord/check`.

4. Go to **Bot** tab. Copy bot **Token** and place it under `APP_SECURITY_OAUTH_DISCORD_BOT_TOKEN` environment variable.
5. Get your Discord **Server Id** from **Server Settings** → **Widget** tab and place it under `APP_SECURITY_OAUTH_DISCORD_SERVER_ID` environment variable.
6. Go to **OAuth2** -> **URL Generator** tab. Select **bot** scope and copy generated URL. Use it to add bot to your server.
7. Enter roles names that user must have assigned to be considered a recruit or a member of the unit and place them under `APP_SECURITY_OAUTH_DISCORD_RECRUIT_ROLE_NAME` and `APP_SECURITY_OAUTH_DISCORD_MEMBER_ROLE_NAME` keys respectively in `.env.local` file.

## Xdebug setup

Port: 9003  
IDE key: armaforces-web  
Path mappings: /www/app

Xdebug can be turned on/off from `php` container using following commands (available for dev environment, disabled by default):

```shell
xon
xoff
```
