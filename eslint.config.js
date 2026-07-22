/*
 * Copyright Victor Witkamp (c) 2020.
 */

const js = require('@eslint/js');
const globals = require('globals');

module.exports = [
    js.configs.recommended,
    {
        languageOptions: {
            ecmaVersion: 2022,
            sourceType: 'script',
            globals: {
                ...globals.browser,
                // Loaded as global vendor scripts, not modules.
                bootstrap: 'readonly',
                DataTable: 'readonly',
                FullCalendar: 'readonly',
                moment: 'readonly'
            }
        },
        rules: {
            'no-unused-vars': 'warn'
        }
    }
];
