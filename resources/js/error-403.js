/**
 * Replaces Nova's 403 page ("Hold Up!") with one that tells the user they have
 * no access, and offers a way back and a way to log in with another account.
 *
 * Nova registers its own CustomError403 only when no component of that name
 * exists yet, and runs booting callbacks before that, so this one wins.
 */
import CustomError403 from './pages/CustomError403.vue'

Nova.booting(app => {
  app.component('CustomError403', CustomError403)
})
