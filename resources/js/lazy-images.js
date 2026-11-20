/**
 * Nova's Vue components render <img> tags without a loading attribute, so every image on a page
 * (index thumbnails, avatars) downloads eagerly. Flip everything Nova renders to native lazy
 * loading so off-screen rows on long index pages don't fetch their images upfront.
 *
 * Best-effort: an image whose fetch already started before the observer ran keeps loading; the
 * attribute is only skipped when a component explicitly set its own loading behavior.
 */
(function () {
    var lazyLoadImages = function (root) {
        if (!(root instanceof Element)) {
            return;
        }

        var images = root.matches('img') ? [root] : root.querySelectorAll('img');

        images.forEach(function (img) {
            if (!img.hasAttribute('loading')) {
                img.setAttribute('loading', 'lazy');
            }
        });
    };

    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(lazyLoadImages);
        });
    });

    observer.observe(document.documentElement, { childList: true, subtree: true });

    lazyLoadImages(document.documentElement);
})();
