'use strict';
const previewOrigin = parent.location.origin;

// Visual In-Place Editor Engine
document.addEventListener('DOMContentLoaded', () => {
  // Prevent standard link navigation, open button popover on button click
  document.addEventListener('click', e => {
    const a = e.target.closest('a, button');
    if (!a) return;

    // If clicking a product card or link inside catalogue preview, navigate parent to edit that product
    const productCard = a.closest('.product-card');
    if (productCard && productCard.dataset.id && document.querySelector('.catalogue-layout')) {
      e.preventDefault();
      e.stopPropagation();
      parent.postMessage({
        type: 'visual-navigate-product',
        id: productCard.dataset.id
      }, previewOrigin);
      return;
    }

    // Do NOT intercept dossier tabs, tab buttons, accordion summaries, swiper buttons, gallery thumbs, or custom editor controls
    if (
      a.closest('.dossier-tabs-nav') ||
      a.matches('[role="tab"]') ||
      a.hasAttribute('data-tab') ||
      a.closest('summary') ||
      a.closest('details') ||
      a.closest('.gallery-thumbs') ||
      a.closest('.gallery-thumb') ||
      a.closest('.swiper-nav-btn') ||
      a.closest('.visual-button-popover') ||
      a.closest('.visual-card-actions') ||
      a.closest('.visual-img-btn-row') ||
      a.classList.contains('visual-add-card-btn') ||
      a.classList.contains('testimonial-nav-btn') ||
      a.closest('.spec-row-delete-btn') ||
      a.matches('#canvasAddSpecRowBtn') ||
      a.closest('#canvasAddSpecRowBtn')
    ) {
      return;
    }

    // Inside related products section, product cards are purely dynamic: DO NOT open button popover
    if (a.closest('.product-related-section')) {
      e.preventDefault();
      e.stopPropagation();
      return;
    }

    if (!a.closest('.visual-button-popover') && !a.closest('.visual-card-actions') && !a.closest('.visual-img-btn-row') && !a.classList.contains('visual-add-card-btn') && !a.classList.contains('testimonial-nav-btn')) {
      e.preventDefault();
      e.stopPropagation();
      if (a.matches('.btn, .text-link, .hero__actions a, .cta-spotlight-card__actions a') || a.dataset.buttonField || a.dataset.preview === 'button_label' || a.dataset.preview === 'secondary_label') {
        openButtonPopover(a);
      }
    }
  }, true);

  // 1. Interactive Floating Button & Link Popover
  let currentTargetButton = null;
  const popover = document.createElement('div');
  popover.className = 'visual-button-popover';
  popover.id = 'visualButtonPopover';
  popover.innerHTML = `
    <div class="visual-popover-header">
      <span><i class="ri-links-line"></i> Edit Button &amp; Link</span>
      <button type="button" class="visual-popover-close" id="popoverCloseBtn" aria-label="Close">&times;</button>
    </div>
    <div class="visual-popover-body">
      <div class="visual-popover-field">
        <label for="popoverBtnText">Button Label / Text:</label>
        <input type="text" id="popoverBtnText" class="visual-popover-input" placeholder="e.g. Explore products">
      </div>
      <div class="visual-popover-field">
        <label for="popoverBtnUrl">Button Link / URL:</label>
        <input type="text" id="popoverBtnUrl" class="visual-popover-input" placeholder="e.g. products, contact, or https://...">
        <div class="visual-quick-links">
          <span class="quick-chip" data-url="products">Products</span>
          <span class="quick-chip" data-url="contact">Contact</span>
          <span class="quick-chip" data-url="about">About</span>
          <span class="quick-chip" data-url="catalogue">Catalogue</span>
          <span class="quick-chip" data-url="https://wa.me/919837050000">WhatsApp</span>
          <span class="quick-chip" data-url="tel:+919837050000">Call Desk</span>
        </div>
      </div>
      <div class="visual-popover-actions">
        <button type="button" class="btn-popover-apply" id="popoverApplyBtn">Apply Changes</button>
      </div>
    </div>
  `;
  document.body.appendChild(popover);

  const popoverText = popover.querySelector('#popoverBtnText');
  const popoverUrl = popover.querySelector('#popoverBtnUrl');
  const popoverClose = popover.querySelector('#popoverCloseBtn');
  const popoverApply = popover.querySelector('#popoverApplyBtn');

  const closeButtonPopover = () => {
    popover.style.display = 'none';
    if (currentTargetButton) currentTargetButton.classList.remove('is-button-selected');
    currentTargetButton = null;
  };

  popoverClose.addEventListener('click', e => {
    e.preventDefault();
    e.stopPropagation();
    closeButtonPopover();
  });

  const getCleanButtonText = el => {
    const clone = el.cloneNode(true);
    clone.querySelectorAll('i, svg, .ri-arrow-right-up-line, .ri-arrow-right-line').forEach(icon => icon.remove());
    return clone.innerText.trim();
  };

  const openButtonPopover = btn => {
    if (currentTargetButton) currentTargetButton.classList.remove('is-button-selected');
    currentTargetButton = btn;
    btn.classList.add('is-button-selected');

    const currentText = getCleanButtonText(btn);
    const rawHref = btn.getAttribute('href') || btn.dataset.buttonUrl || '';
    let currentUrl = rawHref;
    if (currentUrl.startsWith('/shreejigas/')) currentUrl = currentUrl.replace('/shreejigas/', '');
    if (currentUrl.startsWith('/')) currentUrl = currentUrl.slice(1);

    popoverText.value = currentText;
    popoverUrl.value = currentUrl;

    // Position popover near button
    const rect = btn.getBoundingClientRect();
    const scrollY = window.scrollY || document.documentElement.scrollTop;
    const scrollX = window.scrollX || document.documentElement.scrollLeft;
    
    let top = rect.bottom + scrollY + 10;
    let left = rect.left + scrollX;
    
    // Boundary check
    if (left + 320 > window.innerWidth) left = Math.max(10, window.innerWidth - 330);
    if (top + 240 > document.body.scrollHeight) top = Math.max(10, rect.top + scrollY - 250);

    popover.style.top = top + 'px';
    popover.style.left = left + 'px';
    popover.style.display = 'block';
    popoverText.focus();
  };

  // Quick link chips
  popover.querySelectorAll('.quick-chip').forEach(chip => {
    chip.addEventListener('click', e => {
      e.preventDefault();
      popoverUrl.value = chip.dataset.url;
    });
  });

  // Apply Changes
  popoverApply.addEventListener('click', e => {
    e.preventDefault();
    e.stopPropagation();
    if (!currentTargetButton) return;

    const newLabel = popoverText.value.trim();
    const newUrl = popoverUrl.value.trim();

    // Determine field mapping
    let labelField = currentTargetButton.dataset.buttonField || currentTargetButton.dataset.preview;
    let urlField = currentTargetButton.dataset.urlField;

    if (!labelField) {
      if (currentTargetButton.classList.contains('btn-outline') || currentTargetButton.classList.contains('btn-secondary')) {
        labelField = 'secondary_label';
      } else {
        labelField = 'button_label';
      }
    }
    if (!urlField) {
      urlField = labelField === 'secondary_label' ? 'secondary_url' : 'button_url';
    }

    // Update DOM element directly
    const iconEl = currentTargetButton.querySelector('i');
    if (iconEl) {
      currentTargetButton.innerHTML = newLabel + ' ' + iconEl.outerHTML;
    } else {
      currentTargetButton.textContent = newLabel;
    }
    currentTargetButton.setAttribute('href', newUrl || '#');
    currentTargetButton.dataset.buttonUrl = newUrl;

    // Send edit message to parent
    parent.postMessage({
      type: 'visual-button-edit',
      labelField: labelField,
      urlField: urlField,
      labelValue: newLabel,
      urlValue: newUrl
    }, previewOrigin);

    closeButtonPopover();
  });

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && popover.style.display === 'block') closeButtonPopover();
  });

  // 2. Setup in-place direct editing on all text elements
  const makeEditable = (el, fieldName) => {
    if (!el || el.dataset.visualActive) return;

    // Related product cards are dynamic: do not make cards or items inside swiper editable
    if (el.closest('.product-related-section .product-card') || el.closest('.product-related-section .swiper-wrapper')) {
      return;
    }

    el.dataset.visualActive = 'true';
    el.setAttribute('contenteditable', 'true');
    el.setAttribute('spellcheck', 'false');
    el.classList.add('visual-inline-editable');
    if (fieldName) el.dataset.previewField = fieldName;

    el.addEventListener('focus', () => el.classList.add('is-editing'));
    el.addEventListener('blur', () => el.classList.remove('is-editing'));

    el.addEventListener('input', () => {
      // 1. Technical specs row edit (key-value)
      if (el.dataset.specKey) {
        parent.postMessage({
          type: 'visual-spec-edit',
          key: el.dataset.specKey,
          value: el.innerText.trim()
        }, previewOrigin);
        return;
      }

      // 2. Operational Capability Matrix cards (features bullets)
      if (el.hasAttribute('data-cap-field')) {
        const matrix = el.closest('[data-features-matrix]');
        if (matrix) {
          const cards = matrix.querySelectorAll('.dossier-highlight-card');
          const bullets = [];
          cards.forEach(c => {
            const title = (c.querySelector('[data-cap-field="title"]')?.innerText || '').trim();
            const desc = (c.querySelector('[data-cap-field="desc"]')?.innerText || '').trim();
            if (title || desc) {
              bullets.push(`<li><strong>${title}:</strong> ${desc}</li>`);
            }
          });
          const html = bullets.length ? `<ul>${bullets.join('')}</ul>` : '';
          parent.postMessage({
            type: 'visual-field-edit',
            field: 'features',
            value: html
          }, previewOrigin);
          return;
        }
      }

      // 3. Product Specific FAQs (json array)
      if (el.hasAttribute('data-faq-field')) {
        const faqList = el.closest('[data-faq-list]');
        if (faqList) {
          const items = faqList.querySelectorAll('.product-faq-item');
          const faqs = [];
          items.forEach(item => {
            const q = (item.querySelector('[data-faq-field="q"]')?.innerText || '').trim();
            const a = (item.querySelector('[data-faq-field="a"]')?.innerText || '').trim();
            if (q || a) {
              faqs.push({ q, a });
            }
          });
          parent.postMessage({
            type: 'visual-field-edit',
            field: 'faqs',
            value: JSON.stringify(faqs)
          }, previewOrigin);
          return;
        }
      }

      // 4. General field edits
      const field = el.dataset.previewField || el.dataset.preview || getInferredField(el);
      if (field) {
        const val = (field === 'content' || field === 'features' || field === 'applications') ? el.innerHTML : el.innerText.trim();
        // Sync matching preview elements across DOM (e.g. mfc_date, exp_date in strip and table)
        if (['mfc_date', 'exp_date', 'badge_label', 'name', 'heading'].includes(field)) {
          document.querySelectorAll(`[data-preview="${field}"]`).forEach(other => {
            if (other !== el) other.innerText = val;
          });
        }
        parent.postMessage({
          type: 'visual-field-edit',
          field: field,
          value: val
        }, previewOrigin);
      }
    });

    el.addEventListener('keydown', e => {
      if (e.key === 'Enter' && ['heading', 'accent', 'eyebrow', 'badge', 'button_label', 'secondary_label', 'title', 'name', 'badge_label', 'mfc_date', 'exp_date', 'catalogue_eyebrow', 'catalogue_title', 'catalogue_guidance_title', 'catalogue_guidance_btn', 'spec'].includes(fieldName)) {
        e.preventDefault();
      }
    });
  };

  const getInferredField = el => {
    if (el.dataset.preview) return el.dataset.preview;
    if (el.tagName === 'H1' || el.tagName === 'H2') return 'heading';
    if (el.classList.contains('eyebrow') || el.classList.contains('hero__eyebrow')) return 'eyebrow';
    if (el.tagName === 'P' && el.parentElement?.classList.contains('hero__copy')) return 'body';
    if (el.tagName === 'P' && el.parentElement?.classList.contains('section-heading')) return 'body';
    if (el.tagName === 'P' && el.parentElement?.classList.contains('why-copy')) return 'body';
    if (el.dataset.previewField) return el.dataset.previewField;
    return null;
  };

  // Prevent summary click toggle when clicking editable FAQ text
  document.querySelectorAll('[data-faq-field="q"], .faq-question').forEach(el => {
    el.addEventListener('click', e => {
      e.stopPropagation();
    });
  });

  // Target all core section headings, text, and previewable fields
  document.querySelectorAll('[data-preview]').forEach(el => makeEditable(el, el.dataset.preview));
  document.querySelectorAll('[data-spec-key]').forEach(el => makeEditable(el, 'spec'));
  document.querySelectorAll('[data-cap-field]').forEach(el => makeEditable(el, 'cap'));
  document.querySelectorAll('[data-faq-field]').forEach(el => makeEditable(el, 'faq'));
  document.querySelectorAll('h1, h2, h3, h4, .eyebrow, .hero__eyebrow, .hero__copy > p, .section-heading > p, .why-copy > p, .cta-spotlight-card__title, .cta-spotlight-card__desc, .contact-strip__heading h2, .contact-strip__heading p, .journey-heading h2, .journey-heading p, .planning-heading h2, .planning-heading p, .product-guarantee-item strong, .product-guarantee-item small, .dispatch-callout__text strong, .dispatch-callout__text p, .dossier-panel__header h3, .dossier-panel__header p, .dossier-highlight-card h4, .dossier-highlight-card p, .deployment-card__title, .deployment-card__desc, .safety-rule-card__title, .safety-rule-card__desc, .faq-intro h2, .faq-intro p, .product-related-section h2, .product-related-section p, .dossier-sidebar-card strong, .dossier-meta-list li .meta-value, .dossier-meta-list li .meta-label').forEach(el => {
    makeEditable(el, getInferredField(el));
  });

  // 3. Setup interactive repeater card editing (titles, bodies, move, delete, add)
  const setupRepeaterItems = () => {
    const cardSelectors = [
      { container: '.feature-list', items: '.feature-list .feature-row' },
      { container: '.journey-steps', items: '.journey-steps > li' },
      { container: '.planning-cards', items: '.planning-cards > article' },
      { container: '.stats-grid', items: '.stats-grid .stat' },
      { container: '.hero__trust', items: '.hero__trust > div' },
      { container: '.home-section .faq-list', items: '.home-section .faq-list > details' }
    ];

    cardSelectors.forEach(({ container, items }) => {
      const parentList = document.querySelector(container);
      if (!parentList) return;

      const cardNodes = parentList.querySelectorAll(items);
      cardNodes.forEach((card, index) => {
        card.classList.add('visual-card-item');

        // Make title and body inside card in-place editable
        const titleEl = card.querySelector('h3, strong, .faq-question, span > strong');
        const bodyEl = card.querySelector('p, small, dd, span > small, span > span');

        if (titleEl) {
          titleEl.setAttribute('contenteditable', 'true');
          titleEl.setAttribute('spellcheck', 'false');
          titleEl.classList.add('visual-card-title');
          titleEl.addEventListener('input', () => {
            parent.postMessage({
              type: 'visual-card-edit',
              index: index,
              key: 'title',
              value: titleEl.innerText.trim()
            }, previewOrigin);
          });
        }

        if (bodyEl) {
          bodyEl.setAttribute('contenteditable', 'true');
          bodyEl.setAttribute('spellcheck', 'false');
          bodyEl.classList.add('visual-card-body');
          bodyEl.addEventListener('input', () => {
            parent.postMessage({
              type: 'visual-card-edit',
              index: index,
              key: 'body',
              value: bodyEl.innerText.trim()
            }, previewOrigin);
          });
        }

        // Add visual card hover action controls
        if (!card.querySelector('.visual-card-actions')) {
          const actionWrap = document.createElement('div');
          actionWrap.className = 'visual-card-actions';

          const moveUp = document.createElement('button');
          moveUp.type = 'button';
          moveUp.title = 'Move up';
          moveUp.innerHTML = '↑';
          moveUp.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            if (index > 0) parent.postMessage({ type: 'visual-card-move', from: index, to: index - 1 }, previewOrigin);
          });

          const moveDown = document.createElement('button');
          moveDown.type = 'button';
          moveDown.title = 'Move down';
          moveDown.innerHTML = '↓';
          moveDown.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            if (index < cardNodes.length - 1) parent.postMessage({ type: 'visual-card-move', from: index, to: index + 1 }, previewOrigin);
          });

          const removeBtn = document.createElement('button');
          removeBtn.type = 'button';
          removeBtn.className = 'btn-card-delete';
          removeBtn.title = 'Remove item';
          removeBtn.innerHTML = '×';
          removeBtn.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            parent.postMessage({ type: 'visual-card-delete', index: index }, previewOrigin);
          });

          actionWrap.append(moveUp, moveDown, removeBtn);
          card.appendChild(actionWrap);
        }
      });

      // Add visual "+ Add new item" button cleanly wrapped below container
      const containerParent = parentList.parentElement || parentList;
      if (!containerParent.querySelector('.visual-add-card-wrap')) {
        const wrap = document.createElement('div');
        wrap.className = 'visual-add-card-wrap';
        const addBtn = document.createElement('button');
        addBtn.type = 'button';
        addBtn.className = 'visual-add-card-btn';
        addBtn.innerHTML = '<i class="ri-add-line"></i> + Add new item to this section';
        addBtn.addEventListener('click', e => {
          e.preventDefault();
          e.stopPropagation();
          parent.postMessage({ type: 'visual-card-add' }, previewOrigin);
        });
        wrap.appendChild(addBtn);
        containerParent.appendChild(wrap);
      }
    });
  };

  setupRepeaterItems();

  // 3b. Setup interactive Technical Specifications table (In-place edit name/value/tag, delete row, add row)
  const setupSpecsTable = () => {
    // 1. Parameter Name editing [data-spec-param-name]
    document.querySelectorAll('[data-spec-param-name]').forEach(nameEl => {
      if (nameEl.dataset.specNameActive) return;
      nameEl.dataset.specNameActive = 'true';
      nameEl.setAttribute('contenteditable', 'true');
      nameEl.setAttribute('spellcheck', 'false');
      nameEl.classList.add('visual-inline-editable');

      let initialKey = '';
      nameEl.addEventListener('focus', () => {
        initialKey = nameEl.innerText.trim();
        nameEl.classList.add('is-editing');
      });

      nameEl.addEventListener('blur', () => {
        nameEl.classList.remove('is-editing');
        const newKey = nameEl.innerText.trim();
        if (newKey && newKey !== initialKey) {
          const row = nameEl.closest('tr');
          if (row) {
            row.dataset.specRow = newKey;
            const valEl = row.querySelector('.spec-data-table__val strong');
            if (valEl) valEl.dataset.specKey = newKey;
            const tagEl = row.querySelector('[data-spec-tag-name]');
            if (tagEl) {
              tagEl.dataset.specTagName = newKey;
              tagEl.dataset.specKey = 'Tag_' + newKey;
            }
            const delBtn = row.querySelector('[data-del-spec]');
            if (delBtn) delBtn.dataset.delSpec = newKey;

            const val = valEl ? valEl.innerText.trim() : '';
            nameEl.dataset.specParamName = newKey;
            parent.postMessage({
              type: 'visual-spec-rename',
              oldKey: initialKey,
              newKey: newKey,
              value: val
            }, previewOrigin);
          }
        }
      });

      nameEl.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
          e.preventDefault();
          nameEl.blur();
        }
      });
    });

    // 2. Delete Row buttons
    document.querySelectorAll('.spec-row-delete-btn').forEach(btn => {
      if (btn.dataset.delActive) return;
      btn.dataset.delActive = 'true';
      btn.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();
        const row = btn.closest('tr');
        const key = btn.dataset.delSpec || (row ? row.dataset.specRow : '');
        if (row) row.remove();
        if (key) {
          parent.postMessage({
            type: 'visual-spec-delete',
            key: key
          }, previewOrigin);
        }
      });
    });

    // 3. Add Parameter Row button
    const addRowBtn = document.getElementById('canvasAddSpecRowBtn');
    if (addRowBtn && !addRowBtn.dataset.addActive) {
      addRowBtn.dataset.addActive = 'true';
      addRowBtn.addEventListener('click', e => {
        e.preventDefault();
        e.stopPropagation();

        const tbody = document.querySelector('.spec-data-table tbody');
        if (!tbody) return;

        const currentRows = tbody.querySelectorAll('.spec-data-row').length;
        const newKey = 'New Parameter ' + (currentRows + 1);
        const newVal = 'Standard Specification';
        const newTag = 'Verified Quality Standard';

        const tr = document.createElement('tr');
        tr.className = 'spec-data-row';
        tr.dataset.specRow = newKey;
        tr.innerHTML = `
          <th scope="row" class="spec-data-table__key">
            <span class="spec-icon-badge"><i class="ri-checkbox-circle-line" aria-hidden="true"></i></span>
            <span class="spec-param-name" data-spec-param-name="${newKey}" title="Click to edit parameter name">${newKey}</span>
          </th>
          <td class="spec-data-table__val">
            <strong data-spec-key="${newKey}" title="Click to edit specification">${newVal}</strong>
          </td>
          <td class="spec-data-table__std">
            <span class="compliance-tag compliance-tag--std" data-spec-key="Tag_${newKey}" data-spec-tag-name="${newKey}" title="Click to edit compliance standard">${newTag}</span>
            <button type="button" class="spec-row-delete-btn" data-del-spec="${newKey}" title="Delete parameter" aria-label="Delete parameter">×</button>
          </td>
        `;
        tbody.appendChild(tr);

        // Make value and tag editable
        const strongEl = tr.querySelector('strong');
        const tagSpan = tr.querySelector('.compliance-tag');
        if (strongEl) makeEditable(strongEl, 'spec');
        if (tagSpan) makeEditable(tagSpan, 'spec');

        // Setup the new row's name edit and delete button
        setupSpecsTable();

        // Focus new parameter name so user can immediately type
        const newNameEl = tr.querySelector('.spec-param-name');
        if (newNameEl) {
          newNameEl.focus();
          try {
            const range = document.createRange();
            range.selectNodeContents(newNameEl);
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
          } catch(err) {}
        }

        parent.postMessage({
          type: 'visual-spec-add',
          key: newKey,
          value: newVal
        }, previewOrigin);
      });
    }
  };

  setupSpecsTable();

  // 4. Testimonials Swiper & In-Place Add Button
  if (window.Swiper) {
    document.querySelectorAll('.testimonial-swiper').forEach(el => {
      new Swiper(el, {
        slidesPerView: 1,
        autoHeight: true,
        navigation: {
          nextEl: el.querySelector('.testimonial-next'),
          prevEl: el.querySelector('.testimonial-prev')
        },
        pagination: {
          el: el.querySelector('.swiper-pagination'),
          clickable: true
        }
      });
    });

    document.querySelectorAll('.product-swiper').forEach(el => {
      new Swiper(el, {
        slidesPerView: 3,
        spaceBetween: 24,
        pagination: {
          el: el.querySelector('.swiper-pagination'),
          clickable: true
        }
      });
    });
  }

  // Testimonials in-place adding button inside section
  const testimonialSec = document.querySelector('.testimonial-section');
  if (testimonialSec && !testimonialSec.querySelector('.visual-add-testimonial-btn')) {
    const tWrap = document.createElement('div');
    tWrap.className = 'visual-add-card-wrap';
    const addTBtn = document.createElement('button');
    addTBtn.type = 'button';
    addTBtn.className = 'visual-add-card-btn visual-add-testimonial-btn';
    addTBtn.innerHTML = '<i class="ri-chat-quote-line"></i> + Add new testimonial to slider';
    addTBtn.addEventListener('click', e => {
      e.preventDefault();
      e.stopPropagation();
      parent.postMessage({ type: 'open-testimonial-modal' }, previewOrigin);
    });
    tWrap.appendChild(addTBtn);
    testimonialSec.appendChild(tWrap);
  }

  // 5. Setup interactive image overlays (Drag & Drop, upload, remove)
  document.querySelectorAll('.hero__image, .why-image img, .planning-image img, .cta-facility-showcase img, .company-story-photo img').forEach(img => {
    const parentFrame = img.parentElement;
    if (!parentFrame || parentFrame.querySelector('.visual-image-overlay')) return;

    parentFrame.classList.add('visual-image-container');
    const overlay = document.createElement('div');
    overlay.className = 'visual-image-overlay';
    overlay.innerHTML = `
      <div class="visual-image-prompt">
        <span>Drag &amp; drop image here</span>
        <div class="visual-img-btn-row">
          <button type="button" class="btn-img-action btn-img-upload">Upload / Choose</button>
          <button type="button" class="btn-img-action btn-img-delete">Clear</button>
        </div>
      </div>
    `;

    overlay.querySelector('.btn-img-upload')?.addEventListener('click', e => {
      e.preventDefault();
      e.stopPropagation();
      parent.postMessage({ type: 'visual-open-media-picker' }, previewOrigin);
    });

    overlay.querySelector('.btn-img-delete')?.addEventListener('click', e => {
      e.preventDefault();
      e.stopPropagation();
      parent.postMessage({ type: 'visual-image-delete' }, previewOrigin);
    });

    parentFrame.addEventListener('dragover', e => {
      e.preventDefault();
      overlay.classList.add('is-dragover');
    });
    parentFrame.addEventListener('dragleave', () => overlay.classList.remove('is-dragover'));
    parentFrame.addEventListener('drop', e => {
      e.preventDefault();
      overlay.classList.remove('is-dragover');
      const file = e.dataTransfer.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = ev => {
          parent.postMessage({
            type: 'visual-image-dropped',
            fileName: file.name,
            fileType: file.type,
            fileData: ev.target.result
          }, previewOrigin);
        };
        reader.readAsDataURL(file);
      }
    });

    parentFrame.appendChild(overlay);
  });
});

