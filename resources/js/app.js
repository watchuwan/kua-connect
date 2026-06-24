document.addEventListener('alpine:init', () => {
    Alpine.data('heroBg', () => ({
        init() {
            const el = this.$el;
            const bgImg = el.querySelector('[x-ref="bgImg"]');
            const blobTop = el.querySelector('[x-ref="blobTop"]');
            const blobBottom = el.querySelector('[x-ref="blobBottom"]');

            import('gsap').then(({ default: gsap }) => {
                const tl = gsap.timeline({ repeat: -1, yoyo: true, ease: 'power1.inOut' });

                if (bgImg) {
                    tl.to(bgImg, { scale: 1.08, duration: 8 }, 0);
                }
                if (blobTop) {
                    tl.to(blobTop, { x: 25, y: -15, scale: 1.06, duration: 6 }, 0);
                }
                if (blobBottom) {
                    tl.to(blobBottom, { x: -15, y: 20, scale: 1.08, duration: 7 }, 0);
                }
            });
        }
    }));
});
