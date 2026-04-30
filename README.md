# Plan Dekton Studio

WordPress theme and local Docker setup for the Plan Dekton Studio showcase site.

## Local Development

1. Copy `.env.example` to `.env`.
2. Set local database passwords in `.env`.
3. Start WordPress:

```bash
docker compose up -d
```

Local URL:

```text
http://localhost:8090
```

The custom theme is mounted from:

```text
plan-dekton-studio/
```

## Production

Production domain:

```text
https://plan-travail-dekton.com
```

Do not commit real database, SSH, FTP, or WordPress admin credentials.
