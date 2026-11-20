# Error pages

The package replaces Nova's 403 page ("Hold Up!") with one that says the user has no access to the page. It shows the brand logo and two buttons:

- **Go back**: to the previous page, or to Nova's initial path when there is none;
- **Log out**: to log in with another account.

Its texts are in the package's Nova translations, so they follow the user's locale.

The page is a small script (`resources/js/error-403.js`) that registers Nova's `CustomError403` component. It is plain JavaScript on the `Vue` and `Nova` globals, with no build step, so Nova updates don't need a rebuild.

Nova only loads scripts for logged-in users, so a guest who hits a 403 still sees Nova's own page. In practice a 403 comes from a logged-in user: someone without access to the panel, or an admin who followed a link to something they lack the permission for.
