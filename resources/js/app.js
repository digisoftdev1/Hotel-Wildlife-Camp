import './bootstrap';

import Alpine from 'alpinejs';
import jQuery from 'jquery';

import collapse from '@alpinejs/collapse'
import select2 from 'select2';



window.Alpine = Alpine;
window.$ = window.jQuery = jQuery;

select2(window.jQuery);

Alpine.plugin(collapse)
Alpine.start();

