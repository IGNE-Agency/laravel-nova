# Error pages

The package replaces Nova's 403 page ("Hold Up!") with one that says the user has no access to the page. It shows the brand logo and two buttons:

- **Go back**: to the previous page, or to Nova's initial path when there is none;
- **Log out**: to log in with another account.

Its texts are in the package's Nova translations, so they follow the user's locale.

The page is a Vue component (`resources/js/pages/CustomError403.vue`) that `resources/js/error-403.js` registers as Nova's `CustomError403` in `Nova.booting()`, the same way Nova lets apps override its own components. It is built into `dist/js/error-403.js` with Vue and Nova left as globals, so a Nova update needs no rebuild; see [Development](development.md#javascript) for the build.

Nova only loads scripts for logged-in users, so a guest who hits a 403 still sees Nova's own page. In practice a 403 comes from a logged-in user: someone without access to the panel, or an admin who followed a link to something they lack the permission for.
