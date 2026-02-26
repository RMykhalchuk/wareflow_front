export const ZONES = [
  { id: 'zone-001', name: 'Зона A — Холодне зберігання',   color: '#2563EB' },
  { id: 'zone-002', name: 'Зона B — Суха продукція',        color: '#16A34A' },
  { id: 'zone-003', name: 'Зона C — Великогабаритні',       color: '#DC2626' },
  { id: 'zone-004', name: 'Зона D — Небезпечні матеріали',  color: '#D97706' },
];

export const SECTORS = [
  { id: 'sec-a1', zone_id: 'zone-001', name: 'Сектор A1', color: '#3B82F6' },
  { id: 'sec-a2', zone_id: 'zone-001', name: 'Сектор A2', color: '#60A5FA' },
  { id: 'sec-a3', zone_id: 'zone-001', name: 'Сектор A3', color: '#93C5FD' },
  { id: 'sec-b1', zone_id: 'zone-002', name: 'Сектор B1', color: '#22C55E' },
  { id: 'sec-b2', zone_id: 'zone-002', name: 'Сектор B2', color: '#86EFAC' },
  { id: 'sec-c1', zone_id: 'zone-003', name: 'Сектор C1', color: '#EF4444' },
  { id: 'sec-c2', zone_id: 'zone-003', name: 'Сектор C2', color: '#FCA5A5' },
  { id: 'sec-d1', zone_id: 'zone-004', name: 'Сектор D1', color: '#F59E0B' },
  { id: 'sec-d2', zone_id: 'zone-004', name: 'Сектор D2', color: '#FCD34D' },
];

export const ROWS = [
  { id: 'row-a1-1', sector_id: 'sec-a1', name: 'Ряд A1-1', color: '#BFDBFE' },
  { id: 'row-a1-2', sector_id: 'sec-a1', name: 'Ряд A1-2', color: '#93C5FD' },
  { id: 'row-a1-3', sector_id: 'sec-a1', name: 'Ряд A1-3', color: '#60A5FA' },
  { id: 'row-a2-1', sector_id: 'sec-a2', name: 'Ряд A2-1', color: '#DBEAFE' },
  { id: 'row-a2-2', sector_id: 'sec-a2', name: 'Ряд A2-2', color: '#EFF6FF' },
  { id: 'row-a3-1', sector_id: 'sec-a3', name: 'Ряд A3-1', color: '#A5F3FC' },
  { id: 'row-b1-1', sector_id: 'sec-b1', name: 'Ряд B1-1', color: '#D9F99D' },
  { id: 'row-b1-2', sector_id: 'sec-b1', name: 'Ряд B1-2', color: '#BEF264' },
  { id: 'row-b1-3', sector_id: 'sec-b1', name: 'Ряд B1-3', color: '#D1FAE5' },
  { id: 'row-b2-1', sector_id: 'sec-b2', name: 'Ряд B2-1', color: '#FDE68A' },
  { id: 'row-b2-2', sector_id: 'sec-b2', name: 'Ряд B2-2', color: '#ECFDF5' },
  { id: 'row-c1-1', sector_id: 'sec-c1', name: 'Ряд C1-1', color: '#FECACA' },
  { id: 'row-c1-2', sector_id: 'sec-c1', name: 'Ряд C1-2', color: '#FCA5A5' },
  { id: 'row-c2-1', sector_id: 'sec-c2', name: 'Ряд C2-1', color: '#FEE2E2' },
  { id: 'row-d1-1', sector_id: 'sec-d1', name: 'Ряд D1-1', color: '#FED7AA' },
  { id: 'row-d1-2', sector_id: 'sec-d1', name: 'Ряд D1-2', color: '#FDBA74' },
  { id: 'row-d2-1', sector_id: 'sec-d2', name: 'Ряд D2-1', color: '#FEF08A' },
  { id: 'row-d2-2', sector_id: 'sec-d2', name: 'Ряд D2-2', color: '#FDE68A' },
];
