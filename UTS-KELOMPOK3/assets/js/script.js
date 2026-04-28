/* PAK CARIK — Main JS */
(function () {
  'use strict';

  // ── Header scroll ──
  const header = document.querySelector('.site-header');
  function onScroll() {
    if (!header) return;
    header.classList.toggle('scrolled', window.scrollY > 30);
  }
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // ── Mobile nav toggle ──
  const toggle = document.querySelector('.nav-toggle');
  const navList = document.querySelector('.nav-list');
  if (toggle && navList) {
    toggle.addEventListener('click', () => navList.classList.toggle('open'));
    document.querySelectorAll('.nav-list a').forEach(a =>
      a.addEventListener('click', () => navList.classList.remove('open'))
    );
    document.addEventListener('click', e => {
      if (!toggle.contains(e.target) && !navList.contains(e.target))
        navList.classList.remove('open');
    });
  }

  // ── Active nav on scroll ──
  const sections = document.querySelectorAll('section[id], div[id]');
  const navLinks = document.querySelectorAll('.nav-list a[href^="#"]');
  function updateActive() {
    let current = '';
    sections.forEach(s => {
      if (window.scrollY >= s.offsetTop - 140) current = s.id;
    });
    navLinks.forEach(a => {
      a.classList.toggle('active', a.getAttribute('href') === '#' + current);
    });
  }
  window.addEventListener('scroll', updateActive, { passive: true });

  // ── Fade-up on scroll (IntersectionObserver) ──
  const fadeEls = document.querySelectorAll('.fade-up-on-scroll');
  if ('IntersectionObserver' in window && fadeEls.length) {
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('fade-up');
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.12 });
    fadeEls.forEach(el => obs.observe(el));
  }

  // ── Print function ──
  window.printTable = function () { window.print(); };

  // ── Search table ──
  window.searchTable = function (inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;
    input.addEventListener('keyup', function () {
      const val = this.value.toLowerCase();
      table.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
      });
    });
  };

  // ── Animated counter ──
  function animateCount(el, target) {
    let start = 0;
    const step = Math.ceil(target / 60);
    const timer = setInterval(() => {
      start += step;
      if (start >= target) { el.textContent = target + '+'; clearInterval(timer); }
      else el.textContent = start + '+';
    }, 24);
  }
  const counters = document.querySelectorAll('[data-count]');
  if (counters.length) {
    const obs2 = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          animateCount(e.target, parseInt(e.target.dataset.count));
          obs2.unobserve(e.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(c => obs2.observe(c));
  }

})();