// Real-time height reporting with ResizeObserver
const reportHeight = () => {
  if (document.body) {
    const section = document.querySelector('.home-section');
    let contentHeight = 180;
    if (section) {
      contentHeight = Math.ceil(Math.max(section.scrollHeight, section.offsetHeight, section.getBoundingClientRect().height));
    } else {
      contentHeight = Math.ceil(Math.max(document.body.scrollHeight, document.body.offsetHeight));
    }
    const height = Math.max(contentHeight, 160);
    parent.postMessage({ type: 'preview-height', height: height }, '*');
  }
};

window.reportHeight = reportHeight;
window.addEventListener('load', reportHeight);
if (window.ResizeObserver) {
  new ResizeObserver(reportHeight).observe(document.body);
}

// 6. Background Theme and Dynamic Color Harmonization Listener
window.addEventListener('message', event => {
  if (event.origin !== previewOrigin && event.origin !== location.origin) return;
  const data = event.data;
  if (data && data.type === 'visual-bg-change') {
    const section = document.querySelector('.home-section');
    const color = data.color || '';
    const fallbackBg = data.isDark ? '#1b3d6f' : '#ffffff';
    const bgToApply = color || fallbackBg;
    if (section) {
      section.style.backgroundColor = color;
      section.classList.toggle('section-dark', Boolean(data.isDark));
      section.classList.toggle('section-light', !data.isDark);
    }
    document.body.style.backgroundColor = bgToApply;
    document.documentElement.style.backgroundColor = bgToApply;
    reportHeight();
  }

  // Smooth in-page section scrolling
  if (data && data.type === 'scroll-to-section') {
    const targetEl = document.querySelector(data.selector);
    if (targetEl) {
      targetEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  // Live Canvas Image Update from Media Library
  if (data && data.type === 'visual-image-update') {
    const img = document.querySelector('[data-preview="' + data.field + '"]') || document.querySelector('[data-field="' + data.field + '"] img') || document.getElementById('mainProductImage');
    if (img && data.src) {
      let src = data.src;
      if (src.startsWith('assets/')) src = '/shreejigas/' + src;
      img.src = src;
    }
  }
});
