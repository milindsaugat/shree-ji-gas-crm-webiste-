'use strict';
document.addEventListener('DOMContentLoaded', () => {
  const reduced = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const header = document.getElementById('siteHeader');
  const onScroll = () => header?.classList.toggle('is-scrolled', scrollY > 80);
  onScroll(); window.addEventListener('scroll', onScroll, {passive:true});
  if (window.Swiper) {
    document.querySelectorAll('.product-swiper').forEach(el => new Swiper(el,{slidesPerView:1.12,spaceBetween:18,watchOverflow:true,pagination:{el:el.querySelector('.swiper-pagination'),clickable:true},breakpoints:{576:{slidesPerView:2,spaceBetween:20},992:{slidesPerView:3,spaceBetween:28}},a11y:{enabled:true}}));
    document.querySelectorAll('.related-product-swiper').forEach(el => {
      const section = el.closest('.product-related-section');
      new Swiper(el, {
        slidesPerView: 1.12,
        spaceBetween: 18,
        watchOverflow: true,
        navigation: {
          nextEl: section ? section.querySelector('.swiper-button-next-custom') : null,
          prevEl: section ? section.querySelector('.swiper-button-prev-custom') : null
        },
        pagination: {
          el: el.querySelector('.swiper-pagination'),
          clickable: true
        },
        breakpoints: {
          576: { slidesPerView: 2, spaceBetween: 20 },
          992: { slidesPerView: 3, spaceBetween: 28 }
        },
        a11y: { enabled: true }
      });
    });
    document.querySelectorAll('.testimonial-swiper').forEach(el => {
      const prevEl = el.querySelector('.testimonial-prev');
      const nextEl = el.querySelector('.testimonial-next');
      const paginationEl = el.querySelector('.swiper-pagination');
      new Swiper(el, {
        slidesPerView: 1,
        autoHeight: true,
        loop: true,
        autoplay: reduced ? false : { delay: 6500, disableOnInteraction: true, pauseOnMouseEnter: true },
        navigation: {
          prevEl: prevEl,
          nextEl: nextEl
        },
        pagination: {
          el: paginationEl,
          clickable: true
        },
        a11y: { enabled: true }
      });
    });
  }
  if (window.GLightbox) GLightbox({selector:'.glightbox',touchNavigation:true,loop:false});
  if (window.gsap && !reduced && document.querySelector('.hero')) {
    if(window.ScrollTrigger) gsap.registerPlugin(ScrollTrigger);
    gsap.from('.hero__copy > *',{opacity:0,y:20,duration:.65,stagger:.2,ease:'power2.out',clearProps:'all',...(window.ScrollTrigger?{scrollTrigger:{trigger:'.hero',start:'top 95%',once:true}}:{})});
    gsap.from('.hero__visual',{opacity:0,scale:.96,duration:.9,ease:'power2.out',clearProps:'all'});
  }
  // Attach fade attributes only on desktop/tablet viewports when the local library is available.
  if (window.AOS && !reduced && window.innerWidth >= 768) {
    document.querySelectorAll('.home-section:not(.section-hero):not(.section-stats) > .container').forEach(el=>el.setAttribute('data-aos','fade-up'));
    AOS.init({duration:500,offset:80,once:true,easing:'ease-out',mirror:false,disable:'mobile'});
  }
  if (window.countUp?.CountUp && 'IntersectionObserver' in window && !reduced) {
    const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{
      if(!entry.isIntersecting)return;observer.unobserve(entry.target);
      const label=entry.target.textContent.trim(),parts=label.match(/^([0-9]+(?:,[0-9]{3})*)(\+|%)?$/);if(!parts)return;
      const digits=parts[1].replaceAll(',',''),end=Number(digits),suffix=parts[2]||'';
      const counter=new countUp.CountUp(entry.target,end,{duration:1.6,formattingFn:value=>{
        let number=String(Math.round(value));if(digits.startsWith('0'))number=number.padStart(digits.length,'0');else if(parts[1].includes(','))number=Number(number).toLocaleString('en-IN');return number+suffix;
      }});if(!counter.error)counter.start();
    }),{threshold:.5});document.querySelectorAll('.stat strong').forEach(el=>observer.observe(el));
  }
  document.querySelectorAll('.logo-marquee-toggle').forEach(button=>button.addEventListener('click',()=>{const paused=button.getAttribute('aria-pressed')!=='true';button.setAttribute('aria-pressed',String(paused));button.closest('.section-logos').classList.toggle('marquee-paused',paused);button.textContent=paused?'Resume logo scroll':'Pause logo scroll';}));
  const catalogue = document.getElementById('productCatalogue');
  if(catalogue){
    const cards=[...document.querySelectorAll('#productGrid>.product-card')]; const search=document.getElementById('productSearch'), tag=document.getElementById('tagFilter'), sort=document.getElementById('productSort'), checks=[...document.querySelectorAll('.category-filter')];
    const filter=()=>{const term=search.value.trim().toLowerCase(),selected=checks.filter(c=>c.checked).map(c=>c.value);let count=0;cards.forEach(card=>{const shown=card.dataset.name.includes(term)&&(!selected.length||selected.includes(card.dataset.category))&&(!tag.value||card.dataset.tags.split(',').map(t=>t.trim()).includes(tag.value));card.hidden=!shown;if(shown)count++;});document.getElementById('resultCount').textContent=count;document.getElementById('noProducts').hidden=count!==0;};
    const reset=()=>{search.value='';tag.value='';checks.forEach(c=>c.checked=false);filter();};
    search.addEventListener('input',filter);tag.addEventListener('change',filter);checks.forEach(c=>c.addEventListener('change',filter));document.getElementById('clearFilters').addEventListener('click',reset);document.getElementById('resetEmpty').addEventListener('click',reset);
    sort.addEventListener('change',()=>{const sorted=[...cards];if(sort.value!=='default')sorted.sort((a,b)=>a.querySelector('h3').textContent.localeCompare(b.querySelector('h3').textContent)*(sort.value==='az'?1:-1));sorted.forEach(c=>document.getElementById('productGrid').appendChild(c));});filter();
  }
  document.querySelectorAll('[data-product]').forEach(button=>button.addEventListener('click',()=>{const select=document.getElementById('modal-product');if(select)select.value=button.dataset.product;}));
  document.querySelectorAll('.enquiry-form').forEach(form=>form.addEventListener('submit',async event=>{
    event.preventDefault();if(!form.reportValidity())return;
    const button=form.querySelector('button[type="submit"]'),status=form.querySelector('.form-status');const original=button.innerHTML;button.disabled=true;button.innerHTML='<span class="btn-spinner" aria-hidden="true"></span> <span>Sending enquiry…</span>';status.textContent='';status.classList.remove('is-error');
    try{const response=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{Accept:'application/json'}});const data=await response.json();if(!response.ok||!data.ok)throw new Error(data.message||'We could not send your enquiry. Please try again.');status.innerHTML='<div class="form-success-card"><i class="ri-checkbox-circle-fill"></i><div><strong>'+(data.name?'Thank you, '+data.name+'!':'Thank you!')+'</strong><p>'+(data.message||'Your enquiry has been received. Our team will be in touch shortly.')+'</p></div></div>';form.reset();if(data.whatsapp){const waWrap=document.createElement('div');waWrap.className='form-whatsapp-action';const a=document.createElement('a');a.className='btn btn-outline btn-sm';a.href=data.whatsapp;a.textContent='Continue on WhatsApp ↗';a.target='_blank';a.rel='noopener';waWrap.appendChild(a);status.querySelector('.form-success-card > div')?.appendChild(waWrap);}status.scrollIntoView({block:'nearest',behavior:reduced?'auto':'smooth'});}catch(error){status.textContent=error instanceof SyntaxError?'The session may have expired. Refresh and try again, or call our team.':error.message;status.classList.add('is-error');}finally{button.disabled=false;button.innerHTML=original;}
  }));
  const toc=document.getElementById('articleToc');if(toc){const headings=[...document.querySelectorAll('#articleContent h2,#articleContent h3')].filter(h=>!h.closest('.article-cta-spotlight,.usecase-crosslink-panel'));if(headings.length>0){headings.forEach((h,i)=>{if(!h.id)h.id='section-'+i;const a=document.createElement('a');a.href='#'+h.id;a.textContent=h.textContent;toc.appendChild(a);});}else{document.querySelector('.sidebar-box--toc')?.remove();}}
  document.getElementById('copyLink')?.addEventListener('click',async event=>{const button=event.currentTarget;try{await navigator.clipboard.writeText(location.href);button.textContent='Link copied';}catch{button.textContent='Copy the address from your browser';}});
  document.getElementById('loadMap')?.addEventListener('click',()=>{const box=document.querySelector('.map-placeholder'),frame=document.createElement('iframe');frame.src=box.dataset.map;frame.title='Shree Ji Gas Industries location in Agra';frame.loading='lazy';frame.referrerPolicy='no-referrer-when-downgrade';box.replaceChildren(frame);box.classList.add('is-loaded');});
  // Accessible fallback tabs if the Alpine CDN is unavailable.
  if(!window.Alpine)document.querySelectorAll('[data-tab]').forEach(button=>button.addEventListener('click',()=>{document.querySelectorAll('[data-tab]').forEach(b=>{const active=b===button;b.classList.toggle('is-active',active);b.setAttribute('aria-selected',String(active));const panel=document.getElementById('panel-'+b.dataset.tab);panel.removeAttribute('x-cloak');panel.hidden=!active;});}));
  document.querySelectorAll('[role="tablist"]').forEach(list=>list.addEventListener('keydown',event=>{if(!['ArrowLeft','ArrowRight','Home','End'].includes(event.key))return;const tabs=[...list.querySelectorAll('[role="tab"]')],index=tabs.indexOf(document.activeElement);if(index<0)return;event.preventDefault();const next=event.key==='Home'?0:event.key==='End'?tabs.length-1:(index+(event.key==='ArrowRight'?1:-1)+tabs.length)%tabs.length;tabs[next].focus();tabs[next].click();}));
});
