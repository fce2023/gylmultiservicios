const whatsappButtonId = 'whatsapp-btn';
const whatsappOptionsId = 'whatsapp-options';
const tipoDocId = 'tipo_doc';
const documentoId = 'documento';
const telefonoId = 'telefono';

document.addEventListener('DOMContentLoaded', () => {
  initSwiper();
  initWhatsappToggle();
  initDocumentValidation();
  initPhoneSanitizer();
});

function initSwiper() {
  if (typeof Swiper === 'undefined') return;

  new Swiper('.mySwiper', {
    slidesPerView: 3,
    spaceBetween: 20,
    loop: true,
    autoplay: { delay: 2500, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    breakpoints: { 0: { slidesPerView: 1 }, 768: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
  });
}

function initWhatsappToggle() {
  const button = document.getElementById(whatsappButtonId);
  const options = document.getElementById(whatsappOptionsId);

  if (!button || !options) return;

  const toggle = (show) => {
    if (show) {
      options.classList.add('show');
      options.setAttribute('aria-hidden', 'false');
      button.setAttribute('aria-expanded', 'true');
    } else {
      options.classList.remove('show');
      options.setAttribute('aria-hidden', 'true');
      button.setAttribute('aria-expanded', 'false');
    }
  };

  button.addEventListener('click', (event) => {
    event.stopPropagation();
    toggle(!options.classList.contains('show'));
  });

  button.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' || event.key === ' ') {
      event.preventDefault();
      toggle(!options.classList.contains('show'));
    }
  });

  document.addEventListener('click', (event) => {
    if (!options.contains(event.target) && !button.contains(event.target)) toggle(false);
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') toggle(false);
  });
}

function initDocumentValidation() {
  const tipoDoc = document.getElementById(tipoDocId);
  const documento = document.getElementById(documentoId);

  if (!tipoDoc || !documento) return;

  const updateValidation = () => {
    const tipo = tipoDoc.value;
    documento.value = '';
    documento.disabled = !tipo;
    documento.maxLength = tipo === 'dni' ? 8 : tipo === 'ruc' ? 11 : '';
    documento.placeholder = tipo === 'dni'
      ? 'Ingrese su DNI (8 dígitos)'
      : tipo === 'ruc'
        ? 'Ingrese su RUC (11 dígitos)'
        : 'Ingrese documento';
    documento.style.borderColor = '';
  };

  const validateDocumento = () => {
    const tipo = tipoDoc.value;
    let valor = documento.value || '';
    valor = valor.replace(/\D/g, '');
    documento.value = valor;

    const ok = (tipo === 'dni' && valor.length === 8) || (tipo === 'ruc' && valor.length === 11);
    documento.style.borderColor = ok ? 'green' : (valor.length === 0 ? '' : 'red');
  };

  tipoDoc.addEventListener('change', updateValidation);
  documento.addEventListener('input', validateDocumento);
}

function initPhoneSanitizer() {
  const telefono = document.getElementById(telefonoId);
  if (!telefono) return;

  telefono.addEventListener('input', () => {
    telefono.value = telefono.value.replace(/\D/g, '');
  });
}
