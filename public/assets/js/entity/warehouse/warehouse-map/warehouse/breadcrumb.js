export function createBreadcrumb({ onModeChange }) {
  const el = document.createElement('div');
  el.className = 'breadcrumb-bar';

  const icon = document.createElement('i');
  icon.className = 'bi bi-building text-secondary';
  icon.style.fontSize = '15px';
  el.appendChild(icon);

  const nav = document.createElement('nav');
  nav.setAttribute('aria-label', 'breadcrumb');
  nav.style.cssText = 'flex:1;min-width:0';

  const ol = document.createElement('ol');
  ol.className = 'breadcrumb mb-0';
  nav.appendChild(ol);
  el.appendChild(nav);

  const modeSwitcher = document.createElement('div');
  modeSwitcher.className = 'mode-switcher';

  const viewBtn = document.createElement('button');
  viewBtn.type = 'button';
  viewBtn.className = 'mode-btn';
  viewBtn.innerHTML = '<i class="bi bi-eye"></i> Перегляд';
  viewBtn.addEventListener('click', () => onModeChange('view'));

  const editBtn = document.createElement('button');
  editBtn.type = 'button';
  editBtn.className = 'mode-btn';
  editBtn.innerHTML = '<i class="bi bi-pencil"></i> Редагування';
  editBtn.addEventListener('click', () => onModeChange('edit'));

  modeSwitcher.appendChild(viewBtn);
  modeSwitcher.appendChild(editBtn);
  el.appendChild(modeSwitcher);

  function render(items, mode) {
    ol.innerHTML = '';

    items.forEach((item, index) => {
      const li = document.createElement('li');
      li.className = `breadcrumb-item${index === items.length - 1 ? ' active' : ''}`;

      if (index === items.length - 1) {
        const span = document.createElement('span');
        span.style.cssText = 'font-weight:600;font-size:13px';
        span.textContent = item.label;
        li.appendChild(span);
      } else {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-link p-0';
        btn.style.cssText = 'font-size:13px;font-weight:500;text-decoration:none';
        btn.textContent = item.label;
        btn.addEventListener('click', item.onClick);
        li.appendChild(btn);
      }
      ol.appendChild(li);
    });

    viewBtn.className = `mode-btn${mode === 'view' ? ' active' : ''}`;
    editBtn.className = `mode-btn${mode === 'edit' ? ' active' : ''}`;
  }

  return { el, render };
}
