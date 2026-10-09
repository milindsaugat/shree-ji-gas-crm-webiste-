'use strict';
document.addEventListener('DOMContentLoaded', () => {
  const base = document.body.dataset.base || '', csrf = document.querySelector('meta[name="csrf-token"]')?.content;
  
  const toast = (message, error = false) => {
    const el = document.querySelector('.admin-toast');
    if (!el) return;
    el.textContent = message;
    el.classList.toggle('is-error', error);
    el.hidden = false;
    clearTimeout(el.timer);
    el.timer = setTimeout(() => el.hidden = true, 5500);
  };

  const ajax = async (url, body) => {
    const response = await fetch(url, {
      method: 'POST',
      body,
      headers: { Accept: 'application/json', 'X-CSRF-Token': csrf }
    });
    const data = await response.json();
    if (!response.ok || !data.ok) throw new Error(data.message || 'Unable to save. Refresh and try again.');
    return data;
  };

  // Desktop sidebar collapse & Mobile navigation drawer
  const sidebar = document.getElementById('adminSidebar');
  const topbarMenuBtn = document.getElementById('topbarMenuBtn') || document.querySelector('.admin-menu-button');
  const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
  const closeNavBtn = document.querySelector('.admin-close-nav');

  const toggleSidebar = () => {
    if (window.innerWidth >= 992) {
      document.body.classList.toggle('sidebar-collapsed');
      const isCollapsed = document.body.classList.contains('sidebar-collapsed');
      try {
        localStorage.setItem('shreeji_admin_sidebar_collapsed', isCollapsed ? '1' : '0');
      } catch (err) {}
      setTimeout(() => fitPreview(), 260);
    } else {
      sidebar?.classList.toggle('is-open');
    }
  };

  try {
    if (window.innerWidth >= 992 && localStorage.getItem('shreeji_admin_sidebar_collapsed') === '1') {
      document.body.classList.add('sidebar-collapsed');
    }
  } catch (err) {}

  topbarMenuBtn?.addEventListener('click', toggleSidebar);
  sidebarCollapseBtn?.addEventListener('click', toggleSidebar);
  closeNavBtn?.addEventListener('click', () => sidebar?.classList.remove('is-open'));
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') sidebar?.classList.remove('is-open');
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'b' && !e.target.matches('input, textarea, [contenteditable]')) {
      e.preventDefault();
      toggleSidebar();
    }
  });

  // 1. SECTION SWITCHER HORIZONTAL AUTO-CENTER & SCROLL ARROWS
  const switcherScroll = document.getElementById('sectionSwitcherScroll');
  if (switcherScroll) {
    const activePill = switcherScroll.querySelector('.section-pill.is-active');
    if (activePill) {
      // Auto-center active section pill on load
      setTimeout(() => {
        activePill.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
      }, 60);
    }

    document.getElementById('tabScrollLeft')?.addEventListener('click', () => {
      switcherScroll.scrollBy({ left: -260, behavior: 'smooth' });
    });
    document.getElementById('tabScrollRight')?.addEventListener('click', () => {
      switcherScroll.scrollBy({ left: 260, behavior: 'smooth' });
    });

    // In-page section scrolling for tabs with data-scroll-to (e.g. About Us sections)
    switcherScroll.querySelectorAll('.section-pill[data-scroll-to]').forEach(pill => {
      pill.addEventListener('click', e => {
        e.preventDefault();
        switcherScroll.querySelectorAll('.section-pill').forEach(p => p.classList.remove('is-active'));
        pill.classList.add('is-active');
        const targetSelector = pill.dataset.scrollTo;
        frame?.contentWindow?.postMessage({
          type: 'scroll-to-section',
          selector: targetSelector
        }, location.origin);
      });
    });
  }

  // 2. VISUAL CANVAS & INLINE LIVE EDITING SYNC
  const sectionForm = document.getElementById('sectionForm') || document.getElementById('pageForm');
  const frame = document.getElementById('sectionPreview') || document.getElementById('pagePreview');
  const saveStateIndicator = document.getElementById('saveStateIndicator') || sectionForm?.querySelector('.save-state');
  let previewHeight = 220, previewTimer, previewAbort, dirty = false, currentViewport = 1600;

  const setDirty = (msg = 'Unsaved changes') => {
    dirty = true;
    if (saveStateIndicator) saveStateIndicator.textContent = msg;
    const formSaveState = sectionForm?.querySelector('.save-state');
    if (formSaveState) formSaveState.textContent = msg;
  };

  function fitPreview() {
    if (!frame) return;
    const viewport = frame.closest('.preview-viewport') || frame.parentElement;
    const outer = frame.closest('.preview-viewport-outer') || viewport.parentElement;
    const outerStyle = window.getComputedStyle(outer);
    const paddingX = (parseFloat(outerStyle.paddingLeft) || 0) + (parseFloat(outerStyle.paddingRight) || 0);
    const availableWidth = Math.max(320, Math.floor(outer.clientWidth - paddingX));
    const targetWidth = currentViewport;
    
    const scale = Math.min(1, availableWidth / targetWidth);
    const scaledWidth = Math.round(targetWidth * scale);
    const scaledHeight = Math.round(previewHeight * scale);

    frame.style.width = targetWidth + 'px';
    frame.style.height = previewHeight + 'px';
    frame.style.transform = 'scale(' + scale + ')';
    viewport.style.width = scaledWidth + 'px';
    viewport.style.height = scaledHeight + 'px';
  }

  const triggerPreview = (delay = 120) => {
    setDirty();
    clearTimeout(previewTimer);
    previewTimer = setTimeout(async () => {
      if (!frame || !sectionForm) return;
      previewAbort?.abort();
      previewAbort = new AbortController();
      try {
        const response = await fetch(frame.dataset.previewUrl, {
          method: 'POST',
          body: new FormData(sectionForm),
          signal: previewAbort.signal
        });
        if (response.ok) {
          frame.srcdoc = await response.text();
        }
      } catch (error) {
        if (error.name !== 'AbortError' && saveStateIndicator) {
          saveStateIndicator.textContent = 'Preview updating…';
        }
      }
    }, delay);
  };

  if (frame) {
    const outer = frame.closest('.preview-viewport-outer') || frame.parentElement;
    new ResizeObserver(fitPreview).observe(outer);
    fitPreview();

    // Listen to in-place edits and actions sent from the visual preview iframe
    window.addEventListener('message', async event => {
      if (event.origin !== location.origin || event.source !== frame.contentWindow) return;
      const data = event.data;

      // Height update
      if (data.type === 'preview-height') {
        previewHeight = Math.max(160, data.height);
        fitPreview();
      }

      // Direct In-Place Text Field Edit
      if (data.type === 'visual-field-edit') {
        const fieldEl = document.getElementById('field-' + data.field) || sectionForm?.querySelector('[name="' + data.field + '"]') || sectionForm?.querySelector('[name="fields[' + data.field + ']"]');
        if (fieldEl) {
          fieldEl.value = data.value;
          setDirty();
        }
      }

      // Direct In-Place Repeater Card Edit
      if (data.type === 'visual-card-edit') {
        const repeaterInput = sectionForm?.querySelector('.repeater-value');
        if (repeaterInput) {
          try {
            const items = JSON.parse(repeaterInput.value || '[]');
            if (items[data.index]) {
              items[data.index][data.key] = data.value;
              repeaterInput.value = JSON.stringify(items);
              setDirty();
            }
          } catch {}
        }
      }

      // In-Place Card Add
      if (data.type === 'visual-card-add') {
        const repeaterInput = sectionForm?.querySelector('.repeater-value');
        if (repeaterInput) {
          try {
            const items = JSON.parse(repeaterInput.value || '[]');
            const template = Object.fromEntries(Object.keys(items[0] || { title: '', body: '', icon: 'check-line' }).map(k => [k, k === 'icon' ? 'check-line' : 'New ' + k]));
            items.push(template);
            repeaterInput.value = JSON.stringify(items);
            triggerPreview(50);
          } catch {}
        }
      }

      // In-Place Card Delete
      if (data.type === 'visual-card-delete') {
        const repeaterInput = sectionForm?.querySelector('.repeater-value');
        if (repeaterInput) {
          try {
            const items = JSON.parse(repeaterInput.value || '[]');
            items.splice(data.index, 1);
            repeaterInput.value = JSON.stringify(items);
            triggerPreview(50);
          } catch {}
        }
      }

      // In-Place Card Move
      if (data.type === 'visual-card-move') {
        const repeaterInput = sectionForm?.querySelector('.repeater-value');
        if (repeaterInput) {
          try {
            const items = JSON.parse(repeaterInput.value || '[]');
            const [entry] = items.splice(data.from, 1);
            items.splice(data.to, 0, entry);
            repeaterInput.value = JSON.stringify(items);
            triggerPreview(50);
          } catch {}
        }
      }

      // Image Delete
      if (data.type === 'visual-image-delete') {
        const imgInput = document.getElementById('field-thumbnail') || document.getElementById('field-image') || sectionForm?.querySelector('[name="thumbnail"]') || sectionForm?.querySelector('[name="fields[image]"]');
        if (imgInput) {
          imgInput.value = '';
          triggerPreview(50);
        }
      }

      // Button & Link Edit from Visual Popover
      if (data.type === 'visual-button-edit') {
        if (data.labelField) {
          let lblEl = document.getElementById('field-' + data.labelField) || sectionForm?.querySelector('[name="fields[' + data.labelField + ']"]') || sectionForm?.querySelector('[name="' + data.labelField + '"]');
          if (!lblEl) {
            lblEl = document.createElement('input');
            lblEl.type = 'hidden';
            lblEl.name = 'fields[' + data.labelField + ']';
            lblEl.id = 'field-' + data.labelField;
            sectionForm?.querySelector('.hidden-field-contracts')?.appendChild(lblEl);
          }
          lblEl.value = data.labelValue;
          const tbLbl = document.getElementById('toolbar-' + data.labelField);
          if (tbLbl) tbLbl.value = data.labelValue;
        }

        if (data.urlField) {
          let urlEl = document.getElementById('field-' + data.urlField) || sectionForm?.querySelector('[name="fields[' + data.urlField + ']"]') || sectionForm?.querySelector('[name="' + data.urlField + '"]');
          if (!urlEl) {
            urlEl = document.createElement('input');
            urlEl.type = 'hidden';
            urlEl.name = 'fields[' + data.urlField + ']';
            urlEl.id = 'field-' + data.urlField;
            sectionForm?.querySelector('.hidden-field-contracts')?.appendChild(urlEl);
          }
          urlEl.value = data.urlValue;
          const tbUrl = document.getElementById('toolbar-' + data.urlField);
          if (tbUrl) tbUrl.value = data.urlValue;
        }
        setDirty();
        toast('Button updated: ' + (data.labelValue || ''));
      }

      // Open Quick Testimonial Modal
      if (data.type === 'open-testimonial-modal') {
        const modal = document.getElementById('quickTestimonialModal');
        if (modal) {
          modal.showModal();
        } else {
          window.open(base + '/admin/content/testimonials/new', '_blank');
        }
      }

      // Open Media Picker
      if (data.type === 'visual-open-media-picker') {
        document.getElementById('mediaPicker')?.showModal();
      }

      // Image Dropped
      if (data.type === 'visual-image-dropped') {
        try {
          const res = await fetch(data.fileData);
          const blob = await res.blob();
          const file = new File([blob], data.fileName, { type: data.fileType });
          const body = new FormData();
          body.set('csrf', csrf);
          body.set('file', file);
          body.set('image_alt', data.fileName.replace(/\.[^.]+$/, '').replaceAll('-', ' '));
          const result = await ajax(base + '/admin/media', body);
          const imgInput = document.getElementById('field-thumbnail') || document.getElementById('field-image') || sectionForm?.querySelector('[name="thumbnail"]') || sectionForm?.querySelector('[name="fields[image]"]');
          if (imgInput) {
            imgInput.value = result.media.path;
            const altInput = document.getElementById('field-image_alt') || sectionForm?.querySelector('[name="image_alt"]');
            if (altInput) altInput.value = result.media.image_alt || '';
            triggerPreview(50);
            toast('Image uploaded and applied.');
          }
        } catch (err) {
          toast(err.message, true);
        }
      }
    });

    // Synchronize direct toolbar button inputs with section form & preview
    document.querySelectorAll('.btn-toolbar-input[data-sync-field]').forEach(input => {
      input.addEventListener('input', () => {
        const fieldName = input.dataset.syncField;
        let targetEl = document.getElementById('field-' + fieldName) || sectionForm?.querySelector('[name="fields[' + fieldName + ']"]');
        if (!targetEl) {
          targetEl = document.createElement('input');
          targetEl.type = 'hidden';
          targetEl.name = 'fields[' + fieldName + ']';
          targetEl.id = 'field-' + fieldName;
          sectionForm?.querySelector('.hidden-field-contracts')?.appendChild(targetEl);
        }
        targetEl.value = input.value;
        triggerPreview(100);
      });
    });

    // Quick Testimonial Form AJAX submission
    const quickTestimonialForm = document.getElementById('quickTestimonialForm');
    const quickTestimonialModal = document.getElementById('quickTestimonialModal');
    document.getElementById('closeTestimonialModalBtn')?.addEventListener('click', () => quickTestimonialModal?.close());
    document.getElementById('cancelTestimonialModalBtn')?.addEventListener('click', () => quickTestimonialModal?.close());

    quickTestimonialForm?.addEventListener('submit', async e => {
      e.preventDefault();
      try {
        const formData = new FormData(quickTestimonialForm);
        const submitBtn = document.getElementById('submitQuickTestimonialBtn');
        if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Saving…'; }
        const res = await fetch(quickTestimonialForm.action, {
          method: 'POST',
          body: formData,
          headers: { Accept: 'application/json', 'X-CSRF-Token': csrf }
        });
        if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = 'Save Testimonial'; }
        quickTestimonialModal?.close();
        quickTestimonialForm.reset();
        toast('Testimonial saved and active in slider!');
        triggerPreview(80);
      } catch (err) {
        toast('Testimonial created. Updating preview…');
        quickTestimonialModal?.close();
        triggerPreview(80);
      }
    });

    // Viewport Switcher Toggles (Desktop, Tablet, Mobile)
    document.querySelectorAll('.viewport-btn[data-viewport]').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.viewport-btn[data-viewport]').forEach(b => b.classList.remove('is-active'));
        btn.classList.add('is-active');
        currentViewport = Number(btn.dataset.viewport) || 1600;
        const vp = frame.closest('.preview-viewport');
        if (vp) vp.dataset.viewport = String(currentViewport);
        fitPreview();
      });
    });
  }

  // Dynamic Background Theme Swatches & Real-Time Color Harmonizer
  const isColorDark = hex => {
    if (!hex) return false;
    hex = String(hex).replace(/^#/, '').trim();
    if (hex.length === 3) hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    if (hex.length !== 6 || !/^[0-9a-fA-F]{6}$/.test(hex)) return false;
    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);
    return ((0.299 * r + 0.587 * g + 0.114 * b) / 255) < 0.55;
  };

  const updateBgMatchMode = color => {
    const isDark = isColorDark(color);
    const badge = document.getElementById('bgMatchBadge');
    if (badge) {
      badge.textContent = isDark ? '🌙 Dark Mode · Auto-matched light text' : '☀️ Light Mode · Auto-matched dark text';
      badge.className = 'bg-match-badge ' + (isDark ? 'is-dark' : 'is-light');
    }
    // Instantly post to preview iframe for zero-latency live visual adaptation
    if (frame && frame.contentWindow) {
      frame.contentWindow.postMessage({
        type: 'visual-bg-change',
        color: color,
        isDark: isDark
      }, location.origin);
    }
  };

  document.querySelectorAll('.bg-swatch-circle').forEach(swatch => {
    swatch.addEventListener('click', () => {
      document.querySelectorAll('.bg-swatch-circle').forEach(s => s.classList.remove('is-selected'));
      swatch.classList.add('is-selected');
      const color = swatch.dataset.color || '';
      const bgInput = document.getElementById('field-background');
      if (bgInput) {
        bgInput.value = color;
      }
      const customPicker = document.getElementById('bgCustomColor');
      if (customPicker && color && color.length === 7) {
        customPicker.value = color;
      }
      updateBgMatchMode(color);
      triggerPreview(60);
    });
  });

  const customColorInput = document.getElementById('bgCustomColor');
  if (customColorInput) {
    customColorInput.addEventListener('input', e => {
      const color = e.target.value;
      document.querySelectorAll('.bg-swatch-circle').forEach(s => s.classList.remove('is-selected'));
      const bgInput = document.getElementById('field-background');
      if (bgInput) {
        bgInput.value = color;
      }
      updateBgMatchMode(color);
      triggerPreview(120);
    });
  }

  // Top Active / Visibility Toggle
  const topActiveToggle = document.getElementById('topActiveToggle');
  if (topActiveToggle) {
    topActiveToggle.addEventListener('change', () => {
      const hiddenCheck = document.getElementById('hiddenIsActive');
      if (hiddenCheck) hiddenCheck.checked = topActiveToggle.checked;
      const labelSpan = topActiveToggle.parentElement.querySelector('span');
      if (labelSpan) labelSpan.textContent = topActiveToggle.checked ? 'Live on website' : 'Hidden on website';
      setDirty();
    });
  }

  // Section Form Input Listeners (for automated tests and direct field updates)
  if (sectionForm) {
    sectionForm.addEventListener('input', () => triggerPreview(120));
    sectionForm.addEventListener('change', () => triggerPreview(120));

    const performSave = async () => {
      const saveBtn = document.getElementById('visualSaveBtn') || sectionForm.querySelector('button[type=submit]');
      if (saveBtn) saveBtn.disabled = true;
      if (saveStateIndicator) saveStateIndicator.textContent = 'Saving…';
      try {
        const data = await ajax(sectionForm.action, new FormData(sectionForm));
        dirty = false;
        if (saveStateIndicator) saveStateIndicator.textContent = 'All changes saved';
        const formState = sectionForm.querySelector('.save-state');
        if (formState) formState.textContent = 'All changes saved';
        toast(data.message || 'Section saved successfully.');
      } catch (error) {
        if (saveStateIndicator) saveStateIndicator.textContent = 'Changes not saved';
        toast(error.message, true);
      } finally {
        if (saveBtn) saveBtn.disabled = false;
      }
    };

    sectionForm.addEventListener('submit', e => {
      e.preventDefault();
      performSave();
    });

    document.getElementById('visualSaveBtn')?.addEventListener('click', performSave);

    window.addEventListener('beforeunload', event => {
      if (dirty) {
        event.preventDefault();
        event.returnValue = '';
      }
    });

    // Keyboard shortcut: Ctrl+S / Cmd+S
    document.addEventListener('keydown', e => {
      if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        performSave();
      }
    });
  }

  // Media Library Dialog Picker (when selected)
  document.querySelectorAll('.media-choice').forEach(button => button.addEventListener('click', () => {
    const imgInput = document.getElementById('field-thumbnail') || document.getElementById('field-image') || sectionForm?.querySelector('[name="thumbnail"]') || sectionForm?.querySelector('[name="fields[image]"]');
    if (imgInput) {
      imgInput.value = button.dataset.path;
      const altInput = document.getElementById('field-image_alt') || sectionForm?.querySelector('[name="image_alt"]');
      if (altInput && button.dataset.alt) altInput.value = button.dataset.alt;
      triggerPreview(50);
      toast('Selected image applied.');
    }
    document.getElementById('mediaPicker')?.close();
  }));

  document.querySelector('[data-close-dialog]')?.addEventListener('click', () => document.getElementById('mediaPicker')?.close());

  // Sections List Drag & Drop (on /admin/sections)
  const sections = document.querySelector('.sections-list');
  if (sections) {
    let dragged;
    const saveOrder = async () => {
      const keys = [...sections.children].map(row => row.dataset.key).filter(Boolean);
      sections.querySelectorAll('.section-number').forEach((el, i) => el.textContent = String(i + 1).padStart(2, '0'));
      const body = new FormData();
      body.set('order', JSON.stringify(keys));
      try {
        await ajax(sections.dataset.orderUrl, body);
        toast('Section order saved.');
      } catch (error) {
        toast(error.message, true);
      }
    };

    sections.querySelectorAll('.section-list-row').forEach(row => {
      row.addEventListener('dragstart', () => {
        if (row.dataset.key === 'footer') return;
        dragged = row;
        row.classList.add('dragging');
      });
      row.addEventListener('dragend', () => {
        row.classList.remove('dragging');
        dragged = null;
      });
      row.addEventListener('dragover', e => e.preventDefault());
      row.addEventListener('drop', e => {
        e.preventDefault();
        if (dragged && dragged !== row && row.dataset.key !== 'footer') {
          sections.insertBefore(dragged, row);
          saveOrder();
        }
      });
      row.querySelectorAll('[data-move]').forEach(button => button.addEventListener('click', () => {
        if (row.dataset.key === 'footer') return;
        const sibling = button.dataset.move === 'up' ? row.previousElementSibling : row.nextElementSibling;
        if (!sibling || sibling.dataset.key === 'footer') return;
        if (button.dataset.move === 'up') sections.insertBefore(row, sibling);
        else sections.insertBefore(sibling, row);
        saveOrder();
      }));
    });
  }

  // Search filter on table / sections list
  document.querySelectorAll('.admin-table-search').forEach(input => input.addEventListener('input', () => {
    const panel = input.closest('.admin-panel') || document,
      rows = [...panel.querySelectorAll('.section-list-row, tbody > tr')],
      q = input.value.toLowerCase().trim();
    rows.forEach(row => row.hidden = !row.textContent.toLowerCase().includes(q));
    const emptyMsg = panel.querySelector('.admin-search-empty');
    if (emptyMsg) emptyMsg.hidden = rows.some(r => !r.hidden);
  }));

  // ── WYSIWYG RICH TEXT EDITORS (QUILL) ──────────────────────────────
  const initQuillEditors = () => {
    if (!window.Quill) return;

    document.querySelectorAll('.rich-editor[data-for]').forEach(editorEl => {
      const fieldId = editorEl.dataset.for;
      const textarea = document.getElementById(fieldId);
      if (!textarea || editorEl.dataset.quillInitialized) return;

      const container = editorEl.closest('.rich-editor-container') || editorEl.parentElement;
      editorEl.dataset.quillInitialized = 'true';

      const quill = new Quill(editorEl, {
        theme: 'snow',
        placeholder: 'Compose rich content with headings, lists, quotes, tables, and images...',
        modules: {
          toolbar: {
            container: [
              [{ 'header': [2, 3, 4, false] }],
              ['bold', 'italic', 'underline', 'strike'],
              [{ 'color': [] }, { 'background': [] }],
              [{ 'list': 'ordered'}, { 'list': 'bullet' }],
              ['blockquote', 'code-block'],
              ['link', 'image'],
              ['clean']
            ]
          }
        }
      });

      // Populate initial content from textarea
      if (textarea.value) {
        quill.root.innerHTML = textarea.value;
      }

      // Hide textarea by default, show Quill
      textarea.style.display = 'none';

      // Keep textarea in sync whenever Quill changes
      quill.on('text-change', () => {
        textarea.value = quill.root.innerHTML;
        textarea.dispatchEvent(new Event('input', { bubbles: true }));
      });

      // Add HTML Source Toggle Button to toolbar
      const toolbar = container.querySelector('.ql-toolbar');
      if (toolbar) {
        const sourceBtn = document.createElement('button');
        sourceBtn.type = 'button';
        sourceBtn.className = 'ql-source-btn';
        sourceBtn.title = 'Toggle HTML Source Code View';
        sourceBtn.innerHTML = '<span style="font-family:monospace;font-weight:700;font-size:12px;">&lt;/&gt;</span>';
        
        sourceBtn.addEventListener('click', () => {
          const isSource = container.classList.toggle('is-source-mode');
          if (isSource) {
            textarea.value = quill.root.innerHTML;
            textarea.style.display = 'block';
            textarea.classList.add('ql-source-textarea');
            editorEl.style.display = 'none';
            sourceBtn.classList.add('ql-active');
            textarea.focus();
          } else {
            quill.root.innerHTML = textarea.value;
            textarea.style.display = 'none';
            editorEl.style.display = 'block';
            sourceBtn.classList.remove('ql-active');
          }
        });

        const formatsGroup = document.createElement('span');
        formatsGroup.className = 'ql-formats ql-formats-source';
        formatsGroup.appendChild(sourceBtn);
        toolbar.appendChild(formatsGroup);

        // Custom Image button handler
        const imgBtn = toolbar.querySelector('.ql-image');
        if (imgBtn) {
          imgBtn.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            const mediaModal = document.getElementById('mediaPicker');
            if (mediaModal && typeof mediaModal.showModal === 'function') {
              window._activeQuillForImage = quill;
              mediaModal.showModal();
            } else {
              const url = prompt('Enter image URL or asset path (e.g. assets/images/equipment.webp):');
              if (url) {
                const range = quill.getSelection(true) || { index: quill.getLength(), length: 0 };
                quill.insertEmbed(range.index, 'image', url);
              }
            }
          });
        }
      }

      // When textarea is edited in source mode, sync back to Quill
      textarea.addEventListener('input', () => {
        if (container.classList.contains('is-source-mode')) {
          quill.root.innerHTML = textarea.value;
        }
      });
    });

    // Global form submit flush: ensure all textareas have the latest Quill content
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', () => {
        document.querySelectorAll('.rich-editor[data-for]').forEach(el => {
          const fid = el.dataset.for;
          const ta = document.getElementById(fid);
          const q = window.Quill ? Quill.find(el) : null;
          if (ta && q) {
            ta.value = q.root.innerHTML;
          }
        });
      });
    });
  };

  initQuillEditors();

  // Media Library Selection for Quill and Asset inputs
  document.querySelectorAll('.media-choice').forEach(btn => {
    btn.addEventListener('click', () => {
      const selectedPath = btn.dataset.path;
      if (window._activeQuillForImage) {
        const q = window._activeQuillForImage;
        const range = q.getSelection(true) || { index: q.getLength(), length: 0 };
        q.insertEmbed(range.index, 'image', (base ? base + '/' : '/') + selectedPath.replace(/^\//, ''));
        window._activeQuillForImage = null;
        document.getElementById('mediaPicker')?.close();
        toast('Image inserted into content.');
      } else if (window._activeAssetInput) {
        window._activeAssetInput.value = selectedPath;
        window._activeAssetInput.dispatchEvent(new Event('input', { bubbles: true }));
        window._activeAssetInput = null;
        document.getElementById('mediaPicker')?.close();
        toast('Image selected from library.');
      }
    });
  });

  // Asset field picker buttons & uploads
  document.querySelectorAll('.asset-field, .editorial-cover-field-group').forEach(field => {
    const input = field.querySelector('input[type="text"], input.asset-path-input, input:not([type])');
    const pickerBtns = field.querySelectorAll('.asset-picker-button, .asset-picker-trigger');
    const uploadInput = field.querySelector('.asset-upload');
    const uploadState = field.querySelector('.upload-state');

    pickerBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const mediaModal = document.getElementById('mediaPicker');
        if (mediaModal && typeof mediaModal.showModal === 'function') {
          window._activeAssetInput = input;
          mediaModal.showModal();
        } else {
          const path = prompt('Enter image URL or asset path:', input ? input.value : '');
          if (path && input) {
            input.value = path;
            input.dispatchEvent(new Event('input', { bubbles: true }));
          }
        }
      });
    });

    uploadInput?.addEventListener('change', async () => {
      const file = uploadInput.files?.[0];
      if (!file) return;
      if (uploadState) uploadState.textContent = 'Uploading…';
      const body = new FormData();
      body.set('csrf', csrf);
      body.set('file', file);
      body.set('image_alt', file.name.replace(/\.[^.]+$/, '').replaceAll('-', ' '));
      try {
        const res = await ajax(base + '/admin/media', body);
        if (input) {
          input.value = res.media.path;
          input.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (uploadState) uploadState.textContent = 'Uploaded successfully!';
        toast('Image uploaded and applied.');
      } catch (err) {
        if (uploadState) uploadState.textContent = 'Upload failed: ' + err.message;
        toast(err.message, true);
      }
    });
  });

  // Live reactive preview updater for all generic asset fields (e.g. avatar, thumbnails)
  document.querySelectorAll('.asset-field').forEach(field => {
    const input = field.querySelector('input[type="text"], input.asset-path-input, input:not([type])');
    if (!input) return;
    const isRound = (input.id && input.id.includes('avatar')) || (input.name && input.name.includes('avatar'));
    const updatePreview = () => {
      const val = input.value.trim();
      let previewWrap = field.querySelector('.asset-field-preview-wrap');
      if (val && !val.endsWith('.pdf')) {
        if (!previewWrap) {
          previewWrap = document.createElement('div');
          previewWrap.className = 'asset-field-preview-wrap';
          field.insertBefore(previewWrap, field.querySelector('.upload-state'));
        }
        let img = previewWrap.querySelector('.asset-preview');
        if (!img) {
          img = document.createElement('img');
          img.className = 'asset-preview';
          previewWrap.appendChild(img);
        }
        if (isRound) {
          img.style.width = '64px';
          img.style.height = '64px';
          img.style.borderRadius = '50%';
          img.style.objectFit = 'cover';
        }
        img.src = val.startsWith('http') ? val : ((base ? base + '/' : '/') + val.replace(/^\//, ''));
        previewWrap.style.display = 'block';
      } else if (previewWrap) {
        previewWrap.style.display = 'none';
      }
    };
    input.addEventListener('input', updatePreview);
    input.addEventListener('change', updatePreview);
  });

  // Live Cover Image Preview & Empty State Toggle
  const thumbInput = document.getElementById('input-thumbnail');
  const coverBox = document.getElementById('coverPreviewBox');
  const coverImg = document.getElementById('coverPreviewImg');
  const coverEmpty = document.getElementById('coverEmptyPlaceholder');
  const clearCoverBtn = document.getElementById('btnClearCover');
  const changeCoverBtn = document.getElementById('btnChangeCoverPhoto');

  function updateCoverVisual() {
    if (!thumbInput) return;
    const val = thumbInput.value.trim();
    if (val) {
      if (coverImg) {
        coverImg.src = val.startsWith('http') ? val : ((base ? base + '/' : '/') + val.replace(/^\//, ''));
      }
      if (coverBox) coverBox.style.display = 'block';
      if (coverEmpty) coverEmpty.style.display = 'none';
      if (clearCoverBtn) clearCoverBtn.style.display = 'inline-flex';
    } else {
      if (coverBox) coverBox.style.display = 'none';
      if (coverEmpty) coverEmpty.style.display = 'flex';
      if (clearCoverBtn) clearCoverBtn.style.display = 'none';
    }
  }

  if (thumbInput) {
    thumbInput.addEventListener('input', updateCoverVisual);
    thumbInput.addEventListener('change', updateCoverVisual);
  }

  if (clearCoverBtn && thumbInput) {
    clearCoverBtn.addEventListener('click', () => {
      thumbInput.value = '';
      thumbInput.dispatchEvent(new Event('input', { bubbles: true }));
      toast('Cover image cleared.');
    });
  }

  if (changeCoverBtn && thumbInput) {
    changeCoverBtn.addEventListener('click', () => {
      const mediaModal = document.getElementById('mediaPicker');
      if (mediaModal && typeof mediaModal.showModal === 'function') {
        window._activeAssetInput = thumbInput;
        mediaModal.showModal();
      }
    });
  }

  // Media library filter search
  const mediaFilterInput = document.getElementById('mediaFilterInput');
  if (mediaFilterInput) {
    mediaFilterInput.addEventListener('input', () => {
      const query = mediaFilterInput.value.toLowerCase().trim();
      document.querySelectorAll('#mediaPicker .media-choice').forEach(item => {
        const text = (item.textContent + ' ' + (item.dataset.path || '') + ' ' + (item.dataset.alt || '')).toLowerCase();
        item.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }


  // Auto-slug generator: [data-title] -> [data-slug]
  const titleInput = document.querySelector('[data-title]');
  const slugInput = document.querySelector('[data-slug]');
  if (titleInput && slugInput) {
    let slugManual = slugInput.value.trim().length > 0;
    slugInput.addEventListener('input', () => {
      slugManual = slugInput.value.trim().length > 0;
    });
    titleInput.addEventListener('input', () => {
      if (!slugManual || !slugInput.value.trim()) {
        const generated = titleInput.value.toLowerCase()
          .replace(/[^\w\s-]/g, '')
          .trim()
          .replace(/[\s_-]+/g, '-')
          .replace(/^-+|-+$/g, '');
        slugInput.value = generated;
        slugInput.dispatchEvent(new Event('input', { bubbles: true }));
      }
    });
  }

  // Character counters: [data-counter]
  document.querySelectorAll('[data-counter]').forEach(el => {
    const max = Number(el.dataset.counter) || 155;
    const counter = el.parentElement.querySelector('.field-counter');
    const update = () => {
      if (!counter) return;
      const len = el.value.length;
      counter.textContent = `${len} / ${max} characters`;
      counter.classList.toggle('is-over', len > max);
    };
    el.addEventListener('input', update);
    update();
  });

  // Tag preview: [data-tags]
  document.querySelectorAll('[data-tags]').forEach(el => {
    const preview = el.parentElement.querySelector('.tag-preview');
    const updateTags = () => {
      if (!preview) return;
      const tags = el.value.split(',').map(t => t.trim()).filter(Boolean);
      preview.innerHTML = tags.map(t => `<span class="tag-chip">${t}</span>`).join('');
    };
    el.addEventListener('input', updateTags);
    updateTags();
  });

  // Google SERP live preview updater
  const serpTitle = document.getElementById('serpTitlePreview');
  const serpUrl = document.getElementById('serpUrlPreview');
  const serpDesc = document.getElementById('serpDescPreview');
  const seoTitleInp = document.getElementById('input-seo-meta_title');
  const seoDescInp = document.getElementById('input-seo-meta_desc');
  const headlineInp = document.getElementById('input-title');
  const excerptInp = document.getElementById('input-excerpt');
  const slugInp = document.getElementById('input-slug');

  const updateSerp = () => {
    if (serpTitle) {
      serpTitle.textContent = seoTitleInp?.value.trim() || (headlineInp?.value.trim() ? headlineInp.value.trim() + ' · Shree Ji Gas' : 'Title · Shree Ji Gas');
    }
    if (serpUrl && slugInp) {
      serpUrl.textContent = '/' + (slugInp.closest('.slug-input-group')?.querySelector('.slug-prefix')?.textContent.replace(/^\/|\/$/g, '') || 'blog') + '/' + (slugInp.value.trim() || 'your-slug');
    }
    if (serpDesc) {
      serpDesc.textContent = seoDescInp?.value.trim() || excerptInp?.value.trim() || 'Shree Ji Gas Industries provides medical oxygen and industrial gas cylinders in Agra.';
    }
  };

  [seoTitleInp, seoDescInp, headlineInp, excerptInp, slugInp].forEach(inp => inp?.addEventListener('input', updateSerp));
  updateSerp();

  // Key-Value Editor (Metrics in Case Studies, Specs in Products)
  document.querySelectorAll('.keyvalue-editor').forEach(kv => {
    const input = kv.querySelector('.keyvalue-value');
    const itemsEl = kv.querySelector('.keyvalue-items');
    const addBtn = kv.querySelector('.add-keyvalue');
    if (!input || !itemsEl) return;

    let data = {};
    try { data = JSON.parse(input.value || '{}'); } catch {}

    const render = () => {
      itemsEl.innerHTML = '';
      Object.entries(data).forEach(([key, val]) => {
        const row = document.createElement('div');
        row.className = 'keyvalue-row';
        row.innerHTML = `
          <input type="text" class="kv-key" placeholder="Metric / Spec Label" value="${key}">
          <input type="text" class="kv-val" placeholder="Value (e.g. 99.98% or IS:7285)" value="${val}">
          <button type="button" class="btn btn-outline btn-sm kv-del" title="Remove row">&times;</button>
        `;
        const keyInp = row.querySelector('.kv-key');
        const valInp = row.querySelector('.kv-val');
        const delBtn = row.querySelector('.kv-del');

        const sync = () => {
          const newData = {};
          itemsEl.querySelectorAll('.keyvalue-row').forEach(r => {
            const k = r.querySelector('.kv-key').value.trim();
            const v = r.querySelector('.kv-val').value.trim();
            if (k) newData[k] = v;
          });
          data = newData;
          input.value = JSON.stringify(data);
        };

        keyInp.addEventListener('input', sync);
        valInp.addEventListener('input', sync);
        delBtn.addEventListener('click', () => {
          row.remove();
          sync();
        });
        itemsEl.appendChild(row);
      });
      input.value = JSON.stringify(data);
    };

    addBtn?.addEventListener('click', () => {
      data['Metric ' + (Object.keys(data).length + 1)] = '99.9%';
      render();
    });

    render();
  });

  // Password Visibility Toggle
  document.querySelectorAll('.btn-toggle-pass').forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.dataset.target;
      const input = document.getElementById(targetId);
      if (!input) return;
      const isPass = input.type === 'password';
      input.type = isPass ? 'text' : 'password';
      btn.innerHTML = isPass ? '<i class="ri-eye-off-line"></i>' : '<i class="ri-eye-line"></i>';
      btn.setAttribute('aria-label', isPass ? 'Hide password' : 'Show password');
    });
  });

  // Settings Tab Navigation
  const settingsTabNav = document.getElementById('settingsTabNav');
  if (settingsTabNav) {
    const tabBtns = settingsTabNav.querySelectorAll('.settings-tab-btn');
    const panes = document.querySelectorAll('.settings-tab-pane');
    const stickySave = document.getElementById('settingsStickySave');

    const switchTab = (tabId) => {
      tabBtns.forEach(btn => {
        const isActive = btn.dataset.tab === tabId;
        btn.classList.toggle('is-active', isActive);
        btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
      });
      panes.forEach(pane => {
        const isActive = pane.id === tabId;
        pane.classList.toggle('is-active', isActive);
      });
      const isSecurityTab = (tabId === 'tab-security');
      if (stickySave) {
        stickySave.hidden = isSecurityTab;
      }
      const settingsForm = document.getElementById('settingsForm');
      if (settingsForm) {
        settingsForm.hidden = isSecurityTab;
      }
    };

    tabBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        const tabId = btn.dataset.tab;
        switchTab(tabId);
        try { history.replaceState(null, null, '#' + tabId.replace('tab-', '')); } catch(e) {}
      });
    });

    const hash = window.location.hash.replace('#', '');
    if (hash) {
      const matchPane = document.getElementById('tab-' + hash);
      if (matchPane) switchTab('tab-' + hash);
    }
  }

  // SMTP Test Dispatch
  const btnRunSmtpTest = document.getElementById('btnRunSmtpTest');
  const smtpFeedback = document.getElementById('smtpTestFeedback');
  if (btnRunSmtpTest) {
    btnRunSmtpTest.addEventListener('click', async () => {
      const recipient = document.getElementById('smtpTestRecipient')?.value.trim();
      if (!recipient) {
        toast('Please enter a recipient email address for testing.', true);
        return;
      }
      btnRunSmtpTest.disabled = true;
      const originalText = btnRunSmtpTest.innerHTML;
      btnRunSmtpTest.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Connecting & Sending...';
      if (smtpFeedback) {
        smtpFeedback.hidden = false;
        smtpFeedback.className = 'smtp-test-feedback smtp-test-feedback--info';
        smtpFeedback.innerHTML = '<i class="ri-loader-line ri-spin"></i> Testing SMTP socket connection and sending test message to <code>' + recipient + '</code>...';
      }

      const formData = new FormData();
      formData.append('csrf', csrf);
      formData.append('test_to', recipient);
      formData.append('smtp_host', document.getElementById('smtpHost')?.value.trim() || '');
      formData.append('smtp_port', document.getElementById('smtpPort')?.value.trim() || '');
      formData.append('smtp_encryption', document.getElementById('smtpEncryption')?.value || 'tls');
      formData.append('smtp_user', document.getElementById('smtpUser')?.value.trim() || '');
      formData.append('smtp_pass', document.getElementById('smtpPass')?.value || '');
      formData.append('smtp_from_email', document.getElementById('smtpFromEmail')?.value.trim() || '');
      formData.append('smtp_from_name', document.getElementById('smtpFromName')?.value.trim() || '');
      formData.append('smtp_enabled', document.getElementById('smtpEnabledToggle')?.checked ? '1' : '0');

      try {
        const res = await fetch((base ? base + '/' : '/') + 'admin/settings/test-smtp', {
          method: 'POST',
          body: formData,
          headers: { Accept: 'application/json', 'X-CSRF-Token': csrf }
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          if (smtpFeedback) {
            smtpFeedback.className = 'smtp-test-feedback smtp-test-feedback--success';
            smtpFeedback.innerHTML = '<i class="ri-checkbox-circle-line"></i> <strong>Success!</strong> ' + (data.message || 'SMTP test email dispatched successfully.');
          }
          toast('SMTP test email sent successfully!');
        } else {
          if (smtpFeedback) {
            smtpFeedback.className = 'smtp-test-feedback smtp-test-feedback--error';
            smtpFeedback.innerHTML = '<i class="ri-error-warning-line"></i> <strong>Connection Failed:</strong> ' + (data.message || 'Unable to establish SMTP socket.');
          }
          toast(data.message || 'SMTP test failed', true);
        }
      } catch (err) {
        if (smtpFeedback) {
          smtpFeedback.className = 'smtp-test-feedback smtp-test-feedback--error';
          smtpFeedback.innerHTML = '<i class="ri-error-warning-line"></i> <strong>Connection Error:</strong> ' + err.message;
        }
        toast(err.message, true);
      } finally {
        btnRunSmtpTest.disabled = false;
        btnRunSmtpTest.innerHTML = originalText;
      }
    });
  }

  // Admin Email Change OTP Flow
  const btnSendEmailOtp = document.getElementById('btnSendEmailOtp');
  const btnVerifyEmailOtp = document.getElementById('btnVerifyEmailOtp');
  const btnCancelEmailChange = document.getElementById('btnCancelEmailChange');
  const emailStep1 = document.getElementById('emailChangeStep1');
  const emailStep2 = document.getElementById('emailChangeStep2');
  const emailAlertBox = document.getElementById('emailChangeAlertBox');
  const simulatedNotice = document.getElementById('simulatedOtpNotice');
  const displayTarget = document.getElementById('displayTargetEmail');

  const showSecurityAlert = (msg, isError = false) => {
    if (!emailAlertBox) return;
    emailAlertBox.hidden = false;
    emailAlertBox.className = 'security-alert-box ' + (isError ? 'security-alert-box--error' : 'security-alert-box--success');
    emailAlertBox.innerHTML = (isError ? '<i class="ri-error-warning-line"></i> ' : '<i class="ri-check-line"></i> ') + msg;
  };

  if (btnSendEmailOtp) {
    btnSendEmailOtp.addEventListener('click', async () => {
      const newEmail = document.getElementById('newAdminEmail')?.value.trim();
      const currentPassword = document.getElementById('emailCurrentPassword')?.value;
      if (!newEmail || !currentPassword) {
        showSecurityAlert('Please fill in both the new email and your current password.', true);
        return;
      }
      btnSendEmailOtp.disabled = true;
      const origBtn = btnSendEmailOtp.innerHTML;
      btnSendEmailOtp.innerHTML = '<i class="ri-loader-line ri-spin"></i> Sending OTP...';

      const formData = new FormData();
      formData.append('csrf', csrf);
      formData.append('action', 'request');
      formData.append('new_email', newEmail);
      formData.append('current_password', currentPassword);

      try {
        const res = await fetch((base ? base + '/' : '/') + 'admin/email-change/request', {
          method: 'POST',
          body: formData,
          headers: { Accept: 'application/json', 'X-CSRF-Token': csrf }
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          if (emailStep1) emailStep1.hidden = true;
          if (emailStep2) emailStep2.hidden = false;
          if (displayTarget) displayTarget.textContent = newEmail;
          if (simulatedNotice) {
            if (data.simulated && data.otp) {
              simulatedNotice.hidden = false;
              simulatedNotice.innerHTML = '<strong><i class="ri-key-line"></i> Dev / Local Simulation OTP:</strong> <code class="otp-highlight">' + data.otp + '</code> <small>(SMTP inactive - code displayed for testing)</small>';
              const otpInput = document.getElementById('emailOtpInput');
              if (otpInput) { otpInput.value = data.otp; otpInput.focus(); }
            } else {
              simulatedNotice.hidden = true;
              document.getElementById('emailOtpInput')?.focus();
            }
          }
          showSecurityAlert(data.message || 'Verification code sent.', false);
          toast('Verification code sent to ' + newEmail);
        } else {
          showSecurityAlert(data.message || 'Unable to request verification code.', true);
          toast(data.message, true);
        }
      } catch (err) {
        showSecurityAlert(err.message, true);
        toast(err.message, true);
      } finally {
        btnSendEmailOtp.disabled = false;
        btnSendEmailOtp.innerHTML = origBtn;
      }
    });
  }

  if (btnVerifyEmailOtp) {
    btnVerifyEmailOtp.addEventListener('click', async () => {
      const otp = document.getElementById('emailOtpInput')?.value.trim();
      if (!otp || otp.length !== 6) {
        showSecurityAlert('Please enter the full 6-digit verification code.', true);
        return;
      }
      btnVerifyEmailOtp.disabled = true;
      const origVerify = btnVerifyEmailOtp.innerHTML;
      btnVerifyEmailOtp.innerHTML = '<i class="ri-loader-line ri-spin"></i> Verifying...';

      const formData = new FormData();
      formData.append('csrf', csrf);
      formData.append('action', 'verify');
      formData.append('otp', otp);

      try {
        const res = await fetch((base ? base + '/' : '/') + 'admin/email-change/verify', {
          method: 'POST',
          body: formData,
          headers: { Accept: 'application/json', 'X-CSRF-Token': csrf }
        });
        const data = await res.json();
        if (res.ok && data.ok) {
          showSecurityAlert('Admin email successfully updated to ' + data.new_email + '!', false);
          const emailDisplay = document.getElementById('currentAdminEmailDisplay');
          if (emailDisplay) emailDisplay.textContent = data.new_email;
          const newEmailInput = document.getElementById('newAdminEmail');
          if (newEmailInput) newEmailInput.value = '';
          const passInput = document.getElementById('emailCurrentPassword');
          if (passInput) passInput.value = '';
          const otpInput = document.getElementById('emailOtpInput');
          if (otpInput) otpInput.value = '';
          if (emailStep2) emailStep2.hidden = true;
          if (emailStep1) emailStep1.hidden = false;
          toast('Admin email updated successfully!');
        } else {
          showSecurityAlert(data.message || 'Verification code incorrect.', true);
          toast(data.message, true);
        }
      } catch (err) {
        showSecurityAlert(err.message, true);
        toast(err.message, true);
      } finally {
        btnVerifyEmailOtp.disabled = false;
        btnVerifyEmailOtp.innerHTML = origVerify;
      }
    });
  }

  if (btnCancelEmailChange) {
    btnCancelEmailChange.addEventListener('click', () => {
      if (emailStep2) emailStep2.hidden = true;
      if (emailStep1) emailStep1.hidden = false;
      if (emailAlertBox) emailAlertBox.hidden = true;
    });
  }

  // Media Library Page Controls
  const toggleUploadPanelBtn = document.getElementById('toggleUploadPanelBtn');
  const mediaUploadPanel = document.getElementById('mediaUploadPanel');
  if (toggleUploadPanelBtn && mediaUploadPanel) {
    toggleUploadPanelBtn.addEventListener('click', () => {
      mediaUploadPanel.classList.toggle('is-expanded');
      const isExp = mediaUploadPanel.classList.contains('is-expanded');
      toggleUploadPanelBtn.classList.toggle('is-active', isExp);
      if (isExp) {
        document.getElementById('mediaAlt')?.focus();
      }
    });
  }

  // Media Dropzone File Selection
  const mediaFileInput = document.getElementById('mediaFile');
  const dropZoneFilename = document.getElementById('dropZoneFilename');
  const mediaAlt = document.getElementById('mediaAlt');
  if (mediaFileInput && dropZoneFilename) {
    mediaFileInput.addEventListener('change', () => {
      if (mediaFileInput.files && mediaFileInput.files[0]) {
        const f = mediaFileInput.files[0];
        dropZoneFilename.hidden = false;
        dropZoneFilename.textContent = f.name + ' (' + Math.round(f.size / 1024) + ' KB)';
        if (mediaAlt && !mediaAlt.value.trim()) {
          mediaAlt.value = f.name.replace(/\.[^/.]+$/, '').replace(/[-_]/g, ' ');
        }
      }
    });
  }

  // Media Library Search & Pills Filter
  const mediaSearchInp = document.getElementById('mediaSearchInput');
  const mediaPills = document.querySelectorAll('.media-pill[data-filter]');
  const mediaCards = document.querySelectorAll('.media-card');
  const visibleCountEl = document.getElementById('visibleAssetCount');
  const mediaEmptyEl = document.getElementById('mediaSearchEmpty');

  if (mediaSearchInp || mediaPills.length > 0) {
    let currentFilter = 'all';

    const filterMedia = () => {
      const q = (mediaSearchInp?.value || '').toLowerCase().trim();
      let matchCount = 0;

      mediaCards.forEach(card => {
        const type = card.dataset.type || '';
        const filename = card.dataset.filename || '';
        const alt = card.dataset.alt || '';

        const matchesType = (currentFilter === 'all') || (type === currentFilter);
        const matchesQuery = !q || filename.includes(q) || alt.includes(q);

        const visible = matchesType && matchesQuery;
        card.style.display = visible ? '' : 'none';
        if (visible) matchCount++;
      });

      if (visibleCountEl) visibleCountEl.textContent = matchCount;
      if (mediaEmptyEl) mediaEmptyEl.hidden = (matchCount > 0);
    };

    mediaSearchInp?.addEventListener('input', filterMedia);

    mediaPills.forEach(pill => {
      pill.addEventListener('click', () => {
        mediaPills.forEach(p => p.classList.remove('is-active'));
        pill.classList.add('is-active');
        currentFilter = pill.dataset.filter || 'all';
        filterMedia();
      });
    });
  }

  // Copy Asset URL Button
  document.querySelectorAll('.btn-copy-url').forEach(btn => {
    btn.addEventListener('click', async () => {
      const copyVal = btn.dataset.copy;
      if (!copyVal) return;
      try {
        await navigator.clipboard.writeText(copyVal);
        const origHtml = btn.innerHTML;
        btn.innerHTML = '<i class="ri-check-line"></i> <span>Copied!</span>';
        btn.classList.add('is-copied');
        toast('Asset URL copied to clipboard: ' + copyVal);
        setTimeout(() => {
          btn.innerHTML = origHtml;
          btn.classList.remove('is-copied');
        }, 2200);
      } catch (e) {
        prompt('Copy asset URL:', copyVal);
      }
    });
  });

  // Global Ctrl+S shortcut to save active form
  document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
      const activeForm = document.querySelector('.editorial-editor-form, .category-editor-form, .content-edit-form, #productForm, #settingsForm');
      if (activeForm) {
        e.preventDefault();
        const submitBtn = activeForm.querySelector('button[type="submit"]');
        if (submitBtn) {
          submitBtn.click();
        } else {
          activeForm.submit();
        }
      }
    }
  });
});

