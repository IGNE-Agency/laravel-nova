# Layout

The package sets up Nova's layout the way all IGNE panels are. Each setting is under `layout` in `config/igne-nova.php`, and anything the app sets itself in its `NovaServiceProvider` wins, because the app's provider boots after the package's.

| Setting | Default | Config |
|---|---|---|
| Breadcrumbs above every page | On | `layout.breadcrumbs` |
| The path Nova opens after login | Nova's: the main dashboard | `layout.initial_path` |
| Footer: `© {year} {app.name} · Built by IGNE` instead of Nova's version and copyright | On | `layout.footer` |
