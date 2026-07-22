/*
 * Copyright Victor Witkamp (c) 2020.
 *
 * Copies vendor CSS/JS/font assets from node_modules into portal/dist/,
 * and concatenates the DataTables and FullCalendar bundles used by the app.
 * None of the app's own JS is compiled (it's all loaded as plain globals),
 * so a bundler would be overkill here — copying + concatenation is all
 * that's needed.
 */

import cpy from 'cpy';
import { readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const nodeModules = join(root, 'node_modules');
const dist = join(root, 'portal', 'dist');
const merged = join(dist, 'merged');

function concat(relSrcList, relDest) {
    const content = relSrcList
        .map((rel) => readFileSync(join(nodeModules, rel), 'utf8'))
        .join('\n');
    const destPath = join(merged, relDest);
    mkdirSync(dirname(destPath), { recursive: true });
    writeFileSync(destPath, content);
}

// --- plain vendor CSS/JS/fonts, structure preserved relative to node_modules ---
await cpy([
    '@fortawesome/fontawesome-free/css/all.min.css',
    '@fortawesome/fontawesome-free/webfonts/*.{woff,woff2,ttf}',
    'bootstrap-icons/font/bootstrap-icons.min.css',
    'bootstrap-icons/font/fonts/*.{woff,woff2}',
    'bootswatch/dist/*/bootstrap.min.css',
    'cookieconsent/build/cookieconsent.min.{css,js}',
    'moment/min/moment.min.js',
    'moment/locale/nl.js',
    'bootstrap/dist/js/bootstrap.bundle.min.js'
], dist, { cwd: nodeModules, base: 'cwd' });

// --- merged DataTables bundle (CSS) ---
concat([
    'datatables.net-bs5/css/dataTables.bootstrap5.min.css',
    'datatables.net-select-bs5/css/select.bootstrap5.min.css',
    'datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css'
], 'dataTables.min.css');

// --- merged DataTables bundle (JS, jQuery first since DataTables requires it as a global) ---
concat([
    'jquery/dist/jquery.min.js',
    'datatables.net/js/dataTables.min.js',
    'datatables.net-select/js/dataTables.select.min.js',
    'datatables.net-select-bs5/js/select.bootstrap5.min.js',
    'datatables.net-bs5/js/dataTables.bootstrap5.min.js',
    'datatables.net-buttons/js/dataTables.buttons.min.js',
    'datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js'
], 'dataTables.min.js');

// --- merged FullCalendar bundle (JS) ---
concat([
    '@fullcalendar/core/index.global.min.js',
    '@fullcalendar/daygrid/index.global.min.js',
    '@fullcalendar/list/index.global.min.js',
    '@fullcalendar/bootstrap5/index.global.min.js',
    '@fullcalendar/interaction/index.global.min.js',
    '@fullcalendar/core/locales-all.global.min.js'
], 'fullcalendar.min.js');

console.log('Assets built into portal/dist/');
