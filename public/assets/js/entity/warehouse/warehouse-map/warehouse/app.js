import { createApi } from './api.js';
import { createStore } from './state.js';
import { createBreadcrumb } from './breadcrumb.js';
import { createSidebar } from './sidebar.js';
import { createGrid } from './grid.js';

export async function initWarehouseMap(container) {
  const api = createApi();

  const store = createStore({
    zones: [],
    sectors: [],
    rows: [],
    mapElements: [],
    loading: true,
    mode: 'view',
    nav: { level: 'zones' },
    draggedItem: null,
  });

  container.innerHTML = '';
  container.className = 'wh-app-root';

  const loadingEl = document.createElement('div');
  loadingEl.className = 'wh-loading';
  loadingEl.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Завантаження...</span></div>';
  container.appendChild(loadingEl);

  const mainEl = document.createElement('div');
  mainEl.className = 'warehouse-main';
  mainEl.style.display = 'none';
  container.appendChild(mainEl);

  const breadcrumb = createBreadcrumb({
    onModeChange(newMode) {
      store.setState(s => ({ ...s, mode: newMode }));
    },
  });
  mainEl.appendChild(breadcrumb.el);

  const bodyEl = document.createElement('div');
  bodyEl.className = 'warehouse-body';
  mainEl.appendChild(bodyEl);

  const sidebar = createSidebar({
    onDragStart(item) {
      store.setState(s => ({ ...s, draggedItem: item }));
    },
    onItemClick(item) {
      const s = store.getState();
      if (getPlacedIds(s).has(item.id)) return;
      placeItem(store.getState(), item, null, null);
    },
  });
  bodyEl.appendChild(sidebar.el);

  const grid = createGrid({
    onDrop(elementId, gridX, gridY) {
      const s = store.getState();
      placeItem(s, s.draggedItem, gridX, gridY);
      store.setState(prev => ({ ...prev, draggedItem: null }));
    },
    onUpdate(id, updates) {
      const s = store.getState();
      api.updateMapElement(id, updates).then(({ data }) => {
        if (data) {
          store.setState(prev => ({
            ...prev,
            mapElements: prev.mapElements.map(el => el.id === id ? data : el),
          }));
        }
      });
      store.setState(prev => ({
        ...prev,
        mapElements: prev.mapElements.map(el => el.id === id ? { ...el, ...updates } : el),
      }));
    },
    onDelete(id) {
      api.deleteMapElement(id);
      store.setState(prev => ({
        ...prev,
        mapElements: prev.mapElements.filter(el => el.id !== id),
      }));
    },
    onNavigate(elementId) {
      const s = store.getState();
      if (s.nav.level === 'zones') {
        const zone = s.zones.find(z => z.id === elementId);
        store.setState(prev => ({ ...prev, nav: { level: 'sectors', zoneId: elementId, zoneName: zone?.name } }));
      } else if (s.nav.level === 'sectors') {
        const sector = s.sectors.find(sc => sc.id === elementId);
        store.setState(prev => ({
          ...prev,
          nav: { ...prev.nav, level: 'rows', sectorId: elementId, sectorName: sector?.name },
        }));
      }
    },
    get canNavigate() {
      return store.getState().nav.level !== 'rows';
    },
    get readonly() {
      return store.getState().mode === 'view';
    },
  });
  bodyEl.appendChild(grid.el);

  async function placeItem(s, item, gridX, gridY) {
    if (!item) return;
    const { nav, mapElements } = s;
    const elementType = nav.level === 'zones' ? 'zone' : nav.level === 'sectors' ? 'sector' : 'row';
    const parentId = nav.level === 'sectors' ? nav.zoneId : nav.level === 'rows' ? nav.sectorId : null;
    const currentMapElements = mapElements.filter(el => {
      if (el.element_type !== elementType) return false;
      if (nav.level === 'sectors') return el.parent_id === nav.zoneId;
      if (nav.level === 'rows') return el.parent_id === nav.sectorId;
      return true;
    });
    const placedIds = new Set(currentMapElements.map(el => el.element_id));
    if (placedIds.has(item.id)) return;

    let gx = gridX, gy = gridY;
    if (gx === null) {
      const usedPositions = new Set(currentMapElements.map(el => `${el.grid_x},${el.grid_y}`));
      gx = 0; gy = 0;
      while (usedPositions.has(`${gx},${gy}`)) {
        gx += 3;
        if (gx > 12) { gx = 0; gy += 2; }
      }
    }

    const newEl = {
      element_type: elementType,
      element_id: item.id,
      parent_id: parentId,
      grid_x: gx,
      grid_y: gy,
      grid_w: 3,
      grid_h: 2,
      color: item.color,
    };

    const { data } = await api.addMapElement(newEl);
    if (data) {
      store.setState(prev => ({ ...prev, mapElements: [...prev.mapElements, data] }));
    }
  }

  function getPlacedIds(s) {
    const { nav, mapElements } = s;
    const elementType = nav.level === 'zones' ? 'zone' : nav.level === 'sectors' ? 'sector' : 'row';
    return new Set(
      mapElements.filter(el => {
        if (el.element_type !== elementType) return false;
        if (nav.level === 'sectors') return el.parent_id === nav.zoneId;
        if (nav.level === 'rows') return el.parent_id === nav.sectorId;
        return true;
      }).map(el => el.element_id)
    );
  }

  function getLabel(s, elementId) {
    if (s.nav.level === 'zones') return s.zones.find(z => z.id === elementId)?.name ?? elementId;
    if (s.nav.level === 'sectors') return s.sectors.find(sc => sc.id === elementId)?.name ?? elementId;
    return s.rows.find(r => r.id === elementId)?.name ?? elementId;
  }

  function getCurrentItems(s) {
    const { nav, zones, sectors, rows } = s;
    if (nav.level === 'zones') return zones;
    if (nav.level === 'sectors') return sectors.filter(sc => sc.zone_id === nav.zoneId);
    return rows.filter(r => r.sector_id === nav.sectorId);
  }

  function getBreadcrumbItems(s) {
    const { nav } = s;
    const items = [{ label: 'Склад', onClick: () => store.setState(prev => ({ ...prev, nav: { level: 'zones' } })) }];
    if (nav.level === 'sectors' || nav.level === 'rows') {
      items.push({
        label: nav.zoneName ?? 'Зона',
        onClick: () => store.setState(prev => ({ ...prev, nav: { level: 'sectors', zoneId: nav.zoneId, zoneName: nav.zoneName } })),
      });
    }
    if (nav.level === 'rows') {
      items.push({ label: nav.sectorName ?? 'Сектор', onClick: () => {} });
    }
    return items;
  }

  store.subscribe(s => {
    if (s.loading) {
      loadingEl.style.display = 'flex';
      mainEl.style.display = 'none';
      return;
    }
    loadingEl.style.display = 'none';
    mainEl.style.display = 'flex';

    const isView = s.mode === 'view';
    sidebar.el.style.display = isView ? 'none' : '';

    const elementType = s.nav.level === 'zones' ? 'zone' : s.nav.level === 'sectors' ? 'sector' : 'row';
    const currentMapElements = s.mapElements.filter(el => {
      if (el.element_type !== elementType) return false;
      if (s.nav.level === 'sectors') return el.parent_id === s.nav.zoneId;
      if (s.nav.level === 'rows') return el.parent_id === s.nav.sectorId;
      return true;
    });
    const placedIds = new Set(currentMapElements.map(el => el.element_id));
    const currentItems = getCurrentItems(s);

    const placedItems = currentMapElements.map(el => ({
      mapElement: el,
      label: getLabel(s, el.element_id),
    }));

    breadcrumb.render(getBreadcrumbItems(s), s.mode);
    sidebar.render(s.nav.level, currentItems, placedIds);

    grid.setReadonly(isView);
    grid.setItems(placedItems);
  });

  const initialData = await api.fetchAll();
  store.setState(prev => ({ ...prev, ...initialData, loading: false }));
}
