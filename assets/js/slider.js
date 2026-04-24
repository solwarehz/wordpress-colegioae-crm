/**
 * slider.js — Hero slider con auto-rotate.
 */

(function () {
    'use strict';

    function initSlider(root) {
        var slides = Array.from(root.querySelectorAll('.hero__slide'));
        var dots = Array.from(root.querySelectorAll('[data-slider-go]'));
        var prev = root.querySelector('[data-slider-prev]');
        var next = root.querySelector('[data-slider-next]');
        var autoplay = parseInt(root.getAttribute('data-autoplay'), 10) || 0;

        if (slides.length <= 1) return;

        var current = 0;
        var timer = null;

        function go(i) {
            slides[current].classList.remove('hero__slide--active');
            slides[current].setAttribute('aria-hidden', 'true');
            dots[current] && dots[current].classList.remove('hero__dot--active');

            current = (i + slides.length) % slides.length;

            slides[current].classList.add('hero__slide--active');
            slides[current].setAttribute('aria-hidden', 'false');
            dots[current] && dots[current].classList.add('hero__dot--active');
        }

        function startAutoplay() {
            if (!autoplay) return;
            stopAutoplay();
            timer = setInterval(function () { go(current + 1); }, autoplay);
        }

        function stopAutoplay() {
            if (timer) { clearInterval(timer); timer = null; }
        }

        prev && prev.addEventListener('click', function () { go(current - 1); startAutoplay(); });
        next && next.addEventListener('click', function () { go(current + 1); startAutoplay(); });

        dots.forEach(function (dot, i) {
            dot.addEventListener('click', function () { go(i); startAutoplay(); });
        });

        // Pause al hover/focus
        root.addEventListener('mouseenter', stopAutoplay);
        root.addEventListener('mouseleave', startAutoplay);
        root.addEventListener('focusin', stopAutoplay);
        root.addEventListener('focusout', startAutoplay);

        // Pause cuando la pestaña no está visible
        document.addEventListener('visibilitychange', function () {
            if (document.hidden) stopAutoplay(); else startAutoplay();
        });

        // Touch swipe básico
        var touchStart = 0;
        root.addEventListener('touchstart', function (e) {
            touchStart = e.touches[0].clientX;
            stopAutoplay();
        }, { passive: true });
        root.addEventListener('touchend', function (e) {
            var delta = e.changedTouches[0].clientX - touchStart;
            if (Math.abs(delta) > 50) go(current + (delta < 0 ? 1 : -1));
            startAutoplay();
        }, { passive: true });

        startAutoplay();
    }

    function init() {
        document.querySelectorAll('[data-slider]').forEach(initSlider);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
