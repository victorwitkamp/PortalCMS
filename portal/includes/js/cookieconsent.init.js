window.addEventListener('load', function () {
  window.cookieconsent.initialise({
    palette: {
      popup: { background: '#252e39' },
      button: { background: 'transparent', text: '#14a7d0', border: '#14a7d0' }
    },
    position: 'bottom',
    static: !0,
    container: document.getElementById('container')
  })
});
