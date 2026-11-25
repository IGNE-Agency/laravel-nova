<template>
  <div
    class="flex justify-center items-center min-h-screen p-6"
    dusk="403-error-page"
  >
    <Head :title="__('No access')" />

    <div class="max-w-md space-y-6 text-center" role="alert">
      <AppLogo class="h-8 mx-auto" />

      <h1 class="text-3xl font-normal">{{ __('No access') }}</h1>

      <p class="text-lg leading-normal text-gray-500 dark:text-gray-400">
        {{
          __(
            "You don't have permission to view this page. Log in with another account if you should have access."
          )
        }}
      </p>

      <div class="flex justify-center gap-3">
        <button
          type="button"
          class="inline-flex items-center rounded px-3 h-9 font-bold focus:outline-none focus:ring border-2 border-primary-300 hover:border-primary-500 text-primary-500 dark:border-gray-500 dark:text-gray-400"
          @click="goBack"
        >
          {{ __('Go back') }}
        </button>

        <button
          type="button"
          class="inline-flex items-center rounded px-3 h-9 font-bold focus:outline-none focus:ring bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900"
          @click="logOut"
        >
          {{ __('Log out') }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
// Options API with this.$store: importing vuex would bundle a second copy
// instead of Nova's store, because the Nova build only keeps Vue external.
export default {
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
        const redirect = await this.$store.dispatch(
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
}
</script>
