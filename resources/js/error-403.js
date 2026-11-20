/**
 * Replaces Nova's 403 page ("Hold Up!") with one that tells the user they have
 * no access, and offers a way back and a way to log in with another account.
 *
 * Nova registers its own CustomError403 only when no component of that name
 * exists yet, and runs booting callbacks before that, so this one wins. Plain
 * JavaScript on the Vue and Nova globals: no build step, no Nova imports that
 * a Nova update could move.
 */
Nova.booting((app, store) => {
  const { h, resolveComponent } = Vue

  const button = (label, onClick, primary) =>
    h(
      'button',
      {
        type: 'button',
        class: [
          'inline-flex items-center rounded px-3 h-9 font-bold focus:outline-none focus:ring',
          primary
            ? 'bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900'
            : 'border-2 border-primary-300 hover:border-primary-500 text-primary-500 dark:border-gray-500 dark:text-gray-400',
        ],
        onClick,
      },
      label
    )

  app.component('CustomError403', {
    name: 'CustomError403',

    methods: {
      goBack() {
        if (window.history.length > 1) {
          window.history.back()

          return
        }

        Nova.visit(Nova.config('initialPath'))
      },

      async logOut() {
        try {
          const redirect = await store.dispatch(
            'logout',
            Nova.config('customLogoutPath')
          )

          if (redirect !== null) {
            window.location.href = redirect

            return
          }

          Nova.redirectToLogin()
        } catch (error) {
          window.location.reload()
        }
      },
    },

    render() {
      return h(
        'div',
        {
          class: 'flex justify-center items-center min-h-screen p-6',
          dusk: '403-error-page',
        },
        [
          h(resolveComponent('Head'), { title: this.__('No access') }),
          h('div', { class: 'max-w-md space-y-6 text-center', role: 'alert' }, [
            h(resolveComponent('AppLogo'), { class: 'h-8 mx-auto' }),
            h('h1', { class: 'text-3xl font-normal' }, this.__('No access')),
            h(
              'p',
              { class: 'text-lg leading-normal text-gray-500 dark:text-gray-400' },
              this.__(
                "You don't have permission to view this page. Log in with another account if you should have access."
              )
            ),
            h('div', { class: 'flex justify-center gap-3' }, [
              button(this.__('Go back'), this.goBack, false),
              button(this.__('Log out'), this.logOut, true),
            ]),
          ]),
        ]
      )
    },
  })
})
