# OpenAlias

Open-source link shortener with built-in authentication, SSO support, and a clean customizable design.

## Features

- **Link shortening** — create, edit, toggle, and delete short links with click tracking
- **Built-in login** — local username/password authentication out of the box
- **SSO support** — optional OIDC/Keycloak single sign-on integration
- **Custom theming** — three built-in themes (warm, cool, dark) plus a custom CSS editor
- **Admin settings** — manage users, site branding, appearance, and SSO from one place
- **SQLite** — zero-configuration database, no external services needed
- **First-run setup** — guided setup wizard creates your admin account on first visit

## Requirements

- PHP 8.1+
- SQLite3 extension
- cURL extension (for SSO)
- Apache with `mod_rewrite` (or equivalent Nginx config)

## Quick Start

1. **Clone / download** into your web server's document root

2. **Copy environment file**
   ```bash
   cp .env.example .env
   ```

3. **Edit `.env`** with your settings:
   ```
   APP_URL=https://your-domain.com
   ```

4. **Visit your site** — the setup wizard will guide you through creating an admin account

5. **Start creating links!**

## SSO Configuration

To enable single sign-on, add these to your `.env`:

```
SSO_PROVIDER_URL=https://auth.example.com
SSO_REALM=master
SSO_CLIENT_ID=openalias
SSO_CLIENT_SECRET=your-client-secret
```

Set the callback URL in your identity provider to: `https://your-domain.com/callback`

## Theming

OpenAlias ships with three themes:

| Theme | Description |
|-------|-------------|
| **Warm** | Paper-toned with terracotta accents (default) |
| **Cool** | Light blue-grey with slate accents |
| **Dark** | Dark mode with muted purple accents |

Additional built-in test themes: **Forest**, **Sunset**, and **Midnight**.

To add your own theme quickly, edit [includes/themes.php](includes/themes.php) and add a new entry in `oa_theme_definitions()` with:
- `label` and `description`
- CSS variables in `vars` (including colors and `oa-radius*` values)

You can also write custom CSS in **Settings → Appearance**. All styles use CSS custom properties prefixed with `--oa-` for easy overriding.

## Project Structure

```
index.php           Main router
.htaccess           URL rewriting & security
.env.example        Environment template
includes/
  config.php        Configuration loader
  db.php            SQLite database layer
  auth.php          Authentication (local + SSO)
  layout.php        Shared layout, CSS, theme system
pages/
  setup.php         First-run setup wizard
  login.php         Login page
  dashboard.php     Link management dashboard
  settings.php      Admin settings (tabs)
  api.php           REST API endpoints
  callback.php      SSO callback handler
  logout.php        Logout handler
  404.php           Not-found page
data/
  openalias.sqlite  SQLite database (auto-created)
```

## API

All endpoints require authentication. Send JSON with `Content-Type: application/json`.

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/links` | List all links |
| GET | `/api/links?search=query` | Search links |
| POST | `/api/links` | Create a link |
| PUT | `/api/links/{id}` | Update a link |
| PATCH | `/api/links/{id}/toggle` | Enable/disable a link |
| DELETE | `/api/links/{id}` | Delete a link |

## License

MIT License — see [LICENSE](LICENSE) for details.
